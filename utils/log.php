<?php

function writeLog(string $message): void
{
	$date = date('Y-m-d H:i:s');
	$message = "$date - $message\n";

	file_put_contents(__DIR__ . '/../logs/error.log', $message, FILE_APPEND);
}