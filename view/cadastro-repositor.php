<?php
require_once __DIR__ . '/../DAO/Conexao.php';
require_once __DIR__ . '/../DAO/UsuarioDAO.php';
require_once __DIR__ . '/../model/Usuario.php';

$usuarioDAO = new UsuarioDAO();
$status = $_GET['status'] ?? null;
$repositores = [];

try {
    $repositores = $usuarioDAO->listarPorNivelAcesso('repositor');
} catch (Exception $e) {
    $status = 'erro_listar';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GPI - Cadastro Repositor</title>
  <link rel="stylesheet" href="../style.css" />
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">GPI</div>
      <nav class="menu">
        <a class="menu-item" href="home.html">Visao Geral</a>
        <a class="menu-item" href="config-iot.html">Configuracoes IoT</a>
        <a class="menu-item" href="relatorios.html">Relatorios</a>
        <a class="menu-item" href="repositor.html">Repositor</a>
        <a class="menu-item active" href="cadastro-repositor.php">Cadastro Repositor</a>
        <a class="menu-item" href="cadastro-categoria.php">Cadastro Categoria</a>
      </nav>
      <div class="hub-card">
        <strong>Hub Central</strong>
        <p>Sistema IoT online. <span>4</span> sensores ativos.</p>
      </div>
    </aside>

    <main class="content">
      <header class="header">
        <h1>Cadastro de Repositor</h1>
      </header>

      <section class="card">
        <?php if ($status === 'sucesso'): ?>
          <div class="alert-item" style="border-left-color: var(--normal); background: #f4fff4;">
            Repositor cadastrado com sucesso.
          </div>
        <?php elseif ($status === 'erro'): ?>
          <div class="alert-item vazio">
            Erro ao cadastrar o repositor. Verifique os dados e tente novamente.
          </div>
        <?php elseif ($status === 'erro_listar'): ?>
          <div class="alert-item vazio">
            Erro ao carregar a lista de repositores cadastrados.
          </div>
        <?php endif; ?>

        <form class="register-form" action="../roteador.php?controller=Usuario&action=cadastrar" method="POST">
          <div class="form-grid">
            <label>
              Nome completo
              <input type="text" name="nome" placeholder="Nome do repositor" required />
            </label>
            <label>
              Login
              <input type="text" name="login" placeholder="login do repositor" required />
            </label>
            <label>
              Senha
              <input type="password" name="senha" placeholder="Senha" required />
            </label>
            <input type="hidden" name="nivelAcesso" value="repositor" />
          </div>
          <div class="form-actions">
            <button type="submit">Cadastrar Repositor</button>
          </div>
        </form>
      </section>

      <section class="card mt-3">
        <div class="section-title">
          <h2>Repositores Cadastrados</h2>
        </div>
        <div class="table-wrap">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Login</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($repositores)): ?>
                <?php foreach ($repositores as $usuario): ?>
                  <tr>
                    <td><?= htmlspecialchars($usuario['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($usuario['login'], ENT_QUOTES, 'UTF-8') ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="3">Nenhum repositor cadastrado ainda.</td>
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
