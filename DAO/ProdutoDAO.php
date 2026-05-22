<?php

class ProdutoDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getConn();
    }

    public function inserir(Produto $p) {
        try {
            $sql = "INSERT INTO produtos (nome, peso_unitario, id_categoria) VALUES (?, ?, ?)";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$p->getNome(), $p->getPesoUnitario(), $p->getCategoria()->getId()]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir produto no banco de dados." . $e->getMessage());
        }
    }

    public function listarTodos() {
        try {
            $sql = "SELECT i.*, c.nome as categoria_nome FROM produtos i inner join categorias c on i.id_categoria = c.id order by i.nome asc";
            $stmt = $this->conexao->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar produtos. " . $e->getMessage());
        }
    }

    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM produtos WHERE id = ?";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute([$id]);
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$dados) {
                return null;
            }
            $categoriaDAO = new CategoriaDAO();
            $categoria = $categoriaDAO->buscarPorId($dados['id_categoria']);
            $produto = new Produto();
            $produto->setId($dados['id']);
            $produto->setNome($dados['nome']);
            $produto->setPesoUnitario($dados['peso_unitario']);
            $produto->setCategoria($categoria);
            return $produto;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produto. " . $e->getMessage());
        }
    }
}