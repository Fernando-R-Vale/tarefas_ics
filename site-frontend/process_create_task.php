<?php
session_start();
require_once 'db_connection.php'; 

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$titulo = $_POST['taskTitle'] ?? '';
$descricao = $_POST['taskDescription'] ?? '';
$dataLimite = $_POST['taskDueDate'] ?? '';
$prioridade = $_POST['taskPriority'] ?? 'Média';
$categoriaId = $_POST['taskCategory'] ?? null;
$emailUsuario = $_SESSION['email'];

if (empty($titulo) || empty($dataLimite) || empty($categoriaId)) {
    echo "Por favor, preencha todos os campos obrigatórios.";
    exit;
}

try {
    $stmt = $conn->prepare("
        INSERT INTO tarefa (titulo, descricao, dhLimite, prioridade, status, usuario_email, categoria_idCategoria)
        VALUES (?, ?, ?, ?, 'Pendente', ?, ?)
    ");
    $stmt->bind_param('sssssi', $titulo, $descricao, $dataLimite, $prioridade, $emailUsuario, $categoriaId);

    if ($stmt->execute()) {
        header("Location: tarefa.php?success=1"); 
    } else {
        echo "Erro ao criar tarefa: " . $stmt->error;
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
