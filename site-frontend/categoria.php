<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$emailUsuario = $_SESSION['email'];
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeCategoria = trim($_POST['categoryName'] ?? '');

    if (!empty($nomeCategoria)) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO categoria (nomeCategoria, usuario_email) 
                VALUES (?, ?)
            ");
            $stmt->bind_param('ss', $nomeCategoria, $emailUsuario);

            if ($stmt->execute()) {
                $mensagem = "Categoria adicionada com sucesso!";
            } else {
                $mensagem = "Erro ao adicionar categoria: " . $stmt->error;
            }
        } catch (Exception $e) {
            $mensagem = "Erro: " . $e->getMessage();
        }
    } else {
        $mensagem = "O nome da categoria não pode estar vazio.";
    }
}

$stmt = $conn->prepare("SELECT idCategoria, nomeCategoria FROM categoria WHERE usuario_email = ?");
$stmt->bind_param('s', $emailUsuario);
$stmt->execute();
$resultCategorias = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Categorias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
                        <a class="nav-link active" href="#"><i class="fas fa-list-alt"></i> Categorias</a>
                    </li>
		    <li>
			<a class="nav-link" href="estatistica.php"><i class="fas fa-chart-bar"></i> Estatísticas</a>
		    </li>
                </ul>
                <a href="perfil.php" class="btn btn-outline-secondary"><i class="fas fa-user"></i> Perfil</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Gerenciamento de Categorias</h2>

        <?php if ($mensagem): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>

        <form class="row g-3 mb-4" method="post">
            <div class="col-md-10">
                <label for="categoryName" class="form-label">Nova Categoria:</label>
                <input id="categoryName" class="form-control" type="text" name="categoryName" placeholder="Digite o nome da nova categoria" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100" type="submit"><i class="fas fa-plus"></i> Adicionar Categoria</button>
            </div>
        </form>

        <div class="row">
            <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                <div class="col-md-3">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <span class="card-title h6"><?php echo htmlspecialchars($categoria['nomeCategoria']); ?></span>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="editarCategoria.php?id=<?php echo $categoria['idCategoria']; ?>">Editar</a></li>
                                    <li><a class="dropdown-item" href="excluirCategoria.php?id=<?php echo $categoria['idCategoria']; ?>" onclick="return confirm('Tem certeza que deseja excluir esta categoria?');">Excluir</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
