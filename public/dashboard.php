<?php
require_once '../src/auth.php';
requireLogin();
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Bem-vindo ao Dashboard</h2>
        <p>Você está logado como: <?= $_SESSION['user_id'] ?></p>
        <a href="teams.php">Gerenciar Times</a>
        <br><br>
        <a href="logout.php">Sair</a>
    </div>
</body>
</html>
