<?php
// simulador.php
require_once 'DAO/Conexao.php';
require_once 'DAO/SensorDAO.php';
// ... outros requires

$dao = new SensorDAO();
$sensores = $dao->listarTodos();

foreach ($sensores as $s) {
    $produto = $s->getProduto();
    if (!$produto || $produto->getPesoUnitario() <= 0) continue;

    $pesoUnitario = $produto->getPesoUnitario();
    $quantidadeAtual = $s->getQuantidadeAtual();

    // Simula a chance de venda (ex: 50% de chance de reduzir 1 unidade)
    $vendeu = rand(0, 1); 

    if ($quantidadeAtual > 0 && $vendeu == 1) {
        $novaQtd = $quantidadeAtual - 1;
        $novoPeso = $novaQtd * $pesoUnitario;

        // Atualiza o objeto e salva no banco
        $s->atualizarPorSensor($novoPeso);
        $dao->salvarLeituraSensor($s); 
        
        echo "Sensor [{$s->getId()}]: Item vendido! Restam: {$novaQtd}<br>";
    } else {
        echo "Sensor [{$s->getId()}]: Sem alteração ou estoque zerado.<br>";
    }
}
echo "<strong>Simulação de consumo concluída!</strong>";