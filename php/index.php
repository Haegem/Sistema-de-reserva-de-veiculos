<?php

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
        LOGIN
    </title>
</head>

<body>
    <div class="container center">
        <h1>LOGIN SISTEMA RESERVA DE VEÍCULOS</h1>
        <!-- Formulário para preencher login e senha -->
        <form action="ope.php" method="post">
            <label for="usuario">LOGIN</label>
            <input type="email" name="usuario" id="usuario" required placeholder="Nome de usuário">
            <label for="senha">SENHA</label>
            <input type="password" name="senha" id="senha" required placeholder="Sua senha">
            <input type="hidden" name="authToken" id="authToken" value="<?php echo md5(date("Ymd") * 5) ?>">

            <input type="submit" class="btn waves-effect waves-light" name="action" id="btnEntrar" value="ENTRAR">
        </form>
    </div>

    <script src="../slick/slick.min.js"></script>
    <script src="../js/slider.js"></script>
    <script src="../js/main.js"></script>
</body>
<footer>
</footer>

</html>