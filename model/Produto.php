<?php
class Produto {
    public $id;
    public $nome;
    public $pesoUnitario;
    public $estoqueMinimo;

    public function __construct($id, $nome, $pesoUnitario, $estoqueMinimo) {
        $this->id = $id;
        $this->nome = $nome;
        $this->pesoUnitario = $pesoUnitario;
        $this->estoqueMinimo = $estoqueMinimo;
    }
}