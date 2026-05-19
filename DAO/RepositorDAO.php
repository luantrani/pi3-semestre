<?php

class RepositorDAO{
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function listarTodos() {
        $sql = "SELECT * FROM repositores";
        $stmt = $this->conexao->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastrarRepositor(Repositor $repositor) {
        try {
            $sql = "INSERT INTO repositores (nome, login, senha) VALUES (?, ?, ?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([
                $repositor->getNome(),
                $repositor->getLogin(),
                $repositor->getSenha()
            ]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erro ao cadastrar repositor no banco de dados." . $e->getMessage());
        }
    }
}