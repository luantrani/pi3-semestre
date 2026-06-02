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
    unidade_medida VARCHAR(10) DEFAULT 'un',
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

CREATE TABLE historico_alertas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_sensor VARCHAR(50) NOT NULL,
    quantidade_no_momento INT NOT NULL,
    data_hora_alerta DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_fim DATETIME,
    status ENUM('pendente', 'em_andamento', 'resolvido') DEFAULT 'pendente',
    id_usuario_atendimento INT,
    FOREIGN KEY (id_sensor) REFERENCES sensor(id),
    FOREIGN KEY (id_usuario_atendimento) REFERENCES usuarios(id)
);

CREATE TABLE movimentacao_estoque (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_produto INT NOT NULL,
    id_usuario INT NOT NULL,
    id_alerta INT,
    quantidade_adicionada INT NOT NULL,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produto) REFERENCES produtos(id),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    FOREIGN KEY (id_alerta) REFERENCES historico_alertas(id)
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
('Carlos Repositor', 'carlos.gpi', '$2y$10$dLpz.bzESR.qZWDaMaybu.zt9EFhs72TlLEQiLEPNTvkTaNXcr/dG', 'repositor'),
('João Repositor', 'joao.gpi', '$2y$10$dLpz.bzESR.qZWDaMaybu.zt9EFhs72TlLEQiLEPNTvkTaNXcr/dG', 'repositor');

-- 3. Inserindo Produtos
-- O peso_unitario é importante para a lógica do sensor IoT
INSERT INTO produtos (nome, peso_unitario, unidade_medida, id_categoria) VALUES 
('Coca-Cola 2L', 2.100, 'un', 1),
('Amaciante Downy 500ml', 0.550, 'un', 2),
('Arroz Tio João 5kg', 5.050, 'kg', 4),
('Detergente Ypê 500ml', 0.520, 'un', 2),
('Sabonete Dove 90g', 0.095, 'un', 3);

-- 4. Inserindo Sensores (O coração do sistema)
-- Aqui simulamos alguns cheios e outros precisando de reposição
INSERT INTO sensor (id, nome, corredor, lado, peso_atual, quantidade_atual, capacidade_maxima, minimo_reposicao, id_produto, status) VALUES 
('AC-2312', 'Sensor Coca-A1', 'A1', 'Esquerdo', 42.000, 20, 20, 5, 1, 'Ativo'),      -- 20 itens (Max)
('AC-2313', 'Sensor Amaciante-B1', 'B1', 'Direito', 11.000, 20, 20, 5, 2, 'Ativo'),    
('AC-2314', 'Sensor Arroz-C2', 'C2', 'Direito', 75.750, 15, 15, 3, 3, 'Ativo'),      -- 15 itens (Max)
('AC-2315', 'Sensor Detergente-B2', 'B2', 'Direito', 0.000, 0, 25, 6, 4, 'Ativo'),  
('AC-2316', 'Sensor Sabonete-D1', 'D1', 'Esquerdo', 4.750, 0, 50, 10, 5, 'Ativo');      -- 50 itens (Max)

insert into historico_alertas (id_sensor, quantidade_no_momento, data_hora_alerta, data_fim, status, id_usuario_atendimento) values 
('AC-2312', 5, '2026-05-28 10:25:00', "2026-05-28 10:35:00", 'resolvido', 3), -- Alerta para coca-cola
('AC-2314', 2, '2026-05-21 18:00:00', "2026-05-21 18:10:00", 'resolvido', 2), -- Alerta para arroz
('AC-2315', 0, '2026-05-25 14:10:00', "2026-05-25 14:20:00", 'resolvido', 2), -- Alerta para Detergente
('AC-2313', 0, '2026-05-24 11:00:00', "2026-05-24 11:10:00", 'resolvido', 3), -- Alerta para Amaciante
('AC-2316', 4, '2026-05-18 8:05:00', "2026-05-18 8:15:00", 'resolvido', 2), -- Alerta para Sabonete
('AC-2315', 0, '2026-06-02 16:02:00', NULL, 'pendente', NULL),-- Alerta para Detergente
('AC-2316', 0, '2026-06-02 16:50:00', NULL, 'em_andamento', 3); -- Alerta para Detergente

insert into movimentacao_estoque (id_produto, id_usuario, id_alerta, quantidade_adicionada, data_hora) values 
(2, 3, 4, 20, '2026-05-28 10:40:00'), -- João Repositor adicionou 20 amaciante
(4, 2, 3, 25, '2026-05-21 18:15:00'), -- Carlos Repositor adicionou 25 detergentes
(5, 2, 5, 46, '2026-05-18 8:20:00'), -- Carlos Repositor adicionou 46 sabonetes
(1, 3, 1, 15, '2026-05-28 10:35:00'), -- João Repositor adicionou 15 Coca-Colas
(3, 2, 2, 13, '2026-05-21 18:10:00'); -- Carlos Repositor adicionou 13 Arroz Tio João
