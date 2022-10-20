<?php
// session_start inicia a sessão
session_start();
// as variáveis login e senha recebem os dados digitados na página anterior
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
$authToken = $_POST['authToken'];

//Importação de php
require 'config.php';
include 'Classes.php';

//Construtor de Classes
$objClasses = new Classes($mysql);

//$ usuarioRecebe recebe array com a informação do usuário se usuário e senha forem verdadeiras
$encontrado = $objClasses->encontrarUsuario($usuario, $senha);

//Se usuário não tiver entre 8 e 50 caracteres, retornará ao index
if ($encontrado == 0) {
    unset ($_SESSION['usuario']);
    unset ($_SESSION['senha']);
    unset ($_SESSION['authToken']);
    echo "<script>alert('Login tem que ter entre 8 e 50 caracteres');
        location.href=\"index.php\";</script>";
    die();
}
//Se senha não tiver entre 8 e 50 caracteres, retornará ao index
else if ($encontrado == 1) {
    unset ($_SESSION['usuario']);
    unset ($_SESSION['senha']);
    unset ($_SESSION['authToken']);
    echo "<script>alert('Senha tem que ter entre 8 e 50 caracteres');
        location.href=\"index.php\";</script>";
    die();
}
//Se usuário e senha não forem iguais à algum registro do bd, retornará ao index
else if ($encontrado == 2) {
    unset ($_SESSION['usuario']);
    unset ($_SESSION['senha']);
    unset ($_SESSION['authToken']);
    echo "<script>alert('Registro não encontrado');location.href=\"index.php\";</script>";
    die();
}
//Caso autenticação esteja OK e o usuário tiver permissão de administrador, encaminhará-lo à página de admin
else if ($encontrado == 3) {
    $_SESSION['usuario'] = $usuario;
    $_SESSION['senha'] = $senha;
    $_SESSION['authToken'] = $authToken;
    header("Location: admin.php");
    die();
}
/*Caso autenticação esteja OK e o usuário não tiver permissão de administrador, esteja OK encaminhará-lo 
    à página principal*/ else {
    $_SESSION['usuario'] = $usuario;
    $_SESSION['senha'] = $senha;
    $_SESSION['authToken'] = $authToken;
    header("Location: Reserva/principal.php");
    die();
}
