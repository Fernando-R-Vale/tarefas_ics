<?php
session_start();
require 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$emailUsuario = $_SESSION['email'];

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Tarefa</title>
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
        <h2 class="mb-4">Criar Nova Tarefa</h2>
        <form action="process_create_task.php" method="post">
            <div class="mb-3">
                <label for="taskTitle" class="form-label">Título da Tarefa</label>
                <input type="text" class="form-control" id="taskTitle" name="taskTitle" placeholder="Informe o título da tarefa" required>
            </div>
            <div class="mb-3">
                <label for="taskDescription" class="form-label">Descrição</label>
                <textarea class="form-control" id="taskDescription" name="taskDescription" rows="3" placeholder="Descreva a tarefa"></textarea>
            </div>
            <div class="mb-3">
                <label for="taskDueDate" class="form-label">Data Limite</label>
                <input type="datetime-local" class="form-control" id="taskDueDate" name="taskDueDate" required>
            </div>
            <div class="mb-3">
                <label for="taskPriority" class="form-label">Prioridade</label>
                <select class="form-select" id="taskPriority" name="taskPriority">
                    <option value="Baixa">Baixa</option>
                    <option value="Média">Média</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="taskCategory" class="form-label">Categoria</label>
                <select class="form-select" id="taskCategory" name="taskCategory">
                    <?php
                    // Obtém as categorias do usuário logado
                    $queryCategorias = $conn->prepare("SELECT idCategoria, nomeCategoria FROM categoria WHERE usuario_email = ?");
                    $queryCategorias->bind_param("s", $emailUsuario);
                    $queryCategorias->execute();
                    $resultCategorias = $queryCategorias->get_result();

                    if ($resultCategorias->num_rows > 0) {
                        while ($categoria = $resultCategorias->fetch_assoc()) {
                            echo "<option value='{$categoria['idCategoria']}'>{$categoria['nomeCategoria']}</option>";
                        }
                    } else {
                        echo "<option disabled>Sem categorias disponíveis</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Criar Tarefa</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
