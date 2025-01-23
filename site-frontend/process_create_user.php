<?php

require 'db_connection.php';

$userName = $conn->real_escape_string(trim($_POST['userName']));
$userEmail = $conn->real_escape_string(trim($_POST['userEmail']));
$userPassword = $conn->real_escape_string(trim($_POST['userPassword']));

$sql = "INSERT INTO usuario (nome, email, senha) VALUES ('$userName', '$userEmail', '$userPassword')";

if ($conn->query($sql) === TRUE) {
    header("Location: login.php");
    exit();
} else {
    echo "<div class='alert alert-danger' role='alert'>Erro ao criar perfil: " . $conn->error . "</div>";
}

$conn->close();

?>
