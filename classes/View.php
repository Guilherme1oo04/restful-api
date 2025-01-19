<?php

class View
{
	public static function render(string $view, array $data = []): void
	{
		extract($data);
		require_once __DIR__ . '/../views/' . $view . '/index.php';
	}
}