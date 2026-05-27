<?php
// simulador.php
require_once 'DAO/SensorDAO.php';
// Certifique-se de carregar os models de Sensor e Produto

$dao = new SensorDAO();
$sensores = $dao->listarTodos();

foreach ($sensores as $s) {
    $produto = $s->getProduto();
    $pesoUnitario = $produto->getPesoUnitario(); // Você precisará criar esse getter no Model Produto
    
    // Simula a retirada ou adição de UNIDADES (ex: tirou 1 ou 2 produtos)
    $variacaoUnidades = rand(-1, 1); 
    
    // Calcula o novo peso com base na variação de unidades
    $pesoVariacao = $variacaoUnidades * $pesoUnitario;
    $novoPeso = $s->getPesoAtual() + $pesoVariacao;

    // Limites: não menos que 0, não mais que a capacidade máxima * peso unitário
    $pesoMaximoPermitido = $s->getCapacidadeMaxima() * $pesoUnitario;
    
    if ($novoPeso < 0) $novoPeso = 0;
    if ($novoPeso > $pesoMaximoPermitido) $novoPeso = $pesoMaximoPermitido;

    // Atualiza o PESO no banco
    $s->setPesoAtual($novoPeso);
    $dao->atualizarPeso($s); // Crie este método no DAO para dar UPDATE na coluna peso_atual
}
echo "Simulação de peso concluída!";