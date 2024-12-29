<?php

class GetUserById
{
	public function __invoke(DB $db, array $queryParams): array
	{
		return [
			"statusCode" => 200,
			"data" => []
		];
	}
}