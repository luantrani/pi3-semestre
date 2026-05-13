<?php

class CategoriaDAO {
    
    public function listar() {
        $sql = "SELECT * FROM categorias ORDER BY nome ASC";
        $stmt = Conexao::getConn()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}