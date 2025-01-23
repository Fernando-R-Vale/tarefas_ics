<?php
session_start();
require 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

// Verifica se o ID da categoria foi passado
if (!isset($_GET['id'])) {
    header('Location: categoria.php');
    exit;
}

$idCategoria = $_GET['id'];

// Verifica se a categoria existe para o usuário logado
$stmt = $conn->prepare("SELECT idCategoria FROM categoria WHERE idCategoria = ? AND usuario_email = ?");
$stmt->bind_param('is', $idCategoria, $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Categoria não encontrada para o usuário
    header('Location: categoria.php');
    exit;
}

// Exclui a categoria
$stmt = $conn->prepare("DELETE FROM categoria WHERE idCategoria = ? AND usuario_email = ?");
$stmt->bind_param('is', $idCategoria, $_SESSION['email']);

if ($stmt->execute()) {
    // Categoria excluída com sucesso
    header('Location: categoria.php');
    exit;
} else {
    // Erro ao excluir a categoria
    echo "Erro ao excluir categoria: " . $stmt->error;
}
