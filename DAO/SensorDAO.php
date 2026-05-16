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
            $sql = "SELECT s.*, p.nome AS nome_produto, p.peso_unitario, p.estoque_minimo 
                    FROM sensor s 
                    JOIN produtos p ON s.id_produto = p.id";
            $stmt = $this->conexao->query($sql);
            $sensores = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $produto = new Produto($row['id_produto'], $row['nome_produto'], $row['peso_unitario'], $row['estoque_minimo']);
                $sensor = new Sensor($row['id'], $row['corredor'], $row['lado'], $row['capacidade_maxima'], $row['minimo_reposicao'], $produto);
                $sensor->setPesoAtual($row['peso_atual']);
                $sensores[] = $sensor;
            }
            return $sensores;
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar sensores. " . $e->getMessage());
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


}