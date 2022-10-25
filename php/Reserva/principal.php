<?php
//Será criado a session e ao verificar que a session não existe a página redireciona o mesmo para a index
session_start();
$authToken = $_SESSION['authToken'];
if ((!isset($_SESSION['email']) == true) && (!isset($_SESSION['senha']) == true)) {
    header("Location: ../index.php");
} else if ($authToken != md5(date("Ymd") * 5)) {
    echo "<script>alert('Chave de seguranca invalida!');location.href=\"../index.php\";</script>";
    die();
}

//Repassando informações do usuário logado para as variaveis
$logado = $_SESSION['email'];
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
    $verificaBloqueio = $objClasses->exibirDisponibilidade($_POST['nome_veiculo']);

    //Caso veículo esteja disponível
    if ($verificaBloqueio['disp_veiculo'] == 1) {
        /*
        Cadastra no banco de dados uma nova reserva com as informações preenchidas no formulário
        e armazena em $cadastroReserva o retorno da função cadastrarReserva
        */
        $cadastroReserva = $objClasses->cadastrarReserva(
            $_POST['nome_veiculo'],
            $_POST['nome_usuario'],
            $_POST['area_usuario'],
            $_POST['email_usuario'],
            $_POST['motorista_reserva'],
            $_POST['data_retirada_reserva'],
            $_POST['data_devolucao_reserva'],
            $_POST['numero_ocupantes_reserva'],
            $_POST['local_destino_reserva'],
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/google-fonts.css">
    <link rel="stylesheet" href="../../css/style.css">
    <script src="../../js/jquery.js"></script>

    <title>
        PRINCIPAL
    </title>
</head>

<body>
    <!-- Se o usuário logador tiver permissão de administrador e o botão Voltar for pressionado, 
        retornará à página admin, se não retornará à página index -->
    <?php
    if ($verificaAdm['adm_usuario'] != 0) {
    ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand">RESERVA</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <!-- Âncora Reservar (scroll até Reserva da página) -->
                    <a class="nav-item nav-link active" href="#reservar">Reservar</a>
                    <!-- Âncora Lista de reservas (scroll até Lista de reservas da página) -->
                    <a class="nav-item nav-link" href="#lista-reservas">Lista de reservas</a>
                    <!-- Âncora Usuários (Retorna para usuarios.php) -->
                    <a class="nav-item nav-link" href="../Usuario/usuarios.php">Usuários</a>
                    <!-- Âncora Veículos (Retorna para veiculos.php) -->
                    <a class="nav-item nav-link" href="../Veiculo/veiculos.php">Veículos</a>
                    <!-- Âncora Reservas (Retorna para reservas.php) -->
                    <a class="nav-item nav-link" href="../Reserva/reservas.php">Reservas</a>
                    <!-- Âncora Sair (Retorna para admin.php) -->
                    <a class="nav-item nav-link" href="../admin.php">Sair</a>
                </div>
            </div>
        </nav>
    <?php
    } else {
    ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand">RESERVA</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <!-- Âncora Reservar (scroll até Reserva da página) -->
                    <a class="nav-item nav-link active" href="#reservar">Reservar</a>
                    <!-- Âncora Lista de reservas (scroll até Lista de reservas da página) -->
                    <a class="nav-item nav-link" href="#lista-reservas">Lista de reservas</a>
                    <!-- Âncora Sair (Retorna para admin.php) -->
                    <a class="nav-item nav-link" href="../index.php">Sair</a>
                </div>
            </div>
        </nav>
    <?php
    }
    ?>

    <div class="container-fluid center bg-light">
        <div class="row">
            <div class="col-sm"></div>
            <div class="col-sm-8">
                <div class="mt-5 card">
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
                                            <img src="../../img/<?php echo $veiculo['nome_veiculo'] ?>.jpg" 
                                            height="50px" width="50px">
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm"></div>
            <div class="w-100"></div>
            <div class="col-sm"></div>
            <div class="col-sm-8">
                <div class="card my-5 borda-card">
                    <!-- Formulário para solicitar reserva -->
                    <h1 id="reservar">RESERVA</h1>
                    <form action="principal.php" method="POST">
                        <div class="mx-5">
                            <!-- Caixa de opções dos carros disponíveis -->
                            <label for="nome_veiculo">VEÍCULO</label>
                            <select class="form-control" id="nome_veiculo" name="nome_veiculo">
                                <option value="" disabled selected>Selecione sua opção</option>
                                <?php
                                foreach ($veiculos as $veiculo) :
                                    /*
                                    $dispVeiculo recebe array com a informação disponibilidade de acordo 
                                    com nome do veículo passado como parâmetro
                                    */
                                    $dispVeiculo = $objClasses->exibirDisponibilidade($veiculo['nome_veiculo']);
                                    if ($dispVeiculo['disp_veiculo'] == 1) {
                                ?>
                                        <option><?php echo $veiculo['nome_veiculo']; ?></option>
                                <?php }
                                endforeach;
                                ?>
                            </select>
                        </div>
                        <!-- Retorna o nome do usuário logado -->
                        <div class="mx-5">
                            <label for="nome_usuario">NOME</label>
                            <input type="text" id="nome_usuario" name="nome_usuario" class="form-control" 
                            required placeholder="Nome" value="<?php echo $pegarInfo['nome_usuario'] ?>">
                        </div>
                        <!-- Retorna a área do usuário logado -->
                        <div class="mx-5">
                            <label for="area_usuario">ÁREA</label>
                            <input type="text" id="area_usuario" name="area_usuario" class="form-control" 
                            required placeholder="Área" value="<?php echo $pegarInfo['area_usuario'] ?>">
                        </div>
                        <!-- Retorna o e-mail do usuário logado -->
                        <div class="mx-5">
                            <label for="email_usuario">EMAIL</label>
                            <input type="email" id="email_usuario" name="email_usuario" class="form-control" 
                            aria-describedby="emailHelp" required placeholder="E-mail" 
                            value="<?php echo $pegarInfo['email_usuario'] ?>">
                            <small id="emailHelp" class="form-text text-muted">Nós nunca compartilharemos seu e-mail com ninguém.</small>
                        </div>
                        <div class="mx-5">
                            <label for="motorista_reserva">MOTORISTA</label>
                            <input type="text" id="motorista_reserva" name="motorista_reserva" class="form-control" 
                            required placeholder="Motorista">
                        </div>
                        <div class="mx-5">
                            <label for="data_retirada_reserva">DATA DA RETIRADA</label>
                            <input type="datetime-local" id="data_retirada_reserva" class="form-control" 
                            name="data_retirada_reserva">
                        </div>
                        <div class="mx-5">
                            <label for="data_retirada_devolucao">DATA DA DEVOLUÇÃO</label>
                            <input type="datetime-local" id="data_retirada_devolucao" class="form-control" 
                            name="data_retirada_devolucao">
                        </div>
                        <div class="mx-5">
                            <label for="numero_ocupantes_reserva">NÚMERO DE OCUPANTES</label>
                            <input type="number" id="numero_ocupantes_reserva" name="numero_ocupantes_reserva" 
                            class="form-control" required placeholder="Número de ocupantes">
                        </div>
                        <div class="mx-5">
                            <label for="local_destino_reserva">LOCAL / DESTINO</label>
                            <input type="text" id="local_destino_reserva" name="local_destino_reserva" 
                            class="form-control" required placeholder="Local / Destino">
                        </div>

                        <input type="submit" class="btn btn-primary" name="action" id="btnReservar" value="RESERVAR" data-toggle="modal" data-target="#modal-reserva">

                        <!-- Modal -->
                        <div class="modal fade" id="modal-reserva" tabindex="-1" role="dialog" aria-labelledby="modal-reserva-label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-reserva-label">Reservar</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                        <button type="button" class="btn btn-primary">Confirmar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm"></div>
            <div class="w-100"></div>
            <div class="col-sm"></div>
            <div class="col-sm-8">
                <div class="card">
                    <!-- Tabela que mostra todas as reservas realizadas -->
                    <h1 id="lista-reservas">LISTA DE RESERVAS</h1>
                    <div class="form-group mx-5">
                        <h5 class="left">Selecione o número de linhas</h2>
                            <!-- Mostra a quantidade de informação da tabela -->
                            <select class="form-control" name="state" id="maxRows">
                                <option selected value="5000">Mostrar todas as linhas</option>
                                <option value="10">10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                    </div>
                    <div class="mb-5">
                        <table id="table-lista-reservas" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col" class="center">ID</th>
                                    <th scope="col" class="center">Nome</th>
                                    <th scope="col" class="center">Motorista P/</th>
                                    <th scope="col" class="center">Data/Hora</th>
                                    <th scope="col" class="center">Destino</th>
                                    <th scope="col" class="center">Status</th>
                                    <th scope="col" class="center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cont = 1;
                                $agora = date('Y-m-d H:i');
                                foreach ($reservas as $reserva) :

                                    if (
                                        strtotime($reserva['data_retirada_reserva']) > strtotime($agora) ||
                                        strtotime($reserva['data_devolucao_reserva']) > strtotime($agora)
                                    ) {
                                ?>
                                        <tr>
                                            <td scope="row" class="center">
                                                <!-- Retorna o ID da reserva -->
                                                <p class="idReserva_<?php echo $cont['id_reserva'] ?>">
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
                                                    <?php echo $reserva['motorista_reserva']; ?>
                                                </p>
                                            </td>
                                            <td class="center">
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
                                            <td class="center">
                                                <!-- Retorna o local de destino da reserva -->
                                                <p>
                                                    <?php echo $reserva['local_destino_reserva']; ?>
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
                                        $deletaReserva = $mysql->prepare('DELETE FROM tb_reservas WHERE id_reserva = ?;');
                                        $deletaReserva->bind_param('s', $reserva['id_reserva']);
                                        $deletaReserva->execute();
                                    }
                                endforeach; ?>
                            </tbody>
                        </table>
                        <!-- Paginação -->
                        <div class='pagination-container'>
                            <nav>
                                <ul class="pagination">
                                    <li data-page="prev">
                                        <span>
                                            < <span class="sr-only">(current)
                                        </span></span>
                                    </li>
                                    <!-- Aqui a função JS adicionará as linhas -->
                                    <li data-page="next" id="prev">
                                        <span> > <span class="sr-only">(current)</span></span>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm"></div>
        </div>
    </div>
    </div>

    <script>
        //Declaração e atribuição de valor das variáveis
        var nome = $("#nome_usuario");
        var area = $('#area_usuario');
        var email = $('#email_usuario');
        var motorista = $('#motorista_reserva');
        var numeroOcupantes = $("#numero_ocupantes_reserva");
        var localDestino = $("#local_destino_reserva");
        var bordaCard = $(".borda-card");
        getPagination('#table-lista-reservas');

        //Inicializador da página 
        $(function() {
            inicializaMarcadoresPrincipal();
        });



        function getPagination(table) {
            var lastPage = 1;

            $('#maxRows')
                .on('change', function(evt) {
                    //$('.paginationprev').html('');						// reset pagination

                    lastPage = 1;
                    $('.pagination')
                        .find('li')
                        .slice(1, -1)
                        .remove();
                    var trnum = 0; // reseta o contador TR
                    var maxRows = parseInt($(this).val()); // recebe o máximo de linhas da opção selecionada

                    if (maxRows == 5000) {
                        $('.pagination').hide();
                    } else {
                        $('.pagination').show();
                    }

                    var totalRows = $(table + ' tbody tr').length; // número de linhas
                    $(table + ' tr:gt(0)').each(function() {
                        // cada TR na table e não no header
                        trnum++; // Inicia o contador TR
                        if (trnum > maxRows) {
                            $(this).hide();
                        }
                        if (trnum <= maxRows) {
                            $(this).show();
                        }
                    });

                    if (totalRows > maxRows) {
                        var pagenum = Math.ceil(totalRows / maxRows);

                        for (var i = 1; i <= pagenum;) {
                            // Para cada page append pagination li
                            $('.pagination #prev')
                                .before(
                                    '<li data-page="' +
                                    i +
                                    '">\
								  <span>' +
                                    i++ +
                                    '<span class="sr-only">(current)</span></span>\
								</li>'
                                )
                                .show();
                        }
                    }

                    $('.pagination [data-page="1"]').addClass('active'); // Adiciona class active ao primeiro li
                    $('.pagination li').on('click', function(evt) {
                        evt.stopImmediatePropagation();
                        evt.preventDefault();
                        var pageNum = $(this).attr('data-page'); // Recebe número da página
                        var maxRows = parseInt($('#maxRows').val()); // Recebe o máximo de linhas da opção selecionada

                        if (pageNum == 'prev') {
                            if (lastPage == 1) {
                                return;
                            }
                            pageNum = --lastPage;
                        }
                        if (pageNum == 'next') {
                            if (lastPage == $('.pagination li').length - 2) {
                                return;
                            }
                            pageNum = ++lastPage;
                        }

                        lastPage = pageNum;
                        var trIndex = 0; // reseta o contador TR
                        $('.pagination li').removeClass('active'); // remove a class active de todos os li
                        $('.pagination [data-page="' + lastPage + '"]').addClass('active'); // adiciona a class active à opção clicada
                        // $(this).addClass('active');					// add active class to the clicked
                        limitPagging();
                        $(table + ' tr:gt(0)').each(function() {
                            // cada TR na table e não no header
                            trIndex++; // Inicia o contador TR Index
                            if (
                                trIndex > maxRows * pageNum ||
                                trIndex <= maxRows * pageNum - maxRows
                            ) {
                                $(this).hide();
                            } else {
                                $(this).show();
                            }
                        });
                    });
                    limitPagging();
                })
                .val(5)
                .change();
        }

        function limitPagging() {
            // alert($('.pagination li').length)

            if ($('.pagination li').length > 7) {
                if ($('.pagination li.active').attr('data-page') <= 3) {
                    $('.pagination li:gt(5)').hide();
                    $('.pagination li:lt(5)').show();
                    $('.pagination [data-page="next"]').show();
                }
                if ($('.pagination li.active').attr('data-page') > 3) {
                    $('.pagination li:gt(0)').hide();
                    $('.pagination [data-page="next"]').show();
                    for (let i = (parseInt($('.pagination li.active').attr('data-page')) - 2); i <= (parseInt($('.pagination li.active').attr('data-page')) + 2); i++) {
                        $('.pagination [data-page="' + i + '"]').show();

                    }

                }
            }
        }

        //Função para mover o scroll da tela ao ser clicado uma opção
        $('.navbar a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            var id = $(this).attr('href'),
                targetOffset = $(id).offset().top;

            $('html, body').animate({
                scrollTop: targetOffset - 300
            }, 500);
        });

        //Função para trocar borda do borda-card na página de edição
        function inicializaMarcadoresPrincipal() {
            nome.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    bordaCard.addClass("borda-padrao");
                    bordaCard.removeClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    bordaCard.addClass("borda-vermelha");
                    bordaCard.removeClass("borda-verde");
                } else {
                    bordaCard.addClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
                }
            });
            area.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    bordaCard.addClass("borda-padrao");
                    bordaCard.removeClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    bordaCard.addClass("borda-vermelha");
                    bordaCard.removeClass("borda-verde");
                } else {
                    bordaCard.addClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
                }
            });
            email.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    bordaCard.addClass("borda-padrao");
                    bordaCard.removeClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    bordaCard.addClass("borda-vermelha");
                    bordaCard.removeClass("borda-verde");
                } else {
                    bordaCard.addClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
                }
            });
            motorista.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    bordaCard.addClass("borda-padrao");
                    bordaCard.removeClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    bordaCard.addClass("borda-vermelha");
                    bordaCard.removeClass("borda-verde");
                } else {
                    bordaCard.addClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
                }
            });
            numeroOcupantes.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    bordaCard.addClass("borda-padrao");
                    bordaCard.removeClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    bordaCard.addClass("borda-vermelha");
                    bordaCard.removeClass("borda-verde");
                } else {
                    bordaCard.addClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
                }
            });
            localDestino.on("input", function() {
                if (nome.val().length == 0 || area.val().length == 0 || email.val().length == 0 ||
                    motorista.val().length == 0 || numeroOcupantes.val().length == 0 ||
                    localDestino.val().length == 0) {
                    bordaCard.addClass("borda-padrao");
                    bordaCard.removeClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
                } else if (nome.val().length >= 100 || area.val().length >= 50 || email.val().length >= 50 ||
                    motorista.val().length >= 100 || numeroOcupantes.val().length >= 50 ||
                    localDestino.val().length >= 50) {
                    bordaCard.addClass("borda-vermelha");
                    bordaCard.removeClass("borda-verde");
                } else {
                    bordaCard.addClass("borda-verde");
                    bordaCard.removeClass("borda-vermelha");
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="../../slick/slick.min.js"></script>
    <script src="../../js/slider.js"></script>
    <script src="../../js/main.js"></script>
</body>
<footer>
</footer>

</html>