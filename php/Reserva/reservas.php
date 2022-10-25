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

//$reservas adquire as informações de todas as reservas do bd
$reservas = $objClasses->exibirInfoReservas();

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
        ADMIN - RESERVAS
    </title>
</head>

<body>
    <div class="container center">
        <h1>ADMIN - RESERVAS</h1>
        <!-- Tabela com as informações de todas as reservas realizadas -->
        <table>
            <thead>
                <tr>
                    <th>Veículo</th>
                    <th>Data/Hora</th>
                    <th>Motorista</th>
                    <th>Setor</th>
                    <th>Local</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $reserva) : ?>
                    <tr>
                        <td>
                            <!-- Retorna o nome do veículo -->
                            <p>
                                <?php //$tudo adquire todas as informações da reserva referente ao id repassado como parâmetro
                                $tudo = $objClasses->exibeTudo($reserva['id_veiculo']);
                                echo $tudo['nome_veiculo']; ?>
                            </p>
                        </td>
                        <td>
                            <!-- Retorna a data de retirada do veículo referente a reserva realizada -->
                            <p>
                                De:
                                <?php echo (new DateTime($reserva['data_retirada_reserva']))->format('d/m/Y H:i:s'); ?>
                            </p>
                            <!-- Retorna a data de devolução do veículo referente a reserva realizada -->
                            <p>
                                Até:
                                <?php echo (new DateTime($reserva['data_devolucao_reserva']))->format('d/m/Y H:i:s'); ?>
                            </p>
                        </td>
                        <td>
                            <!-- Retorna o motorista cadastrado na reserva -->
                            <p>
                                <?php echo $reserva['motorista_reserva']; ?>
                            </p>
                        </td>
                        <td>
                            <!-- Retorna a área do usuário que cadastrou a reserva -->
                            <p>
                                <?php //$tudo adquire todas as informações da reserva referente ao id repassado como parâmetro
                                $tudo = $objClasses->exibeTudo($reserva['id_usuario']);
                                echo $tudo['area_usuario']; ?>
                            </p>
                        </td>
                        <td>
                            <!-- Retorna o local de destino da reserva -->
                            <p>
                                <?php echo $reserva['local_destino_reserva']; ?>
                            </p>
                        </td>
                        <td>
                            <!-- Se o botão remover for pressionado, removerá reserva feita -->
                            <p>
                                <button class="btnRemover_<?php echo $reserva['id_reserva'] ?> 
                                btn waves-effect waves-light" 
                                onclick="remove('<?php echo $reserva['id_reserva'] ?>');">REMOVER
                            </p>
                        </td>
                    <?php endforeach; ?>
                    </tr>
            </tbody>
        </table>
        <!-- Se o botão remover for pressionado, retornará à página reservas -->
        <a href="../admin.php" class="waves-effect waves-light btn">Voltar</a>
    </div>

    <script>
        //Função que remove a reserva selecionada
        function remove(id_reserva) {
            var dados = {
                id_reserva: id_reserva
            };

            $.ajax({
                url: "removerReserva.php",
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