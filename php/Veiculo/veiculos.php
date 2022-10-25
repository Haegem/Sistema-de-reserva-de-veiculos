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

//$veiculos adquire as informações de todos os veículos do bd
$veiculos = $objClasses->exibirVeiculos();

?>

<!DOCTYPE html>
<html lang="PT-BR">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">

<link rel="stylesheet" href="../../css/reset.css">
<link rel="stylesheet" href="../../css/google-fonts.css">
<link rel="stylesheet" href="../../css/materialize.min.css">
<link rel="stylesheet" href="../../css/style.css">
<script src="../../js/jquery.js"></script>

<head>
    <title>
        ADMIN - VÉICULOS
    </title>
</head>

<body>
    <div class="container center">
        <h1>ADMIN - VEÍCULOS</h1>
        <a href="cadastrarVeiculo.php" 
        class="waves-effect waves-light btn-large">Cadastrar</a>

        <!-- Tabela que mostra os veículos cadastrados -->
        <form>
            <table>
                <thead>
                    <tr>
                        <th class="center">Imagem</th>
                        <th class="center">Veículos</th>
                        <th class="center">Status</th>
                        <th class="center">KM</th>
                        <th class="center"></th>
                        <th class="center"></th>
                        <th class="center"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($veiculos as $veiculo) : ?>
                        <tr>
                            <td class="center">
                                <p>
                                    <!-- Busca imagem em img de acordo com o nome do veículo -->
                                    <img src="../../img/<?php echo $veiculo['nome_veiculo'] ?>.jpg" height="50px" 
                                    width="50px">
                                </p>
                            </td>
                            <td class="center">
                                <!-- Retorna o nome do veículo -->
                                <p class="nomeVeiculo_<?php echo $veiculo['id_veiculo'] ?>">
                                    <?php echo $veiculo['nome_veiculo']; ?>
                                </p>
                            </td>
                            <td class="center">
                                <p>
                                    <?php
                                    /*
                                $dispVeiculo recebe array com a informação disponibilidade de acordo 
                                com nome do veículo passado como parâmetro
                                */
                                    $dispVeiculo = $objClasses->exibirDisponibilidade($veiculo['nome_veiculo']);

                                    //Caso veículo esteja disponível imagem verde na tela, se não imagem vermelha
                                    if ($dispVeiculo['disp_veiculo'] == 1) {
                                    ?>
                                <p class="center">
                                    <img src="../../img/Disponivel.png" height="25px" width="25px">
                                </p>
                            <?php
                                    } else {
                            ?>
                                <p class="center">
                                    <img src="../../img/Indisponivel.png" height="25px" width="25px">
                                </p>
                            <?php
                                    }
                            ?>
                            </p>
                            </td>
                            <td class="center">
                                <!-- Retorna o KM do veículo -->
                                <p>
                                    <?php echo $veiculo['km_veiculo']; ?>
                                </p>
                            </td>
                            <td class="center">
                                <!-- Se o botão bloquear for pressionado, bloqueará o veículo cadastrado -->
                                <p>
                                    <button class="btnBloquear_<?php echo $veiculo['id_veiculo'] ?> 
                                    waves-effect waves-light btn" 
                                    onclick="bloqueia('<?php echo $veiculo['id_veiculo'] ?>');">
                                    <?php
                                        //Caso veiculo esteja disponível, o botão terá valor BLOQUEAR
                                        if($veiculo['disp_veiculo'] == 1){
                                            ?>
                                                BLOQUEAR
                                            <?php
                                        }
                                        //Caso veiculo esteja indisponível, o botão terá valor DESBLOQUEAR
                                        else{
                                            ?>
                                                DESBLOQUEAR
                                            <?php
                                        }
                                    ?>
                                </p>
                            </td>
                            <td class="center">
                                <!-- Se o botão editar for pressionado, encaminhará página para editarVeiculo -->
                                <p>
                                    <button class="btnEditar_<?php echo $veiculo['id_veiculo'] ?> 
                                    waves-effect waves-light btn" 
                                    onclick="edita('<?php echo $veiculo['id_veiculo'] ?>');">EDITAR
                                </p>
                            </td>
                            <td>
                                <!-- Se o botão remover for pressionado, removerá o veículo cadastrado -->
                                <p>
                                    <button class="btnRemover_<?php echo $veiculo['id_veiculo'] ?> 
                                    btn waves-effect waves-light" 
                                    onclick="remove('<?php echo $veiculo['id_veiculo'] ?>');">REMOVER
                                </p>
                            </td>
                        <?php
                    endforeach; ?>
                        </tr>
                </tbody>
            </table>

            <a href="../admin.php" 
            class="waves-effect waves-light btn">Voltar</a>
        </form>
    </div>
    <script>
        //Função que bloqueia o veículo selecionado
        function bloqueia(id_veiculo) {            
            var dados = {
                id_veiculo: id_veiculo
            };

            $.ajax({
                url: "bloquear.php",
                type: "post",
                dataType: "html",
                data: dados,
                success: function() {
                    location.reload(true);
                },
                error: function() {
                    alert("Erro");
                }
            });
        }

        //Função que encaminha para editarVeiculo de acordo com o veículo selecionado
        function edita(id_veiculo) {
            var dados = {
                id_veiculo: id_veiculo,
            };

            $.ajax({
                url: "editarVeiculo.php?id_veiculo=" + id_veiculo,
                type: "post",
                dataType: "html",
                data: dados,
                success: function() {
                    window.location = "editarVeiculo.php?id_veiculo=" + id_veiculo;
                },
                error: function() {
                    alert("Erro");
                }
            });
        }

        //Função que remove o veículo selecionado
        function remove(id_veiculo) {
            var dados = {
                id_veiculo: id_veiculo
            };

            $.ajax({
                url: "removerVeiculo.php",
                type: "post",
                dataType: "html",
                data: dados,
                success: function() {
                    location.reload();
                },
                error: function() {
                    alert("Erro");
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