<?php

class ProdutoController {
    // Removemos os objetos que não precisam ficar fixos na classe inteira
    private $produtoDAO;
    private $categoriaDAO;

    public function __construct() {
        $this->produtoDAO = new ProdutoDAO();
        $this->categoriaDAO = new CategoriaDAO(); // Conversa direto com o DAO de categorias!
    }

    public function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $nome         = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $pesoUnitario = filter_input(INPUT_POST, 'peso_unitario', FILTER_VALIDATE_FLOAT);
            $categoriaId  = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);

            if ($nome && $pesoUnitario !== false && $categoriaId) {
                try {
                    // Instanciamos as entidades apenas no momento do cadastro (escopo local)
                    $categoria = new Categoria();
                    $categoria->setId($categoriaId); 

                    $produto = new Produto();
                    $produto->setNome($nome);
                    $produto->setPesoUnitario($pesoUnitario);
                    $produto->setCategoria($categoria);

                    $sucesso = $this->produtoDAO->inserir($produto);

                    if ($sucesso) {
                        header('Location: roteador.php?controller=Produto&action=index&status=sucesso');
                        exit;
                    }
                } catch (Exception $e) {
                    header('Location: roteador.php?controller=Produto&action=index&status=erro');
                    exit;
                }
            }
        }
        
        header('Location: roteador.php?controller=Produto&action=index&status=erro');
        exit;
    }

    public function listar() {
        try {
            return $this->produtoDAO->listarTodos();
        } catch (Exception $e) {
            throw new Exception("Erro ao listar produtos: " . $e->getMessage());
        }
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Correção de segurança: Primeiro verifica se existe, depois lê o nível
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel_acesso'] !== 'gerente') {
            session_destroy();
            header("Location: index.php?erro=acesso_negado");
            exit;
        }

        $nivel = $_SESSION['usuario']['nivel_acesso'];
        $status = $_GET['status'] ?? null;

        try {
            $categorias = $this->categoriaDAO->listarTodos(); 
            $produtos   = $this->listar();
        } catch (Exception $e) {
            $status = 'erro_listar';
            $categorias = [];
            $produtos = [];
        }
        require_once __DIR__ . '/../view/cadastro-produto.php';
    }
}