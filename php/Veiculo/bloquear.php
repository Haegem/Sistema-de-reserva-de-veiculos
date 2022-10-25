<?php
//Será criado a session e ao verificar que a session não existe a página redireciona o mesmo para a index
session_start();
if ((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)) {
    header('location: ../index.php');
}

$logado = $_SESSION['email'];

//Importação de php
require '../config.php';

//$id_veiculo adquire o id repassado via AJAX
$id_veiculo = $_POST['id_veiculo'];

//$encontra armazena o boolean de disp_veiculo de acordo com o $id_veiculo
$encontra = $mysql->prepare("SELECT disp_veiculo FROM tb_veiculos WHERE id_veiculo = ?");
$encontra->bind_param('i', $id_veiculo);
$encontra->execute();
$verificaDisp = $encontra->get_result()->fetch_assoc();

//Caso $verificaDisp seja falso, atualizará disp_veiculo para true
if ($verificaDisp['disp_veiculo'] == 0) {
    $bloqueiaVeiculo = $mysql->prepare('UPDATE tb_veiculos SET disp_veiculo = true WHERE id_veiculo = ?;');
    $bloqueiaVeiculo->bind_param('i', $id_veiculo);
    $bloqueiaVeiculo->execute();
}
//Caso $verificaDisp seja verdadeiro, atualizará disp_veiculo para false
else {
    $bloqueiaVeiculo = $mysql->prepare('UPDATE tb_veiculos SET disp_veiculo = false WHERE id_veiculo = ?;');
    $bloqueiaVeiculo->bind_param('i', $id_veiculo);
    $bloqueiaVeiculo->execute();
}
