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

            if ($nivelAcesso === 'repositor') {
                header('Location: view/cadastro-repositor.php?status=sucesso');
            } else {
                header('Location: index.php?status=sucesso');
            }
            exit;
        } catch (Exception $e) {
            if (($nivelAcesso ?? '') === 'repositor') {
                header('Location: view/cadastro-repositor.php?status=erro');
            } else {
                echo "Ops! Tivemos um problema ao processar seu cadastro. Tente novamente mais tarde.";
            }
            exit;
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
                    'nivel_acesso' => $usuario->getNivelAcesso()
                ];
                header("Location: view/home.php");
                exit;
            } else {
                echo "login ou senha inválidos. Tente novamente.";
            }
        } catch (Exception $e) {
            echo "Ops! Tivemos um problema ao processar seu login. Tente novamente mais tarde.";
        }
    }
}