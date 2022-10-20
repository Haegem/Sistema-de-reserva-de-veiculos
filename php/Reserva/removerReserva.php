<?php
//Será criado a session e ao verificar que a session não existe a página redireciona o mesmo para a index
session_start();
if ((!isset($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)) {
    header('location: ../index.php');
}

$logado = $_SESSION['usuario'];

//Importação de php
require '../config.php';

//$id_reserva adquire o id repassado via AJAX
$id_reserva = $_POST['id_reserva'];

//Deleta do bd as informações da tabela reserva de acordo com o $id_reserva
$deletaReserva = $mysql->prepare('DELETE FROM tb_reserva WHERE id_reserva = ?;');
$deletaReserva->bind_param('i', $id_reserva);
$deletaReserva->execute();
