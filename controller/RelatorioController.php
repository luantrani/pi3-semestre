<?php

class RelatorioController {
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
            require_once __DIR__ . '/../view/relatorios.php';
        } catch (Exception $e) {
            die("Erro ao carregar relatorios: " . $e->getMessage());
        }
    }
}