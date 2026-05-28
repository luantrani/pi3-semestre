<?php

class RepositorController {
    private $usuarioDAO;
    private $sensorDAO;
    private $HistoricoAlertasDAO; 

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        $this->sensorDAO = new SensorDAO();
        $this->HistoricoAlertasDAO = new HistoricoAlertasDAO();
    }

    public function index() {
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();

            if (!isset($_SESSION['usuario'])) {
                session_destroy();
                header("Location: index.php?erro=acesso_negado");
                exit;
            }

            $sensores = $this->sensorDAO->listarTodos();
            $alertas = $this->HistoricoAlertasDAO->buscarAlertasAtivos(); 
            
            require_once __DIR__ . '/../view/repositor.php';

        } catch (Exception $e) {
            die("Erro ao processar dados do repositor: " . $e->getMessage());
        }
    }

    public function atender() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $idAlerta = $_GET['id'] ?? null;
        $idUsuario = $_SESSION['usuario']['id'] ?? null;
        
        if ($idAlerta && $idUsuario) {
            $sucesso = $this->HistoricoAlertasDAO->assumirAlerta($idAlerta, $idUsuario);
            if ($sucesso) {
                http_response_code(200);
                echo "OK";
            } else {
                http_response_code(500);
                echo "Erro ao assumir no banco";
            }
        }
        exit; 
    }

    public function finalizar() {
        $idAlerta = $_GET['id'] ?? null;
        
        if ($idAlerta) {
            // 1. Usa o objeto que já foi criado no constructor
            // Certifique-se que o método buscarPorId existe no seu HistoricoAlertasDAO
            $alerta = $this->HistoricoAlertasDAO->buscarPorId($idAlerta);
            
            if ($alerta) {
                $sensor = $alerta->getSensor();
                $produto = $sensor->getProduto();

                // 2. Lógica de reposição total
                $capacidadeMax = $sensor->getCapacidadeMaxima();
                $pesoUnitario = $produto->getPesoUnitario();
                $novoPesoTotal = $capacidadeMax * $pesoUnitario;

                // 3. Atualiza o Sensor usando o DAO que já existe na classe
                $sensor->setQuantidadeAtual($capacidadeMax);
                $sensor->setPesoAtual($novoPesoTotal);
                $this->sensorDAO->salvarLeituraSensor($sensor);

                // 4. Finaliza o Alerta
                $alerta->setStatus('concluido');
                $alerta->setDataFim(date('Y-m-d H:i:s'));
                
                // Ajuste aqui para o nome do método que você tem no HistoricoAlertasDAO
                $this->HistoricoAlertasDAO->atualizar($alerta);

                http_response_code(200);
                echo "Finalizado";
            } else {
                http_response_code(404);
                echo "Alerta não encontrado";
            }
        } else {
            http_response_code(400);
            echo "ID inválido";
        }
        exit; // Essencial para o AJAX não receber lixo de memória
    }
}