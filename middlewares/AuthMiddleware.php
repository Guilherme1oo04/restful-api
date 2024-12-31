<?php

include_once __DIR__ . '/Middleware.php';
include_once __DIR__ . '/../classes/Models/User.php';

final class AuthMiddleware extends Middleware
{
	public static function handle(Request $request, DB $db): bool
	{
		$typeAuthorization = $request->getHeaderByName('Type-Authorization');
		if($typeAuthorization !== 'Api-Token' && $typeAuthorization !== 'User-Pass')
		{
			return false;
		}

		return true;
	}
}