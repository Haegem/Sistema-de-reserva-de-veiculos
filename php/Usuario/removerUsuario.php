<?php
//Será criado a session e ao verificar que a session não existe a página redireciona o mesmo para a index
session_start();
$authToken = $_SESSION['authToken'];
if ((!isset($_SESSION['email']) == true) && (!isset($_SESSION['senha']) == true)) {
    header("Location: ../index.php");
}else if($authToken != md5(date("Ymd") * 5)){
    echo "<script>alert('Chave de seguranca invalida!');location.href=\"../index.php\";</script>";
    die();
}

$logado = $_SESSION['email'];

//Importação de php
include '../config.php';

//$id_usuarios adquire o id repassado na URL
$usuario = $_POST['id_usuario'];

//Deleta do bd as informações da tabela usuarios de acordo com o $id_usuarios
$deletaUsuario = $mysql->prepare('DELETE FROM tb_usuarios WHERE id_usuario = ?;');
$deletaUsuario->bind_param('i', $id_usuario);
$deletaUsuario->execute();
