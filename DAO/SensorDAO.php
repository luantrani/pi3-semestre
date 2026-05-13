<?php

class SensorDAO {
    
    public function atualizarPeso($id_sensor, $novo_peso) {
        $sql = "UPDATE sensor SET peso_atual = ?, ultima_atualizacao = NOW() WHERE id = ?";
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->execute([$novo_peso, $id_sensor]);
    }

    public function listarAlertasReposicao() {
        $sql = "SELECT s.*, p.nome, p.peso_unitario, p.estoque_minimo 
                FROM sensor s 
                JOIN produtos p ON s.id_produto = p.id
                ORDER BY s.ultima_atualizacao DESC";
        
        $stmt = Conexao::getConn()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}