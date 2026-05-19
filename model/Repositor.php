<?php

class Repositor{
    private $id;
    private $nome;
    private $login;
    private $senha;


    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getSenha() {
        return $this->senha;
    }
}