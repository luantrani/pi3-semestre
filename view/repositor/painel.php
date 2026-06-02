<?php
// Garantir que a sessão está ativa
if (session_status() === PHP_SESSION_NONE) session_start();
$usuarioLogado = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>GPI - Operação</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background-color: #f0f2f5; font-family: 'Inter', sans-serif; }
        .app-bar { background: #fff; padding: 15px; border-bottom: 1px solid #e0e0e0; position: sticky; top: 0; z-index: 1000; }
        .card-alerta { background: #fff; border-radius: 20px; border-left: 6px solid #dc3545; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 15px; }
        .btn-action { border-radius: 12px; padding: 12px; font-weight: 700; text-transform: uppercase; font-size: 0.9rem; }
    </style>
</head>
<body>

<nav class="app-bar d-flex justify-content-between align-items-center">
    <div class="fw-bold text-primary">GPI <small class="text-muted">Repositor</small></div>
    <a href="roteador.php?controller=Usuario&action=logout" class="btn btn-sm btn-outline-danger border-0"><i class="fa-solid fa-power-off"></i></a>
</nav>

<main class="container p-3">
    <div class="row g-2 mb-4">
        <div class="col-6">
            <div class="card p-3 border-0 rounded-4 shadow-sm text-center">
                <small class="text-muted d-block">PENDENTES</small>
                <strong class="h4 text-danger mb-0" id="kpi-pendentes">--</strong>
            </div>
        </div>
        <div class="col-6">
            <div class="card p-3 border-0 rounded-4 shadow-sm text-center">
                <small class="text-muted d-block">MAIS URGENTE</small>
                <strong class="h6 mb-0 text-truncate" id="kpi-urgente" style="max-width: 100%;">--</strong>
            </div>
        </div>
    </div>

    <h6 class="text-uppercase text-muted fw-bold mb-3 small">Alertas Ativos</h6>
    <div id="lista-alertas">
        </div>
</main>

<script>
    // A forma segura de passar dados do PHP para o JS
    const idLogado = <?= json_encode($usuarioLogado['id'] ?? 0) ?>;

    function templateAlerta(a, extraClass = '') {
    // Forçamos a conversão para string para garantir que a comparação funcione
    const idResp = String(a.idResponsavel || '0');
    const idLog = String(idLogado || '0');
    
    let botao = '';

    if (a.status === 'pendente') {
        botao = `<button class="btn btn-primary btn-action w-100 btn-atender" data-id="${a.id}">Atender</button>`;
    } else if (a.status === 'em_andamento') {
        if (idResp === idLog) {
            botao = `<button class="btn btn-success btn-action w-100 btn-finalizar" data-id="${a.id}">Finalizar</button>`;
        } else {
            botao = `<span class="badge bg-light text-muted w-100 py-3">Em atendimento</span>`;
        }
    }

    return `
        <div class="card card-alerta p-3 ${extraClass}">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="fw-bold text-dark">${a.produto}</div>
                    <div class="small text-muted"><i class="fa-solid fa-location-dot me-1"></i> ${a.corredor} (${a.lado})</div>
                </div>
                ${extraClass ? '<span class="badge bg-danger rounded-pill"><i class="fa-solid fa-fire"></i> URGENTE</span>' : ''}
            </div>
            <div class="mb-3 small text-danger fw-bold"><i class="fa-solid fa-clock me-1"></i> ${a.tempo} de espera</div>
            ${botao}
        </div>`;
}

    function carregarAlertas() {
        fetch('api_alertas.php')
            .then(r => r.json())
            .then(data => {
                // 1. Atualiza KPIs
                document.getElementById('kpi-pendentes').innerText = data.totalAlertas;
                document.getElementById('kpi-urgente').innerText = data.maisUrgente;
                
                const container = document.getElementById('lista-alertas');
                
                // 2. Renderiza a lista com o destaque para o mais urgente (index 0)
                container.innerHTML = data.lista.length === 0 
                    ? '<div class="text-center py-5 text-muted">Tudo abastecido! <i class="fa-solid fa-check"></i></div>'
                    : data.lista.map((a, index) => { 
                        // A classe de destaque só vai no primeiro item da lista
                        const classeUrgente = index === 0 ? 'border-danger border-4 shadow' : '';
                        return templateAlerta(a, classeUrgente); 
                    }).join('');
            })
            .catch(err => console.error("Erro ao carregar alertas:", err));
    }

    // Eventos de clique (igual ao que você já tinha)
    document.addEventListener('click', e => {
        const btn = e.target.closest('.btn-atender, .btn-finalizar');
        if (btn) {
            const action = btn.classList.contains('btn-atender') ? 'atender' : 'finalizar';
            fetch(`roteador.php?controller=Repositor&action=${action}&id=${btn.dataset.id}`)
                .then(() => carregarAlertas());
        }
    });

    setInterval(carregarAlertas, 5000);
    carregarAlertas();
</script>
</body>
</html>