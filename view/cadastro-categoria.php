<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GPI - Cadastro de Categorias</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">GPI</div>
      <nav class="menu">
        <a class="menu-item" href="roteador.php?controller=Home&action=index">Visao Geral</a>
        <a class="menu-item" href="roteador.php?controller=Sensor&action=index">Configuracoes IoT</a>
        <a class="menu-item" href="roteador.php?controller=Relatorio&action=index">Relatorios</a>
        <a class="menu-item" href="roteador.php?controller=Repositor&action=index">Repositor</a>
        <a class="menu-item" href="roteador.php?controller=Produto&action=index">Cadastro Produto</a>
        <a class="menu-item" href="roteador.php?controller=RepositorCadastro&action=index">Cadastro Repositor</a>
        <a class="menu-item active" href="roteador.php?controller=Categoria&action=index">Cadastro Categoria</a>
      </nav>
      <div class="hub-card">
        <strong>Hub Central</strong>
        <p>Sistema IoT online. <span>4</span> sensores ativos.</p>
      </div>
    </aside>

    <main class="content">
      <header class="header">
        <h1>Cadastro de Categoria</h1>
      </header>

      <section class="card">
        <?php if ($status === 'sucesso'): ?>
          <div class="alert-item" style="border-left-color: var(--normal); background: #f4fff4;">
            Categoria cadastrada com sucesso.
          </div>
        <?php elseif ($status === 'erro'): ?>
          <div class="alert-item vazio">
            Erro ao cadastrar a categoria. Verifique os dados e tente novamente.
          </div>
        <?php elseif ($status === 'erro_listar'): ?>
          <div class="alert-item vazio">
            Erro ao carregar a lista de categorias.
          </div>
        <?php endif; ?>

        <form class="register-form" action="roteador.php?controller=Categoria&action=cadastrar" method="POST">
          <div class="form-grid">
            <label class="full-width">
              Nome da Categoria
              <input type="text" name="nome" placeholder="Digite o nome da categoria" required />
            </label>
          </div>
          <div class="form-actions">
            <button type="submit">Cadastrar Categoria</button>
          </div>
        </form>
      </section>

      <section class="card mt-3">
        <div class="section-title">
          <h2>Categorias Cadastradas</h2>
        </div>
        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($categorias)): ?>
                <?php foreach ($categorias as $categoria): ?>
                  <tr>
                    <td><?= htmlspecialchars($categoria['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($categoria['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="2">Nenhuma categoria cadastrada ainda.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
