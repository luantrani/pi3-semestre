<?php

class CategoriaDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function listarTodos() {
        $sql = "SELECT * FROM categorias ORDER BY nome ASC";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function inserir(Categoria $categoria) {
        try {
            $sql = "INSERT INTO categorias (nome) VALUES (:nome)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':nome', $categoria->getNome());
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir categoria no banco de dados. " . $e->getMessage());
        }
    }
}
