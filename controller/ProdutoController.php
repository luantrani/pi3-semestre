<?php
class ProdutoController {

    public function cadastrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $pesoUnitario = filter_input(INPUT_POST, 'peso_unitario', FILTER_VALIDATE_FLOAT);
            $categoriaId = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);

            if ($nome && $pesoUnitario !== false && $categoriaId) {
                try {

                    $categoria = new Categoria();
                    $categoria->setId($categoriaId); 

                    $produto = new Produto();
                    $produto->setNome($nome);
                    $produto->setPesoUnitario($pesoUnitario);
                    

                    $produto->setCategoria($categoria);


                    $produtoDAO = new ProdutoDAO();
                    $sucesso = $produtoDAO->inserir($produto);

                    if ($sucesso) {
                        header('Location: View/cadastro-produto.php?status=sucesso');
                        exit;
                    }
                } catch (Exception $e) {
                    header('Location: View/cadastro-produto.php?status=erro');
                    exit;
                }
            }
        }
        
        header('Location: View/cadastro-produto.php?status=erro');
        exit;
    }


}