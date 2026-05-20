<?php

class RepositorController {
    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    public function index() {
        try {
            $status = $_GET['status'] ?? null;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario'])) {
            session_destroy();
            header("Location: index.php?erro=acesso_negado");
            exit;
        }
            require_once __DIR__ . '/../view/repositor.php';
        } catch (Exception $e) {
            die("Erro ao carregar repositores: " . $e->getMessage());
        }
    }
}