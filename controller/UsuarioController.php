<?php

class UsuarioController {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function index() {
        try {
            $status = $_GET['status'] ?? null;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $nivel = $_SESSION['usuario']['nivel_acesso'];
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel_acesso'] !== 'gerente') {
            session_destroy();
            header("Location: index.php?erro=acesso_negado");
            exit;
        }
            $repositores = $this->usuarioDAO->listarRepositores();
            require_once __DIR__ . '/../view/cadastro-repositor.php';
        } catch (Exception $e) {
            die("Erro ao carregar repositores: " . $e->getMessage());
        }
    }

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

            header('Location: __DIR__ . "/../roteador.php?controller=RepositorCadastro&action=index&status=sucesso"');
            
            exit;
        } catch (Exception $e) {
                header('Location: __DIR__ . "/../roteador.php?controller=RepositorCadastro&action=index&status=erro"');
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
                if ($usuario->getNivelAcesso() === 'gerente') {
                    header("Location: __DIR__ . '/../roteador.php?controller=Home&action=index");
                } else {
                    header("Location: __DIR__ . '/../roteador.php?controller=Repositor&action=index");
                }
                exit;
            } else {
                echo "login ou senha inválidos. Tente novamente.";
            }
        } catch (Exception $e) {
            echo "Ops! Tivemos um problema ao processar seu login. Tente novamente mais tarde.";
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php");
        exit;
    }
}