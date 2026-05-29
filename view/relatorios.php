<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GPI - Relatórios</title>
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
                <a class="menu-item active" href="roteador.php?controller=Relatorio&action=index">
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
            <button class="btn btn-danger mt-auto mx-2 mb-3" onclick="location.href='roteador.php?controller=Usuario&action=logout'">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Sair
            </button>
        </aside>

        <main class="content">
            <header class="header mb-4">
                <h1 class="fw-bold h2 mb-1">Relatórios Analíticos</h1>
                <p class="text-muted small">Analise o desempenho de reposição e comportamento do estoque.</p>
            </header>

            <section class="card border-0 shadow-sm p-4 mb-4">
                <div class="row align-items-end g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold small text-muted">Filtrar por Sensor</label>
                        <select class="form-select shadow-none">
                            <option value="__all__">Todos os sensores</option>
                            </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold small text-muted">Período de Análise</label>
                        <div class="period-toggle d-flex bg-light p-1 rounded-pill" style="border: 1px solid var(--line);">
                            <button class="btn btn-sm flex-fill rounded-pill active">Diário</button>
                            <button class="btn btn-sm flex-fill rounded-pill text-muted">Semanal</button>
                            <button class="btn btn-sm flex-fill rounded-pill text-muted">Mensal</button>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100 fw-bold shadow-sm" style="height: 42px;">
                            <i class="fa-solid fa-magnifying-glass me-2"></i> Gerar Relatório
                        </button>
                    </div>
                </div>
            </section>

            <div class="row g-4">
                <div class="col-lg-4">
                    <section class="card border-0 shadow-sm p-4 h-100">
                        <h2 class="h5 fw-bold mb-4"><i class="fa-solid fa-lightbulb text-warning me-2"></i>Insights</h2>
                        <div class="report-insights">
                            <div class="insight-item mb-3 p-3 rounded bg-light border-start border-primary border-4">
                                <p class="mb-0 small fw-medium">Bebidas ficam vazias com 40% mais frequência no <strong>período da tarde</strong>.</p>
                            </div>
                            <div class="insight-item mb-3 p-3 rounded bg-light border-start border-success border-4">
                                <p class="mb-0 small fw-medium">O <strong>Corredor A1</strong> demanda reposição 2x mais rápido que os demais.</p>
                            </div>
                            <div class="insight-item p-3 rounded bg-light border-start border-info border-4">
                                <p class="mb-0 small fw-medium">Estoque de <strong>Limpeza</strong> está 15% mais estável este mês.</p>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-lg-8">
                    <section class="card border-0 shadow-sm p-0 overflow-hidden h-100">
                        <div class="p-4 bg-white border-bottom">
                            <h2 class="h5 fw-bold mb-0">Ranking: Prateleiras Mais Vazias</h2>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 text-nowrap">
                                <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted fw-semibold small">PRODUTO</th>
                                    <th class="py-3 text-muted fw-semibold small">REPOSITOR</th>
                                    <th class="py-3 text-muted fw-semibold small text-center">QTD ADICIONADA</th>
                                    <th class="py-3 text-muted fw-semibold small text-end pe-4">DATA/HORA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($historico)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Nenhuma reposição registrada ainda.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($historico as $item): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold"><?= htmlspecialchars($item['produto_nome']) ?></div>
                                            <div class="text-muted extra-small">ID Mov: #<?= $item['id'] ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border fw-normal">
                                                <i class="fa-solid fa-user-check me-1 small text-primary"></i>
                                                <?= htmlspecialchars($item['usuario_nome']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-light text-success fw-bold fs-6 px-3">
                                                +<?= $item['quantidade_adicionada'] ?> un
                                            </span>
                                        </td>
                                        <td class="text-end pe-4 small text-muted">
                                            <?= date('d/m/Y', strtotime($item['data_hora'])) ?><br>
                                            <strong><?= date('H:i', strtotime($item['data_hora'])) ?></strong>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>
</body>
</html>