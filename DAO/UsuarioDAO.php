<?php

class UsuarioDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }
    
    public function cadastrar(Usuario $usuario) {
        try {
            $sql = "INSERT INTO usuarios (nome, login, senha, nivel_acesso) 
                    VALUES (:nome, :login, :senha, :nivel_acesso)";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(':login', $usuario->getlogin());
            $stmt->bindValue(':senha', $usuario->getSenha());
            $stmt->bindValue(':nivel_acesso', $usuario->getNivelAcesso());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir usuário no banco de dados." . $e->getMessage());
        }
    }

    public function listarPorNivelAcesso($nivelAcesso) {
        try {
            $sql = "SELECT id, nome, login, nivel_acesso FROM usuarios WHERE nivel_acesso = :nivelAcesso ORDER BY nome ASC";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':nivelAcesso', $nivelAcesso);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar usuários por nível de acesso. " . $e->getMessage());
        }
    }

    public function buscarPorlogin($login) {
        try {
            $sql = "SELECT * FROM usuarios WHERE login = :login";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':login', $login);
            $stmt->execute();
            
            return $stmt->fetchObject('Usuario');
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar usuário. " . $e->getMessage());
        }
    }
}