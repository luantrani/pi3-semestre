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
        $this->historicoDAO = new HistoricoDAO();

        // Obtém a lista de objetos Sensor (já com Produto e Categoria acoplados)
        $sensores = $this->sensorDAO->listarTodos();
        
        // Obtém a lista de objetos Alerta
        $alertas = $this->historicoDAO->listarAlertasPendentes();

        // Processamento dos KPIs usando os métodos da classe Sensor
        $totalCheios = 0;
        $totalCriticos = 0;

        foreach ($sensores as $s) {
        // Usando o método que criamos no Model Sensor.php
        if ($s->getPorcentagemEstoque() >= 80) {
            $totalCheios++;
        }
        
        if ($s->precisaReposicao()) {
            $totalCriticos++;
        }

    }

        // Carrega a View (as variáveis $sensores, $alertas, $totalCheios e $totalCriticos serão usadas lá)
        include __DIR__ . '/../view/home.php';
    }
}