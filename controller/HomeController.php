<?php
class HomeController {
    public function index() {
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
        include __DIR__ . '/../view/home.php';
    }
}