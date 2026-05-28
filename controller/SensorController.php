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

        // Define a quantidade inicial (ou usa a máxima se o campo estiver vazio)
        $qtdInicial = intval($_POST['quantidade_atual'] ?? $capacidadeMaxima);

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
        
        // --- A ORDEM AQUI É IMPORTANTE ---
        // 1º Setamos o produto para o Model saber o peso unitário
        $sensor->setProduto($produto);
        
        // 2º Setamos a quantidade (o Model vai calcular o peso sozinho agora)
        $sensor->setQuantidadeAtual($qtdInicial);
        
        $sensor->setStatus('Ativo');

        $this->sensorDAO->inserir($sensor);

        header("Location: roteador.php?controller=Sensor&action=index&status=sucesso");
        exit;
    } catch (Exception $e) {
        // Exibe o erro na tela para você debugar se algo der errado
        echo "Erro: " . $e->getMessage();
        exit;
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
        // Não crie um "new Sensor()", use o que veio por parâmetro!
        $produto = $this->produtoDAO->buscarPorId($sensor->getProduto()->getId());
        if (!$produto) {
            throw new Exception("Produto não encontrado");
        }
        $sensor->setProduto($produto);
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

    public function getPesoUnitario() {
    return $this->peso_unitario;
    }
}