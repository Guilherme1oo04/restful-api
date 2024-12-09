<?php

include __DIR__ . '/../utils/log.php';

class DB
{
	protected $connection;

	private function __construct(PDO $connection)
	{
		$this->connection = $connection;
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

	public function createTables(): bool
	{
		$stringPath = __DIR__ . '/../config/migrations/tables.sql';
		$sqlTablesPath = realpath($stringPath);

		if(!file_exists($sqlTablesPath))
		{
			writeLog("File not found: $stringPath");
			return false;
		}

		$sqlTables = file_get_contents($sqlTablesPath);

		if($this->connection === null)
		{
			writeLog("PDO Connection is invalid");
			return false;
		}

		try
		{
			$this->connection->exec($sqlTables);
		}
		catch(PDOException $e)
		{
			writeLog("Error creating tables: " . $e->getMessage());
			return false;
		}

		return true;
	}
}