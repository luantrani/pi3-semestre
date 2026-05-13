<?php

class Usuario {
    public $id;
    public $nome;
    public $nivelAcesso; // 'gerente' ou 'repositor'

    public function __construct($id, $nome, $nivelAcesso) {
        $this->id = $id;
        $this->nome = $nome;
        $this->nivelAcesso = $nivelAcesso;
    }

    public function podeConfigurarSensores() {
        return $this->nivelAcesso === 'gerente';
    }
}