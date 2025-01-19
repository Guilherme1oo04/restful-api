<?php

class GetLoginPage
{
	public function __invoke(DB $db, Request $request): array
	{
		return [
			"statusCode" => 200,
			"viewName" => "login",
			"data" => [
				"teste" => "Teste 2"
			]
		];
	}
}