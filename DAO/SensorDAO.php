<?php
include_once 'Conexao.php';
include_once 'model/Categoria.php';
include_once 'model/Sensor.php';
include_once 'model/Produto.php';
include_once 'HistoricoAlertasDAO.php';

class SensorDAO {
    private $conexao;
    
    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function inserir(Sensor $sensor) {
        try {
            $sql = "INSERT INTO sensor (id, nome, corredor, lado, peso_atual, quantidade_atual, capacidade_maxima, minimo_reposicao, id_produto, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindValue(1, $sensor->getId());
            $stmt->bindValue(2, $sensor->getNome());
            $stmt->bindValue(3, $sensor->getCorredor());
            $stmt->bindValue(4, $sensor->getLado());
            $stmt->bindValue(5, $sensor->getPesoAtual()); 
            $stmt->bindValue(6, $sensor->getQuantidadeAtual());
            $stmt->bindValue(7, $sensor->getCapacidadeMaxima());
            $stmt->bindValue(8, $sensor->getMinimoReposicao());
            // Verificação de segurança para o produto
            $stmt->bindValue(9, $sensor->getProduto() ? $sensor->getProduto()->getId() : null);
            $stmt->bindValue(10, $sensor->getStatus() ?? 'Ativo');
            
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir no Banco: " . $e->getMessage());
        }
    }

    public function listarTodos() {
        try {
            $sql = "SELECT s.id AS sensor_id, s.nome AS sensor_nome, s.corredor, s.lado, 
                           s.peso_atual, s.quantidade_atual, s.capacidade_maxima, s.minimo_reposicao, s.status AS sensor_status,
                           p.id AS produto_id, p.nome AS produto_nome, p.peso_unitario,
                           c.id AS categoria_id, c.nome AS categoria_nome
                    FROM sensor s 
                    LEFT JOIN produtos p ON s.id_produto = p.id 
                    LEFT JOIN categorias c ON p.id_categoria = c.id";

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
                $sensor->setStatus($row['sensor_status']);
                
                // --- VITAL: Vincular o produto ao sensor ---
                $sensor->setProduto($produto);
                
                // Carregamos os valores exatos que estão no banco (sem recalcular)
                $sensor->setQuantidadeAtual($row['quantidade_atual']);
                $sensor->setPesoAtual($row['peso_atual']);
                
                $sensores[] = $sensor;
            }
            return $sensores;
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar sensores: " . $e->getMessage());
        }
    }

    public function salvarLeituraSensor(Sensor $sensor) {
        try {
            $sql = "UPDATE sensor SET peso_atual = :peso, quantidade_atual = :qtd, ultima_atualizacao = NOW() WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(":peso", $sensor->getPesoAtual());
            $stmt->bindValue(":qtd", $sensor->getQuantidadeAtual());
            $stmt->bindValue(":id", $sensor->getId());
            
            $sucesso = $stmt->execute();

            // GATILHO: Se a atualização deu certo, verifica se precisa de alerta
            if ($sucesso) {
                $this->verificarNivelCritico($sensor);
            }

            return $sucesso;
        } catch (Exception $e) {
            error_log("Erro ao salvar leitura: " . $e->getMessage());
            return false;
        }
    }

    public function excluir($id) {
        try {
            $sql = "DELETE FROM sensor WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao excluir sensor: " . $e->getMessage());
        }
    }

    private function verificarNivelCritico($sensor) {
        // Usando o limite definido no cadastro do sensor (minimo_reposicao)
        $limite = $sensor->getMinimoReposicao();
        $quantidadeAtual = $sensor->getQuantidadeAtual();

        // Se a quantidade for menor ou igual ao mínimo definido
        if ($quantidadeAtual <= $limite) {
            $historicoDAO = new HistoricoAlertasDAO();
            
            // 1. Verifica se já não tem um alerta aberto para este sensor
            // Isso evita criar centenas de alertas enquanto o produto não é reposto
            if (!$historicoDAO->temAlertaAtivo($sensor->getId())) {
                
                // 2. Registra o alerta no banco
                $historicoDAO->registrarAlerta(
                    $sensor->getId(), 
                    $quantidadeAtual
                );
            }
        }
    }
}