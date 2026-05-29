<?php
// api_alertas.php
// ... (seus includes e conexão) ...
require_once __DIR__ . '/DAO/Conexao.php';
require_once __DIR__ . '/model/Alerta.php'; // Ajuste o caminho se a pasta for outra
require_once __DIR__ . '/DAO/HistoricoAlertasDAO.php';
require_once __DIR__ . '/DAO/ProdutoDAO.php'; // Para acessar a categoria do produto
require_once __DIR__ . '/DAO/SensorDAO.php'; // Para acessar o corredor e lado do sensor
if (session_status() === PHP_SESSION_NONE) session_start();
$dao = new HistoricoAlertasDAO();
$alertas = $dao->buscarAlertasAtivos();
$idLogado = $_SESSION['usuario']['id'] ?? 0;

$dados = [];
foreach ($alertas as $a) {
    // Lógica do Ícone (mesma que você usou no PHP)
    $icon = "fa-box";
    $cat = strtolower($a->getProduto()->getCategoria());
    if (strpos($cat, 'bebi') !== false) $icon = "fa-droplet";
    elseif (strpos($cat, 'limp') !== false) $icon = "fa-spray-can-sparkles";
    elseif (strpos($cat, 'higi') !== false) $icon = "fa-pump-soap";

    $dados[] = [
        'id' => $a->getId(),
        'produto' => $a->getProduto()->getNome(),
        'corredor' => $a->getCorredor(),
        'lado' => $a->getSensor()->getLado(),
        'quantidade' => $a->getQuantidadeNoMomento(),
        'status' => trim(strtolower($a->getStatus())),
        'tempo' => $a->getTempoDesdeAlerta(),
        'icon' => $icon,
        'idResponsavel' => $a->getIdUsuarioAtendimento(),
        'idLogado' => $idLogado
    ];
}

header('Content-Type: application/json');
echo json_encode([
    'totalAlertas' => count($dados),
    'maisUrgente' => count($dados) > 0 ? $dados[0]['produto'] : 'Nenhum',
    'lista' => $dados
]);