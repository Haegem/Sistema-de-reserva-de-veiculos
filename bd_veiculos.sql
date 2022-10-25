CREATE DATABASE IF NOT EXISTS bd_carros DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE bd_carros;

CREATE TABLE IF NOT EXISTS tb_usuarios (
  id_usuario INT(10) NOT NULL AUTO_INCREMENT,
  email_usuario VARCHAR(50) COLLATE utf8mb4_unicode_ci,
  senha_usuario VARCHAR(50) COLLATE utf8mb4_unicode_ci,
  nome_usuario VARCHAR(100) COLLATE utf8mb4_unicode_ci,
  area_usuario VARCHAR(50) COLLATE utf8mb4_unicode_ci,
  adm_usuario BOOLEAN,
  PRIMARY KEY (id_usuario)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tb_veiculos (
  id_veiculo INT(10) NOT NULL AUTO_INCREMENT,
  nome_veiculo VARCHAR(50) COLLATE utf8mb4_unicode_ci,
  km_veiculo INT(10),
  disp_veiculo BOOLEAN,
  PRIMARY KEY (id_veiculo)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tb_reservas (
  id_reserva INT(10) NOT NULL AUTO_INCREMENT,
  id_veiculo INT(10) NOT NULL,
  id_usuario INT(10) NOT NULL,
  motorista_reserva VARCHAR(100) COLLATE utf8mb4_unicode_ci,
  data_retirada_reserva DATETIME COLLATE utf8mb4_unicode_ci,
  data_devolucao_reserva DATETIME COLLATE utf8mb4_unicode_ci,
  numero_ocupantes_reserva INT(3),
  local_destino_reserva VARCHAR(100) COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (id_reserva)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO tb_reservas (id_veiculo, id_usuario, motorista_reserva, 
data_retirada_reserva, data_devolucao_reserva, numero_ocupantes_reserva, local_destino_reserva)
VALUES(4, 4, 'Jorge', '2022-10-24 10:00:00', '2022-10-25 09:47:00', '2', 'São Bento do Sul');

INSERT INTO tb_usuarios (email_usuario, senha_usuario, nome_usuario, area_usuario, adm_usuario)
VALUES('joao.eduardo@buddemeyer.com.br', '123456789', 'Joao', 'TI', true);

INSERT INTO tb_veiculos (nome_veiculo, km_veiculo, disp_veiculo)
VALUES('Ford Ka', '10000', true);

SELECT * FROM tb_usuarios;
SELECT * FROM tb_veiculos;
SELECT * FROM tb_reservas;

ALTER TABLE tb_reservas ADD CONSTRAINT fk_veiculos FOREIGN KEY (id_veiculo) REFERENCES tb_veiculos (id_veiculo);
ALTER TABLE tb_reservas ADD CONSTRAINT fk_usuarios FOREIGN KEY (id_usuario) REFERENCES tb_usuarios (id_usuario);

SELECT B.nome_veiculo, C.nome_usuario, C.area_usuario, C.email_usuario, A.motorista_reserva, A.data_retirada_reserva,
A.data_devolucao_reserva, A.numero_ocupantes_reserva, A.local_destino_reserva FROM tb_reservas A INNER JOIN 
tb_veiculos B ON A.id_veiculo = B.id_veiculo INNER JOIN tb_usuarios C ON A.id_usuario = C.id_usuario;