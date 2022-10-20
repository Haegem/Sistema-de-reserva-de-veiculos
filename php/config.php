<?php

// Construtor para o acesso ao nosso banco de dados
$mysql = new mysqli('localhost', 'root', '123456789', 'bd_carros');
$mysql->set_charset('utf8');

if ($mysql == FALSE) {
    echo ('Erro na conexão');
}
