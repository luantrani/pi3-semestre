<?php

class Sensor {
    private $id;
    private $nome;
    private $corredor;
    private $lado; // 'esquerdo' ou 'direito'
    private $pesoAtual;
    private $capacidadeMaxima;
    private $minimoReposicao; // Peso mínimo para acionar reposição
    private $produto; // Objeto da classe Produto
    private $status; 

    // Calcula a quantidade de itens baseado no peso
    public function getQuantidadeAtual() {
        if ($this->produto->getPesoUnitario() <= 0) return 0;
        return floor($this->pesoAtual / $this->produto->getPesoUnitario());
    }

    // O cálculo da porcentagem que você pediu
    public function getPorcentagemEstoque() {
        $atual = $this->getQuantidadeAtual();
        $porcentagem = ($atual / $this->capacidadeMaxima) * 100;
        return ($porcentagem > 100) ? 100 : round($porcentagem, 1);
    }

    // Verifica se precisa de reposição
    public function precisaRepoiscao() {
        return $this->getQuantidadeAtual() <= $this->minimoReposicao;
    }

    // Getters e setters
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getCorredor() {
        return $this->corredor;
    }
    public function setCorredor($corredor) {
        $this->corredor = $corredor;
    }

    public function getLado() {
        return $this->lado;
    }
    public function setLado($lado) {
        $this->lado = $lado;
    }

    public function getPesoAtual() {
        return $this->pesoAtual;
    }
    public function setPesoAtual($pesoAtual) {
        $this->pesoAtual = $pesoAtual;
    }

    public function getCapacidadeMaxima() {
        return $this->capacidadeMaxima;
    }
    public function setCapacidadeMaxima($capacidadeMaxima) {
        $this->capacidadeMaxima = $capacidadeMaxima;
    }

    public function getMinimoReposicao() {
        return $this->minimoReposicao;
    }
    public function setMinimoReposicao($minimoReposicao) {
        $this->minimoReposicao = $minimoReposicao;
    }

    public function getProduto() {
        return $this->produto;
    }
    public function setProduto(Produto $produto) {
        $this->produto = $produto;
    }

    public function getStatus() {
        if ($this->precisaRepoiscao()) {
            return 'vazio';
        } elseif ($this->getPorcentagemEstoque() >= 80) {
            return 'cheio';
        } else {
            return 'medio';
        }
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}