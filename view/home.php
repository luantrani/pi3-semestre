<?php

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GPI - Monitoramento Estratégico</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <style>
        .shelf-card { transition: transform 0.2s, box-shadow 0.2s; border-radius: 15px; }
        .shelf-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .bg-primary-light { background-color: #e7f1ff; color: #0d6efd; }
        .bg-success-light { background-color: #d1e7dd; color: #0f5132; }
        .bg-danger-light { background-color: #f8d7da; color: #842029; }
    </style>
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-text"><span>GPI</span><small>Gestão Inteligente</small></div>
            </div>
            <nav class="menu">
                <a class="menu-item active" href="roteador.php?controller=Home&action=index">
                    <i class="fa-solid fa-chart-pie"></i> Visão Geral
                </a>
                <a class="menu-item" href="roteador.php?controller=Sensor&action=index">
                    <i class="fa-solid fa-microchip"></i> Configurações IoT
                </a>
                <a class="menu-item" href="roteador.php?controller=Relatorio&action=index">
                    <i class="fa-solid fa-file-lines"></i> Relatórios
                </a>
                <a class="menu-item" href="roteador.php?controller=Repositor&action=index">
                    <i class="fa-solid fa-truck-ramp-box"></i> Área Repositor
                </a>
                <div class="menu-divider"></div>
                <a class="menu-item" href="roteador.php?controller=Gestao&action=index">
                    <i class="fa-solid fa-gears"></i> Administração
                </a>
            </nav>
            <button class="btn btn-danger mt-auto mx-2 mb-3 py-2 w-100" onclick="location.href='roteador.php?controller=Usuario&action=logout'">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Sair
            </button>
        </aside>

        <main class="content">
            <header class="header mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold h2 mb-1">Visão Geral do Estoque</h1>
                    <p class="text-muted small mb-0">Monitoramento em tempo real das gôndolas em Itapira.</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-success-light border-0 px-3 py-2">OK</span>
                    <span class="badge bg-danger-light border-0 px-3 py-2">Crítico</span>
                </div>
            </header>

            <section class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card kpi-card border-0 shadow-sm h-100">
                        <div class="kpi-icon bg-primary-light"><i class="fa-solid fa-boxes-stacked"></i></div>
                        <div class="kpi-info"><small>MONITORADOS</small><strong><?php echo count($sensores); ?> Sensores</strong></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card kpi-card border-0 shadow-sm h-100">
                        <div class="kpi-icon bg-success-light"><i class="fa-solid fa-circle-check"></i></div>
                        <div class="kpi-info"><small>EM CONFORMIDADE</small><strong><?php echo $totalCheios; ?> Itens</strong></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card kpi-card border-0 shadow-sm h-100">
                        <div class="kpi-icon bg-danger-light"><i class="fa-solid fa-triangle-exclamation"></i></div>
                        <div class="kpi-info"><small>ALERTA CRÍTICO</small><strong><?php echo $totalCriticos; ?> Seções</strong></div>
                    </div>
                </div>
            </section>

            <section class="card border-0 shadow-sm p-4">
                <h2 class="h5 fw-bold mb-4"><i class="fa-solid fa-eye me-2 text-primary"></i>Status das Prateleiras</h2>
                <section class="filter-bar border-0 shadow-sm mb-4 p-3 rounded">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <h2 class="h5 fw-bold mb-0 text-dark"><i class="fa-solid fa-filter me-2 text-primary"></i>Filtrar Itens</h2>
                        </div>
                        <div class="col-md-3">
                            <input type="text" id="filtroTexto" class="form-control" placeholder="Buscar produto...">
                        </div>
                        <div class="col-md-2">
                            <select id="filtroCategoria" class="form-select">
                                <option value="todos">Categoria</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filtroStatus" class="form-select">
                                <option value="todos">Todos</option>
                                <option value="critico">Críticos</option>
                                <option value="saudavel">OK</option>
                            </select>
                        </div>
                    </div>
                </section>
                <div class="row g-4" id="lista-sensores">
                    <?php foreach ($sensores as $s): 
                        $isCritico = $s->precisaReposicao();
                        $porcentagem = $s->getPorcentagemEstoque();
                        $id = $s->getId();
                        if ($isCritico) {
                            $corDestaque = 'danger';
                        } elseif ($porcentagem < 50) {
                            $corDestaque = 'warning'; // Amarelo para atenção
                        } else {
                            $corDestaque = 'success'; // Verde apenas se estiver acima de 50%
                        }
                    ?>
                    <div class="col-xl-4 col-md-6">
                        <article class="card shelf-card h-100 border-0 border-start border-5 border-<?php echo $corDestaque; ?> shadow-sm"
                                id="card-sensor-<?php echo $id; ?>"
                                style="cursor: pointer;" 
                                data-bs-toggle="modal" 
                                data-bs-target="#sensorModal"
                                data-id="<?php echo $id; ?>"
                                data-nome="<?php echo $s->getProduto()->getNome(); ?>"
                                data-categoria="<?php echo $s->getProduto()->getCategoria()->getNome(); ?>"
                                data-qtd="<?php echo $s->getQuantidadeAtual(); ?>"
                                data-max="<?php echo $s->getCapacidadeMaxima(); ?>">
                            
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="badge bg-light text-dark border-0 shadow-sm mb-1">
                                            <i class="fa-solid fa-location-dot me-1 text-primary"></i> Corredor <?php echo $s->getCorredor(); ?>
                                        </span>
                                        <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">
                                            <?php echo $s->getProduto()->getCategoria()->getNome(); ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <i id="status-icon-<?php echo $id; ?>" class="fa-solid fa-circle text-<?php echo $corDestaque; ?> small"></i>
                                    </div>
                                </div>

                                <h3 class="h5 fw-bold mb-1"><?php echo $s->getProduto()->getNome(); ?></h3>
                                <p class="text-muted small mb-4">Lado: <?php echo $s->getLado(); ?></p>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-end mb-2">
                                        <span id="porcentagem-<?php echo $id; ?>" class="h2 fw-bold mb-0 text-<?php echo $corDestaque; ?>">
                                            <?php echo $porcentagem; ?>%
                                        </span>
                                        <span class="text-muted small" id="qtd-text-<?php echo $id; ?>">
                                            <strong><?php echo $s->getQuantidadeAtual(); ?></strong>/<?php echo $s->getCapacidadeMaxima(); ?> un
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 10px; background-color: #eee;">
                                        <div id="barra-<?php echo $id; ?>" 
                                             class="progress-bar bg-<?php echo $corDestaque; ?> <?php echo $isCritico ? 'progress-bar-striped progress-bar-animated' : ''; ?>" 
                                             role="progressbar" style="width: <?php echo $porcentagem; ?>%"></div>
                                    </div>
                                </div>

                                <div id="alerta-reposicao-<?php echo $id; ?>" class="mt-3 p-2 rounded <?php echo $isCritico ? 'bg-danger-light' : 'bg-success-light'; ?> d-flex align-items-center gap-2">
                                    <i class="fa-solid <?php echo $isCritico ? 'fa-truck-ramp-box' : 'fa-check-circle'; ?>"></i>
                                    <span class="small fw-bold"><?php echo $isCritico ? 'Reposição Necessária' : 'Estoque Saudável'; ?></span>
                                </div>
                            </div>
                        </article>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>

    <div class="modal fade" id="sensorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalNomeProduto">Detalhes do Sensor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <input type="hidden" id="modalIdSensor" value="">
                    <span class="badge bg-primary-light mb-2" id="modalCategoria">Categoria</span>
                    <div class="display-4 fw-bold text-primary mb-3" id="modalQtdInfo">0/0</div>
                    <p class="text-muted small mb-3">Variação do peso nas últimas horas</p>
                    <div class="bg-light rounded p-3 mb-3" style="height: 250px;">
                        <canvas id="historicoGrafico"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let historicoSensores = {};
    let meuGrafico;

    // --- FUNÇÃO DE FILTRAGEM (PRESERVADA) ---
    function aplicarFiltros() {
    const filtroTexto = document.getElementById('filtroTexto');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const filtroStatus = document.getElementById('filtroStatus');

    const termo = filtroTexto ? filtroTexto.value.toLowerCase() : "";
    const catSelecionada = filtroCategoria ? filtroCategoria.value.toLowerCase() : "todos";
    const statusSelecionado = filtroStatus ? filtroStatus.value : "todos";
    
    // Usamos o seletor que confirmamos que funciona
    const cards = document.querySelectorAll('article[data-categoria]');

    cards.forEach(card => {
        // --- 1. PEGAR DADOS DO CARD ---
        const nomeProduto = card.querySelector('h3') ? card.querySelector('h3').textContent.toLowerCase() : "";
        
        // .trim() remove espaços extras, .toLowerCase() iguala a caixa
        const catCard = card.getAttribute('data-categoria') ? card.getAttribute('data-categoria').trim().toLowerCase() : "";
        
        const barra = card.querySelector('.progress-bar');
        const isCritico = barra ? barra.classList.contains('bg-danger') : false;

        // --- 2. LOGICA DE COMPARAÇÃO ---
        const correspondeTexto = nomeProduto.includes(termo);
        
        // O "pulo do gato" está aqui:
        const correspondeCat = (catSelecionada === 'todos' || catSelecionada === "" || catCard === catSelecionada);
        
        let correspondeStatus = true;
        if (statusSelecionado === 'critico') correspondeStatus = isCritico;
        else if (statusSelecionado === 'saudavel') correspondeStatus = !isCritico;

        // --- 3. APLICAÇÃO ---
        if (correspondeTexto && correspondeCat && correspondeStatus) {
            card.parentElement.style.display = ""; // Mostra o pai (a div col-xl-4)
        } else {
            card.parentElement.style.display = "none"; // Esconde o pai
        }
    });
}

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        const filtroTexto = document.getElementById('filtroTexto');
        const filtroCategoria = document.getElementById('filtroCategoria');
        const filtroStatus = document.getElementById('filtroStatus');

        if (filtroTexto) filtroTexto.addEventListener('keyup', aplicarFiltros);
        if (filtroCategoria) filtroCategoria.addEventListener('change', aplicarFiltros);
        if (filtroStatus) filtroStatus.addEventListener('change', aplicarFiltros);

        // --- LÓGICA DO MODAL (PRESERVADA) ---
        const sensorModal = document.getElementById('sensorModal');
        if (sensorModal) {
            sensorModal.addEventListener('show.bs.modal', event => {
                const card = event.relatedTarget;
                const id = card.getAttribute('data-id');
                const nome = card.getAttribute('data-nome');
                const cat = card.getAttribute('data-categoria');
                const qtd = card.getAttribute('data-qtd');
                const max = card.getAttribute('data-max');

                if(document.getElementById('modalIdSensor')) document.getElementById('modalIdSensor').value = id;
                if(document.getElementById('modalNomeProduto')) document.getElementById('modalNomeProduto').textContent = nome;
                if(document.getElementById('modalCategoria')) document.getElementById('modalCategoria').textContent = cat;
                if(document.getElementById('modalQtdInfo')) document.getElementById('modalQtdInfo').textContent = `${qtd} / ${max} un`;

                const labels = [];
                const agora = new Date();
                for (let i = 5; i >= 0; i--) {
                    const tempo = new Date(agora.getTime() - i * 5 * 60000);
                    labels.push(tempo.getHours() + ":" + tempo.getMinutes().toString().padStart(2, '0'));
                }

                const ctx = document.getElementById('historicoGrafico')?.getContext('2d');
                if (ctx) {
                    if (meuGrafico) meuGrafico.destroy();
                    meuGrafico = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Estoque',
                                data: historicoSensores[id] || [qtd],
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: parseInt(max) } } }
                    });
                }
            });
        }

        // --- POPULAR SELECT CATEGORIAS (PRESERVADA) ---
        const selectCat = document.getElementById('filtroCategoria');
        if (selectCat) {
            const categorias = new Set();
            // AQUI ESTÁ A MUDANÇA: Buscamos as articles que possuem o atributo data-categoria
            const cards = document.querySelectorAll('article[data-categoria]');
            
            cards.forEach(card => {
                const cat = card.getAttribute('data-categoria');
                if (cat && cat.trim() !== "") {
                    categorias.add(cat.trim());
                }
            });

            console.log("Categorias únicas encontradas:", Array.from(categorias)); // Debug

            categorias.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.toLowerCase();
                option.textContent = cat;
                selectCat.appendChild(option);
            });
        }

        // --- FUNÇÃO DE ATUALIZAÇÃO (PRESERVADA) ---
        function atualizarInterface() {
            fetch('dados_estoque.php') 
                .then(res => res.json())
                .then(sensores => {
                    sensores.forEach(s => {
                        if (!historicoSensores[s.id]) historicoSensores[s.id] = [];
                        historicoSensores[s.id].push(s.qtd);
                        if (historicoSensores[s.id].length > 20) historicoSensores[s.id].shift();

                        const barra = document.getElementById(`barra-${s.id}`);
                        const txtQtd = document.getElementById(`qtd-text-${s.id}`);
                        const txtPerc = document.getElementById(`porcentagem-${s.id}`);
                        const card = document.getElementById(`card-sensor-${s.id}`);
                        const icon = document.getElementById(`status-icon-${s.id}`);
                        const alerta = document.getElementById(`alerta-reposicao-${s.id}`);

                        if (barra) {
                            let classeCor = 'success';
                            let textoStatus = 'Estoque Saudável';
                            let iconeStatus = 'fa-check-circle';
                            let hexCor = '#198754';

                            if (s.porcentagem <= 20) {
                                classeCor = 'danger';
                                textoStatus = 'Reposição Necessária';
                                iconeStatus = 'fa-truck-ramp-box';
                                hexCor = '#dc3545';
                            } else if (s.porcentagem < 50) {
                                classeCor = 'warning';
                                textoStatus = 'Atenção: Estoque Baixo';
                                iconeStatus = 'fa-circle-exclamation';
                                hexCor = '#ffc107';
                            }

                            barra.style.width = s.porcentagem + '%';
                            barra.className = `progress-bar bg-${classeCor} ${classeCor === 'danger' ? 'progress-bar-striped progress-bar-animated' : ''}`;
                            
                            if (txtPerc) {
                                txtPerc.textContent = s.porcentagem + '%';
                                txtPerc.className = `h2 fw-bold mb-0 text-${classeCor}`;
                            }
                            if (txtQtd) txtQtd.innerHTML = `<strong>${s.qtd}</strong>/${s.max} un`;
                            if (card) card.style.borderLeftColor = hexCor;
                            if (icon) icon.className = `fa-solid fa-circle text-${classeCor} small`;
                            
                            if (alerta) {
                                alerta.className = `mt-3 p-2 rounded bg-${classeCor}-light d-flex align-items-center gap-2`;
                                alerta.innerHTML = `<i class="fa-solid ${iconeStatus}"></i><span class="small fw-bold">${textoStatus}</span>`;
                            }
                        }

                        // Atualiza gráfico se modal aberto
                        const modalAberto = document.querySelector('#sensorModal.show');
                        if (modalAberto) {
                            const idNoModal = document.getElementById('modalIdSensor')?.value;
                            if (idNoModal == s.id && meuGrafico) {
                                document.getElementById('modalQtdInfo').textContent = `${s.qtd} / ${s.max} un`;
                                const agora = new Date();
                                const novaHora = agora.getHours() + ":" + agora.getMinutes().toString().padStart(2, '0');
                                if (meuGrafico.data.labels[meuGrafico.data.labels.length - 1] !== novaHora) {
                                    meuGrafico.data.labels.shift();
                                    meuGrafico.data.labels.push(novaHora);
                                    meuGrafico.data.datasets[0].data.shift();
                                    meuGrafico.data.datasets[0].data.push(s.qtd);
                                    meuGrafico.update();
                                }
                            }
                        }
                    });
                    aplicarFiltros();
                })
                .catch(err => console.error("Erro na atualização:", err));
        }

        setInterval(() => {
            if (!document.hidden) {
                fetch('simulador.php')
                    .then(res => { if (res.ok) atualizarInterface(); })
                    .catch(err => console.error("Erro no simulador:", err));
            }
        }, 5000);

        atualizarInterface();
    });
</script>
</body>
</html>