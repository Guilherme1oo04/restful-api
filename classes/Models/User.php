<?php

class User
{
	private int $id;
	private string $name;
	private string $userName;
	private string $email;
	private string $isAdmin;

	private DB $database;

	public const TABLE_NAME = "users";
	private const ADMIN_TABLE_NAME = "admins";
	private const HASH_KEY_PASSWORD = "security";

	public function __construct(DB $db, int $id)
	{
		$this->id = $id;
		$this->database = $db;

		$userFetch = $this->database->select(
			User::TABLE_NAME,
			["id", "name", "user_name", "email"],
			[
				[
					"column" => "id",
					"value" => $id,
					"operator" => "="
				]
			]
		);

		if($userFetch["status"])
		{
			$this->name = $userFetch["data"][0]["name"];
			$this->userName = $userFetch["data"][0]["user_name"];
			$this->email = $userFetch["data"][0]["email"];

			$isAdminFetch = $this->database->select(
				User::ADMIN_TABLE_NAME,
				["user_id"],
				[
					[
						"column" => "user_id",
						"value" => $id,
						"operator" => "="
					]
				]
			);

			if(!empty($isAdminFetch["data"]))
			{
				$this->isAdmin = true;
			}
		}
	}

	public function toArray(): array
	{
		return [
			"id" => $this->id,
			"name" => $this->name,
			"userName" => $this->userName,
			"email" => $this->email,
			"isAdmin" => $this->isAdmin
		];
	}

	public static function all(DB $db): array
	{
		$usersFetch = $db->select(User::TABLE_NAME, ["id"], []);

		if(!$usersFetch["status"])
		{
			return [];
		}

		foreach($usersFetch["data"] as $key => $user)
		{
			$usersFetch["data"][$key] = new User($db, $user["id"]);
		}

		return $usersFetch["data"];
	}

	public static function isAdmin(DB $db, int $userId): bool
	{
		$userFetch = $db->select(User::TABLE_NAME, ["user_id"], ["id" => $userId]);

		return true;
	}

	public static function createAdmin(DB $db, string $userName, string $password): bool
	{
		try
		{
			if(!preg_match("/^[a-zA-Z0-9-]+$/", $userName))
			{
				Log::write("Error creating admin: User Name can only contain letters, numbers and hyphens");
				return false;
			}

			if(strlen($password) < 8)
			{
				Log::write("Error creating admin: Password must be at least 8 characters long");
				return false;
			}

			$email = "$userName@admin.com";
			$hashedPassword = hash("sha256", USER::HASH_KEY_PASSWORD . $password);

			$dataAdmin = [
				"name" => $userName,
				"email" => $email,
				"user_name" => $userName,
				"password" => $hashedPassword
			];

			$userCreated = $db->insert(User::TABLE_NAME, $dataAdmin);

			if(!$userCreated["status"])
			{
				Log::write("Error creating admin: Failed to create user");
				return false;
			}

			$adminCreated = $db->insert(User::ADMIN_TABLE_NAME, ["user_id" => $userCreated["id"]]);

			if(!$adminCreated["status"])
			{
				Log::write("Error creating admin: Failed to set user as admin");
				return false;
			}

			return true;
		}
		catch (PDOException $e)
		{
			Log::write("Error creating admin: " . $e->getMessage());
			return false;
		}
	}
}