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

//$usuarios adquire as informações de todos os usuários cadastrados no bd
$usuarios = $objClasses->exibirUsuarios();

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
        ADMIN - USUARIOS
    </title>
</head>

<body>
    <div class="container center">
        <h1>ADMIN - USUARIOS</h1>
        <a href="cadastrarUsuario.php" 
        class="waves-effect waves-light btn-large">Cadastrar</a>

        <!-- Tabela que mostra os usuários cadastrados -->
        <table class="table-usuarios">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Setor</th>
                    <th>Email</th>
                    <th>Senha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario) : ?>
                    <tr>
                        <td>
                            <!-- Retorna o nome do usuário cadastrado -->
                            <p>
                                <?php echo $usuario['nome_usuario']; ?>
                            </p>
                        </td>
                        <td>
                            <!-- Retorna a área do usuário cadastrado -->
                            <p>
                                <?php echo $usuario['area_usuario']; ?>
                            </p>
                        </td>
                        <td>
                            <!-- Retorna o usuário do usuário cadastrado -->
                            <p>
                                <?php echo $usuario['email_usuario']; ?>
                            </p>
                        </td>
                        <td>
                            <!-- Retorna o a senha do usuário cadastrado como type password -->
                            <p>
                                <input type="password" class="senha_<?php echo $usuario['id_usuario'] ?>" 
                                value="<?php echo $usuario['senha_usuario']; ?>" readonly>
                            </p>
                        </td>
                        <td>
                            <!-- Botão para mostrar a senha do usuário cadastrado -->
                            <p>
                                <input type="button" class="btnMostrar_<?php echo $usuario['id_usuario'] ?> 
                                btn waves-effect waves-light" value="MOSTRAR" 
                                onclick="mostra('<?php echo $usuario['id_usuario'] ?>');">
                            </p>
                        </td>
                        <td>
                            <p>
                            <form action="editarUsuario.php?id_usuarios=<?php $usuario['id_usuario'] ?>" method="GET">
                                <input type="hidden" name="btnEditar_<?php echo $usuario['id_usuario'] ?>" 
                                value="<?php echo $usuario['id_usuario'] ?>"
                                class="btnEditar_<?php echo $usuario['id_usuario'] ?>  btn waves-effect waves-light" />
                                <button class="btnEditar_<?php echo $usuario['id_usuario'] ?>  
                                btn waves-effect waves-light">EDITAR</button>
                            </form>
                            </p>
                        </td>
                        <td>
                            <p>
                                <input type="button" class="btnRemover_<?php echo $usuario['id_usuario'] ?> 
                                btn waves-effect waves-light" value="REMOVER" 
                                onclick="remove('<?php echo $usuario['id_usuario'] ?>');">
                            </p>
                        </td>
                    <?php endforeach; ?>
                    </tr>
            </tbody>
        </table>
        <a href="../admin.php" class="waves-effect waves-light btn">Voltar</a>
    </div>

    <script>
        //Função que remove o usuário selecionado
        function remove(id_usuario) {
            var dados = {
                id_usuario: id_usuario
            };

            $.ajax({
                url: "removerUsuario.php",
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

        //Função que mostra a senha do usuário selecionado
        function mostra(btnMostrar) {
            $('.senha_' + btnMostrar).attr('type', (_, attr) => attr == 'password' ? null : 'password');
        }
    </script>

    <script src="../../slick/slick.min.js"></script>
    <script src="../../js/slider.js"></script>
    <script src="../../js/main.js"></script>
</body>
<footer>
</footer>

</html>