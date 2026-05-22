<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GPI - Gestão Administrativa</title>
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
        <a class="menu-item" href="roteador.php?controller=Home&action=index">Visão Geral</a>
        <a class="menu-item" href="roteador.php?controller=Sensor&action=index">Configurações IoT</a>
        <a class="menu-item" href="roteador.php?controller=Relatorio&action=index">Relatórios</a>
        <a class="menu-item" href="roteador.php?controller=Repositor&action=index">Área Repositor</a>
        <div class="menu-divider"></div>
        <a class="menu-item active" href="roteador.php?controller=Gestao&action=index">Administração</a>
      </nav>
      <button class="btn btn-danger mt-auto mx-3 mb-3" onclick="location.href='roteador.php?controller=Usuario&action=logout'">Sair</button>
    </aside>

    <main class="content">
      <header class="header"><h1>Gestão Administrativa</h1></header>

      <?php 
        $status_atual = $_GET['status'] ?? $status ?? null; 
        if ($status_atual): 
      ?>
        <div class="alert alert-<?= $status_atual === 'sucesso' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
            <?= $status_atual === 'sucesso' ? 'Operação realizada com sucesso!' : 'Erro ao processar a requisição.' ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <section class="card shadow-sm p-4">
        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
            <li class="nav-item"><button class="nav-link active" id="tab-prod-btn" data-bs-toggle="tab" data-bs-target="#tab-prod">Produtos</button></li>
            <li class="nav-item"><button class="nav-link" id="tab-cat-btn" data-bs-toggle="tab" data-bs-target="#tab-cat">Categorias</button></li>
            <li class="nav-item"><button class="nav-link" id="tab-repo-btn" data-bs-toggle="tab" data-bs-target="#tab-repo">Repositores</button></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-prod">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Produtos</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalProduto">+ Novo</button>
                </div>
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr><th>ID</th><th>Nome</th><th>Categoria</th><th>Ações</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($produtos as $p): ?>
                        <tr>
                            <td>#<?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['nome']) ?></td>
                            <td><span class="badge bg-secondary"><?= $p['categoria_nome'] ?></span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-warning">Editar</button>
                                <a href="roteador.php?controller=Produto&action=excluir&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir?')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="tab-cat">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Categorias</h5>
                    <form action="roteador.php?controller=Categoria&action=cadastrar" method="POST" class="d-flex gap-2">
                        <input type="text" name="nome" class="form-control form-control-sm" placeholder="Nova Categoria" required>
                        <button class="btn btn-success btn-sm">Adicionar</button>
                    </form>
                </div>
                <table class="table table-hover">
                    <thead class="table-light"><tr><th>ID</th><th>Nome</th><th>Ações</th></tr></thead>
                    <tbody>
                        <?php foreach($categorias as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['nome']) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-warning">Editar</button>
                                <a href="roteador.php?controller=Categoria&action=excluir&id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir?')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="tab-repo">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Equipe de Reposição</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalRepo">+ Novo Repositor</button>
                </div>
                <table class="table table-hover">
                    <thead class="table-light"><tr><th>ID</th><th>Nome</th><th>Login</th><th>Ações</th></tr></thead>
                    <tbody>
                        <?php foreach($repositores as $r): ?>
                        <tr>
                            <td><?= $r['id'] ?></td>
                            <td><?= htmlspecialchars($r['nome']) ?></td>
                            <td><code><?= htmlspecialchars($r['login']) ?></code></td>
                            <td>
                                <button class="btn btn-sm btn-outline-warning">Editar</button>
                                <a href="roteador.php?controller=Usuario&action=excluir&id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remover acesso?')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
      </section>
    </main>

    <div class="modal fade" id="modalRepo" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="roteador.php?controller=Usuario&action=cadastrar" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Cadastrar Repositor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="nivel" value="repositor"> 
                    <div class="mb-3">
                        <label class="form-label">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Login/Usuário</label>
                        <input type="text" name="login" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary w-100">Criar Conta</button></div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalProduto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="roteador.php?controller=Produto&action=cadastrar" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Novo Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome do Produto</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Peso Unitário (kg)</label>
                            <input type="number" step="0.001" name="peso_unitario" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoria</label>
                            <select name="categoria_id" class="form-select" required>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= $c['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-primary">Salvar</button></div>
            </form>
        </div>
    </div>

  </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Manter a aba ativa
      const activeTab = localStorage.getItem("activeTab");
      if (activeTab) {
        const tabEl = document.querySelector(`#${activeTab}`);
        if (tabEl) {
          const tab = new bootstrap.Tab(tabEl);
          tab.show();
        }
      }
      
      document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(btn => {
        btn.addEventListener("shown.bs.tab", (e) => {
          localStorage.setItem("activeTab", e.target.id);
        });
      });
    });
  </script>
</body>
</html>