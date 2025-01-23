<?php

// Incluir a conexão com o banco de dados
require 'db_connection.php';

// Receber dados do formulário
$userName = $conn->real_escape_string(trim($_POST['userName']));
$userEmail = $conn->real_escape_string(trim($_POST['userEmail']));
$userPassword = $conn->real_escape_string(trim($_POST['userPassword'])); // Sem hash para a senha

// Consulta SQL para inserir os dados
$sql = "INSERT INTO usuario (nome, email, senha) VALUES ('$userName', '$userEmail', '$userPassword')";

if ($conn->query($sql) === TRUE) {
    header("Location: login.php"); // Redireciona para a tela de login
    exit();
} else {
    echo "<div class='alert alert-danger' role='alert'>Erro ao criar perfil: " . $conn->error . "</div>";
}

// Fechar conexão
$conn->close();

?>
