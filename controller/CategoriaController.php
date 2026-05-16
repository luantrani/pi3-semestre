<?php

class CategoriaController {

    public function cadastrar() {
        try {
            $nome = trim($_POST['nome'] ?? '');

            if (empty($nome)) {
                throw new Exception('Nome da categoria é obrigatório.');
            }

            $categoria = new Categoria(null, $nome);
            $categoriaDAO = new CategoriaDAO();
            $categoriaDAO->inserir($categoria);

            header('Location: view/cadastro-categoria.php?status=sucesso');
            exit;
        } catch (Exception $e) {
            header('Location: view/cadastro-categoria.php?status=erro');
            exit;
        }
    }
}
