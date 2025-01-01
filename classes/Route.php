<?php

class Route
{
	protected string $path;
	protected string $method;
	protected string $controllerPath;
	protected string $controllerName;
	protected string $contentType;
	protected array $middlewares;

	private const CONTROLLERS_DIR = __DIR__ . '/../controllers/';

	public function __construct(string $path, string $method, string $controllerPath, string $controllerName, string $contentType = 'application/json', array $middlewares = [])
	{
		$this->path = $path;
		$this->method = $method;
		$this->controllerPath = $controllerPath;
		$this->controllerName = $controllerName;
		$this->contentType = $contentType;

		if(!empty($middlewares))
		{
			$this->middlewares = array_unique($middlewares);
		}
		else
		{
			$this->middlewares = [];
		}
	}

	private function getControllerPath(): string
	{
		return realpath(self::CONTROLLERS_DIR . $this->controllerPath . '.php');
	}

	public function getControllerData(DB $db, Request $request): array
	{
		if(!file_exists($this->getControllerPath()))
		{
			return [
				"statusCode" => 404,
				"data" => []
			];
		}

		if(!empty($this->middlewares))
		{
			foreach($this->middlewares as $middleware)
			{
				include_once __DIR__ . '/../middlewares/' . $middleware . '.php';
				$validation = $middleware::handle($request, $db);

				if(!$validation)
				{
					return [
						"statusCode" => 401,
						"data" => []
					];
				}
			}
		}

		include_once $this->getControllerPath();

		$class = new $this->controllerName();

		return $class($db, $request);
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function getContentType(): string
	{
		return $this->contentType;
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