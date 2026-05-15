<?php

class UsuarioController {

    public function cadastrar() {
        try {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $nivelAcesso = $_POST['nivelAcesso'];

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $usuario = new Usuario($nome, $email, $senhaHash, $nivelAcesso);
            $usuarioDAO = new UsuarioDAO();
            $sucesso = $usuarioDAO->cadastrar($usuario);
            header("Location: index.php?status=sucesso");
            exit;
        } catch (Exception $e) {
            echo "Ops! Tivemos um problema ao processar seu cadastro. Tente novamente mais tarde.";
        }
    }

    public function login() {
        try {
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $usuarioDAO = new UsuarioDAO();
            $usuario = $usuarioDAO->buscarPorEmail($email);

            if ($usuario && password_verify($senha, $usuario->getSenha())) {
                session_start();
                $_SESSION['usuario'] = [
                    'id' => $usuario->getId(),
                    'nome' => $usuario->getNome(),
                    'nivelAcesso' => $usuario->getNivelAcesso()
                ];
                header("Location: home.php");
                exit;
            } else {
                echo "Email ou senha inválidos. Tente novamente.";
            }
        } catch (Exception $e) {
            echo "Ops! Tivemos um problema ao processar seu login. Tente novamente mais tarde.";
        }
    }
}