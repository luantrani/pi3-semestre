<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GPI - Login</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
  <div class="login-page">
    <div class="login-card">
      <div class="login-brand" aria-hidden="true">
        <img class="login-logo-img" src="view/logo.jpeg" alt="Logo GPI" />
      </div>
      <h1>GPI</h1>
      <form class="login-form" action="roteador.php?controller=Usuario&action=login" method="POST">

        <label>Usuário</label>
        <input type="text" name="login" placeholder="Digite seu usuário" required />

        <label>Senha</label>
        <input type="password" name="senha" placeholder="Digite sua senha" required />

        <button type="submit">Entrar</button>
      </form>
    </div>
  </div>
</body>
</html>