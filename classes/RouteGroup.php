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

	public function getRoute(string $path): Route | RouteGroup | null
	{
		if(empty($this->routes))
		{
			return null;
		}

		foreach($this->routes as $route)
		{
			if($route instanceof RouteGroup)
			{
				if($route->getPrefix() == $path)
				{
					return $route;
					break;
				}
			}

			if($route instanceof Route)
			{
				if($route->getPath() == $path)
				{
					return $route;
					break;
				}
			}
		}

		return null;
	}

	public function getPrefix(): string
	{
		return $this->prefix;
	}
}