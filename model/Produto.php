<?php
class Produto {
    private $id;
    private $nome;
    private $peso_unitario;
    private $categoria;

    // Getters
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getPesoUnitario() {
        return $this->peso_unitario;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    // Setters
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setPesoUnitario($peso_unitario) {
        $this->peso_unitario = $peso_unitario;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

}