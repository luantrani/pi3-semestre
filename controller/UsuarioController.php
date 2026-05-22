<?php

class UsuarioController
{
    private $usuarioDAO;

    public function __construct()
    {
        $this->usuarioDAO = new UsuarioDAO();
    }

    // ... (método index omitido para focar no erro)

    public function cadastrar()
    {
        try {
            $nome = $_POST['nome'];
            $login = $_POST['login'];
            $senha = $_POST['senha'];
            $nivel_acesso = "repositor";

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
            $usuario = new Usuario();
            $usuario->setNome($nome);
            $usuario->setLogin($login);
            $usuario->setSenha($senhaHash);
            $usuario->setNivelAcesso($nivel_acesso);

            $sucesso = $this->usuarioDAO->cadastrar($usuario);

            if (!$sucesso) {
                throw new Exception("Erro ao cadastrar usuário");
            }

            // CORREÇÃO AQUI: Removido o __DIR__ e as aspas simples problemáticas
            header("Location: roteador.php?controller=Gestao&action=index&status=sucesso");
            exit;
        } catch (Exception $e) {
            // CORREÇÃO AQUI: Redireciona para erro se algo falhar
            header("Location: roteador.php?controller=Gestao&action=index&status=erro");
            exit;
        }
    }

    public function login()
    {
        try {
            $login = $_POST['login'];
            $senha = $_POST['senha'];

            $usuario = $this->usuarioDAO->buscarPorlogin($login);
            if ($usuario && password_verify($senha, $usuario->getSenha())) {
                if (session_status() === PHP_SESSION_NONE) session_start();

                $_SESSION['usuario'] = [
                    'id' => $usuario->getId(),
                    'nome' => $usuario->getNome(),
                    'nivel_acesso' => $usuario->getNivelAcesso()
                ];

                // CORREÇÃO AQUI: Redirecionamento limpo para o roteador
                if ($usuario->getNivelAcesso() === 'gerente') {
                    header("Location: roteador.php?controller=Home&action=index");
                } else {
                    header("Location: roteador.php?controller=Repositor&action=index");
                }
                exit;
            } else {
                header("Location: index.php?erro=login_invalido");
            }
        } catch (Exception $e) {
            header("Location: index.php?erro=problema_servidor");
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: index.php");
        exit;
    }
}
