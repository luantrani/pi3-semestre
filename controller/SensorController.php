<?php

class SensorController {
    private $sensorDAO;
    private $produtoDAO;

    public function __construct() {
        $this->sensorDAO = new SensorDAO();
        $this->produtoDAO = new ProdutoDAO();
    }

    public function cadastrarSensor($id, $nome, $corredor, $lado, $capacidadeMaxima, $minimoReposicao, $idProduto) {
       try {
        $produto = $this->produtoDAO->buscarPorId($idProduto);
        if (!$produto) {
            throw new Exception("Produto não encontrado");
        }
        $sensor = new Sensor(null, $corredor, $lado, $capacidadeMaxima, $minimoReposicao, $produto);
        $this->sensorDAO->inserir($sensor);
    } catch (Exception $e) {
        throw new Exception("Erro ao cadastrar sensor: " . $e->getMessage());
    }
    }

    public function listarSensores() {
        try {
            return $this->sensorDAO->listarTodos();
        } catch (Exception $e) {
            throw new Exception("Erro ao listar sensores: " . $e->getMessage());
        }
    }
}