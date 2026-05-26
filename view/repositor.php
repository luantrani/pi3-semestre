<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GPI - Área do Repositor</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-text"><span>GPI</span><small>Gestão Inteligente</small></div>
            </div>
            <nav class="menu">
                <a class="menu-item" href="roteador.php?controller=Home&action=index">
                    <i class="fa-solid fa-chart-pie"></i> Visão Geral
                </a>
                <a class="menu-item" href="roteador.php?controller=Sensor&action=index">
                    <i class="fa-solid fa-microchip"></i> Configurações IoT
                </a>
                <a class="menu-item" href="roteador.php?controller=Relatorio&action=index">
                    <i class="fa-solid fa-file-lines"></i> Relatórios
                </a>
                <a class="menu-item active" href="roteador.php?controller=Repositor&action=index">
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
                    <h1 class="fw-bold h2 mb-1">Painel do Repositor</h1>
                    <p class="text-muted small mb-0">Prioridades de abastecimento em tempo real.</p>
                </div>
                <a class="btn btn-primary fw-bold shadow-sm px-4" href="roteador.php?controller=RepositorCadastro&action=index">
                    <i class="fa-solid fa-user-plus me-2"></i> Cadastrar Repositor
                </a>
            </header>

            <section class="row g-4 mb-4">
                <div class="col-md-4">
                    <article class="card kpi-card border-0 shadow-sm h-100">
                        <div class="kpi-icon bg-primary-light">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div class="kpi-info">
                            <small class="text-muted">MONITORADOS</small>
                            <strong><?php echo count($sensores); ?> Seções</strong>
                        </div>
                    </article>
                </div>

                <div class="col-md-4">
                    <article class="card kpi-card border-0 shadow-sm h-100">
                        <div class="kpi-icon bg-danger-light text-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div class="kpi-info">
                            <small class="text-muted">EM ALERTA</small>
                            <strong><?php echo count($alertas); ?> Pendentes</strong>
                        </div>
                    </article>
                </div>

                <div class="col-md-4">
                    <article class="card kpi-card border-0 shadow-sm h-100">
                        <div class="kpi-icon bg-warning-light text-warning">
                            <i class="fa-solid fa-fire-flame-curved"></i>
                        </div>
                        <div class="kpi-info">
                            <small class="text-muted">MAIS URGENTE</small>
                            <strong class="text-truncate d-block" style="max-width: 100%;">
                                <?php echo !empty($alertas) ? $alertas[0]->getProduto()->getNome() : 'Nenhum'; ?>
                            </strong>
                        </div>
                    </article>
                </div>
            </section>

            <section class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h5 fw-bold mb-0">Alertas de Reposição</h2>
                    <span class="badge bg-light text-dark border fw-normal px-3 py-2">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Atualizado às <?php echo date('H:i'); ?>
                    </span>
                </div>
                
                <div class="alerts row g-3">
                    <?php if (empty($alertas)): ?>
                        <div class="col-12 text-center py-5">
                            <i class="fa-solid fa-circle-check text-success opacity-25 mb-3" style="font-size: 4rem;"></i>
                            <h3 class="h5 text-muted">Tudo abastecido!</h3>
                            <p class="text-muted small">Não há produtos abaixo do nível mínimo no momento.</p>
                        </div>
                    <?php else: foreach ($alertas as $a): 
                        // Lógica de ícone por categoria
                        $icon = "fa-box";
                        $categoria = strtolower($a->getProduto()->getCategoria());
                        if (strpos($categoria, 'bebi') !== false) $icon = "fa-droplet";
                        elseif (strpos($categoria, 'limp') !== false) $icon = "fa-spray-can-sparkles";
                        elseif (strpos($categoria, 'higi') !== false) $icon = "fa-pump-soap";
                    ?>
                        <article class="alert-item col-12 d-flex align-items-center justify-content-between p-3 rounded-4 border-start border-danger border-5 bg-danger-light shadow-sm">
                            <div class="d-flex align-items-center gap-3">
                                <div class="alert-icon-circle bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="min-width: 48px; height: 48px;">
                                    <i class="fa-solid <?php echo $icon; ?> fa-lg"></i>
                                </div>
                                <div>
                                    <strong class="alert-title d-block h6 mb-1 text-danger">
                                        <?php echo $a->getProduto()->getNome(); ?>
                                    </strong>
                                    <p class="alert-subtitle mb-0 small text-muted">
                                        <i class="fa-solid fa-location-dot me-1"></i> <?php echo $a->getCorredor(); ?> (<?php echo $a->getSensor()->getLado(); ?>) — 
                                        <i class="fa-solid fa-clock ms-2 me-1"></i> Há <?php echo $a->getTempoDesdeAlerta(); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="text-end d-flex flex-column align-items-end gap-2">
                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                    Restam <?php echo $a->getQuantidadeNoMomento(); ?> un
                                </span>

                                <?php 
                                // Captura os dados necessários para a decisão
                                $statusAlerta = trim(strtolower($a->getStatus()));
                                $idResponsavel = $a->getIdUsuarioAtendimento();
                                $idLogado = $_SESSION['usuario']['id'] ?? 0; // Garante que não dê erro se não houver sessão
                                ?>

                                <?php if ($statusAlerta === 'pendente'): ?>
                                    <button class="btn btn-primary btn-sm px-4 fw-bold btn-atender" data-id="<?php echo $a->getId(); ?>">
                                        <i class="fa-solid fa-hand-pointer me-1"></i> Atender
                                    </button>

                                <?php elseif ($statusAlerta === 'em_andamento'): ?>
                                    <?php if ($idResponsavel == $idLogado): ?>
                                        <button class="btn btn-success btn-sm px-4 fw-bold btn-finalizar" data-id="<?php echo $a->getId(); ?>">
                                            <i class="fa-solid fa-check-double me-1"></i> Finalizar
                                        </button>
                                    <?php else: ?>
                                        <span class="badge bg-secondary py-2 px-3 opacity-75">
                                            <i class="fa-solid fa-spinner fa-spin me-1"></i> Em andamento
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; endif; ?>
                </div>
            </section>
        </main>
    </div>

    <script>
    document.addEventListener('click', function(e) {
        // Ação de ATENDER
        if (e.target.closest('.btn-atender')) {
            const btn = e.target.closest('.btn-atender');
            const idAlerta = btn.dataset.id;

            // Chamada AJAX usando Fetch
            fetch(`roteador.php?controller=Repositor&action=atender&id=${idAlerta}`)
                .then(response => {
                    if(response.ok) {
                        // Recarrega apenas a lista ou a página para atualizar o visual
                        location.reload(); 
                    }
                });
        }

        // Ação de FINALIZAR
        if (e.target.closest('.btn-finalizar')) {
            const btn = e.target.closest('.btn-finalizar');
            const idAlerta = btn.dataset.id;

            fetch(`roteador.php?controller=Repositor&action=finalizar&id=${idAlerta}`)
                .then(response => {
                    if(response.ok) {
                        location.reload();
                    }
                });
        }
    });
    </script>
</body>
</html>