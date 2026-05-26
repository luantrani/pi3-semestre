<?php

class RepositorController {
    private $usuarioDAO;
    private $sensorDAO;
    private $HistoricoAlertasDAO; // O nome definido aqui deve ser usado em todo o arquivo

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        $this->sensorDAO = new SensorDAO();
        $this->HistoricoAlertasDAO = new HistoricoAlertasDAO();
    }

    public function index() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['usuario'])) {
                session_destroy();
                header("Location: index.php?erro=acesso_negado");
                exit;
            }

            $sensores = $this->sensorDAO->listarTodos();
            $alertas = $this->HistoricoAlertasDAO->buscarAlertasAtivos(); 
            $totalAlertas = count($alertas);

            require_once __DIR__ . '/../view/repositor.php';

        } catch (Exception $e) {
            die("Erro ao processar dados do repositor: " . $e->getMessage());
        }
    }

    public function atender() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $idAlerta = $_GET['id'] ?? null;
        $idUsuario = $_SESSION['usuario']['id'] ?? null;
        
        // CORREÇÃO: Usando o nome correto HistoricoAlertasDAO
        if ($idAlerta && $idUsuario) {
            $sucesso = $this->HistoricoAlertasDAO->assumirAlerta($idAlerta, $idUsuario);
            
            if ($sucesso) {
                http_response_code(200);
                echo "OK";
            } else {
                http_response_code(500);
                echo "Erro ao assumir no banco";
            }
        } else {
            http_response_code(400);
            echo "Dados insuficientes";
        }
        exit; // Importante para não carregar o HTML da view no meio do AJAX
    }

    public function finalizar() {
        $idAlerta = $_GET['id'] ?? null;
        
        if ($idAlerta) {
            // CORREÇÃO: Usando o nome correto HistoricoAlertasDAO
            $sucesso = $this->HistoricoAlertasDAO->finalizarAlerta($idAlerta);
            
            if ($sucesso) {
                http_response_code(200);
                echo "OK";
            } else {
                http_response_code(500);
            }
        }
        exit;
    }
}