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
            header("Location: index.php?erro=acesso_negado");
            exit;
        }
            $produtos = $this->produtoDAO->listarTodos();
            $sensores = $this->sensorDAO->listarTodos();
            require_once __DIR__ . '/../view/config-iot.php';
        } catch (Exception $e) {
            die("Erro ao carregar sensores: " . $e->getMessage());
        }
    }

    public function cadastrarSensor() {
       try {
        $idSensor = trim($_POST['idSensor'] ?? '');
        $nome = trim($_POST['nomeSensor'] ?? '');
        $corredor = trim($_POST['corredor'] ?? '');
        $lado = trim($_POST['lado'] ?? '');
        $capacidadeMaxima = intval($_POST['capacidade_maxima'] ?? 0);
        $minimoReposicao = intval($_POST['minimo_reposicao'] ?? 0);
        $idProduto = intval($_POST['id_produto'] ?? 0);
        $produto = $this->produtoDAO->buscarPorId($idProduto);
        if (!$produto) {
            throw new Exception("Produto não encontrado");
        }
        $sensor = new Sensor();
        $sensor->setId($idSensor);
        $sensor->setNome($nome);
        $sensor->setCorredor($corredor);
        $sensor->setLado($lado);
        $sensor->setCapacidadeMaxima($capacidadeMaxima);
        $sensor->setMinimoReposicao($minimoReposicao);
        $sensor->setProduto($produto);
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

    public function atualizarSensor(Sensor $sensor) {
        try {
            $produto = $this->produtoDAO->buscarPorId($sensor->getIdProduto());
            if (!$produto) {
                throw new Exception("Produto não encontrado");
            }
            $sensor = new Sensor();
            $this->sensorDAO->atualizar($sensor);
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar sensor: " . $e->getMessage());
        }
    }

    public function excluirSensor(Sensor $sensor) {
        try {
            $this->sensorDAO->excluir($sensor);
        } catch (Exception $e) {
            throw new Exception("Erro ao excluir sensor: " . $e->getMessage());
        }
}
}