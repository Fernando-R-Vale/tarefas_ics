<?php
require 'db_connection.php';

$queryUsuarios = $conn->query("SELECT DISTINCT usuario_email FROM tarefa");
$usuarios = $queryUsuarios->fetch_all(MYSQLI_ASSOC);

$usuarioSelecionado = $_GET['usuario'] ?? '';
$tarefas = [];

if ($usuarioSelecionado) {

    $queryTarefas = $conn->prepare("SELECT * FROM tarefa WHERE usuario_email = ?");
    $queryTarefas->bind_param("s", $usuarioSelecionado);
    $queryTarefas->execute();
    $resultTarefas = $queryTarefas->get_result();
    $tarefas = $resultTarefas->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="mb-4">Painel Administrativo</h2>
        <form method="get" class="mb-4">
            <label for="usuario" class="form-label">Selecione um usuário:</label>
            <select name="usuario" id="usuario" class="form-select" onchange="this.form.submit()">
                <option value="">-- Escolha um usuário --</option>
                <?php foreach ($usuarios as $usuario) : ?>
                    <option value="<?php echo $usuario['usuario_email']; ?>" <?php echo ($usuarioSelecionado == $usuario['usuario_email']) ? 'selected' : ''; ?>>
                        <?php echo $usuario['usuario_email']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        
        <?php if ($usuarioSelecionado) : ?>
            <h4>Tarefas de <?php echo $usuarioSelecionado; ?>:</h4>
            <ul class="list-group">
                <?php foreach ($tarefas as $tarefa) : ?>
                    <li class="list-group-item">
                        <strong><?php echo $tarefa['titulo']; ?></strong><br>
                        <?php echo $tarefa['descricao']; ?><br>
                        <small>Status: <?php echo $tarefa['status']; ?></small>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($tarefas)) : ?>
                    <li class="list-group-item">Nenhuma tarefa encontrada.</li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
