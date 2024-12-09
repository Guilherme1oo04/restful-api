<?php

include __DIR__ . '/../../db/DB.php';
include __DIR__ . '/../env.php';

$db = DB::connect(
	$_ENV['DB_HOST'],
	$_ENV['DB_NAME'],
	$_ENV['DB_USER'],
	$_ENV['DB_PASSWORD']
);

$tablesCreation = $db->createTables();

if($tablesCreation)
{
	print_r('Tabelas criadas com sucesso');
}
else
{
	print_r('Erro ao criar tabelas');
}