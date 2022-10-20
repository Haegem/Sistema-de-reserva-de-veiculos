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

//$encontra armazena o boolean de disponibilidade de acordo com o $id_veiculo
$encontra = $mysql->prepare("SELECT disponibilidade FROM tb_veiculos WHERE id_veiculo = ?");
$encontra->bind_param('i', $id_veiculo);
$encontra->execute();
$verificaDisp = $encontra->get_result()->fetch_assoc();

//Caso $verificaDisp seja falso, atualizará disponibilidade para true
if ($verificaDisp['disponibilidade'] == 0) {
    $bloqueiaVeiculo = $mysql->prepare('UPDATE tb_veiculos SET disponibilidade = true WHERE id_veiculo = ?;');
    $bloqueiaVeiculo->bind_param('i', $id_veiculo);
    $bloqueiaVeiculo->execute();
}
//Caso $verificaDisp seja verdadeiro, atualizará disponibilidade para false
else {
    $bloqueiaVeiculo = $mysql->prepare('UPDATE tb_veiculos SET disponibilidade = false WHERE id_veiculo = ?;');
    $bloqueiaVeiculo->bind_param('i', $id_veiculo);
    $bloqueiaVeiculo->execute();
}
