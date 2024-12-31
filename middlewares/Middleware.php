<?php

abstract class Middleware
{
	abstract public static function handle(Request $request, DB $db): bool;
}