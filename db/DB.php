<?php

include __DIR__ . '/../utils/log.php';

class DB
{
	protected $connection;

	private function __construct(PDO $connection)
	{
		$this->connection = $connection;
	}

	public static function connect(string $host, string $dbName, string $user, string $password)
	{
		try
		{
			$connection = new PDO("mysql:host=$host;dbname=$dbName", $user, $password);
		}
		catch(PDOException $e)
		{
			writeLog("Connection failed: " . $e->getMessage());
			return null;
		}

		return new self($connection);
	}

	public function getConnection()
	{
		return $this->connection;
	}
}