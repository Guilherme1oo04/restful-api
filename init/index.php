<?php

include __DIR__ . '/../router/routes.php';
include __DIR__ . '/../config/env.php';
include __DIR__ . '/../db/DB.php';

echo $_SERVER['REQUEST_URI'];

$db = DB::connect(
    $_ENV['DB_HOST'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASSWORD']
);

if($db !== null)
{
    var_dump($db->getConnection());
}