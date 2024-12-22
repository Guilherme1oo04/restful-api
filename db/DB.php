<?php

include __DIR__ . '/../utils/Log.php';

/**
 * Classe de operações com o banco de dados
 */
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
			Log::write("Connection failed: " . $e->getMessage());
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
			Log::write("File not found: $stringPath");
			return false;
		}

		$sqlTables = file_get_contents($sqlTablesPath);

		if($this->connection === null)
		{
			Log::write("Error creating tables: PDO Connection is invalid");
			return false;
		}

		try
		{
			$this->connection->exec($sqlTables);
		}
		catch(PDOException $e)
		{
			Log::write("Error creating tables: " . $e->getMessage());
			return false;
		}

		return true;
	}

	public function insert(string $table, array $data): bool
	{
		if($this->connection === null)
		{
			Log::write("Error inserting data: PDO Connection is invalid");
			return false;
		}

		if(empty($data))
		{
			Log::write("Error inserting data: Data is empty");
			return false;
		}

		$this->connection->beginTransaction();

		try
		{
			$colunms = array_keys($data);

			$sql = "INSERT INTO $table (";
			$sql .= implode(", ", $colunms);
			$sql .= ") VALUES (";
			$sql .= implode(", ", array_fill(0, count($colunms), "?"));
			$sql .= ")";

			$statement = $this->connection->prepare($sql);

			$statement->execute(array_values($data));

			$this->connection->commit();
		}
		catch(PDOException $e)
		{
			$this->connection->rollBack();
			Log::write("Error inserting data: " . $e->getMessage());
			return false;
		}

		return true;
	}
}