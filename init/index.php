<?php

include_once __DIR__ . '/../router/routes.php';
include_once __DIR__ . '/../config/env.php';
include_once __DIR__ . '/../db/DB.php';
include_once __DIR__ . '/../classes/Request.php';

$path = explode('?', $_SERVER['REQUEST_URI'])[0] ?? '/';

$path = explode('/', $path);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$queryParams = explode('?', $_SERVER['REQUEST_URI'])[1] ?? '';
$queryParams = explode('&', $queryParams);

$requestDataBody = json_decode(file_get_contents('php://input'), true) ?? [];

$path = array_values(array_filter(
	$path,
	function($item)
	{
		return $item !== '';
	}
));

$request = new Request(
	getallheaders(),
	$requestMethod,
	$queryParams,
	$requestDataBody,
	null
);

$routeSelected = null;

if(!empty($path))
{
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
			if($route->getMethod() === $requestMethod)
			{
				if(preg_match('/^\/\{\w+\}$/', $route->getPath()))
				{
					$routeSelected = $route;
					$request->setUrlParameter($path[0]);
				}

				if($route->getPath() === "/" . $path[0])
				{
					$routeSelected = $route;
					$request->setUrlParameter(null);
				}
			}
		}
	}
}
else
{
	if($_SERVER['REQUEST_URI'] === '/')
	{
		foreach($routes as $route)
		{
			if($route instanceof Route)
			{
				if($route->getPath() === $_SERVER['REQUEST_URI'])
				{
					$routeSelected = $route;
					break;
				}
			}
		}
	}
}

if($routeSelected !== null)
{
	$routeSelected = getSelectedRoute($routeSelected, array_slice($path, 1), $request);
}

if($routeSelected === null)
{
	header("HTTP/1.0 404 Not Found");
	exit;
}

$db = DB::connect(
	$_ENV['DB_HOST'],
	$_ENV['DB_NAME'],
	$_ENV['DB_USER'],
	$_ENV['DB_PASSWORD']
);

$return = $routeSelected->getControllerData($db, $request);

if($return['statusCode'] === 401)
{
	header("HTTP/1.0 401 Unauthorized");
	exit;
}

if($routeSelected->getContentType() === 'application/json')
{
	header('Content-Type: application/json');
	http_response_code($return['statusCode']);
	echo json_encode($return['data']);
}
else
{
	header('Content-Type: text/html');
	echo $return['html'];
}

exit;