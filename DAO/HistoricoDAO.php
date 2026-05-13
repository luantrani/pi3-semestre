<?php
class HistoricoDAO {
    
    public function registrarAlerta($id_sensor, $quantidade) {
        $sql = "INSERT INTO historico_alertas (id_sensor, quantidade_no_momento) VALUES (?, ?)";
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->execute([$id_sensor, $quantidade]);
    }

    public function marcarComoResolvido($id_alerta) {
        $sql = "UPDATE historico_alertas SET status = 'resolvido' WHERE id = ?";
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->execute([$id_alerta]);
    }
}