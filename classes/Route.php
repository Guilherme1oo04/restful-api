<?php

class Route
{
	protected string $path;
	protected string $method;
	protected string $controllerPath;
	protected string $controllerName;
	protected string $contentType;
	protected string | null $urlParameter;

	private const CONTROLLERS_DIR = __DIR__ . '/../controllers/';

	public function __construct(string $path, string $method, string $controllerPath, $controllerName, string $contentType = 'application/json', string $urlParameter = null)
	{
		$this->path = $path;
		$this->method = $method;
		$this->controllerPath = $controllerPath;
		$this->controllerName = $controllerName;
		$this->contentType = $contentType;
		$this->urlParameter = $urlParameter;
	}

	private function getControllerPath(): string
	{
		return realpath(self::CONTROLLERS_DIR . $this->controllerPath . '.php');
	}

	public function getControllerData(DB $db, array $requestData, array $queryParams): array
	{
		if(!file_exists($this->getControllerPath()))
		{
			return null;
		}

		require_once $this->getControllerPath();

		$class = new $this->controllerName();

		if($this->method === 'GET' || $this->method === 'DELETE')
		{
			return $class($db, $queryParams);
		}

		if($this->method === 'POST')
		{
			return $class($db, $requestData);
		}

		return $class($db, $requestData, $queryParams);
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

	public function getUrlParameter(): string | null
	{
		return $this->urlParameter;
	}

	public function setUrlParameter(string | null $urlParameter): void
	{
		$this->urlParameter = $urlParameter;
	}
}