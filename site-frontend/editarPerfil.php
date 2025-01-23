<?php
session_start();
require_once 'db_connection.php'; // Arquivo de conexão com o banco de dados

// Email do usuário logado (simulado para fins de exemplo)
$usuario_email = $_SESSION['email'];

// Mensagem de feedback
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $novo_nome = trim($_POST['nome']);
    $nova_senha = trim($_POST['senha']);

    // Valida se os campos foram preenchidos
    if (!empty($novo_nome) && !empty($nova_senha)) {
        // Atualiza o nome e a senha do usuário no banco de dados
        $sql_update = "UPDATE usuario SET nome = ?, senha = ? WHERE email = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("sss", $novo_nome, $nova_senha, $usuario_email);

        if ($stmt->execute()) {
            $mensagem = "Perfil atualizado com sucesso!";
        } else {
            $mensagem = "Erro ao atualizar o perfil: " . $conn->error;
        }
        $stmt->close();
    } else {
        $mensagem = "Por favor, preencha todos os campos.";
    }
}

// Consulta os dados atuais do usuário
$sql_select = "SELECT nome FROM usuario WHERE email = ?";
$stmt = $conn->prepare($sql_select);
$stmt->bind_param("s", $usuario_email);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Menu Superior -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gerenciador de Tarefas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html"><i class="fas fa-tasks"></i> Tarefas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categorias.html"><i class="fas fa-list-alt"></i> Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="estatisticas.html"><i class="fas fa-chart-bar"></i> Estatísticas</a>
                    </li>
                </ul>
                <a href="perfil.html" class="btn btn-outline-secondary"><i class="fas fa-user"></i> Perfil</a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container mt-5">
        <h2>Editar Perfil</h2>

        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-info"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <form action="editar_perfil.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail (não editável)</label>
                <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($usuario_email); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" placeholder="Digite seu nome">
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite uma nova senha">
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="perfil.html" class="btn btn-secondary">Voltar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
