<?php

class CategoriaController {
    private $categoriaDAO;

    public function __construct() {
        $this->categoriaDAO = new CategoriaDAO();
    }

    public function index() {
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
        try {
            $categorias = $this->categoriaDAO->listarTodos();
        } catch (Exception $e) {
            die("Erro ao carregar categorias: " . $e->getMessage());
        }
        
        include __DIR__ . '/../view/cadastro-categoria.php';
    }

    public function cadastrar() {
    try {
        $nome = trim($_POST['nome'] ?? '');

        if (empty($nome)) {
            throw new Exception('Nome da categoria é obrigatório.');
        }

        $categoria = new Categoria();
        $categoria->setNome($nome);
        $this->categoriaDAO->inserir($categoria);

        header('Location: roteador.php?controller=Categoria&action=index&status=sucesso');
        exit;
    } catch (Exception $e) {
        header('Location: roteador.php?controller=Categoria&action=index&status=erro');
        exit;
    }
}
}
