<?php

include __DIR__ . '/../../classes/Models/User.php';

class GetUsers
{
	public function __invoke(DB $db, array $queryParams): array
	{
		$users = User::all($db);

		if(empty($users))
		{
			return [
				"statusCode" => 200,
				"data" => []
			];
		}

		$users = array_map(function($user) {
			return $user->toArray();
		}, $users);

		return [
			"statusCode" => 200,
			"data" => $users
		];
	}
}