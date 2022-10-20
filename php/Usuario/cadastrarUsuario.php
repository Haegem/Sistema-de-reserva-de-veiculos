<?php
//Será criado a session e ao verificar que a session não existe a página redireciona o mesmo para a index
session_start();
if ((!isset($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)) {
    header('location: ../index.php');
}

$logado = $_SESSION['usuario'];

//Importação de php
require '../config.php';
include '../Classes.php';

//Construtor de Classes
$objClasses = new Classes($mysql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //$verificaEmail recebe boolean após verificar se o login digitado já existe no bd
    $verificaEmail = $objClasses->verificaExisteUsuario($_POST['usuarioCad']);

    //Caso ainda não existir login no bd
    if ($verificaEmail) {
        //Se o checkbox para habilitar administrador ao usuário for selecionado, $habilitaAdm receberá boolean
        if ($_POST['adm'] == null) {
            $habilitaAdm = 0;
        } else {
            $habilitaAdm = 1;
        }

        /*
        Cadastra no banco de dados um novo usuário com as informações preenchidas no formulário
        e armazena em $usuario o retorno da função cadastrarUsuario
        */
        $usuario = $objClasses->cadastrarUsuario(
            $_POST['usuarioCad'],
            $_POST['senhaCad'],
            $_POST['nome'],
            $_POST['area'],
            $_POST['confirmarSenha'],
            $habilitaAdm
        );
        //Se e-mail não tiver entre 8 e 50 caracteres, retornará ao cadastrarUsuario
        if ($usuario == 0) {
            echo "<script>alert('Email tem que ter entre 8 e 50 caracteres');
            location.href=\"cadastrarUsuario.php\";</script>";
            die();
        }
        //Se senha não tiver entre 8 e 50 caracteres, retornará ao cadastrarUsuario
        else if ($usuario == 1) {
            echo "<script>alert('Senha tem que ter entre 8 e 50 caracteres');
            location.href=\"cadastrarUsuario.php\";</script>";
            die();
        }
        //Se o nome exceder 100 caracteres, retornará à página cadastrarUsuario
        else if ($usuario == 2) {
            echo "<script>alert('Nome não pode exceder 50 caracteres');
            location.href=\"cadastrarUsuario.php\";</script>";
            die();
        }
        //Se o área exceder 50 caracteres, retornará à página cadastrarUsuario
        else if ($usuario == 3) {
            echo "<script>alert('Área não pode exceder 50 caracteres');
            location.href=\"cadastrarUsuario.php\";</script>";
            die();
        }
        //Se tudo estiver OK, informações serão registradas no bd e será retornado à página cadastrarUsuário
        else if ($usuarioEditado == 4) {
            header("Location: cadastrarUsuario.php");
            die();
        }
        //Se a senha e a confirmação da senha não forem iguais, retornará à editarUsuario
        else {
            echo "<script>alert('Senha e confirmação de senha não são iguais!');
            location.href=\"editarUsuario.php\";</script>";
            die();
        }
    }
    //Caso já existir login no bd, retornará à página principal
    else {
        echo "<script>alert('Email já cadastrado');
        location.href=\"cadastrarUsuario.php\";</script>";
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
        CADASTRAR USUÁRIO
    </title>
</head>

<body>
    <div class="container center">
        <h1>CADASTRAR NOVO USUÁRIO</h1>

        <!-- Formulário para cadastrar um novo usuário -->
        <form action="cadastrarUsuario.php" method="post">
            <label for="nome">NOME</label>
            <input type="text" name="nome" id="nome" required placeholder="Nome">
            <label for="area">ÁREA</label>
            <input type="text" name="area" id="area" required placeholder="Área">
            <label for="usuarioCad">LOGIN</label>
            <input type="email" name="usuarioCad" id="usuarioCad" required placeholder="E-mail">
            <label for="senhaCad">SENHA</label>
            <input type="password" name="senhaCad" id="senhaCad" required placeholder="Senha">
            <label for="confirmarSenha">REPETIR SENHA</label>
            <input type="password" name="confirmarSenha" id="confirmarSenha" required placeholder="Repita sua senha">
            <div>
                <input type="checkbox" id="adm" name="adm" value="1">
                <label for="adm">Habilitar administrador</label>
            </div>

            <input type="submit" class="btn waves-effect waves-light" name="action" id="btnCadastrar" 
            value="CADASTRAR">
            <a href="usuarios.php" 
            class="waves-effect waves-light btn">Voltar</a>
        </form>
    </div>

    <script>
        //Declaração e atribuição de valor das variáveis
        var nome = $("#nome");
        var area = $('#area');
        var email = $("#usuarioCad");
        var senhaCad = $("#senhaCad");
        var confirmarSenha = $("#confirmarSenha");

        //Inicializador da página 
        $(function() {
            inicializaMarcadoresUsuarios();
        });

        //Função para trocar borda do container na página de edição
        function inicializaMarcadoresUsuarios() {
            nome.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                 senhaCad.val().length == 0 ||
                    confirmarSenha.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    senhaCad.val().length >= 50 || confirmarSenha.val().length >= 50 ||
                    senhaCad.val().length < 8 || confirmarSenha.val().length < 8) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
            });
            area.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                 senhaCad.val().length == 0 ||
                    confirmarSenha.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    senhaCad.val().length >= 50 || confirmarSenha.val().length >= 50 ||
                    senhaCad.val().length < 8 || confirmarSenha.val().length < 8) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
            });
            email.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                 senhaCad.val().length == 0 ||
                    confirmarSenha.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    senhaCad.val().length >= 50 || confirmarSenha.val().length >= 50 ||
                    senhaCad.val().length < 8 || confirmarSenha.val().length < 8) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
            });
            senhaCad.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                 senhaCad.val().length == 0 ||
                    confirmarSenha.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    senhaCad.val().length >= 50 || confirmarSenha.val().length >= 50 ||
                    senhaCad.val().length < 8 || confirmarSenha.val().length < 8) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
            });
            confirmarSenha.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                 senhaCad.val().length == 0 ||
                    confirmarSenha.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    senhaCad.val().length >= 50 || confirmarSenha.val().length >= 50 ||
                    senhaCad.val().length < 8 || confirmarSenha.val().length < 8) {
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