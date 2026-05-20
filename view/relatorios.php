<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GPI - Relatórios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">GPI</div>
      <nav class="menu">
        <a class="menu-item" href="roteador.php?controller=Home&action=index">Visao Geral</a>
        <a class="menu-item" href="roteador.php?controller=Sensor&action=index">Configuracoes IoT</a>
          <a class="menu-item active" href="roteador.php?controller=Relatorio&action=index">Relatorios</a>
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
        <h1>Relatórios</h1>
        <div class="header-actions">
        </div>
      </header>

      <section>
        <section class="card">
          <div class="report-filter">
            <div>
              <label for="report-sensor-select">Sensor</label>
              <select>
                <option value="__all__">Todos os sensores</option>
              </select>
            </div>
            <div>
              <label>Tipo de relatorio</label>
              <div class="period-toggle">
                <button class="period-btn active">Diario</button>
                <button class="period-btn">Semanal</button>
                <button class="period-btn">Mensal</button>
              </div>
            </div>
          </div>
        </section>

        <section class="card report-insights-card">
          <h2>Leitura Rapida (Insights)</h2>
          <ul class="report-insights">
            <li>As prateleiras de bebidas ficam vazias mais frequentemente no período da tarde.</li>
            <li>O corredor A1 tem maior taxa de reposição necessária.</li>
            <li>Produtos de limpeza tendem a manter estoque por mais tempo.</li>
          </ul>
        </section>

        <section class="card">
          <h2>Prateleiras Mais Vazias</h2>
          <table class="sensor-table">
            <thead>
              <tr>
                <th>Prateleira</th>
                <th>Corredor</th>
                <th>Vezes vazia</th>
                <th>Última vez vazia</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Bebidas - Sucos</td>
                <td>Corredor A1</td>
                <td>12</td>
                <td>Hoje, 10:00</td>
              </tr>
              <tr>
                <td>Limpeza - Amaciante</td>
                <td>Corredor A2</td>
                <td>9</td>
                <td>Hoje, 09:45</td>
              </tr>
              <tr>
                <td>Bebidas - Refrigerantes</td>
                <td>Corredor A1</td>
                <td>5</td>
                <td>Hoje, 11:30</td>
              </tr>
              <tr>
                <td>Limpeza - Sabão em Pó</td>
                <td>Corredor A2</td>
                <td>3</td>
                <td>Ontem, 17:20</td>
              </tr>
            </tbody>
          </table>
        </section>
      </section>

    </main>
  </div>

</body>
</html>