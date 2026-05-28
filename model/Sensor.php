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
    private $statusDispositivo; // 'Ativo' ou 'Inativo' (do banco)

    // --- LÓGICA DE SINCRONIZAÇÃO ---

    // Quando você define a QUANTIDADE (ex: no Cadastro), ele calcula o PESO
    public function setQuantidadeAtual($quantidade) {
        $this->quantidadeAtual = $quantidade;
        if ($this->produto) {
            $this->pesoAtual = $quantidade * $this->produto->getPesoUnitario();
        }
    }

    // Quando o simulador define o PESO, ele calcula a QUANTIDADE
    public function setPesoAtual($pesoAtual) {
        $this->pesoAtual = $pesoAtual;
        if ($this->produto && $this->produto->getPesoUnitario() > 0) {
            $this->quantidadeAtual = floor($pesoAtual / $this->produto->getPesoUnitario());
        }
    }

    // --- MÉTODOS DE INTELIGÊNCIA ---
    public function getPorcentagemEstoque() {
        // Se a capacidade for 0 ou não estiver setada, retorna 0% para não bugar
        if (empty($this->capacidadeMaxima) || $this->capacidadeMaxima <= 0) {
            return 0;
        }
        
        $atual = $this->getQuantidadeAtual();
        $porcentagem = ($atual / $this->capacidadeMaxima) * 100;
        
        return ($porcentagem > 100) ? 100 : round($porcentagem, 1);
    }

    public function precisaReposicao() {
        return $this->getQuantidadeAtual() <= $this->minimoReposicao;
    }

    // Retorna a "situação visual" do estoque
   public function getSituacaoEstoque() {
    $atual = $this->getQuantidadeAtual();
    $maximo = $this->getCapacidadeMaxima();

    // Se estiver abaixo ou igual ao mínimo, é CRÍTICO (Prioridade 1)
    if ($atual <= $this->getMinimoReposicao()) {
        return 'critico'; 
    }

    // Se não tem capacidade definida, evita erro de divisão por zero
    if ($maximo <= 0) return 'indisponivel';

    $porcentagem = ($atual / $maximo) * 100;

    if ($porcentagem >= 80) {
        return 'cheio';
    }
    
    return 'medio';
}

    // --- GETTERS E SETTERS PADRÃO ---

    public function getQuantidadeAtual() { 
        return $this->quantidadeAtual ?? 0; 
    }
    public function getPesoAtual() { return $this->pesoAtual; }
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }
    public function getCorredor() { return $this->corredor; }
    public function setCorredor($corredor) { $this->corredor = $corredor; }
    public function getLado() { return $this->lado; }
    public function setLado($lado) { $this->lado = $lado; }
    public function getCapacidadeMaxima() { return $this->capacidadeMaxima; }
    public function setCapacidadeMaxima($max) { $this->capacidadeMaxima = $max; }
    public function getMinimoReposicao() { return $this->minimoReposicao; }
    public function setMinimoReposicao($min) { $this->minimoReposicao = $min; }
    public function getProduto() { return $this->produto; }
    public function setProduto(Produto $produto) {
        $this->produto = $produto;
        
        // CORREÇÃO: Se já temos um peso carregado do banco, 
        // agora que o produto chegou, calculamos a quantidade.
        if ($this->pesoAtual > 0 && $this->produto->getPesoUnitario() > 0) {
            $this->quantidadeAtual = floor($this->pesoAtual / $this->produto->getPesoUnitario());
        }
        
        // Se já definiram uma quantidade antes, recalcula o peso
        if ($this->quantidadeAtual > 0) {
            $this->setQuantidadeAtual($this->quantidadeAtual);
        }
    }
    
    // Status de conexão/sistema
    public function getStatus() { return $this->statusDispositivo; }
    public function setStatus($status) { $this->statusDispositivo = $status; }
}