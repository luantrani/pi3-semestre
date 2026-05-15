<?php

class Usuario {
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $nivelAcesso; // 'gerente' ou 'repositor'

    public function __construct($nome, $email, $senha, $nivelAcesso) {
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->nivelAcesso = $nivelAcesso;
    }

    public function podeConfigurarSensores() {
        return $this->nivelAcesso === 'gerente';
    }

    // Getters e setters
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getNivelAcesso() {
        return $this->nivelAcesso;
    }

    public function setNivelAcesso($nivelAcesso) {
        $this->nivelAcesso = $nivelAcesso;
    }
}