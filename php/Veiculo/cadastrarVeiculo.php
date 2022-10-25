<?php
//Será criado a session e ao verificar que a session não existe a página redireciona o mesmo para a index
session_start();
if ((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)) {
    header('location: ../index.php');
}

$logado = $_SESSION['email'];

//Importação de php
require '../config.php';
include '../Classes.php';

//Construtor de Classes
$objClasses = new Classes($mysql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //$verificaNome recebe boolean de acordo com nome do veículo selecionado
    $verificaNome = $objClasses->verificaExisteVeiculo($_POST['nome_veiculo']);
    
    //Caso não exista um veículo com o mesmo nome
    if ($verificaNome) {
        /*
        Cadastra no banco de dados um novo veículo com as informações preenchidas no formulário
        e armazena em $veiculo o retorno da função cadastrarVeiculo
        */
        $veiculo = $objClasses->cadastrarVeiculo($_POST['nome_veiculo'], $_POST['km_veiculo']);
        //Se o nome exceder 50 caracteres, retornará à página cadastrarVeiculo
        if ($veiculo == 0) {
            echo "<script>alert('Nome não pode exceder 50 caracteres');
            location.href=\"cadastrarVeiculo.php\";</script>";
            die();
        }
        //Se o KM exceder 50 caracteres, retornará à página cadastrarVeiculo
        else if ($veiculo == 1) {
            echo "<script>alert('KM não pode exceder 10 caracteres');
            location.href=\"cadastrarVeiculo.php\";</script>";
            die();
        }
        //Se tudo estiver OK, informações serão registradas no bd e será retornado à página cadastrarVeiculo
        else {
            header("Location: cadastrarVeiculo.php");
            die();
        }
    }
    //Caso exista um veículo com o mesmo nome, retornará à página cadastrarVeiculo
    else {
        echo "<script>alert('Veículo já cadastrado');
        location.href=\"cadastrarVeiculo.php\";</script>";
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
        CADASTRAR VEÍCULO
    </title>
</head>

<body>
    <div class="container center">
        <h1>CADASTRAR NOVO VEÍCULO</h1>

        <!-- Formulário para cadastrar um novo veículo -->
        <form action="cadastrarVeiculo.php" method="post">
            <label for="nome_veiculo">NOME</label>
            <input type="text" name="nome_veiculo" id="nome_veiculo" required placeholder="Nome">
            <label for="km_veiculo">KM</label>
            <input type="text" name="km_veiculo" id="km_veiculo" required placeholder="KM" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
            <input type="submit" class="btn waves-effect waves-light" name="action" id="btnCadastrar" value="CADASTRAR">
            <a href="veiculos.php" class="waves-effect waves-light btn">Voltar</a>
        </form>
    </div>

    <script>
        //Declaração e atribuição de valor das variáveis
        var nome = $("#nome_veiculo");
        var km = $('#km_veiculo');

        //Inicializador da página 
        $(function() {
            inicializaMarcadoresVeiculos();
        });

        //Função para trocar borda do container na página de edição
        function inicializaMarcadoresVeiculos() {
            nome.on("input", function() {
                if (nome.val().length == 0 || km.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 50 || km.val().length >= 10) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
            });
            km.on("input", function() {
                if (nome.val().length == 0 && km.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nome.val().length >= 50 || km.val().length >= 10) {
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