<?php

class RepositorController {
    private $repositorDAO;

    public function __construct() {
        $this->repositorDAO = new RepositorDAO();
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
            //$repositores = $this->repositorDAO->listarTodos();
            require_once __DIR__ . '/../view/repositor.php';
        } catch (Exception $e) {
            die("Erro ao carregar repositores: " . $e->getMessage());
        }
    }
}