<?php

class User
{
	private int $id;
	private string $name;
	private string $email;
	private string $password;
	private string $is_admin;

	private DB $database;

	public const TABLE_NAME = "users";
	private const HASH_KEY_PASSWORD = "security";

	public function __construct(DB $db, int $id)
	{
		$this->id = $id;
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

			$idUserAdmin = $db->insert(User::TABLE_NAME, $dataAdmin);
		}
		catch (PDOException $e)
		{
			Log::write("Error creating admin: " . $e->getMessage());
			return false;
		}
	}
}