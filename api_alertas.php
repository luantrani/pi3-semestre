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
        'idResponsavel' => $a->getIdUsuarioAtendimento() !== null ? (int)$a->getIdUsuarioAtendimento() : 0,
        'idLogado' => (int)$idLogado
    ];
}
usort($dados, function($a, $b) {
    // Se o status for diferente, pendentes vêm antes
    if ($a['status'] !== $b['status']) {
        return ($a['status'] === 'pendente') ? -1 : 1;
    }
    // Se o status for igual, compara pelo tempo (supondo que tempo seja uma string formatada ou numérica)
    return strcmp($a['tempo'], $b['tempo']); 
});

header('Content-Type: application/json');
echo json_encode([
    'totalAlertas' => count($dados),
    'maisUrgente' => count($dados) > 0 ? $dados[0]['produto'] : 'Nenhum',
    'lista' => $dados
]);