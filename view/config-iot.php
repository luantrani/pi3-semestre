<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GPI - Configurações IoT</title>
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
                <a class="menu-item active" href="roteador.php?controller=Sensor&action=index">
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
            <button class="btn btn-danger mt-auto mx-2 mb-3" onclick="location.href='roteador.php?controller=Usuario&action=logout'">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Sair
            </button>
        </aside>

        <main class="content">
            <header class="header mb-4">
                <div>
                    <h1 class="fw-bold h2 mb-1">Configurações IoT</h1>
                    <p class="text-muted small">Gerencie seus dispositivos ESP32/NodeMCU e mapeie as prateleiras.</p>
                </div>
            </header>

            <section>
                <section class="card border-0 shadow-sm mb-4 p-4">
                    <h2 class="h5 fw-bold mb-4 text-primary"><i class="fa-solid fa-plus-circle me-2"></i>Cadastrar Novo Dispositivo</h2>
                    
                    <form class="sensor-form" method="POST" action="roteador.php?controller=Sensor&action=cadastrarSensor">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Nome do Sensor *</label>
                                <input type="text" name="nomeSensor" class="form-control shadow-none" placeholder="Ex: Sensor Bebidas Frias" required />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">ID do Sensor (NodeMCU/ESP32) *</label>
                                <input type="text" name="idSensor" class="form-control shadow-none" placeholder="Ex: US-1023" required />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-muted">Corredor *</label>
                                <input type="text" name="corredor" class="form-control shadow-none" placeholder="Ex: A3" required />
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-muted">Lado do Corredor *</label>
                                <select name="lado" class="form-select shadow-none" required>
                                    <option value="Esquerda">Esquerda</option>
                                    <option value="Direita">Direita</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted">Produto Vinculado</label>
                                <select name="id_produto" class="form-select shadow-none">
                                    <option value="">Nenhum produto selecionado</option>
                                    <?php foreach ($produtos as $produto): ?>
                                        <option value="<?= $produto['id'] ?>"><?= $produto['nome'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">Capacidade Máxima *</label>
                                <div class="input-group">
                                    <input type="number" name="capacidadeMaxima" step="0.1" class="form-control shadow-none" placeholder="Ex: 25.0" required />
                                    <span class="input-group-text bg-light border-start-0 text-muted small">kg</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">Peso Mínimo para Alerta (kg) *</label>
                                <div class="input-group">
                                    <input type="number" name="minimoReposicao" step="0.1" class="form-control shadow-none" placeholder="Ex: 5.0" required />
                                    <span class="input-group-text bg-light border-start-0 text-muted small">kg</span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label d-block" style="visibility: hidden;">Ação</label>
                                <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center" style="height: 42px;">
                                    <i class="fa-solid fa-save me-2"></i> Salvar Dispositivo
                                </button>
                            </div>
                        </div>
                    </form>
                </section>

                <section class="card border-0 shadow-sm p-0 overflow-hidden">
                    <div class="p-4 bg-white border-bottom">
                        <h2 class="h5 fw-bold mb-0">Sensores Ativos</h2>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted fw-semibold small">ID SENSOR</th>
                                    <th class="py-3 text-muted fw-semibold small">NOME</th>
                                    <th class="py-3 text-muted fw-semibold small">POSIÇÃO</th>
                                    <th class="py-3 text-muted fw-semibold small">PRODUTO/PESO</th>
                                    <th class="py-3 text-muted fw-semibold small text-center">STATUS</th>
                                    <th class="py-3 text-muted fw-semibold small text-end pe-4">AÇÕES</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($sensores)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted italic">
                                            <i class="fa-solid fa-inbox d-block mb-2 h2 opacity-25"></i>
                                            Nenhum sensor cadastrado.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($sensores as $sensor): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary"><?= htmlspecialchars($sensor->getId()) ?></td>
                                            <td><?= htmlspecialchars($sensor->getNome()) ?></td>
                                            <td>
                                                <span class="badge bg-light text-dark border fw-normal">
                                                    <i class="fa-solid fa-map-pin me-1 text-primary"></i> 
                                                    Corr. <?= htmlspecialchars($sensor->getCorredor()) ?> (<?= htmlspecialchars($sensor->getLado()) ?>)
                                                </span>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold"><?= htmlspecialchars($sensor->getProduto()->getNome()) ?></div>
                                                <div class="text-muted" style="font-size: 0.75rem;">Cap: <?= htmlspecialchars($sensor->getCapacidadeMaxima()) ?>kg</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill <?= $sensor->getStatus() === 'Ativo' ? 'bg-success-light text-success' : 'bg-secondary-light text-secondary' ?> px-3">
                                                    <i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i> <?= htmlspecialchars($sensor->getStatus()) ?>
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="btn-group shadow-sm border rounded">
                                                    <button class="btn btn-white btn-sm px-3" title="Editar"><i class="fa-solid fa-pen text-muted"></i></button>
                                                    <button class="btn btn-white btn-sm px-3" title="Remover"><i class="fa-solid fa-trash text-danger"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </main>
    </div>
</body>
</html>