<?php
$servername = '192.0.0.20';
$dbname = 'mydb';
$username = 'server';
$password = 'ifrn';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
