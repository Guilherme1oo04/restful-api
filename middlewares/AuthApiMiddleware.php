<?php

include_once __DIR__ . '/Middleware.php';
include_once __DIR__ . '/../classes/Models/ApiToken.php';

final class AuthApiMiddleware extends Middleware
{
	public static function handle(Request $request, DB $db): bool
	{
		$authorization = $request->getHeaderByName('Authorization');
		if(empty($authorization))
		{
			return false;
		}

		if(!ApiToken::validate($db, $authorization))
		{
			return false;
		}

		return true;
	}
}