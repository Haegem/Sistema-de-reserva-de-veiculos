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

//Arquivo para retornar duas páginas se chamado
?>
<script>
    window.history.go(-2);
</script>