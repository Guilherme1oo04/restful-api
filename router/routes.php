<?php

include __DIR__ . '/../classes/Route.php';
include __DIR__ . '/../classes/RouteGroup.php';

function getSelectedRoute(Route | RouteGroup | null $route, array $path): Route | RouteGroup | null
{
	if($route instanceof RouteGroup)
	{
		if(empty($path))
		{
			return getSelectedRoute($route->getRoute("/"), []);
		}

		return getSelectedRoute($route->getRoute("/" . $path[0]), array_slice($path, 1));
	}

	return $route;
}

$routes = [
	new RouteGroup('/api', [
		new RouteGroup('/users', [
			new Route('/', 'GET', 'user/GetUsers', 'GetUsers'),
			// new Route('/{id}', 'GET', ),
		]),
		new Route('/teste', 'GET', 'user/GetUsers', 'GetUsers'),
	]),
];