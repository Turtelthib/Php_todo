<?php
    session_start();
    require 'config.php';

    if (isset($_GET['id'])) {
        $stmt = $pdo->prepare('DELETE FROM todos WHERE id = ? AND id_utilisateur = ?');
        $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    }
    header('Location: dashboard.php');
    exit();