<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GPI - Repositor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css" />
</head>
<body>
  <div class="container-fluid py-3">
    <div class="row g-3">
      <aside class="sidebar col-12 col-lg-3">
        <div class="brand">GPI</div>
        <nav class="menu">
        <a class="menu-item" href="home.php">Visao Geral</a>
        <a class="menu-item" href="config-iot.php">Configuracoes IoT</a>
        <a class="menu-item" href="relatorios.php">Relatorios</a>
        <a class="menu-item active" href="repositor.php">Repositor</a>
        <a class="menu-item" href="cadastro-produto.php">Cadastro Produto</a>
        <a class="menu-item" href="cadastro-repositor.php">Cadastro Repositor</a>
        <a class="menu-item" href="cadastro-categoria.php">Cadastro Categoria</a>
      </nav>
        <div class="hub-card mt-3">
          <strong>Hub Central</strong>
          <p>Sistema IoT online. <span>4</span> sensores ativos.</p>
        </div>
      </aside>

      <main class="content col-12 col-lg-9">
        <header class="header">
          <h1>Painel do Repositor</h1>
          <div class="header-actions">
            <a class="button-link" href="cadastro-repositor.php">Cadastrar Repositor</a>
          </div>
        </header>

        <section>
          <section class="card">
            <div class="row row-cols-1 row-cols-md-3 g-3 report-kpis">
              <article class="kpi-card col">
                <small>Total monitorado</small>
                <strong>4</strong>
                <p>Secoes ativas</p>
              </article>
              <article class="kpi-card danger col">
                <small>Precisa de reposição</small>
                <strong>2</strong>
                <p>Alertas ativos</p>
              </article>
              <article class="kpi-card col">
                <small>Mais urgente</small>
                <strong>Bebidas - Sucos (15% cheio)</strong>
                <p>Prateleira critica</p>
              </article>
            </div>
          </section>
          <section class="card mt-3">
            <h2>Alertas de Reposição</h2>
            <div class="alerts row g-3">
              <article class="alert-item vazio col-12">
                <strong class="alert-title">Bebidas - Sucos - 15% cheio — 10 unidades</strong>
                <p class="alert-time">Corredor A1 - Há 10 minutos</p>
              </article>
              <article class="alert-item vazio col-12">
                <strong class="alert-title">Limpeza - Amaciante - 10% cheio — 5 unidades</strong>
                <p class="alert-time">Corredor A2 - Há 15 minutos</p>
              </article>
            </div>
          </section>
        </section>

      </main>
    </div>
  </div>

</body>
</html>