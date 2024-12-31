<?php

class Request
{
	private array $headers;
	private string $method;
	private array $queryParams;
	private array $body;
	private string | null $urlParameter;

	public function __construct(array $headers, string $method, array $queryParams, array $body, string | null $urlParameter)
	{
		$this->headers = $headers;
		$this->method = $method;
		$this->queryParams = $queryParams;
		$this->body = $body;
		$this->urlParameter = $urlParameter;
	}

	public function getHeaders(): array
	{
		return $this->headers;
	}

	public function getHeaderByName(string $name): string | null
	{
		return $this->headers[$name] ?? null;
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function getQueryParams(): array
	{
		return $this->queryParams;
	}

	public function getBody(): array
	{
		return $this->body;
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