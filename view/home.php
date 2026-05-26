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
        /* Ajustes específicos para o novo layout expandido */
        .shelf-card { transition: transform 0.2s, box-shadow 0.2s; border-radius: 15px; }
        .shelf-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .bg-primary-light { background-color: #e7f1ff; color: #0d6efd; }
        .bg-success-light { background-color: #d1e7dd; color: #0f5132; }
        .bg-danger-light { background-color: #f8d7da; color: #842029; }
        .progress-label { font-size: 0.85rem; font-weight: 600; }
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
            <button class="btn btn-danger mt-auto mx-2 mb-3 py-2" onclick="location.href='roteador.php?controller=Usuario&action=logout'">
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
                
                <div class="row g-4">
                    <?php foreach ($sensores as $s): 
                        $isCritico = $s->precisaRepoiscao();
                        $porcentagem = $s->getPorcentagemEstoque();
                        $corDestaque = $isCritico ? 'danger' : 'success';
                    ?>
                    <div class="col-xl-4 col-md-6">
                        <article class="card shelf-card h-100 border-0 border-start border-5 border-<?php echo $corDestaque; ?> shadow-sm"
                                style="cursor: pointer;" 
                                data-bs-toggle="modal" 
                                data-bs-target="#sensorModal"
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
                                        <i class="fa-solid fa-circle <?php echo $isCritico ? 'text-danger' : 'text-success'; ?> small"></i>
                                    </div>
                                </div>

                                <h3 class="h5 fw-bold mb-1"><?php echo $s->getProduto()->getNome(); ?></h3>
                                <p class="text-muted small mb-4">Lado: <?php echo $s->getLado(); ?></p>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-end mb-2">
                                        <span class="h2 fw-bold mb-0 <?php echo 'text-'.$corDestaque; ?>">
                                            <?php echo $porcentagem; ?>%
                                        </span>
                                        <span class="text-muted small">
                                            <strong><?php echo $s->getQuantidadeAtual(); ?></strong>/<?php echo $s->getCapacidadeMaxima(); ?> un
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 10px; background-color: #eee;">
                                        <div class="progress-bar bg-<?php echo $corDestaque; ?> <?php echo $isCritico ? 'progress-bar-striped progress-bar-animated' : ''; ?>" 
                                            role="progressbar" style="width: <?php echo $porcentagem; ?>%"></div>
                                    </div>
                                </div>

                                <?php if($isCritico): ?>
                                    <div class="mt-3 p-2 rounded bg-danger-light d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-truck-ramp-box"></i>
                                        <span class="small fw-bold">Reposição Necessária</span>
                                    </div>
                                <?php else: ?>
                                    <div class="mt-3 p-2 rounded bg-success-light d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-check-circle"></i>
                                        <span class="small fw-bold">Estoque Saudável</span>
                                    </div>
                                <?php endif; ?>
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
            <span class="badge bg-primary-light mb-2" id="modalCategoria">Categoria</span>
            <div class="display-4 fw-bold text-primary mb-3" id="modalQtdInfo">0/0</div>
            <p class="text-muted">Status detalhado e histórico de reposição serão exibidos aqui.</p>
            <div class="bg-light rounded p-5 mb-3">
                <i class="fa-solid fa-chart-line fa-3x text-muted opacity-25"></i>
                <p class="small text-muted mt-2">Área para Gráfico de Histórico</p>
            </div>
        </div>
        </div>
    </div>
    </div>

    <script>
    const sensorModal = document.getElementById('sensorModal');
    sensorModal.addEventListener('show.bs.modal', event => {
    const card = event.relatedTarget;
    const nome = card.getAttribute('data-nome');
    const cat = card.getAttribute('data-categoria');
    const qtd = card.getAttribute('data-qtd');
    const max = card.getAttribute('data-max');

    sensorModal.querySelector('#modalNomeProduto').textContent = nome;
    sensorModal.querySelector('#modalCategoria').textContent = cat;
    sensorModal.querySelector('#modalQtdInfo').textContent = `${qtd} / ${max} un`;
    });
    </script>
</body>
</html>