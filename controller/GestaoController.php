<?php

class GestaoController {

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
        // 1. Instanciar os DAOs necessários
        $produtoDAO = new ProdutoDAO();
        $categoriaDAO = new CategoriaDAO();
        $usuarioDAO = new UsuarioDAO();

        // 2. Buscar todos os dados para as abas
        // Certifique-se que seu ProdutoDAO tenha um método que traga o nome da categoria (JOIN)
        $produtos = $produtoDAO->listarTodos(); 
        $categorias = $categoriaDAO->listarTodos();
        
        // Filtramos para buscar apenas quem tem nível 'repositor'
        $repositores = $usuarioDAO->listarRepositores();

        // 3. Capturar status de operações anteriores (se houver)
        $status = $_GET['status'] ?? null;

        // 4. Carregar a View única passando o "pacotão" de dados
        require_once "view/gestao.php";
    }
}