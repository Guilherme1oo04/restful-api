<?php

include __DIR__ . '/../../db/DB.php';
include __DIR__ . '/../env.php';
include __DIR__ . '/../../classes/Models/User.php';

if(empty($argv[1]))
{
	print_r("\n");
	print_r('O Nome do Usuário é obrigatório');
	print_r("\n");
	print_r("Exemplo de uso:\033[35m php \033[37m" . '.\config\factories\create_admin_user.php' . " \033[32m\"Nome do usuário\" \"Senha do usuário\"\033[37m");
	print_r("\n");
	print_r("\n");
	exit;
}

if(empty($argv[2]))
{
	print_r("\n");
	print_r('A Senha do Usuário é obrigatória');
	print_r("\n");
	print_r("Exemplo de uso:\033[33m php \033[37m" . '.\config\factories\create_admin_user.php' . " \033[36m\"Nome do usuário\" \"Senha do usuário\"\033[37m");
	print_r("\n");
	print_r("\n");
	exit;
}

$db = DB::connect(
	$_ENV['DB_HOST'],
	$_ENV['DB_NAME'],
	$_ENV['DB_USER'],
	$_ENV['DB_PASSWORD']
);

$admin_created = User::createAdmin($db, $argv[1], $argv[2]);

if($admin_created)
{
	print_r("\n");
	print_r('Usuário criado com sucesso');
	print_r("\n");
	print_r("\n");
	exit;
}
else
{
	print_r("\n");
	print_r('Erro ao criar usuário');
	print_r("\n");
	print_r("\n");
	exit;
}