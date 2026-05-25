<?php
class HistoricoDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function registrarAlerta($id_sensor, $quantidade) {
        $sql = "INSERT INTO historico_alertas (id_sensor, quantidade_no_momento) VALUES (?, ?)";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$id_sensor, $quantidade]);
    }

    public function marcarComoResolvido($id_alerta) {
        $sql = "UPDATE historico_alertas SET status = 'resolvido' WHERE id = ?";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([$id_alerta]);
    }

    public function listarAlertasPendentes() {
        $sql = "SELECT ha.*, p.nome as nome_p, p.peso_unitario as peso_p 
                FROM historico_alertas ha
                JOIN sensor s ON ha.id_sensor = s.id
                JOIN produtos p ON s.id_produto = p.id
                WHERE ha.status = 'pendente' ORDER BY data_hora_alerta DESC";
        
        $stmt = $this->conexao->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $objetos = [];
        foreach ($dados as $d) {
            // Cria o objeto Produto primeiro
            $prod = new Produto();
            $prod->setNome($d['nome_p']);
            
            // Cria o objeto Alerta passando o Produto
            $objetos[] = new Alerta($prod, $d['quantidade_no_momento'], $d['data_hora_alerta']);
        }
        return $objetos;
    }
}