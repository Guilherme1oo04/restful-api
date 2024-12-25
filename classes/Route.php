<?php

class Route
{
	protected string $path;
	protected string $method;
	protected string $controllerPath;
	protected string $controllerName;
	protected string $contentType;

	private const CONTROLLERS_DIR = __DIR__ . '/../controllers/';

	public function __construct(string $path, string $method, string $controllerPath, $controllerName, string $contentType = 'application/json')
	{
		$this->path = $path;
		$this->method = $method;
		$this->controllerPath = $controllerPath;
		$this->controllerName = $controllerName;
		$this->contentType = $contentType;
	}

	private function getControllerPath(): string
	{
		return realpath(self::CONTROLLERS_DIR . $this->controllerPath . '.php');
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function getPath(): string
	{
		return $this->path;
	}
}