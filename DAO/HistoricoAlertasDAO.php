<?php

class HistoricoAlertasDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    /**
     * REGISTAR: Cria um novo alerta no banco (usado pelo SensorDAO ou Simulador)
     */
    public function registrarAlerta($id_sensor, $quantidade) {
        try {
            $sql = "INSERT INTO historico_alertas (id_sensor, quantidade_no_momento, status, data_hora_alerta) 
                    VALUES (:id_sensor, :qtd, 'pendente', NOW())";
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([
                'id_sensor' => $id_sensor, 
                'qtd' => $quantidade
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao registar alerta: " . $e->getMessage());
        }
    }

    /**
     * LEITURA: Busca alertas ativos (pendentes e em andamento) para o painel
     */
    public function buscarAlertasAtivos() {
    try {
        $sql = "SELECT h.*, s.corredor, s.lado, p.nome as produto_nome, c.nome as categoria_nome,
                       u.nome as nome_responsavel
                FROM historico_alertas h
                JOIN sensor s ON h.id_sensor = s.id
                JOIN produtos p ON s.id_produto = p.id
                JOIN categorias c ON p.id_categoria = c.id
                LEFT JOIN usuarios u ON h.id_usuario_atendimento = u.id
                WHERE h.status IN ('pendente', 'em_andamento') 
                ORDER BY h.status DESC, h.data_hora_alerta DESC";

        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        
        $alertas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $alertas[] = $this->mapearAlerta($row);
        }
        return $alertas;
    } catch (PDOException $e) {
        throw new Exception("Erro ao buscar alertas ativos: " . $e->getMessage());
    }
}

    /**
     * LEITURA: Busca um alerta específico por ID (necessário para o Controller)
     */
    public function buscarPorId($id) {
    try {
        $sql = "SELECT h.*, s.corredor, s.lado, s.capacidade_maxima,
                       p.id as id_produto, p.nome as produto_nome, 
                       p.peso_unitario, c.nome as categoria_nome,
                       u.nome as nome_responsavel
                FROM historico_alertas h
                JOIN sensor s ON h.id_sensor = s.id
                JOIN produtos p ON s.id_produto = p.id
                JOIN categorias c ON p.id_categoria = c.id
                LEFT JOIN usuarios u ON h.id_usuario_atendimento = u.id
                WHERE h.id = :id";
        
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapearAlerta($row) : null;
    } catch (PDOException $e) {
        throw new Exception("Erro ao buscar alerta por ID: " . $e->getMessage());
    }
}

    /**
     * VALIDAÇÃO: Verifica se já existe um alerta aberto para aquele sensor
     */
    public function temAlertaAtivo($idSensor) {
        $sql = "SELECT COUNT(*) FROM historico_alertas 
                WHERE id_sensor = :id_sensor AND status IN ('pendente', 'em_andamento')";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute(['id_sensor' => $idSensor]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * ATUALIZAÇÃO: Muda o status para 'em_andamento' e define o responsável
     */
    public function assumirAlerta($idAlerta, $idUsuario) {
        $sql = "UPDATE historico_alertas SET status = 'em_andamento', id_usuario_atendimento = :id_user 
                WHERE id = :id_alerta AND status = 'pendente'";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute(['id_user' => $idUsuario, 'id_alerta' => $idAlerta]);
    }

    /**
     * ATUALIZAÇÃO: Finaliza o alerta (usado no processo de reposição concluída)
     */
    public function atualizar($alerta) {
        try {
            $sql = "UPDATE historico_alertas SET 
                    status = :status, 
                    data_fim = :data_fim 
                    WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            return $stmt->execute([
                'status' => $alerta->getStatus(),
                'data_fim' => $alerta->getDataFim(),
                'id' => $alerta->getId()
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar alerta: " . $e->getMessage());
        }
    }

    /**
     * AUXILIAR: Transforma os dados do banco num objeto Alerta (Model)
     */
    private function mapearAlerta($row) {
        $alerta = new Alerta();
        $alerta->setId($row['id']);
        $alerta->setQuantidadeNoMomento($row['quantidade_no_momento']);
        $alerta->setDataHoraAlerta($row['data_hora_alerta'] ?? null);
        $alerta->setStatus($row['status']);
        $alerta->setIdUsuarioAtendimento($row['id_usuario_atendimento'] ?? null);
        $alerta->setDataFim($row['data_fim'] ?? null);
        $alerta->setNomeResponsavel($row['nome_responsavel'] ?? null);

        $produto = new Produto();
        $produto->setId($row['id_produto'] ?? null);
        $produto->setNome($row['produto_nome']);
        $produto->setCategoria($row['categoria_nome']); 
        if(isset($row['peso_unitario'])) $produto->setPesoUnitario($row['peso_unitario']);
        
        $sensor = new Sensor();
        $sensor->setId($row['id_sensor']);
        $sensor->setCorredor($row['corredor']);
        $sensor->setLado($row['lado']);
        if(isset($row['capacidade_maxima'])) $sensor->setCapacidadeMaxima($row['capacidade_maxima']);
        
        $alerta->setProduto($produto);
        $alerta->setSensor($sensor);
        
        return $alerta;
    }
}