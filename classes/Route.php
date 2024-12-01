<?php

class Route
{
	protected string $url;
	protected string $method;

	public function __construct(string $url, string $method)
	{
		$this->url = $url;
		$this->method = $method;
	}
}