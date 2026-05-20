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
            $senhahash = password_hash($senha, PASSWORD_DEFAULT);
            if (!$nome || !$login || !$senha) {
                header("Location: roteador.php?controller=RepositorCadastro&action=index&status=erro_cadastro");
                exit;
            }
            $repositor = new Repositor();
            $repositor->setNome($nome);
            $repositor->setLogin($login);
            $repositor->setSenha($senhahash);

            $this->repositorDAO->cadastrarRepositor($repositor);
            header("Location: roteador.php?controller=RepositorCadastro&action=index&status=sucesso");
        } catch (Exception $e) {
            die("Erro ao cadastrar repositor: " . $e->getMessage());
        }
    }

    
}