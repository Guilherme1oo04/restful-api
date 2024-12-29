<?php

include __DIR__ . '/../classes/Route.php';
include __DIR__ . '/../classes/RouteGroup.php';

function getSelectedRoute(Route | RouteGroup | null $route, array $path, string $requestMethod): Route | RouteGroup | null
{
	if($route instanceof RouteGroup)
	{
		if(empty($path))
		{
			return getSelectedRoute($route->getRoute("/", $requestMethod), [], $requestMethod);
		}

		return getSelectedRoute($route->getRoute("/" . $path[0], $requestMethod), array_slice($path, 1), $requestMethod);
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
		new Route('/teste', 'GET', 'user/GetUsers', 'GetUsers'),
	]),
];