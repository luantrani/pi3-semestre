<?php
class Alerta {
    private $produto;
    private $quantidade_no_momento;
    private $data_hora_alerta;

    public function __construct($produto, $quantidade, $data) {
        $this->produto = $produto; // Deve ser um objeto da classe Produto
        $this->quantidade_no_momento = $quantidade;
        $this->data_hora_alerta = $data;
    }

    public function getProduto() { return $this->produto; }
    public function getQuantidadeNoMomento() { return $this->quantidade_no_momento; }
    public function getDataHoraAlerta() { return $this->data_hora_alerta; }
}