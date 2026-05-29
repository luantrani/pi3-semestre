<?php
class Alerta {
    private $id;
    private $produto;
    private $sensor; // Novo atributo para o local (corredor/lado)
    private $quantidade_no_momento;
    private $data_hora_alerta;
    private $data_fim; // Para controle de tempo gasto no atendimento do alerta
    private $status;
    private $id_usuario_atendimento;
    private $nome_responsavel; // Para exibir o nome de quem assumiu o alerta

    public function setStatus($status) { $this->status = $status; }
    public function getStatus() { return $this->status; }

    public function setIdUsuarioAtendimento($id) { $this->id_usuario_atendimento = $id; }
    public function getIdUsuarioAtendimento() { return $this->id_usuario_atendimento; }

    public function setId($id) { $this->id = $id; }
    public function setProduto($produto) { $this->produto = $produto; }
    public function setSensor($sensor) { $this->sensor = $sensor; } // Novo
    public function setQuantidadeNoMomento($quantidade) { $this->quantidade_no_momento = $quantidade; }
    public function setDataHoraAlerta($dataHora) { $this->data_hora_alerta = $dataHora; }
    public function setDataFim($dataFim) { $this->data_fim = $dataFim; }

    public function getId() { return $this->id; }
    public function getProduto() { return $this->produto; }
    public function getSensor() { return $this->sensor; } // Novo
    public function getQuantidadeNoMomento() { return $this->quantidade_no_momento; }
    public function getDataHoraAlerta() { return $this->data_hora_alerta; }
    public function getDataFim() { return $this->data_fim; }

    // Atalhos úteis para a View
    public function getCorredor() { return $this->sensor ? $this->sensor->getCorredor() : 'N/A'; }
    public function getTempoDesdeAlerta() {
        $inicio = new DateTime($this->data_hora_alerta);
        $agora = new DateTime();
        $diff = $inicio->diff($agora);
        if ($diff->h > 0) return $diff->h . "h " . $diff->i . "min";
        return $diff->i . " minutos";
    }

    public function setNomeResponsavel($nome) {
    $this->nome_responsavel = $nome;
}
public function getResponsavelNome() {
    return $this->nome_responsavel ?? null;
}
}