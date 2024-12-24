<?php

class Route
{
	protected string $url;
	protected string $method;
	protected string $controllerPath;
	protected string $controllerName;
	protected string $contentType;

	private const CONTROLLERS_DIR = __DIR__ . '/../controllers/';

	public function __construct(string $url, string $method, string $controllerPath, $controllerName, string $contentType = 'application/json')
	{
		$this->url = $url;
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

	public function getUrl(): string
	{
		return $this->url;
	}
}