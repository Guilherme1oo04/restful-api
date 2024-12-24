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

	/**
	 * Retorna um array contendo os dados das colunas de uma tabela especificada e o status indicando o sucesso ou falha da operação
	 * @param string $table Nome da tabela
	 * @param array $columns Array contendo os nomes das colunas
	 * @param array $conditions Array contendo as condições das colunas, cada condição contém um array contendo:
	 * 		"column" => string,
	 * 		"value" => string,
	 * 		"operator" => string
	 * @return array
	 * 		"status" => bool,
	 * 		"data" => array
	 */
	public function select(string $table, array $columns, array $conditions): array
	{
		if($this->connection === null)
		{
			Log::write("Error selecting data: PDO Connection is invalid");
			return [
				"status" => false,
				"data" => []
			];
		}

		if(empty($columns))
		{
			Log::write("Error selecting data: Columns are empty");
			return [
				"status" => false,
				"data" => []
			];
		}

		$this->connection->beginTransaction();

		try
		{
			$columnsString = implode(", ", $columns);
			$query = "SELECT $columnsString FROM $table";

			if(!empty($conditions))
			{
				$query .= " WHERE ";
				$firstCondition = $conditions[0];
				$query .= $firstCondition["column"] . " " . $firstCondition["operator"] . " '" . $firstCondition["value"] . "'";

				$conditionsLength = count($conditions);
				for($i = 1; $i < $conditionsLength; $i++)
				{
					$query .= " AND " . $conditions[$i]["column"] . " " . $conditions[$i]["operator"] . " '" . $conditions[$i]["value"] . "'";
				}
			}

			$statement = $this->connection->prepare($query);
			$statement->execute();

			$data = $statement->fetchAll(PDO::FETCH_ASSOC);

			$this->connection->commit();

			return [
				"status" => true,
				"data" => $data
			];
		}
		catch(PDOException $e)
		{
			$this->connection->rollBack();
			Log::write("Error selecting data: " . $e->getMessage());
			return [
				"status" => false,
				"data" => []
			];
		}
	}

	/**
	 * Insere dados na tabela especificada e retorna a chave primária do registro inserido e o status indicando o sucesso ou falha da operação
	 * @param string $table Nome da tabela
	 * @param array $data Array contendo os dados a serem inseridos
	 * @return array
	 * 		"status" => bool,
	 * 		"id" => int
	 */
	public function insert(string $table, array $data): array
	{
		if($this->connection === null)
		{
			Log::write("Error inserting data: PDO Connection is invalid");
			return [
				"status" => false,
				"id" => null
			];
		}

		if(empty($data))
		{
			Log::write("Error inserting data: Data is empty");
			return [
				"status" => false,
				"id" => null
			];
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

			$id = $this->connection->lastInsertId();

			$this->connection->commit();

			return [
				"status" => true,
				"id" => $id
			];
		}
		catch(PDOException $e)
		{
			$this->connection->rollBack();
			Log::write("Error inserting data: " . $e->getMessage());
			return [
				"status" => false,
				"id" => null
			];
		}
	}
}