<?php

class GetUserById
{
	public function __invoke(DB $db, Request $request): array
	{
		return [
			"statusCode" => 200,
			"data" => []
		];
	}
}