<?php

class SensorDAO {
    private $conexao;
    
    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function inserir(Sensor $sensor) {
        try {
            $sql = "INSERT INTO sensor (corredor, lado, capacidadeMaxima, minimoReposicao, id_produto) VALUES (?, ?, ?, ?, ?)";
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
            $sql = "SELECT s.*, p.*, c.nome AS nome_categoria
                    FROM sensor s 
                    JOIN produtos p ON s.id_produto = p.id JOIN categorias c ON p.idCategoria = c.id";
            $stmt = $this->conexao->query($sql);
            $sensores = [];
            $stmt->fetch(PDO::FETCH_ASSOC);
            foreach ($stmt as $row) {
                $categoria = new Categoria();
                $categoria->setId($row['idCategoria']);
                $categoria->setNome($row['nome_categoria']);
                
                $produto = new Produto();
                $produto->setId($row['id_produto']);
                $produto->setNome($row['nome']);
                $produto->setPesoUnitario($row['peso_unitario']);
                $produto->setCategoria($categoria);
                
                $sensor = new Sensor();
                $sensor->setId($row['id']);
                $sensor->setNome($row['nome']);
                $sensor->setCorredor($row['corredor']);
                $sensor->setLado($row['lado']);
                $sensor->setCapacidadeMaxima($row['capacidadeMaxima']);
                $sensor->setMinimoReposicao($row['minimoReposicao']);
                $sensor->setProduto($produto);
                $sensor->setStatus($row['status']);
                
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