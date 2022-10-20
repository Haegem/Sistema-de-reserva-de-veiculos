<?php
//Será criado a session e ao verificar que a session não existe a página redireciona o mesmo para a index
session_start();
if ((!isset($_SESSION['usuario']) == true) and (!isset($_SESSION['senha']) == true)) {
    header('location: ../index.php');
}

$logado = $_SESSION['usuario'];
$logadoSenha = $_SESSION['senha'];

//Importação de php
require '../config.php';
include '../Classes.php';

//Construtor de Classes
$objClasses = new Classes($mysql);

//$reservas adquire as informações de todas as reservas do bd
$reservas = $objClasses->exibirInfoReservas();

//$veiculos adquire as informações de todos os veículos do bd
$veiculos = $objClasses->exibirVeiculos();

//$pegarEmail adquire o e-mail da tabela usuário de acordo com o id repassado na URL
$pegarInfo = $objClasses->exibirUsuario($logado);

//$verificaAdm adquire todas as informações do usuário de acordo com o id contido em $logado
$verificaAdm = $objClasses->exibirUsuario($logado);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //$verificaBloqueio recebe array com a informação disponibilidade de acordo com nome do veículo selecionado
    $verificaBloqueio = $objClasses->exibirDisponibilidade($_POST['veiculo']);

    //Caso veículo esteja disponível
    if ($verificaBloqueio['disponibilidade'] == 1) {
        /*
        Cadastra no banco de dados uma nova reserva com as informações preenchidas no formulário
        e armazena em $cadastroReserva o retorno da função cadastrarReserva
        */
        $cadastroReserva = $objClasses->cadastrarReserva(
            $_POST['veiculo'],
            $_POST['nome'],
            $_POST['area'],
            $_POST['email'],
            $_POST['motorista'],
            $_POST['dataRetirada'],
            $_POST['dataDevolucao'],
            $_POST['numeroOcupantes'],
            $_POST['local'],
        );
        //Se o nome exceder 100 caracteres, retornará à página principal
        if ($cadastroReserva == 1) {
            echo "<script>alert('Nome não pode exceder 100 caracteres');
            location.href=\"principal.php\";</script>";
            die();
        }
        //Se a área exceder 50 caracteres, retornará à página principal
        else if ($cadastroReserva == 2) {
            echo "<script>alert('Área não pode exceder 50 caracteres');
            location.href=\"principal.php\";</script>";
            die();
        }
        //Se o e-mail exceder 50 caracteres, retornará à página principal
        else if ($cadastroReserva == 3) {
            echo "<script>alert('Email não pode exceder 50 caracteres');
            location.href=\"principal.php\";</script>";
            die();
        }
        //Se motorista exceder 100 caracteres, retornará à página principal
        else if ($cadastroReserva == 4) {
            echo "<script>alert('Nome do motorista não pode exceder 100 caracteres');
            location.href=\"principal.php\";</script>";
            die();
        }
        //Se o número de ocupantes exceder 3 caracteres, retornará à página principal
        else if ($cadastroReserva == 5) {
            echo "<script>alert('Número de ocupantes não pode exceder 3 caracteres');
            location.href=\"principal.php\";</script>";
            die();
        }
        //Se o local/destino exceder 50 caracteres, retornará à página principal
        else if ($cadastroReserva == 6) {
            echo "<script>alert('Local/Destino não pode exceder 50 caracteres');
            location.href=\"principal.php\";</script>";
            die();
        }
        //Se o local/destino exceder 50 caracteres, retornará à página principal
        else if ($cadastroReserva == 8) {
            echo "<script>alert('Data de retirada ou devolução não pode ser antes da data de hoje');
            location.href=\"principal.php\";</script>";
            die();
        }
        //Se o a data reserva para o veículo já estiver em uso, retornará à página principal
        else if ($cadastroReserva == 9) {
            echo "<script>alert('Veículo já reservado neste horário');
            location.href=\"principal.php\";</script>";
            die();
        }
        //Se tudo estiver OK, informações serão registradas no bd e será retornado à página principal
        else {
            header("Location: principal.php");
            die();
        }
    }
    //Caso veículo não esteja disponível, retornará à página principal
    else {
        echo "<script>alert('O veículo não está disponível');
        location.href=\"principal.php\";</script>";
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="PT-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="../../css/reset.css">
    <link rel="stylesheet" href="../../css/materialize.min.css">
    <link rel="stylesheet" href="../../css/google-fonts.css">
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/jquery.js"></script>

    <title>
        PRINCIPAL
    </title>
</head>

<body>
    <div class="principal center">
        <!-- Botão sair (Retorna para login) -->
        <a href="../index.php" class="botao-sair waves-effect waves-light btn-large" 
        onclick="destruirSessao()">Sair</a>

        <!-- Tabela que mostra os veículos cadastrados -->
        <h1><?php echo "Bem vindo $logado"; ?></h1>
        <table>
            <thead>
                <tr>
                    <th class="center">Imagem</th>
                    <th class="center">Nome</th>
                    <th class="center">Situação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($veiculos as $veiculo) : ?>
                    <tr>
                        <td class="center">
                            <p>
                                <!-- Busca imagem em img de acordo com o nome do veículo -->
                                <img src="../../img/<?php echo $veiculo['nome'] ?>.jpg" height="50px" width="50px">
                            </p>
                        </td>
                        <td class="center">
                            <p>
                                <!-- Retorna o nome do veículo -->
                                <?php echo $veiculo['nome']; ?>
                            </p>
                        </td>
                        <td class="center">
                            <p>
                                <?php
                                /*
                                $dispVeiculo recebe array com a informação disponibilidade de acordo 
                                com nome do veículo passado como parâmetro
                                */
                                $dispVeiculo = $objClasses->exibirDisponibilidade($veiculo['nome']);

                                //Caso veículo esteja disponível imagem verde na tela, se não imagem vermelha
                                if ($dispVeiculo['disponibilidade'] == 1) {
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Formulário para solicitar reserva -->
        <h1>RESERVA</h1>
        <form action="principal.php" method="POST">
            <!-- Caixa de opções dos carros disponíveis -->
            <label for="veiculo">VEÍCULO</label>
            <select class="browser-default" id="veiculo" name="veiculo">
                <option value="" disabled selected>Selecione sua opção</option>
                <?php
                foreach ($veiculos as $veiculo) :
                    /*
                    $dispVeiculo recebe array com a informação disponibilidade de acordo 
                    com nome do veículo passado como parâmetro
                    */
                    $dispVeiculo = $objClasses->exibirDisponibilidade($veiculo['nome']);
                    if ($dispVeiculo['disponibilidade'] == 1) {
                ?>
                        <option><?php echo $veiculo['nome']; ?></option>
                <?php }
                endforeach;
                ?>
            </select>
            <!-- Retorna o nome do usuário logado -->
            <label for="nome">NOME</label>
            <input type="text" id="nome" name="nome" required placeholder="Nome" value="<?php echo $pegarInfo['nome'] ?>">
            <!-- Retorna a área do usuário logado -->
            <label for="area">ÁREA</label>
            <input type="text" id="area" name="area" required placeholder="Área" value="<?php echo $pegarInfo['area'] ?>">
            <!-- Retorna o e-mail do usuário logado -->
            <label for="email">EMAIL</label>
            <input type="email" id="email" name="email" required placeholder="E-mail" value="<?php echo $pegarInfo['usuario'] ?>">
            <label for="motorista">MOTORISTA</label>
            <input type="text" id="motorista" name="motorista" required placeholder="Motorista">
            <label for="dataRetirada">DATA DA RETIRADA</label>
            <input type="datetime-local" id="dataRetirada" name="dataRetirada">
            <label for="dataDevolucao">DATA DA DEVOLUÇÃO</label>
            <input type="datetime-local" id="dataDevolucao" name="dataDevolucao">
            <label for="numeroOcupantes">NÚMERO DE OCUPANTES</label>
            <input type="number" id="numeroOcupantes" name="numeroOcupantes" required placeholder="Número de ocupantes">
            <label for="local">LOCAL / DESTINO</label>
            <input type="text" id="local" name="local" required placeholder="Local / Destino">

            <input type="submit" class="btn waves-effect waves-light" name="action" id="btnReservar" value="RESERVAR">
        </form>

        <!-- Tabela que mostra todas as reservas realizadas -->
        <h1>LISTA DE RESERVAS</h1>
        <form>
            <table>
                <thead>
                    <tr>
                        <th class="center">ID</th>
                        <th class="center">Nome</th>
                        <th class="center">Motorista P/</th>
                        <th class="center">Data/Hora</th>
                        <th class="center">Destino</th>
                        <th class="center">Status</th>
                        <th class="center"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $agora = date('Y-m-d H:i');
                    foreach ($reservas as $reserva) :

                        if (
                            strtotime($reserva['data_retirada']) > strtotime($agora) ||
                            strtotime($reserva['data_devolucao']) > strtotime($agora)
                        ) {
                    ?>
                            <tr>
                                <td class="center">
                                    <!-- Retorna o ID da reserva -->
                                    <p class="idReserva_<?php echo $reserva['id_reserva'] ?>">
                                        <?php echo $reserva['id_reserva']; ?>
                                    </p>
                                </td>
                                <td class="center">
                                    <!-- Retorna o nome do veículo reservado -->
                                    <p>
                                        <?php echo $reserva['nome_veiculo']; ?>
                                    </p>
                                </td>
                                <td class="center">
                                    <!-- Retorna o motorista cadastrado na reserva -->
                                    <p>
                                        <?php echo $reserva['motorista']; ?>
                                    </p>
                                </td>
                                <td class="center">
                                    <!-- Retorna a data de retirada do veículo referente a reserva realizada -->
                                    <p>
                                        De:
                                        <?php echo (new DateTime($reserva['data_retirada']))->format('d/m/Y H:i:s'); ?>
                                    </p>
                                    <!-- Retorna a data de devolução do veículo referente a reserva realizada -->
                                    <p>
                                        Até:
                                        <?php echo (new DateTime($reserva['data_devolucao']))->format('d/m/Y H:i:s'); ?>
                                    </p>
                                </td>
                                <td class="center">
                                    <!-- Retorna o local de destino da reserva -->
                                    <p>
                                        <?php echo $reserva['local_destino']; ?>
                                    </p>
                                </td>
                                <td class="center">
                                    <p>
                                        <?php
                                        /*
                                    $dispVeiculo recebe array com a informação disponibilidade do veículo
                                    de acordo com nome do veículo passado como parâmetro
                                    */
                                        $dispVeiculo = $objClasses->exibirDisponibilidade($reserva['nome_veiculo']);

                                        //Caso veículo esteja disponível imagem verde na tela, se não imagem vermelha
                                        if ($dispVeiculo['disponibilidade'] == 1) {
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
                                    <!-- Se o usuário logador tiver permissão de administrador e o 
                                botão remover for pressionado, removerá reserva feita -->
                                    <?php
                                    if ($verificaAdm['adm'] != 0) {
                                    ?>
                                        <p>
                                            <input type="button" class="btnRemover_<?php echo $reserva['id_reserva'] ?> 
                                    btn waves-effect waves-light" value="REMOVER" onclick="remove('<?php echo $reserva['id_reserva'] ?>');">
                                        </p>
                                    <?php
                                    } ?>
                                </td>
                            </tr>
                    <?php } else {
                            $deletaReserva = $mysql->prepare('DELETE FROM tb_reserva WHERE id_reserva = ?;');
                            $deletaReserva->bind_param('s', $reserva['id_reserva']);
                            $deletaReserva->execute();
                        }
                    endforeach; ?>
                </tbody>
            </table>
        </form>
        <!-- Se o usuário logador tiver permissão de administrador e o botão Voltar for pressionado, 
        retornará à página admin, se não retornará à página index -->
        <?php
        if ($verificaAdm['adm'] != 0) {
        ?>
            <a href="../admin.php" class="waves-effect waves-light btn">Voltar</a>
        <?php
        } else {
        ?>
            <a href="../index.php" class="waves-effect waves-light btn">Voltar</a>
        <?php
        }
        ?>
    </div>

    <script>
        //Declaração e atribuição de valor das variáveis
        var nome = $("#nome");
        var area = $('#area');
        var email = $('#email');
        var motorista = $('#motorista');
        var numeroOcupantes = $("#numeroOcupantes");
        var localDestino = $("#local");

        //Inicializador da página 
        $(function() {
            inicializaMarcadoresPrincipal();
        });

        //Função para trocar borda do container na página de edição
        function inicializaMarcadoresPrincipal() {
            nome.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    principal.addClass("borda-padrao");
                    principal.removeClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    principal.addClass("borda-vermelha");
                    principal.removeClass("borda-verde");
                } else {
                    principal.addClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                }
            });
            area.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    principal.addClass("borda-padrao");
                    principal.removeClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    principal.addClass("borda-vermelha");
                    principal.removeClass("borda-verde");
                } else {
                    principal.addClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                }
            });
            email.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    principal.addClass("borda-padrao");
                    principal.removeClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    principal.addClass("borda-vermelha");
                    principal.removeClass("borda-verde");
                } else {
                    principal.addClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                }
            });
            motorista.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    principal.addClass("borda-padrao");
                    principal.removeClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    principal.addClass("borda-vermelha");
                    principal.removeClass("borda-verde");
                } else {
                    principal.addClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                }
            });
            numeroOcupantes.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    principal.addClass("borda-padrao");
                    principal.removeClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    principal.addClass("borda-vermelha");
                    principal.removeClass("borda-verde");
                } else {
                    principal.addClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                }
            });
            localDestino.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    principal.addClass("borda-padrao");
                    principal.removeClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    principal.addClass("borda-vermelha");
                    principal.removeClass("borda-verde");
                } else {
                    principal.addClass("borda-verde");
                    principal.removeClass("borda-vermelha");
                }
            });
        }

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

        //Função que remove a reserva selecionada
        function destruirSessao() {
            $.ajax({
                url: "../destruirSessao.php",
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

    <script src="../../slick/slick.min.js"></script>
    <script src="../../js/slider.js"></script>
    <script src="../../js/main.js"></script>
</body>
<footer>
</footer>

</html>