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

//$veiculo adquire todas as informações do veiculo referente ao id_veiculo repassado como parâmetro
$veiculo = $objClasses->exibirVeiculo($_GET['id_veiculo']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /*
    Edita no banco de com as informações preenchidas no formulário de acordo com o id_veiculo 
    e armazena em $veiculoEditado o retorno da função editarVeiculo
    */
    $veiculoEditado = $objClasses->editarVeiculo($_GET['id_veiculo'], $_POST['nome'], $_POST['km']);

    //Se o nome exceder 50 caracteres, retornará à página editarVeiculo
    if ($veiculoEditado == 0) {
        echo "<script>alert('Nome não pode exceder 50 caracteres');
        location.href=\"editarVeiculo.php?id_veiculo=$veiculo[id_veiculo]\";</script>";
        die();
    }
    //Se o KM exceder 50 caracteres, retornará à página editarVeiculo
    else if ($veiculoEditado == 1) {
        echo "<script>alert('KM não pode exceder 50 caracteres');
        location.href=\"editarVeiculo.php?id_veiculo=$veiculo[id_veiculo]\";</script>";
        die();
    }
    //Se tudo estiver OK, informações serão atualizadas no bd e será retornado à página editarVeiculo
    else {
        header("Location: editarVeiculo.php?id_veiculo=$veiculo[id_veiculo]");
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
        EDITAR VEÍCULO
    </title>
</head>

<body>
    <div class="container center">
        <h1>EDITAR VEÍCULO</h1>

        <!-- Formulário para solicitar reserva -->
        <form action="editarVeiculo.php?id_veiculo=<?php echo $veiculo['id_veiculo'] ?>" method="POST">
            <label for="nome">NOME</label>
            <input type="text" name="nome" id="nome" value="<?php echo $veiculo['nome'] ?>">
            <label for="km">KM</label>
            <input type="text" name="km" id="km" value="<?php echo $veiculo['km'] ?>">

            <input type="hidden" name="id_veiculo" value="<?php echo $veiculo['id_veiculo'] ?>" 
            class="btnEditar_<?php echo $veiculo['id_veiculo'] ?>  btn waves-effect waves-light" />
            <button class="btnEditar_<?php echo $veiculo['id_veiculo'] ?>  
            btn waves-effect waves-light">EDITAR</button>
            <a href="../voltar.php" class="waves-effect waves-light btn">Voltar</a>
        </form>
    </div>

    <script>
        //Declaração e atribuição de valor das variáveis
        var nomeVeiculo = $("#nome");
        var km = $("#km");

        //Inicializador da página 
        $(function() {
            inicializaMarcadoresEdicao();
        });

        //Função para trocar borda do container na página de edição
        function inicializaMarcadoresEdicao() {
            nomeVeiculo.on("input", function() {
                if (nomeVeiculo.val().length == 0 || km.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nomeVeiculo.val().length >= 50 || km.val().length >= 10) {
                    container.addClass("borda-vermelha");
                    container.removeClass("borda-verde");
                } else {
                    container.addClass("borda-verde");
                    container.removeClass("borda-vermelha");
                }
            });
            km.on("input", function() {
                if (nomeVeiculo.val().length == 0 || km.val().length == 0) {
                    container.addClass("borda-padrao");
                    container.removeClass("borda-verde");
                    container.removeClass("borda-vermelha");
                } else if (nomeVeiculo.val().length >= 50 || km.val().length >= 10) {
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