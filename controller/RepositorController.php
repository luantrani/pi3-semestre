<?php

class RepositorController {
    private $usuarioDAO;
    private $sensorDAO;
    private $HistoricoAlertasDAO; 
    private $movimentacaoEstoqueDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        $this->sensorDAO = new SensorDAO();
        $this->HistoricoAlertasDAO = new HistoricoAlertasDAO();
        $this->movimentacaoEstoqueDAO = new MovimentacaoEstoqueDAO();
    }

    public function index() {
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $nivel = $_SESSION['usuario']['nivel_acesso'] ?? null;
            if (!isset($_SESSION['usuario'])) {
                session_destroy();
                header("Location: index.php?erro=acesso_negado");
                exit;
            }
            $sensores = $this->sensorDAO->listarTodos();
            $alertas = $this->HistoricoAlertasDAO->buscarAlertasAtivos(); 
           if ($nivel === 'gerente') {
                // Se for admin, carrega a versão com menu lateral e filtros
                require_once __DIR__ . '/../view/repositor.php';
            } else {
                // Se for repositor, carrega a versão "App Mobile" sem menu
                require_once __DIR__ . '/../view/repositor/painel.php';
            }

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
        // CORREÇÃO 1: Precisa iniciar a sessão para ler o ID do usuário!
        if (session_status() === PHP_SESSION_NONE) session_start();

        $idAlerta = $_GET['id'] ?? null;
        $idUsuario = $_SESSION['usuario']['id'] ?? null;
        
        if ($idAlerta && $idUsuario) { // Só entra se tiver os dois IDs
            $alerta = $this->HistoricoAlertasDAO->buscarPorId($idAlerta);
            
            if ($alerta) {
                $sensor = $alerta->getSensor();
                $produto = $alerta->getProduto(); 

                // CORREÇÃO 2: Verifique se o ID do produto existe antes de prosseguir
                if ($produto && $produto->getId() !== null) {
                    
                    $capacidadeMax = $sensor->getCapacidadeMaxima();
                    $pesoUnitario = $produto->getPesoUnitario();
                    $novoPesoTotal = $capacidadeMax * $pesoUnitario;
                    $qtdNoAlerta = $alerta->getQuantidadeNoMomento();
                    $qtdAdicionada = $capacidadeMax - $qtdNoAlerta;

                    // 3. Atualiza o Sensor
                    $sensor->setQuantidadeAtual($capacidadeMax);
                    $sensor->setPesoAtual($novoPesoTotal);
                    $this->sensorDAO->salvarLeituraSensor($sensor);

                    // 4. Finaliza o Alerta
                    $alerta->setStatus('concluido');
                    $alerta->setDataFim(date('Y-m-d H:i:s'));
                    $this->HistoricoAlertasDAO->atualizar($alerta);
                    
                    // 5. Registra Movimentação
                    $this->movimentacaoEstoqueDAO->registrar(
                        $produto->getId(),
                        $idUsuario,
                        $qtdAdicionada,
                        $idAlerta
                    );

                    http_response_code(200);
                    echo "Finalizado";
                } else {
                    http_response_code(500);
                    echo "Erro: ID do produto está nulo no objeto. Verifique o DAO.";
                }
            } else {
                http_response_code(404);
                echo "Alerta não encontrado";
            }
        } else {
            http_response_code(401);
            echo "Erro: Usuário não identificado ou ID ausente. Faça login novamente.";
        }
        exit;
    }
}