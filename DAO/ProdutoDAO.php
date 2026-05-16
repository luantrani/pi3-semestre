<?php

class ProdutoDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function inserir(Produto $p) {
        try {
            $sql = "INSERT INTO produtos (nome, peso_unitario, idcategoria) VALUES (?, ?, ?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$p->getNome(), $p->getPesoUnitario(), $p->getCategoria()->getId()]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir produto no banco de dados." . $e->getMessage());
        }
    }

    public function listarTodos() {
        try {
            $sql = "SELECT * FROM produtos";
            $stmt = $this->conexao->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar produtos. " . $e->getMessage());
        }
    }

    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM produtos WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produto. " . $e->getMessage());
        }
    }
}