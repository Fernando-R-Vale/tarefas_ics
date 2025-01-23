<?php
session_start();
require 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$emailUsuario = $_SESSION['email'];
$mensagem = '';

// Processa as ações (Concluir, Excluir)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'], $_POST['idtarefa'])) {
    $id = intval($_POST['idtarefa']);
    $acao = $_POST['acao'];

    try {
        if ($acao === 'concluir') {
            $stmt = $conn->prepare("UPDATE tarefa SET status = 'Concluido' WHERE idtarefa = ? AND usuario_email = ?");
            $stmt->bind_param('is', $id, $emailUsuario);
            if ($stmt->execute()) {
                $mensagem = "Tarefa concluída com sucesso!";
            }
        } elseif ($acao === 'excluir') {
            $stmt = $conn->prepare("DELETE FROM tarefa WHERE idtarefa = ? AND usuario_email = ?");
            $stmt->bind_param('is', $id, $emailUsuario);
            if ($stmt->execute()) {
                $mensagem = "Tarefa excluída com sucesso!";
            }
        }
    } catch (Exception $e) {
        $mensagem = "Erro: " . $e->getMessage();
    }
}

// Filtros e ordenação
$search = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';
$sortBy = $_GET['sort'] ?? 'data_criacao';

$query = "SELECT t.idtarefa, t.titulo, t.descricao, t.dhLimite, t.prioridade, c.nomeCategoria AS categoria, t.status 
          FROM tarefa t
          JOIN categoria c ON t.categoria_idCategoria = c.idCategoria
          WHERE t.usuario_email = ?";
$params = [$emailUsuario];
$types = 's';

if (!empty($search)) {
    $query .= " AND t.titulo LIKE ?";
    $params[] = "%$search%";
    $types .= 's';
}

if (!empty($categoryFilter)) {
    $query .= " AND c.nomeCategoria = ?";
    $params[] = $categoryFilter;
    $types .= 's';
}

$query .= " ORDER BY " . ($sortBy === 'data_limite' ? "t.dhLimite" : ($sortBy === 'prioridade' ? "t.prioridade" : "t.idtarefa"));

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$tarefas = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Gerenciador de Tarefas</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="tarefa.php"><i class="fas fa-tasks"></i> Tarefas</a></li>
                <li class="nav-item"><a class="nav-link" href="categoria.php"><i class="fas fa-list-alt"></i> Categorias</a></li>
                <li class="nav-item"><a class="nav-link" href="estatistica.php"><i class="fas fa-chart-bar"></i> Estatísticas</a></li>
            </ul>
            <a href="perfil.php" class="btn btn-outline-secondary"><i class="fas fa-user"></i> Perfil</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Minhas Tarefas</h2>
    
    <form method="get" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="search" class="form-control" name="search" placeholder="Pesquisar tarefas" value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-4">
            <select class="form-select" name="category">
                <option value="">Todas</option>
                <?php
                $stmtCat = $conn->prepare("SELECT nomeCategoria FROM categoria WHERE usuario_email = ?");
                $stmtCat->bind_param('s', $emailUsuario);
                $stmtCat->execute();
                $resultCat = $stmtCat->get_result();
                while ($cat = $resultCat->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($cat['nomeCategoria']) ?>" <?= $categoryFilter === $cat['nomeCategoria'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nomeCategoria']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4">
            <select class="form-select" name="sort">
                <option value="data_criacao" <?= $sortBy === 'data_criacao' ? 'selected' : '' ?>>Data de Criação</option>
                <option value="data_limite" <?= $sortBy === 'data_limite' ? 'selected' : '' ?>>Data Limite</option>
                <option value="prioridade" <?= $sortBy === 'prioridade' ? 'selected' : '' ?>>Prioridade</option>
            </select>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <a href="criarTarefas.php" class="btn btn-success"><i class="fas fa-plus"></i> Criar Nova Tarefa</a>
        </div>
    </form>

    <?php if ($mensagem): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <div class="row">
        <?php while ($tarefa = $tarefas->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="dropdown" style="position: absolute; top: 10px; right: 10px;">
                            <button class="btn btn-sm btn-link" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="editarTarefa.php?id=<?= $tarefa['idtarefa'] ?>">Editar</a></li>
                                <li>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="acao" value="concluir">
                                        <input type="hidden" name="idtarefa" value="<?= $tarefa['idtarefa'] ?>">
                                        <button type="submit" class="dropdown-item">Concluir</button>
                                    </form>
                                </li>
                                <li>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="idtarefa" value="<?= $tarefa['idtarefa'] ?>">
                                        <button type="submit" class="dropdown-item text-danger">Excluir</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <h5 class="card-title"><?= htmlspecialchars($tarefa['titulo']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($tarefa['descricao']) ?></p>
                        <p class="text-muted">Data limite: <?= htmlspecialchars($tarefa['dhLimite']) ?></p>
                        <p class="text-muted">Categoria: <span class="badge bg-info"><?= htmlspecialchars($tarefa['categoria']) ?></span></p>
                        <p class="text-muted">Prioridade: <span class="badge bg-danger"><?= htmlspecialchars($tarefa['prioridade']) ?></span></p>
                        <span class="badge bg-primary"><?= htmlspecialchars($tarefa['status']) ?></span>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
