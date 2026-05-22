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
                    <p class="text-muted small mb-0">Status em tempo real das seções da loja.</p>
                </div>
                <div class="header-actions">
                    <a class="btn btn-primary shadow-sm px-4 fw-bold" href="roteador.php?controller=Relatorio&action=index">
                        <i class="fa-solid fa-download me-2"></i> Relatórios Completos
                    </a>
                </div>
            </header>

            <section class="kpis mb-4">
                <article class="card kpi-card border-0 shadow-sm">
                    <div class="kpi-icon bg-primary-light"><i class="fa-solid fa-boxes-stacked"></i></div>
                    <div>
                        <small class="text-uppercase fw-semibold text-muted" style="font-size: 0.7rem;">Monitorado</small>
                        <strong class="d-block h4 mb-0">4 Seções</strong>
                    </div>
                </article>
                <article class="card kpi-card border-0 shadow-sm">
                    <div class="kpi-icon bg-success-light text-success"><i class="fa-solid fa-circle-check"></i></div>
                    <div>
                        <small class="text-uppercase fw-semibold text-muted" style="font-size: 0.7rem;">Estoque Cheio</small>
                        <strong class="d-block h4 mb-0">2</strong>
                    </div>
                </article>
                <article class="card kpi-card border-0 shadow-sm">
                    <div class="kpi-icon bg-danger-light text-danger"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div>
                        <small class="text-uppercase fw-semibold text-muted" style="font-size: 0.7rem;">Crítico (Vazio)</small>
                        <strong class="d-block h4 mb-0">2</strong>
                    </div>
                </article>
            </section>

            <div class="main-grid">
                <section class="card border-0 shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h5 fw-bold mb-0"><i class="fa-solid fa-eye me-2 text-primary"></i>Status das Prateleiras</h2>
                        <div class="legend d-flex gap-2">
                            <span class="badge bg-success-light text-success border-0 px-2 py-1"><span class="dot cheio me-1"></span> Cheio</span>
                            <span class="badge bg-danger-light text-danger border-0 px-2 py-1"><span class="dot vazio me-1"></span> Vazio</span>
                        </div>
                    </div>

                    <div class="shelf-grid">
                        <article class="shelf-item status-cheio p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light text-muted border small fw-normal">Corredor A1</span>
                                <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>5 min</small>
                            </div>
                            <h3 class="h6 fw-bold mb-3">Bebidas - Refrigerantes</h3>
                            <div class="shelf-status-bar">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-muted">Nível: 85%</span>
                                    <span class="fw-bold text-success">OK</span>
                                </div>
                                <div class="progress" style="height: 6px; border-radius: 10px;">
                                    <div class="progress-bar bg-success" style="width: 85%"></div>
                                </div>
                            </div>
                        </article>

                        <article class="shelf-item status-vazio p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light text-muted border small fw-normal">Corredor A1</span>
                                <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>10 min</small>
                            </div>
                            <h3 class="h6 fw-bold mb-3">Bebidas - Sucos</h3>
                            <div class="shelf-status-bar">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span class="text-muted">Nível: 15%</span>
                                    <span class="fw-bold text-danger">Repor</span>
                                </div>
                                <div class="progress" style="height: 6px; border-radius: 10px;">
                                    <div class="progress-bar bg-danger" style="width: 15%"></div>
                                </div>
                            </div>
                        </article>

                        </div>
                </section>

                <section class="card border-0 shadow-sm p-4">
                    <h2 class="h5 fw-bold mb-4"><i class="fa-solid fa-bell me-2 text-warning"></i>Histórico</h2>
                    <div class="alerts d-flex flex-column gap-3">
                        <article class="alert-item p-3 rounded-3 bg-light border-start border-warning border-4">
                            <strong class="d-block small fw-bold">Bebidas - Sucos está vazio</strong>
                            <p class="alert-time mb-0 smaller text-muted">Há 10 minutos</p>
                        </article>
                        <article class="alert-item p-3 rounded-3 bg-light border-start border-warning border-4">
                            <strong class="d-block small fw-bold">Limpeza - Amaciante crítico</strong>
                            <p class="alert-time mb-0 smaller text-muted">Há 15 minutos</p>
                        </article>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>