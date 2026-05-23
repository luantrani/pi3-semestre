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

    public function excluir() {
        try {
            $id = $_GET['id'] ?? null;

            if (!$id) {
                throw new Exception('ID da categoria é obrigatório para exclusão.');
            }

            $this->categoriaDAO->excluir($id);

            header('Location: roteador.php?controller=Gestao&action=index&status=sucesso');
            exit;
        } catch (Exception $e) {
            header('Location: roteador.php?controller=Gestao&action=index&status=erro');
            exit;
        }
    }

    public function editar() {
        try {
            $id = $_POST['id'] ?? null;
            $nome = trim($_POST['nome'] ?? '');

            if (!$id) {
                throw new Exception('ID da categoria é obrigatório para edição.');
            }

            if (empty($nome)) {
                throw new Exception('Nome da categoria é obrigatório.');
            }

            $categoria = new Categoria();
            $categoria->setId($id);
            $categoria->setNome($nome);
            $this->categoriaDAO->alterar($categoria);

            header('Location: roteador.php?controller=Gestao&action=index&status=sucesso');
            exit;
        } catch (Exception $e) {
            header('Location: roteador.php?controller=Gestao&action=index&status=erro');
            exit;
        }
    }
}
