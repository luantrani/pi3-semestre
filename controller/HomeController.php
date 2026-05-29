<?php

class HomeController {
    private $sensorDAO;
    private $historicoDAO;

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Validação de acesso para Gerente
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel_acesso'] !== 'gerente') {
            session_destroy();
            header("Location: index.php?erro=acesso_negado");
            exit;
        }

        $this->sensorDAO = new SensorDAO();
        $this->historicoAlertasDAO = new HistoricoAlertasDAO();

        // Obtém a lista de objetos Sensor (já com Produto e Categoria acoplados)
        $sensores = $this->sensorDAO->listarTodos();
        
        // Obtém a lista de objetos Alerta
        $alertas = $this->historicoAlertasDAO->buscarAlertasAtivos();

        // Processamento dos KPIs usando os métodos da classe Sensor
        $totalCheios = 0;
        $totalCriticos = 0;
        $totalAtencao = 0; // Opcional, se quiser mostrar o amarelo

        foreach ($sensores as $s) {
            $porcentagem = $s->getPorcentagemEstoque();
            
            if ($s->precisaReposicao()) {
                $totalCriticos++;
            } elseif ($porcentagem >= 50) {
                $totalCheios++; // Só conta como "Em Conformidade" se for 50% ou mais
            } else {
                $totalAtencao++; // Sensores entre 21% e 49%
            }
        }

        // Carrega a View (as variáveis $sensores, $alertas, $totalCheios e $totalCriticos serão usadas lá)
        include __DIR__ . '/../view/home.php';
    }
}