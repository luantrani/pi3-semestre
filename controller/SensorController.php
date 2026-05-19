<?php

class SensorController {
    private $sensorDAO;
    private $produtoDAO;

    public function __construct() {
        $this->sensorDAO = new SensorDAO();
        $this->produtoDAO = new ProdutoDAO();
    }

    public function index() {
        try {
            $status = $_GET['status'] ?? null;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $nivel = $_SESSION['usuario']['nivel_acesso'];
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel_acesso'] !== 'gerente') {
            session_destroy();
            header("Location: ../index.php?erro=acesso_negado");
            exit;
        }
            //$sensores = $this->sensorDAO->listarTodos();
            require_once __DIR__ . '/../view/config-iot.php';
        } catch (Exception $e) {
            die("Erro ao carregar sensores: " . $e->getMessage());
        }
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