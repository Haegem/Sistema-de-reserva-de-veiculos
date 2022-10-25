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
require '../config.php';

//$id_reserva adquire o id repassado via AJAX
$id_reserva = $_POST['id_reserva'];

//Deleta do bd as informações da tabela reserva de acordo com o $id_reserva
$deletaReserva = $mysql->prepare('DELETE FROM tb_reservas WHERE id_reserva = ?;');
$deletaReserva->bind_param('i', $id_reserva);
$deletaReserva->execute();
