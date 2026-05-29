<?php if (empty($alertas)): ?>
    <div class="col-12 text-center py-5">
        <i class="fa-solid fa-circle-check text-success opacity-25 mb-3" style="font-size: 4rem;"></i>
        <h3 class="h5 text-muted">Tudo abastecido!</h3>
        <p class="text-muted small">Não há produtos abaixo do nível mínimo no momento.</p>
    </div>
<?php else: foreach ($alertas as $a): 
    // 1. Lógica de ícone por categoria (pra deixar o visual no grau)
    $icon = "fa-box";
    $categoria = strtolower($a->getProduto()->getCategoria());
    if (strpos($categoria, 'bebi') !== false) $icon = "fa-droplet";
    elseif (strpos($categoria, 'limp') !== false) $icon = "fa-spray-can-sparkles";
    elseif (strpos($categoria, 'higi') !== false) $icon = "fa-pump-soap";

    // 2. Variáveis de controle de status e segurança
    $statusAlerta = trim(strtolower($a->getStatus()));
    $idResponsavel = $a->getIdUsuarioAtendimento();
    $idLogado = $_SESSION['usuario']['id'] ?? 0;
?>
    <article class="alert-item col-12 d-flex align-items-center justify-content-between p-3 rounded-4 border-start border-danger border-5 bg-danger-light shadow-sm mb-3">
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