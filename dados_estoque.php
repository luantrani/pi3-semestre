<?php
header('Content-Type: application/json');
ini_set('display_errors', 0); // Evita que erros HTML quebrem o JSON novamente

try {
    // 1. Importe a Conexão
    require_once 'DAO/Conexao.php'; 

    // 2. IMPORTANTE: Importe os modelos ANTES do DAO
    // O SensorDAO usa Produto e Categoria, então eles precisam existir na memória
    require_once 'model/Categoria.php'; 
    require_once 'model/Produto.php';
    require_once 'model/Sensor.php';

    // 3. Agora importe o DAO
    require_once 'DAO/SensorDAO.php';

    $dao = new SensorDAO();
    $sensores = $dao->listarTodos();
    
    $res = [];
    foreach($sensores as $s) {
                // Dentro do loop do dados_estoque.php
        $pesoAtual = $s->getPesoAtual();
        $pesoUnit = $s->getProduto()->getPesoUnitario();

        // Cálculo da quantidade real
        $quantidadeCalculada = ($pesoUnit > 0) ? floor($pesoAtual / $pesoUnit) : 0;

        // No loop do dados_estoque.php
        $porcentagemRaw = ($s->getCapacidadeMaxima() > 0) ? ($quantidadeCalculada / $s->getCapacidadeMaxima()) * 100 : 0;

        $res[] = [
            'id' => $s->getId(),
            'qtd' => $quantidadeCalculada,
            'max' => $s->getCapacidadeMaxima(),
            // round(valor, casas_decimais)
            'porcentagem' => round($porcentagemRaw, 1), 
            'critico' => $quantidadeCalculada <= $s->getMinimoReposicao()
        ];
    }
    echo json_encode($res);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}