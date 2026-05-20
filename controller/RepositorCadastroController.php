<?php

class RepositorCadastroController {
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
            $nome = $_POST['nome'] ?? null;
            $login = $_POST['login'] ?? null;
            $senha = $_POST['senha'] ?? null;
            $senhahash = password_hash($senha, PASSWORD_DEFAULT);
            if (!$nome || !$login || !$senha) {
                header("Location: roteador.php?controller=RepositorCadastro&action=index&status=erro_cadastro");
                exit;
            }
            $usuario = new Usuario();
            $usuario->setNome($nome);
            $usuario->setLogin($login);
            $usuario->setSenha($senhahash);
            $usuario->setNivelAcesso('repositor');

            $this->usuarioDAO->cadastrar($usuario);
            header("Location: roteador.php?controller=RepositorCadastro&action=index&status=sucesso");
        } catch (Exception $e) {
            die("Erro ao cadastrar repositor: " . $e->getMessage());
        }
    }

    
}