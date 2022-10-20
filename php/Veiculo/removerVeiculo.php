<?php
//Será criado a session e ao verificar que a session não existe a página redireciona o mesmo para a index
session_start();
if ((!isset($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)) {
    header('location: ../index.php');
}

$logado = $_SESSION['usuario'];

//Importação de php
require '../config.php';

//$id_veiculo adquire o id repassado via AJAX
$id_veiculo = $_POST['id_veiculo'];

//Deleta do bd as informações da tabela veiculos de acordo com o $id_veiculo
$deletaUsuario = $mysql->prepare('DELETE FROM tb_veiculos WHERE id_veiculo = ?;');
$deletaUsuario->bind_param('i', $id_veiculo);
$deletaUsuario->execute();
