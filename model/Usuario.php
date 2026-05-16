<?php

class Usuario {
    private $id;
    private $nome;
    private $login;
    private $senha;
    private $nivelAcesso; // 'gerente' ou 'repositor'

    public function __construct($id = null, $nome = null, $login = null, $senha = null, $nivelAcesso = 'repositor') {
        $this->id = $id;
        $this->nome = $nome;
        $this->login = $login;
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

    public function getlogin() {
        return $this->login;
    }

    public function setlogin($login) {
        $this->login = $login;
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