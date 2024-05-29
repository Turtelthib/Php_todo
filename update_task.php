<?php
session_start();
require 'config.php';

if (isset($_GET['id'], $_GET['status']) && ($_GET['status'] == 'en cours' || $_GET['status'] == 'fini')) {
    $stmt = $pdo->prepare('UPDATE todos SET status = ? WHERE id = ? AND id_utilisateur = ?');
    $stmt->execute([$_GET['status'], $_GET['id'], $_SESSION['user_id']]);
}

header('Location: dashboard.php');
exit();