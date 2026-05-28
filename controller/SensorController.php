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
            // Coletando apenas o que vem do formulário HTML
            $idSensor         = trim($_POST['idSensor'] ?? '');
            $nome             = trim($_POST['nomeSensor'] ?? '');
            $corredor         = trim($_POST['corredor'] ?? '');
            $lado             = $_POST['lado'] ?? '';
            $idProduto        = intval($_POST['id_produto'] ?? 0);
            $capacidadeMaxima = floatval($_POST['capacidadeMaxima'] ?? 0);
            $minimoReposicao  = floatval($_POST['minimoReposicao'] ?? 0);

            // Instanciando o Model
            $sensor = new Sensor();
            $sensor->setId($idSensor);
            $sensor->setNome($nome);
            $sensor->setCorredor($corredor);
            $sensor->setLado($lado);
            $sensor->setCapacidadeMaxima($capacidadeMaxima);
            $sensor->setMinimoReposicao($minimoReposicao);
            $sensor->setStatus('Ativo');

            // Vinculando o produto (se houver um selecionado)
            if ($idProduto > 0) {
                $produto = $this->produtoDAO->buscarPorId($idProduto);
                if ($produto) {
                    $sensor->setProduto($produto);
                }
            }

            // IMPORTANTE: Peso e Quantidade começam zerados. 
            // O valor real virá assim que o HX711/ESP32 fizer a primeira leitura.
            $sensor->setPesoAtual(0);
            $sensor->setQuantidadeAtual(0);

            $this->sensorDAO->inserir($sensor);

            header("Location: roteador.php?controller=Sensor&action=index&status=sucesso");
            exit;

        } catch (Exception $e) {
            echo "Erro ao cadastrar: " . $e->getMessage();
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
        $this->sensorDAO->salvarLeituraSensor($sensor);
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