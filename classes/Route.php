<?php

if(!defined('__INCLUDE__'))
{
	http_response_code(404);
}

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