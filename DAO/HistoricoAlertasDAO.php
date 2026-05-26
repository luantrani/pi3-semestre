<?php

class HistoricoAlertasDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    /**
     * Busca alertas que estão 'pendente' OU 'em_andamento'.
     * Alertas 'resolvido' não devem aparecer no painel do repositor.
     */
    public function buscarAlertasAtivos() {
        try {
            // ATUALIZADO: Agora busca pendentes e em_andamento
            $sql = "SELECT h.*, s.corredor, s.lado, p.nome as produto_nome, c.nome as categoria_nome
                    FROM historico_alertas h
                    JOIN sensor s ON h.id_sensor = s.id
                    JOIN produtos p ON s.id_produto = p.id
                    JOIN categorias c ON p.id_categoria = c.id
                    WHERE h.status IN ('pendente', 'em_andamento') 
                    ORDER BY h.status DESC, h.data_hora_alerta DESC"; 
                    // Ordenamos para que 'pendente' (prioridade) apareça primeiro

            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            
            $alertas = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $alertas[] = $this->mapearAlerta($row);
            }
            return $alertas;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar alertas: " . $e->getMessage());
        }
    }

    private function mapearAlerta($row) {
        $alerta = new Alerta();
        $alerta->setId($row['id']);
        $alerta->setQuantidadeNoMomento($row['quantidade_no_momento']);
        $alerta->setDataHoraAlerta($row['data_hora_alerta']);
        
        // ATUALIZADO: Mapeando as novas colunas para o Model Alerta
        $alerta->setStatus($row['status']);
        $alerta->setIdUsuarioAtendimento($row['id_usuario_atendimento']);
        
        $produto = new Produto();
        $produto->setNome($row['produto_nome']);
        $produto->setCategoria($row['categoria_nome']); 
        
        $sensor = new Sensor();
        $sensor->setCorredor($row['corredor']);
        $sensor->setLado($row['lado']);
        
        $alerta->setProduto($produto);
        $alerta->setSensor($sensor);
        
        return $alerta;
    }

    public function assumirAlerta($idAlerta, $idUsuario) {
        $sql = "UPDATE historico_alertas SET status = 'em_andamento', id_usuario_atendimento = :id_user 
                WHERE id = :id_alerta AND status = 'pendente'";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute(['id_user' => $idUsuario, 'id_alerta' => $idAlerta]);
    }

    public function finalizarAlerta($idAlerta) {
        $sql = "UPDATE historico_alertas SET status = 'resolvido' WHERE id = :id_alerta";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute(['id_alerta' => $idAlerta]);
    }
}