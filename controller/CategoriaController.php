<?php

class CategoriaController {
    private $categoriaDAO;

    public function __construct() {
        $this->categoriaDAO = new CategoriaDAO();
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

        header('Location: roteador.php?controller=Gestao&action=index&status=sucesso');
        exit;
    } catch (Exception $e) {
        header('Location: roteador.php?controller=Gestao&action=index&status=erro');
        exit;
    }
}
}
