<?php

include_once __DIR__ . '/User.php';

class ApiToken
{
	private const TABLE_NAME = "api_tokens";
	private const SECRET_KEY = "secret_key";
	private const HEADERS = [
		'type' => 'JWT'
	];

	public static function generate(DB $db, int $userId): array
	{
		if(!User::exists($db, $userId))
		{
			Log::write("Error generating API token: User does not exist");
			return [
				"status" => false,
				"token" => ""
			];
		}

		$user = new User($db, $userId);

		$expiresAt = date("Y-m-d H:i:s", time() + 60 * 60 * 24 * 3);
		$payload = [
			"userId" => $userId,
			"isAdmin" => $user->getIsAdmin(),
			"expiresAt" => $expiresAt
		];

		$header = json_encode(self::HEADERS);
		$payload = json_encode($payload);

		$base64Header = base64_encode($header);
		$base64Payload = base64_encode($payload);

		$signature = hash_hmac("SHA256", "$base64Header.$base64Payload", self::SECRET_KEY, true);
		$base64Signature = base64_encode($signature);

		$token = "$base64Header.$base64Payload.$base64Signature";

		$apiTokenInsert = $db->insert(
			self::TABLE_NAME,
			[
				"user_id" => $userId,
				"token" => $token,
				"expires_at" => $expiresAt
			]
		);

		if(!$apiTokenInsert["status"])
		{
			Log::write("Error generating API token: Failed to insert token into database");
			return [
				"status" => false,
				"token" => ""
			];
		}

		return [
			"status" => true,
			"token" => $token
		];
	}

	public static function validate(DB $db, string $token): bool
	{
		$tokenParts = explode(".", $token);

		if(count($tokenParts) !== 3)
		{
			return false;
		}

		$headerBase64 = $tokenParts[0];
		$payloadBase64 = $tokenParts[1];
		$signatureReceived = $tokenParts[2];

		$header = json_decode(base64_decode($headerBase64), true);

		if($header["type"] !== "JWT")
		{
			return false;
		}

		$payload = json_decode(base64_decode($payloadBase64), true);

		$userId = $payload["userId"];
		$expiresAt = $payload["expiresAt"];

		if(!User::exists($db, intval($userId)))
		{
			return false;
		}

		$user = new User($db, intval($userId));

		if($user->getIsAdmin() !== $payload["isAdmin"])
		{
			return false;
		}

		if(time() > strtotime($expiresAt))
		{
			return false;
		}

		$signature = hash_hmac("SHA256", "$headerBase64.$payloadBase64", self::SECRET_KEY, true);
		$base64Signature = base64_encode($signature);

		if($base64Signature !== $signatureReceived)
		{
			return false;
		}

		return true;
	}
}