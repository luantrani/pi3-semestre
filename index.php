<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GPI - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css" />
</head>
<body class="login-body">
    <div class="login-page">
        <div class="login-card shadow">
            <div class="login-brand">
                <img class="login-logo-img" src="view/logo.jpeg" alt="Logo GPI" />
            </div>
            
            <form class="login-form" action="roteador.php?controller=Usuario&action=login" method="POST">
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Usuário</label>
                    <input type="text" name="login" class="form-control" placeholder="Digite seu usuário" required />
                </div>

                <div class="mb-4 text-start">
                    <label class="form-label fw-bold">Senha</label>
                    <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required />
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>