<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gerenciador de Tarefas</a>
            <a href="login.php" class="btn btn-outline-secondary">Voltar</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="mb-4">Criar Perfil</h2>
        <form action="process_create_user.php" method="post">
            <div class="mb-3">
                <label for="userName" class="form-label">Nome</label>
                <input type="text" class="form-control" id="userName" name="userName" placeholder="Digite seu nome" required>
            </div>
            <div class="mb-3">
                <label for="userEmail" class="form-label">Email</label>
                <input type="email" class="form-control" id="userEmail" name="userEmail" placeholder="Digite seu email" required>
            </div>
            <div class="mb-3">
                <label for="userPassword" class="form-label">Senha</label>
                <input type="password" class="form-control" id="userPassword" name="userPassword" placeholder="Digite sua senha" required>
            </div>
            <button type="submit" class="btn btn-primary">Criar Perfil</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
