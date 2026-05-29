<?php
require_once __DIR__ . '/Conexao.php';

class MovimentacaoEstoqueDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getConn();
    }

    public function registrar($idProduto, $idUsuario, $quantidade, $idAlerta = null) {
        // Agora com a coluna id_alerta criada no banco, esse SQL vai funcionar
        $sql = "INSERT INTO movimentacao_estoque (id_produto, id_usuario, quantidade_adicionada, id_alerta, data_hora) 
                VALUES (:id_prod, :id_user, :qtd, :id_alerta, NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id_prod', $idProduto);
        $stmt->bindValue(':id_user', $idUsuario);
        $stmt->bindValue(':qtd', $quantidade);
        $stmt->bindValue(':id_alerta', $idAlerta);
        
        return $stmt->execute();
    }

    public function listarHistorico() {
    try {
        $sql = "SELECT m.*, p.nome as produto_nome, u.nome as usuario_nome 
                FROM movimentacao_estoque m
                JOIN produtos p ON m.id_produto = p.id
                JOIN usuarios u ON m.id_usuario = u.id
                ORDER BY m.data_hora DESC LIMIT 20";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

}