<?php
class Produto {
    private $id;
    private $nome;
    private $pesoUnitario;
    private $categoria;

    public function __construct($id, $nome, $pesoUnitario, $categoria) {
        $this->id = $id;
        $this->nome = $nome;
        $this->pesoUnitario = $pesoUnitario;
        $this->categoria = $categoria;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getPesoUnitario() {
        return $this->pesoUnitario;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    // Setters
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setPesoUnitario($pesoUnitario) {
        $this->pesoUnitario = $pesoUnitario;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

}