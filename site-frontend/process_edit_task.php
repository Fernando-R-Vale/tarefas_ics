<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$emailUsuario = $_SESSION['email'];
$idTarefa = $_POST['idTarefa'] ?? null;

if (!$idTarefa) {
    echo "Tarefa não especificada.";
    exit;
}

$titulo = $_POST['taskTitle'] ?? '';
$descricao = $_POST['taskDescription'] ?? '';
$dataLimite = $_POST['taskDueDate'] ?? '';
$prioridade = $_POST['taskPriority'] ?? 'Média';
$categoriaId = $_POST['taskCategory'] ?? null;

if (empty($titulo) || empty($dataLimite) || empty($categoriaId)) {
    echo "Por favor, preencha todos os campos obrigatórios.";
    exit;
}

try {
    $stmt = $conn->prepare("
        UPDATE tarefa 
        SET titulo = ?, descricao = ?, dhLimite = ?, prioridade = ?, categoria_idCategoria = ?
        WHERE idtarefa = ? AND usuario_email = ?
    ");
    $stmt->bind_param('sssssis', $titulo, $descricao, $dataLimite, $prioridade, $categoriaId, $idTarefa, $emailUsuario);

    if ($stmt->execute()) {
        header("Location: tarefa.php");
    } else {
        echo "Erro ao atualizar a tarefa: " . $stmt->error;
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
