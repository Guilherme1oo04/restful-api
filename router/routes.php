<?php

if(!defined('__INCLUDE__'))
{
	http_response_code(404);
}

include __DIR__ . '/../classes/Route.php';

$routes = [
    '/teste' => new Route('/teste', 'GET')
];