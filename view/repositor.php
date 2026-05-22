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
            <button class="btn btn-danger mt-auto mx-2 mb-3" onclick="location.href='roteador.php?controller=Usuario&action=logout'">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Sair
            </button>
        </aside>

        <main class="content">
            <header class="header mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold h2 mb-1">Painel do Repositor</h1>
                    <p class="text-muted small">Prioridades de abastecimento em tempo real.</p>
                </div>
                <a class="btn btn-primary fw-bold shadow-sm" href="roteador.php?controller=RepositorCadastro&action=index">
                    <i class="fa-solid fa-user-plus me-2"></i> Cadastrar Repositor
                </a>
            </header>

            <section class="kpis mb-4">
                <article class="card kpi-card border-0 shadow-sm">
                    <div class="kpi-icon bg-primary-light">
                        <i class="fa-solid fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Monitorados</small>
                        <strong class="h4 mb-0">4 Seções</strong>
                    </div>
                </article>

                <article class="card kpi-card border-0 shadow-sm">
                    <div class="kpi-icon bg-danger-light text-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Reposição</small>
                        <strong class="h4 mb-0 text-danger">2 Alertas</strong>
                    </div>
                </article>

                <article class="card kpi-card border-0 shadow-sm">
                    <div class="kpi-icon bg-warning-light text-warning">
                        <i class="fa-solid fa-fire-flame-curved"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">Urgente</small>
                        <strong class="h5 mb-0">Bebidas - Sucos</strong>
                    </div>
                </article>
            </section>

            <section class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="h5 fw-bold mb-0">Alertas de Reposição</h2>
                    <span class="badge bg-light text-dark border fw-normal">Atualizado agora</span>
                </div>
                
                <div class="alerts row g-3">
                    <article class="alert-item col-12 d-flex align-items-center justify-content-between p-3 rounded-4 border-start border-danger border-5 bg-danger-light">
                        <div class="d-flex align-items-center gap-3">
                            <div class="alert-icon-circle bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fa-solid fa-droplet"></i>
                            </div>
                            <div>
                                <strong class="alert-title d-block h6 mb-1 text-danger">Bebidas - Sucos (15% cheio)</strong>
                                <p class="alert-subtitle mb-0 small text-muted">
                                    <i class="fa-solid fa-location-dot me-1"></i> Corredor A1 — <i class="fa-solid fa-clock ms-2 me-1"></i> Há 10 minutos
                                </p>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger rounded-pill px-3 py-2">10 unidades restantes</span>
                        </div>
                    </article>

                    <article class="alert-item col-12 d-flex align-items-center justify-content-between p-3 rounded-4 border-start border-danger border-5 bg-danger-light">
                        <div class="d-flex align-items-center gap-3">
                            <div class="alert-icon-circle bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fa-solid fa-spray-can-sparkles"></i>
                            </div>
                            <div>
                                <strong class="alert-title d-block h6 mb-1 text-danger">Limpeza - Amaciante (10% cheio)</strong>
                                <p class="alert-subtitle mb-0 small text-muted">
                                    <i class="fa-solid fa-location-dot me-1"></i> Corredor A2 — <i class="fa-solid fa-clock ms-2 me-1"></i> Há 15 minutos
                                </p>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-danger rounded-pill px-3 py-2">5 unidades restantes</span>
                        </div>
                    </article>
                </div>
            </section>
        </main>
    </div>
</body>
</html>