<?php
//Será criado a session e ao verificar que a session não existe a página redireciona o mesmo para a index
session_start();
if ((!isset($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)) {
    header('location: ../index.php');
}

$logado = $_SESSION['usuario'];

//Importação de php
include '../config.php';

//$id_usuarios adquire o id repassado na URL
$usuario = $_POST['id_usuarios'];

//Deleta do bd as informações da tabela usuarios de acordo com o $id_usuarios
$deletaUsuario = $mysql->prepare('DELETE FROM tb_usuarios WHERE id_usuarios = ?;');
$deletaUsuario->bind_param('i', $id_usuarios);
$deletaUsuario->execute();
