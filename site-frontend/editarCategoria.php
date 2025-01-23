<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: categoria.php');
    exit;
}

$idCategoria = $_GET['id'];
$mensagem = '';

$stmt = $conn->prepare("SELECT idCategoria, nomeCategoria FROM categoria WHERE idCategoria = ? AND usuario_email = ?");
$stmt->bind_param('is', $idCategoria, $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: categoria.php');
    exit;
}

$categoria = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeCategoria = trim($_POST['categoryName'] ?? '');

    if (!empty($nomeCategoria)) {
        try {
            $stmt = $conn->prepare("UPDATE categoria SET nomeCategoria = ? WHERE idCategoria = ? AND usuario_email = ?");
            $stmt->bind_param('sis', $nomeCategoria, $idCategoria, $_SESSION['email']);

            if ($stmt->execute()) {
                $mensagem = "Categoria atualizada com sucesso!";
		header('Location: categoria.php');
		exit;
            } else {
                $mensagem = "Erro ao atualizar categoria: " . $stmt->error;
            }
        } catch (Exception $e) {
            $mensagem = "Erro: " . $e->getMessage();
        }
    } else {
        $mensagem = "O nome da categoria não pode estar vazio.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

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
			<a class="nav-link" href="estatistica.php"><i class="fas fa-chart-bar"></i> Estatísticas</a>
		    </li>
                </ul>
                <a href="perfil.php" class="btn btn-outline-secondary"><i class="fas fa-user"></i> Perfil</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Editar Categoria</h2>

        <?php if ($mensagem): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form class="row g-3 mb-4" method="post">
            <div class="col-md-10">
                <label for="categoryName" class="form-label">Nome da Categoria:</label>
                <input id="categoryName" class="form-control" type="text" name="categoryName" value="<?php echo htmlspecialchars($categoria['nomeCategoria']); ?>" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100" type="submit"><i class="fas fa-save"></i> Atualizar Categoria</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
