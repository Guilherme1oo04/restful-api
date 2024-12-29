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

	public function getRoute(string $path, string $requestMethod): Route | RouteGroup | null
	{
		$routeSelected = null;

		if(empty($this->routes))
		{
			return $routeSelected;
		}

		foreach($this->routes as $route)
		{
			if($route instanceof RouteGroup)
			{
				if($route->getPrefix() == $path)
				{
					$routeSelected = $route;
					break;
				}
			}

			if($route instanceof Route)
			{
				if($route->getMethod() == $requestMethod)
				{
					if(preg_match('/^\/\{\w+\}$/', $route->getPath()) && $routeSelected === null)
					{
						$route->setUrlParameter($path);
						$routeSelected = $route;
					}

					if($route->getPath() == $path)
					{
						$route->setUrlParameter(null);
						$routeSelected = $route;
					}
				}
			}
		}

		return $routeSelected;
	}

	public function getPrefix(): string
	{
		return $this->prefix;
	}
}