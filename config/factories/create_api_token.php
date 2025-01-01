<?php

include __DIR__ . '/../../db/DB.php';
include __DIR__ . '/../env.php';
include __DIR__ . '/../../classes/Models/ApiToken.php';

if(empty($argv[1]))
{
	print_r("\n");
	print_r('O código do Usuário é obrigatório');
	print_r("\n");
	print_r("Exemplo de uso:\033[35m php \033[37m" . '.\config\factories\create_api_token.php' . " \033[32m\"Código do usuário\"\033[37m");
	print_r("\n");
	print_r("\n");
	exit;
}

if(!is_numeric($argv[1]))
{
	print_r("\n");
	print_r('O código do Usuário deve ser um número inteiro');
	print_r("\n");
	print_r("Exemplo de uso:\033[35m php \033[37m" . '.\config\factories\create_api_token.php' . " \033[32m\"10\"\033[37m");
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

$generateToken = ApiToken::generate($db, intval($argv[1]));

if($generateToken['status'] === true)
{
	print_r("\n");
	print_r('Token criado com sucesso!');
	print_r("\n");
	print_r("Token: \033[32m" . $generateToken['token'] . "\033[37m");
	print_r("\n");
	print_r("\n");
}
else
{
	print_r("\n");
	print_r('Erro ao criar token!');
	print_r("\n");
	print_r("\n");
}