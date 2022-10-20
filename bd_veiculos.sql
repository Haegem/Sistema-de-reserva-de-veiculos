CREATE DATABASE IF NOT EXISTS bd_carros DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE bd_carros;

CREATE TABLE IF NOT EXISTS tb_usuarios (
  id_usuarios int(10) NOT NULL AUTO_INCREMENT,
  usuario VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  senha VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  adm boolean,
  nome VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  area VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id_usuarios)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tb_veiculos (
  id_veiculo int(10) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  km INT(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  disponibilidade boolean,
  PRIMARY KEY (id_veiculo)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tb_reserva (
  id_reserva int(10) NOT NULL AUTO_INCREMENT,
  nome_veiculo VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  nome_pessoa VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  area VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  email VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  motorista VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  data_retirada datetime COLLATE utf8mb4_unicode_ci NOT NULL,
  data_devolucao datetime COLLATE utf8mb4_unicode_ci NOT NULL,
  numero_ocupantes int(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  local_destino VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id_reserva)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;