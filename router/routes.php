<?php

include __DIR__ . '/../classes/Route.php';
include __DIR__ . '/../classes/RouteGroup.php';

$routes = [
	'/users' => new RouteGroup('/users', [
		new Route('/', 'GET', 'user/GetUsers', 'GetUsers'),
		// new Route('/{id}', 'GET', ),
	])
];