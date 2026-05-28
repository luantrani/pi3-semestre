<?php
include_once 'Conexao.php';
include_once 'model/Categoria.php';
include_once 'model/Sensor.php';
include_once 'model/Produto.php';
class SensorDAO {
    private $conexao;
    
    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

public function inserir(Sensor $sensor) {
    try {
        // Verifique se a sua tabela tem exatamente essa ordem de colunas
        $sql = "INSERT INTO sensor (id, nome, corredor, lado, peso_atual, quantidade_atual, capacidade_maxima, minimo_reposicao, id_produto, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conexao->prepare($sql);
        
        $stmt->bindValue(1, $sensor->getId());
        $stmt->bindValue(2, $sensor->getNome());
        $stmt->bindValue(3, $sensor->getCorredor());
        $stmt->bindValue(4, $sensor->getLado());
        
        // AQUI É O PONTO CRÍTICO:
        // O Model calculou esses valores no setQuantidadeAtual(), agora pegamos eles:
        $stmt->bindValue(5, $sensor->getPesoAtual()); 
        $stmt->bindValue(6, $sensor->getQuantidadeAtual());
        
        $stmt->bindValue(7, $sensor->getCapacidadeMaxima());
        $stmt->bindValue(8, $sensor->getMinimoReposicao());
        $stmt->bindValue(9, $sensor->getProduto()->getId());
        $stmt->bindValue(10, $sensor->getStatus() ?? 'Ativo');
        
        $stmt->execute();
    } catch (PDOException $e) {
        throw new Exception("Erro no Banco: " . $e->getMessage());
    }
}

    public function listarTodos() {
    try {
        $sql = "SELECT s.id AS sensor_id, s.nome AS sensor_nome, s.corredor, s.lado, 
                       s.peso_atual, s.quantidade_atual, s.capacidade_maxima, s.minimo_reposicao, s.status AS sensor_status,
                       p.id AS produto_id, p.nome AS produto_nome, p.peso_unitario,
                       c.id AS categoria_id, c.nome AS categoria_nome
                FROM sensor s 
                JOIN produtos p ON s.id_produto = p.id 
                JOIN categorias c ON p.id_categoria = c.id";

        $stmt = $this->conexao->query($sql);
        $sensores = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categoria = new Categoria();
            $categoria->setId($row['categoria_id']);
            $categoria->setNome($row['categoria_nome']);
            
            $produto = new Produto();
            $produto->setId($row['produto_id']);
            $produto->setNome($row['produto_nome']);
            $produto->setPesoUnitario($row['peso_unitario']);
            $produto->setCategoria($categoria);
            
            $sensor = new Sensor();
            $sensor->setId($row['sensor_id']);
            $sensor->setNome($row['sensor_nome']);
            $sensor->setCorredor($row['corredor']);
            $sensor->setLado($row['lado']);
            $sensor->setCapacidadeMaxima($row['capacidade_maxima']);
            $sensor->setMinimoReposicao($row['minimo_reposicao']);
            $sensor->setStatus($row['sensor_status']);// Setamos o produto antes da quantidade!
            
            // AQUI ESTAVA O ERRO: Precisamos setar a quantidade que vem do banco
            $sensor->setQuantidadeAtual($row['quantidade_atual']);
            $sensor->setPesoAtual($row['peso_atual']);
            
            if ($sensor->getId() == 'SENS-COCA-01') { // ou um ID que você sabe que existe
        echo "Sensor: " . $sensor->getNome() . "<br>";
        echo "Qtd no Objeto: " . $sensor->getQuantidadeAtual() . "<br>";
        echo "Max no Objeto: " . $sensor->getCapacidadeMaxima() . "<br>";
        echo "Situação: " . $sensor->getSituacaoEstoque() . "<br><hr>";
    }
            $sensores[] = $sensor;
        }
        return $sensores;
    } catch (PDOException $e) {
        throw new Exception("Erro ao listar sensores: " . $e->getMessage());
    }
}

    public function atualizarPeso($sensor) {
        try {
            $sql = "UPDATE sensor SET peso_atual = :peso WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            
            // Garanta que o getter do peso está retornando o valor correto
            $stmt->bindValue(":peso", $sensor->getPesoAtual());
            $stmt->bindValue(":id", $sensor->getId());
            
            return $stmt->execute(); // Sem o execute, o banco não muda!
        } catch (Exception $e) {
            error_log("Erro no SensorDAO: " . $e->getMessage());
            return false;
        }
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
            $sql = "UPDATE sensor SET nome = ?, corredor = ?, lado = ?, capacidade_maxima = ?, minimo_reposicao = ?, id_produto = ?, status = ? WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(1, $sensor->getNome());
            $stmt->bindValue(2, $sensor->getCorredor());
            $stmt->bindValue(3, $sensor->getLado());
            $stmt->bindValue(4, $sensor->getCapacidadeMaxima());
            $stmt->bindValue(5, $sensor->getMinimoReposicao());
            $stmt->bindValue(6, $sensor->getProduto()->getId());
            $stmt->bindValue(7, $sensor->getStatus());
            $stmt->bindValue(8, $sensor->getId());
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

    public function atualizarQuantidade(Sensor $sensor) {
        try {
            $sql = "UPDATE sensor SET quantidade_atual = :qtd WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":qtd", $sensor->getQuantidadeAtual());
            $stmt->bindValue(":id", $sensor->getId());
            
            return $stmt->execute(); // IMPORTANTE: Precisa do execute para salvar!
        } catch (Exception $e) {
            print "Erro ao atualizar banco: " . $e->getMessage();
        }
    }

}