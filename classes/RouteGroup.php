<?php

class RouteGroup
{
	protected string $prefix;
	protected array $routes;
	protected array $middlewares;

	public function __construct(string $prefix, array $routes, array $middlewares = [])
	{
		$this->prefix = $prefix;
		$this->routes = $routes;

		if(!empty($middlewares))
		{
			$this->middlewares = array_unique($middlewares);
		}
		else
		{
			$this->middlewares = [];
		}
	}

	public function getRoute(string $path, Request $request): Route | RouteGroup | null
	{
		$routeSelected = null;

		if(empty($this->routes))
		{
			return $routeSelected;
		}

		foreach($this->routes as $route)
		{
			if(!empty($this->getMiddlewares()))
			{
				$route->setMiddlewares($this->getMiddlewares());
			}
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
				if($route->getMethod() == $request->getMethod())
				{
					if(preg_match('/^\/\{\w+\}$/', $route->getPath()) && $routeSelected === null)
					{
						$request->setUrlParameter($path);
						$routeSelected = $route;
					}

					if($route->getPath() == $path)
					{
						$request->setUrlParameter(null);
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

	public function getMiddlewares(): array
	{
		return $this->middlewares;
	}

	public function setMiddlewares(array $middlewares): void
	{
		$this->middlewares = array_merge($this->middlewares, $middlewares);
		$this->middlewares = array_unique($this->middlewares);
	}
}