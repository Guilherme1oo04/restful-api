<?php

class RouteGroup
{
	protected $prefix;
	protected $routes;

	public function __construct(string $prefix, array $routes)
	{
		$this->prefix = $prefix;
		$this->routes = $routes;
	}

	public function getRoute(string $url, string $method): ?Route
	{
		if(empty($this->routes))
		{
			return null;
		}

		foreach($this->routes as $route)
		{
			if($route->getUrl() === $url && $route->getMethod() === $method)
			{
				return $route;
				break;
			}
		}
	}
}