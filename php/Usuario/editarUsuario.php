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
include '../Classes.php';

//Construtor de Classes
$objClasses = new Classes($mysql);

//$user adquire todas as informações do usuário referente ao id_usuarios repassado como parâmetro
$user = $objClasses->exibirUsuario($_GET['id_usuario']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*
    Edita no banco de com as informações preenchidas no formulário de acordo com o id_usuarios 
    e armazena em $usuarioEditado o retorno da função editarUsuario
    */
    $usuarioEditado = $objClasses->editarUsuario(
        $_GET['id_usuario'],
        $_POST['emailEdit'],
        $_POST['senhaEdit'],
        $_POST['nome_usuario'],
        $_POST['area_usuario'],
        $_POST['confirmarSenha']
    );

    //Se o login não tiver entre 8 e 50 caracteres, retornará ao editarUsuario
    if ($usuarioEditado == 0) {
        echo "<script>alert('Login tem que ter entre 8 e 50 caracteres');
        location.href=\"editarUsuario.php?id_usuario=$user[id_usuario]\";</script>";
        die();
    }
    //Se a senha não tiver entre 8 e 50 caracteres, retornará ao editarUsuario
    else if ($usuarioEditado == 1) {
        echo "<script>alert('Senha tem que ter entre 8 e 50 caracteres');
        location.href=\"editarUsuario.php?id_usuario=$user[id_usuario]\";</script>";
        die();
    }
    //Se o nome exceder 100 caracteres, retornará à página editarUsuario
    else if ($usuarioEditado == 2) {
        echo "<script>alert('Nome não pode exceder 100 caracteres');
        location.href=\"editarUsuario.php?id_usuario=$user[id_usuario]\";</script>";
        die();
    }
    //Se a área exceder 50 caracteres, retornará à página editarUsuario
    else if ($usuarioEditado == 3) {
        echo "<script>alert('Área não pode exceder 50 caracteres');
        location.href=\"editarUsuario.php?id_usuario=$user[id_usuario]\";</script>";
        die();
    }
    //Se tudo estiver OK, informações serão atualizadas no bd e será retornado à página editarUsuario
    else if ($usuarioEditado == 4) {
        header("Location: editarUsuario.php?id_usuario=$user[id_usuario]");
        die();
    }
    //Se a senha e a confirmação da senha não forem iguais, retornará à editarUsuario
    else {
        echo "<script>alert('Senha e confirmação de senha não são iguais!');
        location.href=\"editarUsuario.php?id_usuario=$user[id_usuario]\";</script>";
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="PT-BR">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">

<link rel="stylesheet" href="../../css/reset.css">
<link rel="stylesheet" href="../../css/materialize.min.css">
<link rel="stylesheet" href="../../css/google-fonts.css">
<link rel="stylesheet" href="../../css/style.css">

<script src="../../js/jquery.js"></script>

<head>
    <title>
        EDITAR USUÁRIO
    </title>
</head>

<body>
    <div class="container center">
        <h1>EDITAR USUÁRIO</h1>

        <!-- Formulário para cadastrar um novo usuário, com as informações do usuário selecionadas, sendo
        carregadas do bd -->
        <form action="editarUsuario.php?id_usuario=<?php echo $user['id_usuario'] ?>" method="POST">
            <label for="nome">NOME</label>
            <input type="text" name="nome" id="nome" value="<?php echo $user['nome_usuario'] ?>">
            <label for="area">ÁREA</label>
            <input type="text" name="area" id="area" value="<?php echo $user['area_usuario'] ?>">
            <label for="emailEdit">LOGIN</label>
            <input type="email" name="emailEdit" id="emailEdit" value="<?php echo $user['email_usuario'] ?>">
            <label for="senhaEdit">SENHA</label>
            <input type="password" name="senhaEdit" id="senhaEdit" required placeholder="Senha">
            <label for="confirmarSenha">REPETIR SENHA</label>
            <input type="password" name="confirmarSenha" id="confirmarSenha" required placeholder="Repita sua senha">

            <input type="hidden" name="btnEditar_<?php echo $logado['email'] ?> 
            value=<?php echo $logado['email'] ?>"
            class="btnEditar_<?php echo $logado['email'] ?>  btn waves-effect waves-light" />
            <button class="btnEditar_<?php echo $logado['email'] ?>  
            btn waves-effect waves-light">EDITAR</button>
            <a href="../voltar.php" class="waves-effect waves-light btn">Voltar</a>
        </form>
    </div>

    <script>
        //Declaração e atribuição de valor das variáveis
        var nome = $("#nome_usuario");
        var area = $('#area_usuario');
        var usuarioEdit = $('#emailEdit');
        var senhaEdit = $("#senhaEdit");
        var confirmarSenha = $("#confirmarSenha");

        //Inicializador da página 
        $(function() {
            inicializaMarcadoresEdicao();
        });

        //Função para trocar borda do container na página de edição
        function inicializaMarcadoresEdicao() {
            nome.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || usuarioEdit.val().length == 0 ||
                    senhaEdit.val().length == 0 || confirmarSenha.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || usuarioEdit.val().length >= 50 ||
                    senhaEdit.val().length >= 50 || confirmarSenha.val().length >= 50 ||
                    senhaEdit.val().length < 8 || confirmarSenha.val().length < 8) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
            });
            area.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || usuarioEdit.val().length == 0 ||
                    senhaEdit.val().length == 0 || confirmarSenha.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || usuarioEdit.val().length >= 50 ||
                    senhaEdit.val().length >= 50 || confirmarSenha.val().length >= 50 ||
                    senhaEdit.val().length < 8 || confirmarSenha.val().length < 8) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
            });
            usuarioEdit.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || usuarioEdit.val().length == 0 ||
                    senhaEdit.val().length == 0 || confirmarSenha.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || usuarioEdit.val().length >= 50 ||
                    senhaEdit.val().length >= 50 || confirmarSenha.val().length >= 50 ||
                    senhaEdit.val().length < 8 || confirmarSenha.val().length < 8) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
            });
            senhaEdit.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || usuarioEdit.val().length == 0 ||
                    senhaEdit.val().length == 0 || confirmarSenha.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || usuarioEdit.val().length >= 50 ||
                    senhaEdit.val().length >= 50 || confirmarSenha.val().length >= 50 ||
                    senhaEdit.val().length < 8 || confirmarSenha.val().length < 8) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
            });
            confirmarSenha.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || usuarioEdit.val().length == 0 ||
                    senhaEdit.val().length == 0 || confirmarSenha.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || usuarioEdit.val().length >= 50 ||
                    senhaEdit.val().length >= 50 || confirmarSenha.val().length >= 50 ||
                    senhaEdit.val().length < 8 || confirmarSenha.val().length < 8) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
            });
        }
    </script>

    <script src="../../slick/slick.min.js"></script>
    <script src="../../js/slider.js"></script>
    <script src="../../js/main.js"></script>
</body>
<footer>
</footer>

</html>