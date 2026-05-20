<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GPI - Visão Geral</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">
        <div class="brand-logo" aria-hidden="true">
          <img
            class="brand-logo-img"
            src="logo.jpeg"
            alt="Logo GPI"
          />
        </div>

        <div class="brand-text">
          <span>GPI</span>
          <small>Gestão de Prateleiras Inteligente</small>
        </div>
      </div>
      <nav class="menu">
        <a class="menu-item active" href="roteador.php?controller=Home&action=index">Visao Geral</a>
        <a class="menu-item" href="roteador.php?controller=Sensor&action=index">Configuracoes IoT</a>
        <a class="menu-item" href="roteador.php?controller=Relatorio&action=index">Relatorios</a>
        <a class="menu-item" href="roteador.php?controller=Repositor&action=index">Repositor</a>
        <a class="menu-item" href="roteador.php?controller=Produto&action=index">Cadastro Produto</a>
        <a class="menu-item" href="roteador.php?controller=RepositorCadastro&action=index">Cadastro Repositor</a>
        <a class="menu-item" href="roteador.php?controller=Categoria&action=index">Cadastro Categoria</a>
      </nav>
      <button class="btn btn-danger" onclick="location.href='roteador.php?controller=Usuario&action=logout'">Sair</button>
      <div class="hub-card">
        <strong>Hub Central</strong>
        <p>Sistema IoT online. <span>4</span> sensores ativos.</p>
      </div>
    </aside>

    <main class="content">
      <header class="header">
        <div>
          <h1>Painel de Monitoramento</h1>
        </div>
        <div class="header-actions">
          <a class="button-link" href="relatorios.html">Ver Relatórios</a>
        </div>
      </header>
      <section>
        <section class="kpis">
          <article class="kpi-card">
            <small>Total Monitorado</small>
            <strong>4 secoes</strong>
            <p>Cobertura ativa na loja</p>
          </article>
          <article class="kpi-card">
            <small>Cheio</small>
            <strong>2</strong>
            <p>Secoes com estoque disponivel</p>
          </article>
          <article class="kpi-card danger">
            <small>Vazio</small>
            <strong>2</strong>
            <p>Secoes que precisam de reposicao</p>
          </article>
        </section>

        <section class="main-grid">
          <div class="left-panel card">
            <div class="section-title">
              <h2>Status em Tempo Real (Visao Corredor)</h2>
              <div class="legend">
                <span class="dot cheio"></span>Cheio
                <span class="dot vazio"></span>Vazio
              </div>
            </div>
            <div class="shelf-grid">
              <article class="shelf-item status-cheio">
                <div class="shelf-top">
                  <small class="shelf-corredor">Corredor A1</small>
                </div>
                <h3 class="shelf-nome">Bebidas - Refrigerantes</h3>
                <div class="shelf-status">
                  <span class="peso-atual">85%</span> cheio
                </div>
                <div class="shelf-bottom">
                  <small class="shelf-updated">Atualizado há 5 min</small>
                </div>
              </article>
              <article class="shelf-item status-vazio">
                <div class="shelf-top">
                  <small class="shelf-corredor">Corredor A1</small>
                </div>
                <h3 class="shelf-nome">Bebidas - Sucos</h3>
                <div class="shelf-status">
                  <span class="peso-atual">15%</span> cheio
                </div>
                <div class="shelf-bottom">
                  <small class="shelf-updated">Atualizado há 10 min</small>
                </div>
              </article>
              <article class="shelf-item status-cheio">
                <div class="shelf-top">
                  <small class="shelf-corredor">Corredor A2</small>
                </div>
                <h3 class="shelf-nome">Limpeza - Sabão em Pó</h3>
                <div class="shelf-status">
                  <span class="peso-atual">90%</span> cheio
                </div>
                <div class="shelf-bottom">
                  <small class="shelf-updated">Atualizado há 2 min</small>
                </div>
              </article>
              <article class="shelf-item status-vazio">
                <div class="shelf-top">
                  <small class="shelf-corredor">Corredor A2</small>
                </div>
                <h3 class="shelf-nome">Limpeza - Amaciante</h3>
                <div class="shelf-status">
                  <span class="peso-atual">10%</span> cheio
                </div>
                <div class="shelf-bottom">
                  <small class="shelf-updated">Atualizado há 15 min</small>
                </div>
              </article>
            </div>
          </div>

          <div class="right-panel">
            <section class="card">
              <h2>Histórico de Alertas</h2>
              <div class="alerts">
                <article class="alert-item">
                  <strong class="alert-title">Bebidas - Sucos está vazio — 10 unidades</strong>
                  <p class="alert-time">Há 10 minutos</p>
                </article>
                <article class="alert-item">
                  <strong class="alert-title">Limpeza - Amaciante precisa reposição — 5 unidades</strong>
                  <p class="alert-time">Há 15 minutos</p>
                </article>
                <article class="alert-item vazio">
                  <strong class="alert-title">Bebidas - Sucos ficou vazio — 2 vezes hoje</strong>
                  <p class="alert-time">Hoje, 07:30</p>
                </article>
                <article class="alert-item vazio">
                  <strong class="alert-title">Limpeza - Amaciante ficou vazio — 1 vez hoje</strong>
                  <p class="alert-time">Hoje, 06:45</p>
                </article>
                <article class="alert-item">
                  <strong class="alert-title">Bebidas - Refrigerantes volta ao nível normal</strong>
                  <p class="alert-time">Ontem, 18:20</p>
                </article>
                <article class="alert-item">
                  <strong class="alert-title">Limpeza - Sabão em Pó mantém estoque</strong>
                  <p class="alert-time">Ontem, 16:10</p>
                </article>
              </div>
            </section>
          </div>
        </section>
      </section>

    </main>
  </div>

</body>
</html>