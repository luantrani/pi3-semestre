<?php

class Sensor {
    private $id;
    private $nome;
    private $corredor;
    private $lado; 
    private $pesoAtual;
    private $capacidadeMaxima;
    private $quantidadeAtual; 
    private $minimoReposicao; 
    private $produto; 
    private $statusDispositivo;

    // --- LÓGICA DE SINCRONIZAÇÃO (USAR PARA ALTERAÇÕES EM TEMPO REAL) ---

    // Use quando o REPOSITOR mexer no sistema
    public function atualizarManualPorQuantidade($quantidade) {
        $this->quantidadeAtual = $quantidade;
        if ($this->produto) {
            $this->pesoAtual = $quantidade * $this->produto->getPesoUnitario();
        }
    }

    // Use quando o ESP32 (IOT) enviar dados
    public function atualizarPorSensor($pesoBruto) {
        $this->pesoAtual = $pesoBruto;
        if ($this->produto && $this->produto->getPesoUnitario() > 0) {
            $this->quantidadeAtual = floor($pesoBruto / $this->produto->getPesoUnitario());
        }
    }

    // --- MÉTODOS DE INTELIGÊNCIA ---

    public function getPorcentagemEstoque() {
        if (empty($this->capacidadeMaxima) || $this->capacidadeMaxima <= 0) return 0;
        
        $porcentagem = ($this->getQuantidadeAtual() / $this->capacidadeMaxima) * 100;
        return ($porcentagem > 100) ? 100 : round($porcentagem, 1);
    }

    public function precisaReposicao() {
        return $this->getQuantidadeAtual() <= $this->minimoReposicao;
    }

    public function getSituacaoEstoque() {
        $atual = $this->getQuantidadeAtual();
        $maximo = $this->getCapacidadeMaxima();

        if ($atual <= $this->minimoReposicao) return 'critico'; 
        if ($maximo <= 0) return 'indisponivel';

        $porcentagem = ($atual / $maximo) * 100;
        if ($porcentagem >= 80) return 'cheio';
        
        return 'medio';
    }

    // --- GETTERS E SETTERS (PARA O DAO E USO GERAL) ---

    public function setQuantidadeAtual($qtd) { $this->quantidadeAtual = $qtd; } // Setter simples para o DAO
    public function getQuantidadeAtual() { return $this->quantidadeAtual ?? 0; }
    public function setPesoAtual($peso) { $this->pesoAtual = $peso; } // Setter simples para o DAO
    public function getPesoAtual() { return $this->pesoAtual ?? 0.0; }

    public function setProduto(Produto $produto) { $this->produto = $produto; }
    public function getProduto() { return $this->produto; }
    public function setCapacidadeMaximaEInicializar($capacidade) {
        $this->capacidadeMaxima = $capacidade;
        $this->quantidadeAtual = $capacidade; // Começa com o total máximo
    }


    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getNome() { return $this->nome; }
    public function setNome($n) { $this->nome = $n; }
    public function getCorredor() { return $this->corredor; }
    public function setCorredor($c) { $this->corredor = $c; }
    public function getLado() { return $this->lado; }
    public function setLado($l) { $this->lado = $l; }
    public function getCapacidadeMaxima() { return $this->capacidadeMaxima; }
    public function setCapacidadeMaxima($m) { $this->capacidadeMaxima = $m; }
    public function getMinimoReposicao() { return $this->minimoReposicao; }
    public function setMinimoReposicao($min) { $this->minimoReposicao = $min; }
    public function getStatus() { return $this->statusDispositivo; }
    public function setStatus($s) { $this->statusDispositivo = $s; }
}