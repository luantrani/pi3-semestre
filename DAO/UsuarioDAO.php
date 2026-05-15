<?php

class UsuarioDAO {
    public function cadastrar(Usuario $usuario) {
        try {
            // Ajustei para 'usuarios' assumindo ser o padrão
            $sql = "INSERT INTO usuarios (nome, email, senha, nivel_acesso) 
                    VALUES (:nome, :email, :senha, :nivelAcesso)";
            
            $stmt = Conexao::getConn()->prepare($sql);
            $stmt->bindValue(':nome', $usuario->getNome());
            $stmt->bindValue(':email', $usuario->getEmail());
            $stmt->bindValue(':senha', $usuario->getSenha());
            $stmt->bindValue(':nivelAcesso', $usuario->getNivelAcesso());
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir usuário no banco de dados.");
        }
    }

    public function buscarPorEmail($email) {
        try {
            $sql = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = Conexao::getConn()->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            
            return $stmt->fetchObject('Usuario');
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar usuário.");
        }
    }
}