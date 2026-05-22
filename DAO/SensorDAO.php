<?php

class SensorDAO {
    private $conexao;
    
    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function inserir(Sensor $sensor) {
        try {
            $sql = "INSERT INTO sensor (corredor, lado, capacidade_maxima, minimo_reposicao, id_produto) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $sensor->getCorredor());
            $stmt->bindValue(2, $sensor->getLado());
            $stmt->bindValue(3, $sensor->getCapacidadeMaxima());
            $stmt->bindValue(4, $sensor->getMinimoReposicao());
            $stmt->bindValue(5, $sensor->getProduto()->getId());
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir sensor no banco de dados.". $e->getMessage());
        }
    }

    public function listarTodos() {
    try {
        // Selecionamos as colunas explicitamente para evitar conflitos de nomes
        $sql = "SELECT s.id AS sensor_id, s.nome AS sensor_nome, s.corredor, s.lado, 
                       s.peso_atual, s.capacidade_maxima, s.minimo_reposicao, s.status AS sensor_status,
                       p.id AS produto_id, p.nome AS produto_nome, p.peso_unitario,
                       c.id AS categoria_id, c.nome AS categoria_nome
                FROM sensor s 
                JOIN produtos p ON s.id_produto = p.id 
                JOIN categorias c ON p.id_categoria = c.id";

        $stmt = $this->conexao->query($sql);
        $sensores = [];

        // O foreach já itera sobre o statement, não precisa de fetch antes
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // 1. Monta Categoria
            $categoria = new Categoria();
            $categoria->setId($row['categoria_id']);
            $categoria->setNome($row['categoria_nome']);
            
            // 2. Monta Produto
            $produto = new Produto();
            $produto->setId($row['produto_id']);
            $produto->setNome($row['produto_nome']);
            $produto->setPesoUnitario($row['peso_unitario']);
            $produto->setCategoria($categoria);
            
            // 3. Monta Sensor
            $sensor = new Sensor();
            $sensor->setId($row['sensor_id']);
            $sensor->setNome($row['sensor_nome']);
            $sensor->setCorredor($row['corredor']);
            $sensor->setLado($row['lado']);
            $sensor->setPesoAtual($row['peso_atual']);
            $sensor->setCapacidadeMaxima($row['capacidade_maxima']);
            $sensor->setMinimoReposicao($row['minimo_reposicao']);
            $sensor->setProduto($produto);
            $sensor->setStatus($row['sensor_status']);
            
            $sensores[] = $sensor;
        }
        return $sensores;
    } catch (PDOException $e) {
        throw new Exception("Erro ao listar sensores: " . $e->getMessage());
    }
}

    public function atualizarPeso($id_sensor, $novo_peso) {
        $sql = "UPDATE sensor SET peso_atual = ?, ultima_atualizacao = NOW() WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$novo_peso, $id_sensor]);
    }

    public function listarAlertasReposicao() {
        $sql = "SELECT s.*, p.nome, p.peso_unitario, p.estoque_minimo 
                FROM sensor s 
                JOIN produtos p ON s.id_produto = p.id
                ORDER BY s.ultima_atualizacao DESC";
        
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar(Sensor $sensor) {
        try {
            $sql = "UPDATE sensor SET corredor = ?, lado = ?, capacidade_maxima = ?, minimo_reposicao = ?, id_produto = ?, status = ? WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $sensor->getCorredor());
            $stmt->bindValue(2, $sensor->getLado());
            $stmt->bindValue(3, $sensor->getCapacidadeMaxima());
            $stmt->bindValue(4, $sensor->getMinimoReposicao());
            $stmt->bindValue(5, $sensor->getProduto()->getId());
            $stmt->bindValue(6, $sensor->getStatus());
            $stmt->bindValue(7, $sensor->getId());
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar sensor no banco de dados. " . $e->getMessage());
        }
    }

    public function excluir(Sensor $sensor) {
        try {
            $sql = "DELETE FROM sensor WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([$sensor->getId()]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir sensor do banco de dados. " . $e->getMessage());
        }
    }

}