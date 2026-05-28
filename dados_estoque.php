<?php
header('Content-Type: application/json');
ini_set('display_errors', 0); 

try {
    require_once 'DAO/Conexao.php'; 
    require_once 'model/Categoria.php'; 
    require_once 'model/Produto.php';
    require_once 'model/Sensor.php';
    require_once 'DAO/SensorDAO.php';

    $dao = new SensorDAO();
    $sensores = $dao->listarTodos();
    
    $res = [];
    foreach($sensores as $s) {
        // Verificamos se existe um produto vinculado para evitar erro de "call on null"
        $temProduto = ($s->getProduto() !== null);

        $res[] = [
            'id'          => $s->getId(),
            'nome'        => $s->getNome(),
            'corredor'    => $s->getCorredor(),
            'lado'        => $s->getLado(),
            'produto'     => $temProduto ? $s->getProduto()->getNome() : 'Sem Produto',
            'qtd'         => $s->getQuantidadeAtual(),   // O Model já tem essa conta
            'max'         => $s->getCapacidadeMaxima(),
            'porcentagem' => $s->getPorcentagemEstoque(), // O Model já arredonda e trata divisão por zero
            'status'      => $s->getSituacaoEstoque(),   // Retorna 'critico', 'medio' ou 'cheio'
        ];
    }
    
    echo json_encode($res);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error'   => true,
        'message' => $e->getMessage()
    ]);
}