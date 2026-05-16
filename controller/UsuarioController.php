<?php

class UsuarioController {

    public function cadastrar() {
        try {
            $nome = $_POST['nome'];
            $login = $_POST['login'];
            $senha = $_POST['senha'];
            $nivelAcesso = $_POST['nivelAcesso'];

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $usuario = new Usuario($nome, $login, $senhaHash, $nivelAcesso);
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
            $login = $_POST['login'];
            $senha = $_POST['senha'];
            
            $usuarioDAO = new UsuarioDAO();
            $usuario = $usuarioDAO->buscarPorlogin($login);

            if ($usuario && password_verify($senha, $usuario->getSenha())) {
                session_start();
                $_SESSION['usuario'] = [
                    'id' => $usuario->getId(),
                    'nome' => $usuario->getNome(),
                    'nivelAcesso' => $usuario->getNivelAcesso()
                ];
                header("Location: view/home.html");
                exit;
            } else {
                echo "login ou senha inválidos. Tente novamente.";
            }
        } catch (Exception $e) {
            echo "Ops! Tivemos um problema ao processar seu login. Tente novamente mais tarde.";
        }
    }
}