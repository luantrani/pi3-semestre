CREATE DATABASE gpi;
use gpi;

CREATE TABLE usuarios (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    login VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, 
    nivel_acesso ENUM('gerente', 'repositor') NOT NULL DEFAULT 'repositor'
);

CREATE TABLE categorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(60) NOT NULL
);

CREATE TABLE produtos (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nome VARCHAR(120) NOT NULL,
    peso_unitario DECIMAL(10,3) NOT NULL,
    idcategoria INT,
    FOREIGN KEY (idcategoria) REFERENCES categorias(id)
);

CREATE TABLE sensor (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    corredor VARCHAR(120) NOT NULL,
    lado VARCHAR(120) NOT NULL,
    pesoAtual DECIMAL(10,3) NOT NULL DEFAULT 0,
    capacidadeMaxima INT NOT NULL DEFAULT 20,
    minimoReposicao INT NOT NULL DEFAULT 5,
    ultima_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    id_produto INT NOT NULL,
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
);

CREATE TABLE logs_reposicao (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_sensor INT NOT NULL,
    id_usuario_repositor INT NOT NULL,
    data_hora_conclusao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sensor) REFERENCES sensor(id),
    FOREIGN KEY (id_usuario_repositor) REFERENCES usuarios(id)
);

CREATE TABLE historico_alertas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_sensor INT NOT NULL,
    quantidade_no_momento INT NOT NULL,
    data_hora_alerta DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pendente', 'resolvido') DEFAULT 'pendente',
    FOREIGN KEY (id_sensor) REFERENCES sensor(id)
);

CREATE TABLE movimentacao_estoque (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_produto INT NOT NULL,
    id_usuario INT NOT NULL,
    quantidade_adicionada INT NOT NULL,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produto) REFERENCES produtos(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

INSERT INTO `usuarios`(`nome`, `login`, `senha`, `nivel_acesso`) VALUES ('administrador','gpiadmin','$2y$10$dLpz.bzESR.qZWDaMaybu.zt9EFhs72TlLEQiLEPNTvkTaNXcr/dG','gerente');