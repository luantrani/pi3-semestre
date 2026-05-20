<?php

class Categoria {
    private $id;
    private $nome;

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

    // Setters
    public function setNome($nome) {
        $this->nome = $nome;
    }
}