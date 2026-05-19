<?php

class RepositorCadastroController {
    private $repositorDAO;

    public function __construct() {
        $this->repositorDAO = new RepositorDAO();
    }
    
    public function cadastrar() {
        try {
            $nome = $_POST['nome'] ?? null;
            $login = $_POST['login'] ?? null;
            $senha = $_POST['senha'] ?? null;

            if (!$nome || !$login || !$senha) {
                header("Location: roteador.php?controller=RepositorCadastro&action=index&status=erro_cadastro");
                exit;
            }
            $repositor = new Repositor();
            $repositor->setNome($nome);
            $repositor->setLogin($login);
            $repositor->setSenha($senha);

            $this->repositorDAO->cadastrarRepositor($repositor);
            header("Location: roteador.php?controller=RepositorCadastro&action=index&status=sucesso");
        } catch (Exception $e) {
            die("Erro ao cadastrar repositor: " . $e->getMessage());
        }
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
            header("Location: ../index.php?erro=acesso_negado");
            exit;
        }
            $repositores = $this->repositorDAO->listarTodos();
            require_once __DIR__ . '/../view/cadastro-repositor.php';
        } catch (Exception $e) {
            die("Erro ao carregar repositores: " . $e->getMessage());
        }
    }
}