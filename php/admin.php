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
require 'config.php';
include 'Classes.php';

//Construtor de Classes
$objClasses = new Classes($mysql);

?>

<!DOCTYPE html>
<html lang="PT-BR">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">

<link rel="stylesheet" href="../css/reset.css">
<link rel="stylesheet" href="../css/materialize.min.css">
<link rel="stylesheet" href="../css/google-fonts.css">
<link rel="stylesheet" href="../css/style.css">
<script src="../js/jquery.js"></script>

<head>
    <title>
        ADMIN
    </title>
</head>

<body>
    <div class="container center">
        <!-- Botão sair (Retorna para login) -->
        <a href="index.php" class="botao-sair waves-effect waves-light btn-large"
        onclick="destruirSessao()">Sair</a>

        <h1 class="titulo-admin">ADMIN</h1>

        <!-- Botões para redirecionar para a sessão desejada -->
        <div class="botoes">
            <a href="Reserva/principal.php" 
            class="waves-effect waves-light btn-large">Reservar</a>
            <a href="Veiculo/veiculos.php" 
            class="waves-effect waves-light btn-large">Veículos</a>
            <a href="Reserva/reservas.php" 
            class="waves-effect waves-light btn-large">Reservas</a>
            <a href="Usuario/usuarios.php" 
            class="waves-effect waves-light btn-large">Usuarios</a>
        </div>
    </div>

    <script>
    //Função que remove a reserva selecionada
    function destruirSessao() {
        $.ajax({
            url: "destruirSessao.php",
            type: "post",
            dataType: "html",
            success: function() {
                alert('Deslogado com sucesso');
            },
            error: function() {
                alert("Erro");
            }
        });
    }
    </script>

    <script src="../slick/slick.min.js"></script>
    <script src="../js/slider.js"></script>
    <script src="../js/main.js"></script>
</body>
<footer>
</footer>

</html>