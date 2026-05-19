<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GPI - Cadastro de Produtos</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    .btn-action {
      padding: 4px 8px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 13px;
      font-weight: bold;
      margin-right: 5px;
      display: inline-block;
    }
    .btn-edit {
      background-color: #f0ad4e;
      color: white;
      border: 1px solid #eea236;
    }
    .btn-delete {
      background-color: #d9534f;
      color: white;
      border: 1px solid #d43f3a;
    }
    .btn-edit:hover { background-color: #ec971f; }
    .btn-delete:hover { background-color: #c9302c; }
  </style>
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
        <a class="menu-item active" href="roteador.php?controller=Produto&action=index">Cadastro Produto</a>
        <a class="menu-item" href="roteador.php?controller=RepositorCadastro&action=index">Cadastro Repositor</a>
        <a class="menu-item" href="roteador.php?controller=Categoria&action=index">Cadastro Categoria</a>
      </nav>
      <div class="hub-card">
        <strong>Hub Central</strong>
        <p>Sistema IoT online. <span>4</span> sensores ativos.</p>
      </div>
    </aside>

    <main class="content">
      <header class="header">
        <h1>Cadastro de Produto</h1>
      </header>

      <section class="card">
        <?php if ($status === 'sucesso'): ?>
          <div class="alert-item" style="border-left-color: var(--normal); background: #f4fff4;">
            Operação realizada com sucesso.
          </div>
        <?php elseif ($status === 'erro'): ?>
          <div class="alert-item vazio">
            Erro ao processar a requisição. Verifique os dados e tente novamente.
          </div>
        <?php elseif ($status === 'erro_listar'): ?>
          <div class="alert-item vazio">
            Erro ao carregar os dados do banco de dados.
          </div>
        <?php endif; ?>

        <form class="register-form" action="roteador.php?controller=Produto&action=cadastrar" method="POST">
          <div class="form-grid" style="display: flex; flex-direction: column; gap: 15px;">
            
            <label class="full-width">
              Nome do Produto
              <input type="text" name="nome" placeholder="Digite o nome do produto" required />
            </label>

            <label class="full-width">
              Peso Unitário (kg)
              <input type="number" step="0.001" name="peso_unitario" placeholder="Ex: 0.500" required />
            </label>

            <label class="full-width">
              Categoria
              <!-- O required impede o envio se o valor selecionado for "" -->
              <select name="categoria_id" required style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #ccc; background: white;">
                <option value="" disabled selected>Selecione uma categoria...</option>
                <?php foreach ($categorias as $categoria): ?>
                  <option value="<?= htmlspecialchars($categoria['id'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($categoria['nome'], ENT_QUOTES, 'UTF-8') ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>

          </div>
          <div class="form-actions" style="margin-top: 20px;">
            <button type="submit">Cadastrar Produto</button>
          </div>
        </form>
      </section>

      <section class="card mt-3">
        <div class="section-title">
          <h2>Produtos Cadastrados</h2>
        </div>
        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Peso (kg)</th>
                <th>Categoria</th>
                <th style="width: 150px; text-align: center;">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($produtos)): ?>
                <?php foreach ($produtos as $produto): ?>
                  <tr>
                    <td><?= htmlspecialchars($produto['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($produto['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($produto['peso_unitario'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($produto['categoria_nome'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td style="text-align: center;">
                      <!-- Botões direcionando para o Roteador passando o ID do produto -->
                      <a href="editar-produto.php?id=<?= $produto['id'] ?>" class="btn-action btn-edit">Alterar</a>
                      <a href="roteador.php?controller=Produto&action=excluir&id=<?= $produto['id'] ?>" class="btn-action btn-delete" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5">Nenhum produto cadastrado ainda ou listagem não ativa.</td>
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