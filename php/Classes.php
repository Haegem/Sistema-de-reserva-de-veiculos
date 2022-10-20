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
            
                $resultado = $this->mysql->query('SELECT * FROM tb_reserva');
                $veiculosDisponibilidade = $resultado->fetch_all(MYSQLI_ASSOC);
    
                $aux = 0;
                foreach($veiculosDisponibilidade as $veiculo){
                    if($veiculo['nome_veiculo'] == $nome_veiculo){
                        // Comparando as Datas
                        if(strtotime($data_retirada) < strtotime($veiculo['data_retirada']) &&
                        strtotime($data_devolucao) < strtotime($veiculo['data_retirada'])){
                            $aux++;
                        }else if(strtotime($data_retirada) > strtotime($veiculo['data_devolucao']) &&
                        strtotime($data_devolucao) > strtotime($veiculo['data_devolucao'])){
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
                    $cadastraReserva = $this->mysql->prepare('INSERT INTO tb_reserva (nome_veiculo, nome_pessoa, area, 
                    email, motorista, data_retirada, data_devolucao, numero_ocupantes, local_destino) 
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?);');
                    $cadastraReserva->bind_param('sssssssss', $nome_veiculo, $nome_pessoa, $area, $email, $motorista,
                    $data_retirada, $data_devolucao, $numero_ocupantes, $local_destino);
                    $cadastraReserva->execute();
                    return 7;
                }else{
                    return 9;
                }
            
        }

        //Função para cadastrar um novo usuário no banco de dados
        public function cadastrarUsuario(string $usuario, string $senha, string $nome, string $area,
        string $confirmarSenha, string $adm): int{
            if($senha == $confirmarSenha){
                if(strlen($usuario) >= 50 || strlen($usuario) < 8){
                    return 0;
                }else if(strlen($senha) >= 50 || strlen($senha) < 8 || 
                strlen($confirmarSenha) >= 50 || strlen($confirmarSenha) < 8){
                    return 1;
                }else if(strlen($nome) >= 100){
                    return 2;
                }else if(strlen($area) >= 50){
                    return 3;
                }else{
                    $cadastraUsuario = $this->mysql->prepare('INSERT INTO tb_usuarios (usuario, senha, adm, nome, area)
                    VALUES(?, ?, ?, ?, ?);');
                    $cadastraUsuario->bind_param('sssss', $usuario, $senha, $adm, $nome, $area);
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
                $cadastraUsuario = $this->mysql->prepare('INSERT INTO tb_veiculos (nome, KM, disponibilidade)
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
                    $editaVeiculo = $this->mysql->prepare('UPDATE tb_veiculos SET nome = ?, km = ?
                     WHERE id_veiculo = ?;');
                    $editaVeiculo->bind_param('sss', $nome, $KM, $id_veiculo);
                    $editaVeiculo->execute();
                    return 2;
                }
        }

        //Função para editar as informações de um usuário específico
        public function editarUsuario(string $id_usuarios, string $usuario, string $senha, string $nome, string $area,
        string $confirmarSenha): int{
            if($senha == $confirmarSenha){
                if(strlen($usuario) >= 50 || strlen($usuario) < 9){
                    return 0;
                }else if(strlen($senha) >= 50 || strlen($senha) < 9){
                    return 1;
                }else if(strlen($nome) >= 100){
                    return 2;
                }else if(strlen($area) >= 50){
                    return 3;
                }else{
                    $editaUsuario = $this->mysql->prepare('UPDATE tb_usuarios SET usuario = ?, senha = ?, nome = ?, area = ? WHERE id_usuarios = ?;');
                    $editaUsuario->bind_param('sssss', $usuario, $senha, $nome, $area, $id_usuarios);
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
            $resultado = $this->mysql->prepare('SELECT disponibilidade FROM tb_veiculos
            WHERE nome = ?');
            $resultado->bind_param('s', $nome);
            $resultado->execute();
            $exibir = $resultado->get_result()->fetch_assoc();
            return $exibir;
        }

        //Função para exibir as informações da reserva
        public function exibirInfoReservas(): array{
            $resultado = $this->mysql->query('SELECT * FROM tb_reserva');
            $exibir = $resultado->fetch_all(MYSQLI_ASSOC);
            return $exibir;
        }

        //Função para verificar se existe uma data reservada para aquele veiculo
        public function exibirDisponibilidadeVeiculo(string $nome_veiculo): array{
            $resultado = $this->mysql->prepare('SELECT * FROM tb_reserva WHERE nome_veiculo = ?');
            $resultado->bind_param('s', $nome_veiculo);
            $resultado->execute();
            $exibir = $resultado->get_result()->fetch_assoc();
            return $exibir;
        }

        //Função para exibir as informações de um usuário específico
        public function exibirUsuario(string $usuario): array{
            $resultado = $this->mysql->prepare('SELECT * FROM tb_usuarios WHERE usuario = ?');
            $resultado->bind_param('s', $usuario);
            $resultado->execute();
            $exibir = $resultado->get_result()->fetch_assoc();
            return $exibir;
        }

        //Função para exibir as informações de um usuário específico
        public function exibirUsuarioPorEmail(string $usuario): array{
            $resultado = $this->mysql->prepare('SELECT * FROM tb_usuarios WHERE usuario = ?');
            $resultado->bind_param('s', $usuario);
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

        /*
        Funções de verificação de dados
        */
        
        //Função para verificar se existe o veiculo na tabela de veículos no bd
        public function verificaExisteUsuario(string $usuario): bool{
            $resultado = $this->mysql->prepare('SELECT usuario FROM tb_usuarios WHERE usuario = ?');
            $resultado->bind_param('s', $usuario);
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
            $resultado = $this->mysql->prepare('SELECT nome FROM tb_veiculos WHERE nome = ?');
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
        public function verificaExiste($nome): bool{
            $resultado = $this->mysql->prepare('SELECT nome_veiculo FROM tb_reserva WHERE nome_veiculo = ?');
            $resultado->bind_param('s', $nome);
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
                $bloqueiaVeiculo = $this->mysql->prepare('UPDATE tb_veiculos SET disponibilidade = true 
                WHERE nome = ?;');
                $bloqueiaVeiculo->bind_param('s', $nome);
                $bloqueiaVeiculo->execute();
            }else{
                $bloqueiaVeiculo = $this->mysql->prepare('UPDATE tb_veiculos SET disponibilidade = false 
                WHERE nome = ?;');
                $bloqueiaVeiculo->bind_param('s', $nome);
                $bloqueiaVeiculo->execute();
            }
        }

        //Função para verificar o login
        public function encontrarUsuario(string $usuario, string $senha): int{
            $encontraUsuario = $this->mysql->prepare("SELECT * 
            FROM tb_usuarios WHERE usuario = ? AND senha = ?");
            $encontraUsuario->bind_param('ss', $usuario, $senha);
            $encontraUsuario->execute();
            $encontrado = $encontraUsuario->get_result()->fetch_assoc();
            if(strlen($usuario) > 50 || strlen($usuario) < 9){
                return 0;
            }else if(strlen($senha) > 50 || strlen($senha) < 9){
                return 1;
            }else if($encontrado == null){
                return 2;
            }else if($encontrado['adm'] == true){
                return 3;
            }else{
                return 4;
            }
        }
        
    }
