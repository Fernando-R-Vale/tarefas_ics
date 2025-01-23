<?php
session_start();
require 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

// Obtém o email do usuário logado
$usuario_email = $_SESSION['email'];

// Consulta para obter as estatísticas
$sql_total_tarefas = "SELECT COUNT(*) AS total FROM tarefa WHERE usuario_email = ?";
$sql_tarefas_concluidas = "SELECT COUNT(*) AS concluidas FROM tarefa WHERE usuario_email = ? AND status = 'Concluido'";
$sql_tarefas_pendentes = "SELECT COUNT(*) AS pendentes FROM tarefa WHERE usuario_email = ? AND status = 'Pendente'";
$sql_tarefas_atrasadas = "SELECT COUNT(*) AS atrasadas FROM tarefa WHERE usuario_email = ? AND dhLimite < NOW() AND status != 'Concluido'";

// Prepara e executa as consultas
$stmt_total = $conn->prepare($sql_total_tarefas);
$stmt_total->bind_param("s", $usuario_email);
$stmt_total->execute();
$total_tarefas = $stmt_total->get_result()->fetch_assoc()['total'];

$stmt_concluidas = $conn->prepare($sql_tarefas_concluidas);
$stmt_concluidas->bind_param("s", $usuario_email);
$stmt_concluidas->execute();
$tarefas_concluidas = $stmt_concluidas->get_result()->fetch_assoc()['concluidas'];

$stmt_pendentes = $conn->prepare($sql_tarefas_pendentes);
$stmt_pendentes->bind_param("s", $usuario_email);
$stmt_pendentes->execute();
$tarefas_pendentes = $stmt_pendentes->get_result()->fetch_assoc()['pendentes'];

$stmt_atrasadas = $conn->prepare($sql_tarefas_atrasadas);
$stmt_atrasadas->bind_param("s", $usuario_email);
$stmt_atrasadas->execute();
$tarefas_atrasadas = $stmt_atrasadas->get_result()->fetch_assoc()['atrasadas'];

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estatísticas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gerenciador de Tarefas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="tarefa.php"><i class="fas fa-tasks"></i> Tarefas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categoria.php"><i class="fas fa-list-alt"></i> Categorias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="estatistica.php"><i class="fas fa-chart-bar"></i> Estatísticas</a>
                    </li>
                </ul>
                <a href="perfil.php" class="btn btn-outline-secondary"><i class="fas fa-user"></i> Perfil</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Estatísticas</h2>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tarefas Pendentes</h5>
                        <p class="card-text display-4"><?php echo $tarefas_pendentes; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tarefas Concluídas</h5>
                        <p class="card-text display-4"><?php echo $tarefas_concluidas; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tarefas Atrasadas</h5>
                        <p class="card-text display-4"><?php echo $tarefas_atrasadas; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total de Tarefas Criadas</h5>
                        <p class="card-text display-4"><?php echo $total_tarefas; ?></p>
                    </div>
                </div>
            </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
