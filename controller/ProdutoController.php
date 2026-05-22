<?php

class ProdutoController {
    private $produtoDAO;
    private $categoriaDAO;

    public function __construct() {
        $this->produtoDAO = new ProdutoDAO();
        $this->categoriaDAO = new CategoriaDAO();
    }

    public function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $nome         = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $peso_unitario = filter_input(INPUT_POST, 'peso_unitario', FILTER_VALIDATE_FLOAT);
            $id_categoria  = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);

            if ($nome && $peso_unitario !== false && $id_categoria) {
                try {
                    $categoria = new Categoria();
                    $categoria = $this->categoriaDAO->buscarPorId($id_categoria); 

                    $produto = new Produto();
                    $produto->setNome($nome);
                    $produto->setPesoUnitario($peso_unitario);
                    $produto->setCategoria($categoria);

                    $sucesso = $this->produtoDAO->inserir($produto);

                    if ($sucesso) {
                        header('Location: roteador.php?controller=Gestao&action=index&status=sucesso');
                        exit;
                    }
                } catch (Exception $e) {
                    header('Location: roteador.php?controller=Gestao&action=index&status=erro');
                    exit;
                }
            }
        }
        
        header('Location: roteador.php?controller=Gestao&action=index&status=erro');
        exit;
    }

    public function listar() {
        try {
            return $this->produtoDAO->listarTodos();
        } catch (Exception $e) {
            throw new Exception("Erro ao listar produtos: " . $e->getMessage());
        }
    }
}