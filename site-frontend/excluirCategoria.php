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

$stmt = $conn->prepare("SELECT idCategoria FROM categoria WHERE idCategoria = ? AND usuario_email = ?");
$stmt->bind_param('is', $idCategoria, $_SESSION['email']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: categoria.php');
    exit;
}

$stmt = $conn->prepare("DELETE FROM categoria WHERE idCategoria = ? AND usuario_email = ?");
$stmt->bind_param('is', $idCategoria, $_SESSION['email']);

if ($stmt->execute()) {
    header('Location: categoria.php');
    exit;
} else {
    echo "Erro ao excluir categoria: " . $stmt->error;
}
