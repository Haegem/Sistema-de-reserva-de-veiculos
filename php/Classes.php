<?php

    class Classes{

        //Ligação com o banco de dados
        private $mysql;
        public function __construct(mysqli $mysql){
            $this->mysql = $mysql;
        }

        /*
        Funções de cadastro de novas informações no banco de dados
        */

        //Função para cadastrar uma reserva de um veiculo
        public function cadastrarReserva(string $nome_veiculo, string $nome_pessoa, string $area,
        string $email, string $motorista, string $data_retirada, string $data_devolucao, string $numero_ocupantes, 
        string $local_destino): int{
                $agora = date('Y-m-d H:i');
            
                $resultado = $this->mysql->query('SELECT * FROM tb_reservas');
                $veiculosDisponibilidade = $resultado->fetch_all(MYSQLI_ASSOC);
    
                $aux = 0;
                foreach($veiculosDisponibilidade as $veiculo){
                    if($veiculo['nome_veiculo'] == $nome_veiculo){
                        // Comparando as Datas
                        if(strtotime($data_retirada) < strtotime($veiculo['data_retirada_reservas']) &&
                        strtotime($data_devolucao) < strtotime($veiculo['data_retirada_reservas'])){
                            $aux++;
                        }else if(strtotime($data_retirada) > strtotime($veiculo['data_devolucao_reservas']) &&
                        strtotime($data_devolucao) > strtotime($veiculo['data_devolucao_reservas'])){
                            $aux++;
                        }else{
                            $aux--;
                        }
                    }else{
                        $aux++;
                    }
                }
    
                if(strlen($nome_pessoa) >= 100){
                    return 1;
                }else if(strlen($area) >= 50){
                    return 2;
                }else if(strlen($email) >= 50){
                    return 3;
                }else if(strlen($motorista) >= 100){
                    return 4;
                }else if(strlen($numero_ocupantes) >= 3){
                    return 5;
                }else if(strlen($local_destino) >= 50){
                    return 6;
                }else if(strtotime($data_retirada) < strtotime($agora) ||
                strtotime($data_devolucao) < strtotime($agora)){
                    return 8;
                }else if($aux == sizeof($veiculosDisponibilidade)){
                    $cadastraReserva = $this->mysql->prepare('INSERT INTO tb_reservas (motorista_reserva, 
                    data_retirada_reserva, data_devolucao_reserva, numero_ocupantes_reserva, local_destino_reserva) 
                    VALUES(?, ?, ?, ?, ?);');
                    $cadastraReserva->bind_param('sssss', $motorista, $data_retirada, $data_devolucao, 
                    $numero_ocupantes, $local_destino);
                    $cadastraReserva->execute();
                    $cadastraReserva = $this->mysql->prepare('INSERT INTO tb_usuarios (nome_usuario, area_usuario, 
                    email_usuario) VALUES(?, ?, ?);');
                    $cadastraReserva->bind_param('sss', $nome_pessoa, $area, $email);
                    $cadastraReserva->execute();
                    $cadastraReserva = $this->mysql->prepare('INSERT INTO tb_veiculos (nome_veiculo) 
                    VALUES(?);');
                    $cadastraReserva->bind_param('s', $nome_veiculo);
                    $cadastraReserva->execute();
                    return 7;
                }else{
                    return 9;
                }
            
        }

        //Função para cadastrar um novo usuário no banco de dados
        public function cadastrarUsuario(string $email, string $senha, string $nome, string $area,
        string $confirmarSenha, string $adm): int{
            if($senha == $confirmarSenha){
                if(strlen($email) >= 50 || strlen($email) < 8){
                    return 0;
                }else if(strlen($senha) >= 50 || strlen($senha) < 8 || 
                strlen($confirmarSenha) >= 50 || strlen($confirmarSenha) < 8){
                    return 1;
                }else if(strlen($nome) >= 100){
                    return 2;
                }else if(strlen($area) >= 50){
                    return 3;
                }else{
                    $cadastraUsuario = $this->mysql->prepare('INSERT INTO tb_usuarios (email_usuario, senha_usuario, 
                    nome_usuario, area_usuario, adm_usuario)
                    VALUES(?, ?, ?, ?, ?);');
                    $cadastraUsuario->bind_param('sssss', $email, $senha, $nome, $area, $adm);
                    $cadastraUsuario->execute();
                    return 4;
                }
            }else{
                return 5;
            }
        }

        //Função cadastrar novos veículos
        public function cadastrarVeiculo(string $nome, string $KM): int{
            if(strlen($nome) >= 50){
                return 0;
            }else if(strlen($KM) >= 10){
                return 1;
            }else{
                $cadastraUsuario = $this->mysql->prepare('INSERT INTO tb_veiculos (nome_veiculo, km_veiculo, 
                disp_veiculo)
                VALUES(?, ?, true);');
                $cadastraUsuario->bind_param('ss', $nome, $KM);
                $cadastraUsuario->execute();
                return 2;
            }
        }

        /*
        Funções de edição de informações do nosso banco de dados
        */

        //Função para editar as informações de um usuário específico
        public function editarVeiculo(string $id_veiculo, string $nome, string $KM): int{
            if(strlen($nome) >= 50){
                return 0;
            }else if(strlen($KM) >= 10){
                return 1;
            }else{
                    $editaVeiculo = $this->mysql->prepare('UPDATE tb_veiculos SET nome_veiculo = ?, km_veiculo = ?
                    WHERE id_veiculo = ?;');
                    $editaVeiculo->bind_param('sss', $nome, $KM, $id_veiculo);
                    $editaVeiculo->execute();
                    return 2;
                }
        }

        //Função para editar as informações de um usuário específico
        public function editarUsuario(string $id_usuario, string $email, string $senha, string $nome, string $area,
        string $confirmarSenha): int{
            if($senha == $confirmarSenha){
                if(strlen($email) >= 50 || strlen($email) < 9){
                    return 0;
                }else if(strlen($senha) >= 50 || strlen($senha) < 9){
                    return 1;
                }else if(strlen($nome) >= 100){
                    return 2;
                }else if(strlen($area) >= 50){
                    return 3;
                }else{
                    $editaUsuario = $this->mysql->prepare('UPDATE tb_usuarios SET email_usuario = ?, 
                    senha_usuario = ?, nome_usuario = ?, area_usuario = ? WHERE id_usuario = ?;');
                    $editaUsuario->bind_param('sssss', $email, $senha, $nome, $area, $id_usuario);
                    $editaUsuario->execute();
                    return 4;
                }
            }else{
                return 5;
            }
        }

        /*
        Funções de exibição de dados do nosso banco de dados
        */

        //Função para exibir as informações dos veiculos
        public function exibirVeiculos(): array{
            $resultado = $this->mysql->query('SELECT * FROM tb_veiculos');
            $exibir = $resultado->fetch_all(MYSQLI_ASSOC);
            return $exibir;
        }

        //Função para exibir as informações de um certo veiculo
        public function exibirVeiculo(string $id_veiculo): array{
            $resultado = $this->mysql->prepare('SELECT * FROM tb_veiculos WHERE id_veiculo = ?');
            $resultado->bind_param('s', $id_veiculo);
            $resultado->execute();
            $encontrado = $resultado->get_result()->fetch_assoc();
            return $encontrado;
        }

        //Função para exibir datas de acordo com o veiculo
        public function exibirDisponibilidade(string $nome): array{
            $resultado = $this->mysql->prepare('SELECT disp_veiculo FROM tb_veiculos
            WHERE nome_veiculo = ?');
            $resultado->bind_param('s', $nome);
            $resultado->execute();
            $exibir = $resultado->get_result()->fetch_assoc();
            return $exibir;
        }

        //Função para exibir as informações da reserva
        public function exibirInfoReservas(): array{
            $resultado = $this->mysql->query('SELECT * FROM tb_reservas');
            $exibir = $resultado->fetch_all(MYSQLI_ASSOC);
            return $exibir;
        }

        //Função para verificar se existe uma data reservada para aquele veiculo
        public function exibirDisponibilidadeVeiculo(string $id_veiculo): array{
            $resultado = $this->mysql->prepare('SELECT * FROM tb_reservas WHERE id_veiculo = ?');
            $resultado->bind_param('s', $id_veiculo);
            $resultado->execute();
            $exibir = $resultado->get_result()->fetch_assoc();
            return $exibir;
        }

        //Função para exibir as informações de um usuário específico
        public function exibirUsuario(string $email): array{
            $resultado = $this->mysql->prepare('SELECT * FROM tb_usuarios WHERE email_usuario = ?');
            $resultado->bind_param('s', $email);
            $resultado->execute();
            $exibir = $resultado->get_result()->fetch_assoc();
            return $exibir;
        }

        //Função para exibir as informações dos usuários cadastrados
        public function exibirUsuarios(): array{
            $resultado = $this->mysql->query('SELECT * FROM tb_usuarios');
            $exibir = $resultado->fetch_all(MYSQLI_ASSOC);
            return $exibir;
        }

        //Função para exibir todas as informações para a reserva das três tabelas
        public function exibeTudo($id): array{
            $resultado = $this->mysql->prepare('SELECT B.nome_veiculo, C.nome_usuario, C.area_usuario, 
            C.email_usuario, A.motorista_reserva, A.data_retirada_reserva, A.data_devolucao_reserva, 
            A.numero_ocupantes_reserva, A.local_destino_reserva FROM tb_reservas A INNER JOIN tb_veiculos B 
            ON A.id_veiculo = ? INNER JOIN tb_usuarios C ON A.id_usuario = ?;');
            $resultado->bind_param('ss', $id, $id);
            $resultado->execute();
            $exibir = $resultado->get_result()->fetch_assoc();
            return $exibir;
        }

        /*
        Funções de verificação de dados
        */
        
        //Função para verificar se existe o veiculo na tabela de veículos no bd
        public function verificaExisteUsuario(string $email): bool{
            $resultado = $this->mysql->prepare('SELECT email_usuario FROM tb_usuarios WHERE email_usuario = ?');
            $resultado->bind_param('s', $email);
            $resultado->execute();
            $encontrado = $resultado->get_result()->fetch_assoc();
            if($encontrado == null){
                return true;
            }else{
                return false;
            }
        }

        //Função para verificar se existe o veiculo na tabela de veículos no bd
        public function verificaExisteVeiculo($nome): bool{
            $resultado = $this->mysql->prepare('SELECT nome_veiculo FROM tb_veiculos WHERE nome_veiculo = ?');
            $resultado->bind_param('s', $nome);
            $resultado->execute();
            $encontrado = $resultado->get_result()->fetch_assoc();
            if($encontrado == null){
                return true;
            }else{
                return false;
            }
        }
        
        //Função para verificar se existe o veiculo na tabela de reserva no bd
        public function verificaExiste($id_veiculo): bool{
            $resultado = $this->mysql->prepare('SELECT id_veiculo FROM tb_reservas WHERE id_veiculo = ?');
            $resultado->bind_param('s', $id_veiculo);
            $resultado->execute();
            $encontrado = $resultado->get_result()->fetch_assoc();
            if($encontrado == null){
                return false;
            }else{
                return true;
            }
        }

        /*
        Funções gerais
        */
        
        //Função para bloquear veiculo
        public function bloquearVeiculo(bool $param, string $nome): void{
            if($param){
                $bloqueiaVeiculo = $this->mysql->prepare('UPDATE tb_veiculos SET disp_veiculo = true 
                WHERE nome_veiculo = ?;');
                $bloqueiaVeiculo->bind_param('s', $nome);
                $bloqueiaVeiculo->execute();
            }else{
                $bloqueiaVeiculo = $this->mysql->prepare('UPDATE tb_veiculos SET disp_veiculo = false 
                WHERE nome_veiculo = ?;');
                $bloqueiaVeiculo->bind_param('s', $nome);
                $bloqueiaVeiculo->execute();
            }
        }

        //Função para verificar o login
        public function encontrarUsuario(string $email, string $senha): int{
            $encontraUsuario = $this->mysql->prepare("SELECT * FROM tb_usuarios WHERE email_usuario = ? 
            AND senha_usuario = ?");
            $encontraUsuario->bind_param('ss', $email, $senha);
            $encontraUsuario->execute();
            $encontrado = $encontraUsuario->get_result()->fetch_assoc();
            if(strlen($email) > 50 || strlen($email) < 9){
                return 0;
            }else if(strlen($senha) > 50 || strlen($senha) < 9){
                return 1;
            }else if($encontrado == null){
                return 2;
            }else if($encontrado['adm_usuario'] == true){
                return 3;
            }else{
                return 4;
            }
        }
        
    }
