<?php

class ProdutoDAO {
    public function inserir(Produto $p) {
        $sql = "INSERT INTO produtos (nome, peso_unitario, estoque_minimo) VALUES (?, ?, ?)";
        $stmt = Conexao::getConn()->prepare($sql);
        $stmt->execute([$p->nome, $p->pesoUnitario, $p->estoqueMinimo]);
    }

    public function listarTodos() {
        $sql = "SELECT * FROM produtos";
        $stmt = Conexao::getConn()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}