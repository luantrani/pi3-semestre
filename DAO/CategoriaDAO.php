<?php

class CategoriaDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function listar() {
        $sql = "SELECT * FROM categorias ORDER BY nome ASC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}