<?php

class Sensor {
    public $id;
    public $localizacao;
    public $pesoAtual;
    public $capacidadeMaxima;
    public $produto; // Objeto da classe Produto

    public function __construct($id, $localizacao, $capacidadeMaxima, Produto $produto) {
        $this->id = $id;
        $this->localizacao = $localizacao;
        $this->capacidadeMaxima = $capacidadeMaxima;
        $this->produto = $produto;
    }

    // Calcula a quantidade de itens baseado no peso
    public function getQuantidadeAtual() {
        if ($this->produto->pesoUnitario <= 0) return 0;
        return floor($this->pesoAtual / $this->produto->pesoUnitario);
    }

    // O cálculo da porcentagem que você pediu
    public function getPorcentagemEstoque() {
        $atual = $this->getQuantidadeAtual();
        $porcentagem = ($atual / $this->capacidadeMaxima) * 100;
        return ($porcentagem > 100) ? 100 : round($porcentagem, 1);
    }

    // Verifica se precisa de reposição
    public function precisaRepoiscao() {
        return $this->getQuantidadeAtual() <= $this->produto->estoqueMinimo;
    }
}