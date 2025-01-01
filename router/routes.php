<?php

include_once __DIR__ . '/../classes/Route.php';
include_once __DIR__ . '/../classes/RouteGroup.php';
include_once __DIR__ . '/../utils/Archive.php';

Archive::includeAll(
	__DIR__ . '/../controllers',
	'php',
	[
		'Middleware.php'
	]
);

function getSelectedRoute(Route | RouteGroup | null $route, array $path, Request &$request): Route | RouteGroup | null
{
	if($route instanceof RouteGroup)
	{
		if(empty($path))
		{
			return getSelectedRoute($route->getRoute("/", $request), [], $request);
		}

		return getSelectedRoute($route->getRoute("/" . $path[0], $request), array_slice($path, 1), $request);
	}

	if(count($path) > 0)
	{
		return null;
	}

	return $route;
}

$routes = [
	new RouteGroup('/api', [
		new RouteGroup('/users', [
			new Route('/', 'GET', 'user/GetUsers', 'GetUsers'),
			new Route('/{id}', 'GET', 'user/GetUserById', 'GetUserById'),
		]),
	], [AuthApiMiddleware::class]),
	new Route('/login', 'GET', 'auth/GetLoginPage', 'GetLoginPage', 'text/html'),
];