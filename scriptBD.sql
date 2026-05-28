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
    id_categoria INT,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id)
);

CREATE TABLE sensor (
    id VARCHAR(50) PRIMARY KEY NOT NULL,
    nome VARCHAR(120) NOT NULL,
    corredor VARCHAR(120) NOT NULL,
    lado VARCHAR(120) NOT NULL,
    peso_atual DECIMAL(10,3) NOT NULL DEFAULT 0,
    capacidade_maxima INT NOT NULL DEFAULT 20,
    minimo_reposicao INT NOT NULL DEFAULT 5,
    ultima_atualizacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    quantidade_atual INT NOT NULL DEFAULT 0,
    id_produto INT NOT NULL,
    status VARCHAR(20) NOT NULL,
    FOREIGN KEY (id_produto) REFERENCES produtos(id)
);

CREATE TABLE logs_reposicao (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_sensor VARCHAR(50) NOT NULL,
    id_usuario_repositor INT NOT NULL,
    data_hora_conclusao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sensor) REFERENCES sensor(id),
    FOREIGN KEY (id_usuario_repositor) REFERENCES usuarios(id)
);

CREATE TABLE historico_alertas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_sensor VARCHAR(50) NOT NULL,
    quantidade_no_momento INT NOT NULL,
    data_hora_alerta DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pendente', 'em_andamento', 'resolvido') DEFAULT 'pendente',
    id_usuario_atendimento INT,
    FOREIGN KEY (id_sensor) REFERENCES sensor(id),
    FOREIGN KEY (id_usuario_atendimento) REFERENCES usuarios(id)
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

-- 1. Inserindo Categorias (Essenciais para o Produto)
INSERT INTO categorias (nome) VALUES 
('Bebidas'), 
('Limpeza'), 
('Higiene'), 
('Alimentos');

-- 2. Inserindo Usuários (Um de cada nível)
-- Senha para ambos: admin123 (usando o hash que você forneceu)
INSERT INTO usuarios (nome, login, senha, nivel_acesso) VALUES 
('Carlos Repositor', 'carlos.gpi', '$2y$10$dLpz.bzESR.qZWDaMaybu.zt9EFhs72TlLEQiLEPNTvkTaNXcr/dG', 'repositor');

-- 3. Inserindo Produtos
-- O peso_unitario é importante para a lógica do sensor IoT
INSERT INTO produtos (nome, peso_unitario, id_categoria) VALUES 
('Coca-Cola 2L', 2.100, 1),
('Amaciante Downy 500ml', 0.550, 2),
('Arroz Tio João 5kg', 5.050, 4),
('Detergente Ypê 500ml', 0.520, 2),
('Sabonete Dove 90g', 0.095, 3);

-- 4. Inserindo Sensores (O coração do sistema)
-- Aqui simulamos alguns cheios e outros precisando de reposição
INSERT INTO sensor (id, nome, corredor, lado, peso_atual, quantidade_atual, capacidade_maxima, minimo_reposicao, id_produto, status) VALUES 
('AC-2312', 'Sensor Coca-A1', 'Corredor 1', 'Lado A', 21.000, 10, 20, 5, 1, 'Ativo'),      -- 10 itens
('AC-2313', 'Sensor Amaciante-B1', 'Corredor 3', 'Lado B', 1.100, 2, 20, 5, 2, 'Ativo'),    -- 2 itens
('AC-2314', 'Sensor Arroz-C2', 'Corredor 5', 'Lado A', 50.500, 10, 15, 3, 3, 'Ativo'),      -- 10 itens
('AC-2315', 'Sensor Detergente-B2', 'Corredor 3', 'Lado B', 2.080, 4, 25, 6, 4, 'Ativo'),   -- 4 itens
('AC-2316', 'Sensor Sabonete-D1', 'Corredor 2', 'Lado A', 0.190, 2, 50, 10, 5, 'Inativo');

-- 5. Inserindo Histórico de Alertas (Para popular a sua barra lateral)
-- Simulando alertas que ainda não foram resolvidos
INSERT INTO historico_alertas (id_sensor, quantidade_no_momento, status) VALUES 
('AC-2313', 2, 'pendente'),
('AC-2315', 4, 'pendente');

-- 6. Inserindo uma Movimentação de Estoque (Para o Dashboard)
INSERT INTO movimentacao_estoque (id_produto, id_usuario, quantidade_adicionada) VALUES 
(1, 1, 10),
(3, 1, 15);


-- Adicionando coluna para ajudar na exibição do Dashboard
ALTER TABLE produtos ADD COLUMN unidade_medida VARCHAR(10) DEFAULT 'un';

-- 5. Inserindo mais Alertas Históricos (Para popular a barra lateral com mais dados)
INSERT INTO historico_alertas (id_sensor, quantidade_no_momento, status, data_hora_alerta) VALUES 
('AC-2313', 2, 'pendente', '2026-05-25 10:15:00'),
('AC-2315', 4, 'pendente', '2026-05-25 11:30:00'),
('AC-2314', 1, 'resolvido', '2026-05-24 09:00:00');

-- 6. Inserindo Logs de Reposição (Para que a Área do Repositor não pareça vazia)
-- Isso mostra quem trabalhou e quando
INSERT INTO logs_reposicao (id_sensor, id_usuario_repositor, data_hora_conclusao) VALUES 
('AC-2312', 2, '2026-05-24 14:00:00'),
('AC-2314', 2, '2026-05-25 08:30:00');

-- 7. Inserindo mais Movimentações de Estoque (Para os gráficos de Relatório)
INSERT INTO movimentacao_estoque (id_produto, id_usuario, quantidade_adicionada, data_hora) VALUES 
(1, 1, 20, '2026-05-20 10:00:00'),
(2, 2, 10, '2026-05-21 11:00:00'),
(4, 2, 30, '2026-05-22 15:30:00'),
(5, 1, 100, '2026-05-23 09:15:00');

