<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GPI - Painel de Monitoramento</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-text">
                    <span>GPI</span>
                    <small>Gestão Inteligente</small>
                </div>
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
                    <h1 class="fw-bold h2 mb-1">Painel de Monitoramento</h1>
                    <p class="text-muted small mb-0">Status em tempo real das seções em Itapira.</p>
                </div>
                <div class="header-actions">
                    <a class="btn btn-primary shadow-sm px-4 fw-bold" href="roteador.php?controller=Relatorio&action=index">
                        <i class="fa-solid fa-download me-2"></i> Relatórios Completos
                    </a>
                </div>
            </header>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card kpi-card h-100">
                        <div class="kpi-icon bg-primary-light">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div class="kpi-info">
                            <small>MONITORADO</small>
                            <strong><?php echo count($sensores); ?> Seções</strong>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card kpi-card h-100">
                        <div class="kpi-icon bg-success-light">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div class="kpi-info">
                            <small>ESTOQUE CHEIO</small>
                            <strong><?php echo $totalCheios; ?></strong>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card kpi-card h-100">
                        <div class="kpi-icon bg-danger-light">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div class="kpi-info">
                            <small>CRÍTICO (VAZIO)</small>
                            <strong><?php echo $totalCriticos; ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="main-grid">
                <section class="card border-0 shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h5 fw-bold mb-0"><i class="fa-solid fa-eye me-2 text-primary"></i>Status das Prateleiras</h2>
                        <div class="legend d-flex gap-2">
                            <span class="badge bg-success-light text-success border-0 px-2 py-1"><span class="dot cheio me-1"></span> OK</span>
                            <span class="badge bg-danger-light text-danger border-0 px-2 py-1"><span class="dot vazio me-1"></span> Repor</span>
                        </div>
                    </div>

                    <div class="shelf-grid">
                        <?php foreach ($sensores as $s): 
                            $quantidade = $s->getQuantidadeAtual();
                            $porcentagem = $s->getPorcentagemEstoque();
                            $isCritico = $s->precisaRepoiscao();
                            $classeStatus = $isCritico ? 'status-vazio' : 'status-cheio';
                            $corBarra = $isCritico ? 'bg-danger' : 'bg-success';
                        ?>
                        <article class="shelf-item <?php echo $classeStatus; ?> p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light text-muted border small fw-normal">
                                    <?php echo $s->getCorredor() . " - " . $s->getLado(); ?>
                                </span>
                                <small class="text-muted"><i class="fa-regular fa-clock me-1"></i><?php echo date('H:i'); ?></small>
                            </div>
                            
                            <h3 class="h6 fw-bold mb-3"><?php echo $s->getProduto()->getNome(); ?></h3>
                            
                            <div class="shelf-status-bar">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-muted">Estoque: <strong><?php echo $quantidade; ?></strong>/<?php echo $s->getCapacidadeMaxima(); ?> un</span>
                                    
                                    <span class="fw-bold <?php echo $isCritico ? 'text-danger' : 'text-success'; ?>">
                                        <?php if($isCritico): ?>
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i>Repor
                                        <?php else: ?>
                                            <i class="fa-solid fa-check me-1"></i>OK
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px; border-radius: 10px;">
                                    <div class="progress-bar <?php echo $corBarra; ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $porcentagem; ?>%" 
                                         aria-valuenow="<?php echo $porcentagem; ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="card border-0 shadow-sm p-4">
                    <h2 class="h5 fw-bold mb-4"><i class="fa-solid fa-bell me-2 text-warning"></i>Histórico de Alertas</h2>
                    <div class="alerts d-flex flex-column gap-3">
                        <?php if (empty($alertas)): ?>
                            <div class="text-center py-4">
                                <i class="fa-solid fa-circle-check text-success opacity-25 mb-2" style="font-size: 2rem;"></i>
                                <p class="text-muted small">Nenhum alerta pendente.</p>
                            </div>
                        <?php else: foreach ($alertas as $a): ?>
                            <article class="alert-item p-3 rounded-3 bg-light border-start border-danger border-4">
                                <strong class="d-block small fw-bold text-dark"><?php echo $a->getProduto()->getNome(); ?> está em nível crítico!</strong>
                                <p class="mb-1 smaller text-muted">Apenas <?php echo $a->getQuantidadeNoMomento(); ?> unidades restantes.</p>
                                <p class="alert-time mb-0 smaller text-muted">
                                    <i class="fa-regular fa-clock me-1"></i> <?php echo date('d/m H:i', strtotime($a->getDataHoraAlerta())); ?>
                                </p>
                            </article>
                        <?php endforeach; endif; ?>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>