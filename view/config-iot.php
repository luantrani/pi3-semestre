<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GPI - Configurações IoT</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">GPI</div>
      <nav class="menu">
        <a class="menu-item" href="roteador.php?controller=Home&action=index">Visao Geral</a>
        <a class="menu-item active" href="roteador.php?controller=Sensor&action=index">Configuracoes IoT</a>
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
        <h1>Configurações IoT</h1>
        <div class="header-actions">
        </div>
      </header>

      <section>
        <section class="card">
          <h2>Cadastrar Novo Dispositivo</h2>
          <form class="sensor-form" method="POST" action="roteador.php?controller=Sensor&action=cadastrarSensor">
            <div class="form-col">
              <label>Nome do Sensor *</label>
              <input type="text" name="nomeSensor" placeholder="Ex: Sensor Bebidas Frias" required />

              <label>ID do Sensor (NodeMCU/ESP32) *</label>
              <input type="text" name="idSensor" placeholder="Ex: US-1023" required />

            </div>

            <div class="form-col">
              <label>Corredor *</label>
              <input type="text" name="corredor" placeholder="Ex: A3" required />

              <label>Lado do Corredor *</label>
              <select name="lado" required>
                <option value="Esquerda">Esquerda</option>
                <option value="Direita">Direita</option>
              </select>

              <label>Produto</label>
              <select name="id_produto">
                <option value="">Nenhum produto vinculado</option>
                <?php foreach ($produtos as $produto): ?>
                  <option value="<?= $produto['id'] ?>"><?= $produto['nome'] ?></option>
                <?php endforeach; ?>
              </select>

              <label>Capacidade Máxima (kg) *</label>
              <input type="number" name="capacidadeMaxima" step="0.1" placeholder="Ex: 25.0" required />

              <label>Peso Mínimo para Alerta (kg) *</label>
              <input type="number" name="minimoReposicao" step="0.1" placeholder="Ex: 20.0" required />
            </div>

            <div class="sensor-actions">
              <button type="submit">Salvar Dispositivo</button>
            </div>
          </form>
        </section>

        <section class="card sensor-list-card">
          <h2>Sensores Cadastrados</h2>
          <div class="sensor-table-wrap">
            <table class="sensor-table">
              <thead>
                <tr>
                  <th>ID do Sensor</th>
                  <th>Nome</th>
                  <th>Posicao</th>
                  <th>Categoria Mapeada</th>
                  <th>Peso Unitário</th>
                  <th>Peso Máximo</th>
                  <th>Peso Mínimo</th>
                  <th>Status</th>
                  <th>Acoes</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($sensores)): ?>
                  <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">Nenhum sensor cadastrado. Use o formulário acima para adicionar um novo dispositivo.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($sensores as $sensor): ?>
                    <tr>
                      <td><?= htmlspecialchars($sensor->getId(), ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($sensor->getNome(), ENT_QUOTES, 'UTF-8') ?></td>
                      <td>Corredor <?= htmlspecialchars($sensor->getCorredor(), ENT_QUOTES, 'UTF-8') ?> - Lado <?= htmlspecialchars($sensor->getLado(), ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($sensor->getProduto()->getCategoria()->getNome(), ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($sensor->getProduto()->getPesoUnitario(), ENT_QUOTES, 'UTF-8') ?>kg</td>
                      <td><?= htmlspecialchars($sensor->getCapacidadeMaxima(), ENT_QUOTES, 'UTF-8') ?>kg</td>
                      <td><?= htmlspecialchars($sensor->getMinimoReposicao(), ENT_QUOTES, 'UTF-8') ?>kg</td>
                      <td><?= htmlspecialchars($sensor->getStatus(), ENT_QUOTES, 'UTF-8') ?></td>
                      <td><button>Editar</button> <button>Remover</button></td>
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