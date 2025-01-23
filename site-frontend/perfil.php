<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.html'); 
    exit;
}

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT nome, email FROM usuario WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt = $conn->prepare("
    SELECT 
        COUNT(*) AS total_tarefas,
        SUM(CASE WHEN status = 'Concluido' THEN 1 ELSE 0 END) AS tarefas_concluidas
    FROM tarefa
    WHERE usuario_email = ?
");
$stmt->bind_param('s', $email);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
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
                        <a class="nav-link" href="index.php"><i class="fas fa-tasks"></i> Tarefas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categorias.php"><i class="fas fa-list-alt"></i> Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="estatisticas.php"><i class="fas fa-chart-bar"></i> Estatísticas</a>
                    </li>
                </ul>
                <a href="perfil.php" class="btn btn-outline-secondary"><i class="fas fa-user"></i> Perfil</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Perfil do Usuário</h2>
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($user['nome']); ?></h5>
                <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p class="card-text"><strong>Tarefas Criadas:</strong> <?php echo $stats['total_tarefas'] ?? 0; ?></p>
                <p class="card-text"><strong>Tarefas Concluídas:</strong> <?php echo $stats['tarefas_concluidas'] ?? 0; ?></p>
                <a href="editarPerfil.php" class="btn btn-primary">Editar Perfil</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
