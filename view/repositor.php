<?php
// Segurança: Se o admin acessar, garantimos que os dados existem
$alertas = $alertas ?? [];
$historico = $historico ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GPI - Gestão de Reposição</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
    <style>
        .badge-status { font-size: 0.75rem; padding: 5px 12px; border-radius: 50px; }
        .bg-pending { background-color: #ffe5e5; color: #d63031; }
        .bg-progress { background-color: #fff4e5; color: #f39c12; }
        .stat-card { border: 1px solid #edf2f7; transition: all 0.3s; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-text"><span>GPI</span><small>Gestão Inteligente</small></div>
            </div>
            <nav class="menu">
                <a class="menu-item" href="roteador.php?controller=Home&action=index"><i class="fa-solid fa-chart-pie"></i> Visão Geral</a>
                <a class="menu-item" href="roteador.php?controller=Sensor&action=index"><i class="fa-solid fa-microchip"></i> Configurações IoT</a>
                <a class="menu-item" href="roteador.php?controller=Relatorio&action=index"><i class="fa-solid fa-file-lines"></i> Relatórios</a>
                <a class="menu-item active" href="roteador.php?controller=Repositor&action=index"><i class="fa-solid fa-truck-ramp-box"></i> Área Repositor</a>
                <div class="menu-divider"></div>
                <a class="menu-item" href="roteador.php?controller=Gestao&action=index"><i class="fa-solid fa-gears"></i> Administração</a>
            </nav>
            <button class="btn btn-danger mt-auto mx-2 mb-3" onclick="location.href='roteador.php?controller=Usuario&action=logout'">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Sair
            </button>
        </aside>

        <main class="content">
            <header class="header mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold h3 mb-1">Monitor de Operação</h1>
                    <p class="text-muted small mb-0">Acompanhamento em tempo real da equipe de campo.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary shadow-sm btn-sm" onclick="location.reload()"><i class="fa-solid fa-sync me-2"></i>Atualizar</button>
                </div>
            </header>

            <section class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stat-card p-3 border-0 shadow-sm">
                    <small class="text-muted fw-bold">ALERTAS ATIVOS</small>
                    <h2 class="fw-800 mb-0 text-danger"><?= count($alertas) ?></h2>
                    <div class="progress mt-2" style="height: 4px;"><div class="progress-bar bg-danger" style="width: 100%"></div></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card p-3 border-0 shadow-sm">
                    <small class="text-muted fw-bold">EM ATENDIMENTO</small>
                    <?php 
                    $emAtendimento = count(array_filter($alertas, fn($a) => $a->getStatus() === 'em_andamento'));
                    ?>
                    <h2 class="fw-800 mb-0 text-warning"><?= $emAtendimento ?></h2>
                    <div class="progress mt-2" style="height: 4px;"><div class="progress-bar bg-warning" style="width: 100%"></div></div>
                </div>
            </div>
            </section>

            <section class="card border-0 shadow-sm">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                    <h2 class="h5 fw-bold mb-0">Status das Prateleiras</h2>
                    <input type="text" class="form-control form-control-sm w-25" placeholder="Buscar produto ou corredor...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="ps-4">PRODUTO / LOCAL</th>
                                <th>NÍVEL ATUAL</th>
                                <th>STATUS OPERACIONAL</th>
                                <th>RESPONSÁVEL</th>
                                <th class="text-end pe-4">AÇÕES</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($alertas)): ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">Nenhum alerta ativo.</td></tr>
                        <?php else: ?>
                            <?php foreach ($alertas as $alerta): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold"><?= htmlspecialchars($alerta->getProduto()->getNome()) ?></div>
                                    <div class="extra-small text-muted"><i class="fa-solid fa-location-dot me-1"></i> Corredor <?= htmlspecialchars($alerta->getCorredor()) ?></div>
                                </td>
                                <td></td>
                                <td>
                                    <?php if ($alerta->getStatus() === 'pendente'): ?>
                                        <span class="badge-status bg-pending fw-bold">Aguardando</span>
                                    <?php else: ?>
                                        <span class="badge-status bg-progress fw-bold">Em Atendimento</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($alerta->getStatus() === 'pendente'): ?>
                                        <span class="text-muted small fst-italic">Aguardando...</span>
                                    <?php else: ?>
                                        <span class="fw-medium text-dark">
                                            <?= htmlspecialchars($alerta->getResponsavelNome() ?? 'Não identificado') ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4"><button class="btn btn-sm btn-light border"><i class="fa-solid fa-eye text-primary"></i></button></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>
</html>