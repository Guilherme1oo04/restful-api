<?php

include __DIR__ . '/../router/routes.php';
include __DIR__ . '/../config/env.php';
include __DIR__ . '/../db/DB.php';

$path = explode('/', $_SERVER['REQUEST_URI']);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$path = array_values(array_filter(
	$path,
	function($item)
	{
		return $item !== '';
	}
));

$routeSelected = null;

foreach($routes as $route)
{
	if($route instanceof RouteGroup)
	{
		if($route->getPrefix() === "/" . $path[0])
		{
			$routeSelected = $route;
			break;
		}
	}

	if($route instanceof Route)
	{
		if($route->getPath() === "/" . $path[0])
		{
			$routeSelected = $route;
			break;
		}
	}
}

if($routeSelected !== null)
{
	$routeSelected = getSelectedRoute($routeSelected, array_slice($path, 1));
}

if($routeSelected === null)
{
	header("HTTP/1.0 404 Not Found");
	exit;
}

var_dump($routeSelected);

exit;