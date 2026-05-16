<?php

class UsuarioDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }
    
    public function cadastrar(Usuario $usuario) {
        try {
            // Ajustei para 'usuarios' assumindo ser o padrão
            $sql = "INSERT INTO usuarios (nome, login, senha, nivel_acesso) 
                    VALUES (:nome, :login, :senha, :nivelAcesso)";
            
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(':login', $usuario->getlogin());
            $stmt->bindValue(':senha', $usuario->getSenha());
            $stmt->bindValue(':nivelAcesso', $usuario->getNivelAcesso());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir usuário no banco de dados.");
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