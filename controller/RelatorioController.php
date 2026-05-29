<?php

class RelatorioController {
    private $sensorDAO;
    private $produtoDAO;
    private $movimentacaoEstoqueDAO;

    public function __construct() {
        $this->sensorDAO = new SensorDAO();
        $this->produtoDAO = new ProdutoDAO();
        $this->movimentacaoEstoqueDAO = new MovimentacaoEstoqueDAO();
    }

    public function index() {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Verificação de segurança
            if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel_acesso'] !== 'gerente') {
                header("Location: index.php?erro=acesso_negado");
                exit;
            }

            // O PULO DO GATO ESTÁ AQUI:
            // Você precisa buscar os dados e guardar numa variável que a View conheça.
            $historico = $this->movimentacaoEstoqueDAO->listarHistorico();
           

            // Agora sim, com o $historico preenchido, a gente chama a view
            require_once __DIR__ . '/../view/relatorios.php';

        } catch (Exception $e) {
            die("Erro ao carregar relatorios: " . $e->getMessage());
        }
    }
}