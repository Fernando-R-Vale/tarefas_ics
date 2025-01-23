<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$emailUsuario = $_SESSION['email'];
$idTarefa = $_GET['id'] ?? null;

if (!$idTarefa) {
    echo "Tarefa não especificada.";
    exit;
}

$queryTarefa = $conn->prepare("SELECT * FROM tarefa WHERE idtarefa = ? AND usuario_email = ?");
$queryTarefa->bind_param("is", $idTarefa, $emailUsuario);
$queryTarefa->execute();
$resultTarefa = $queryTarefa->get_result();

if ($resultTarefa->num_rows === 0) {
    echo "Tarefa não encontrada.";
    exit;
}

$tarefa = $resultTarefa->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Gerenciador de Tarefas</a>
            <a href="index.php" class="btn btn-outline-secondary">Voltar</a>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="mb-4">Editar Tarefa</h2>
        <form action="process_edit_task.php" method="post">
            <input type="hidden" name="idTarefa" value="<?php echo $idTarefa; ?>">
            <div class="mb-3">
                <label for="taskTitle" class="form-label">Título da Tarefa</label>
                <input type="text" class="form-control" id="taskTitle" name="taskTitle" value="<?php echo $tarefa['titulo']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="taskDescription" class="form-label">Descrição</label>
                <textarea class="form-control" id="taskDescription" name="taskDescription" rows="3"><?php echo $tarefa['descricao']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="taskDueDate" class="form-label">Data Limite</label>
                <input type="datetime-local" class="form-control" id="taskDueDate" name="taskDueDate" value="<?php echo date('Y-m-d\TH:i', strtotime($tarefa['dhLimite'])); ?>" required>
            </div>
            <div class="mb-3">
                <label for="taskPriority" class="form-label">Prioridade</label>
                <select class="form-select" id="taskPriority" name="taskPriority">
                    <option value="Baixa" <?php echo ($tarefa['prioridade'] === 'Baixa') ? 'selected' : ''; ?>>Baixa</option>
                    <option value="Média" <?php echo ($tarefa['prioridade'] === 'Média') ? 'selected' : ''; ?>>Média</option>
                    <option value="Alta" <?php echo ($tarefa['prioridade'] === 'Alta') ? 'selected' : ''; ?>>Alta</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="taskCategory" class="form-label">Categoria</label>
                <select class="form-select" id="taskCategory" name="taskCategory">
                    <?php
                    $queryCategorias = $conn->prepare("SELECT idCategoria, nomeCategoria FROM categoria WHERE usuario_email = ?");
                    $queryCategorias->bind_param("s", $emailUsuario);
                    $queryCategorias->execute();
                    $resultCategorias = $queryCategorias->get_result();

                    while ($categoria = $resultCategorias->fetch_assoc()) {
                        $selected = ($tarefa['categoria_idCategoria'] == $categoria['idCategoria']) ? 'selected' : '';
                        echo "<option value='{$categoria['idCategoria']}' $selected>{$categoria['nomeCategoria']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="taskStatus" class="form-label">Status</label>
                <select class="form-select" id="taskStatus" name="taskStatus">
                    <option value="Pendente" <?php echo ($tarefa['status'] === 'Pendente') ? 'selected' : ''; ?>>Pendente</option>
                    <option value="Em andamento" <?php echo ($tarefa['status'] === 'Em andamento') ? 'selected' : ''; ?>>Em andamento</option>
                    <option value="Concluído" <?php echo ($tarefa['status'] === 'Concluido') ? 'selected' : ''; ?>>Concluido</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
