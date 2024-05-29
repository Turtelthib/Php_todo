<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$stmt = $pdo->prepare('SELECT id, taches, status FROM todos WHERE id_utilisateur = ? ORDER BY id DESC');
$stmt->execute([$_SESSION['user_id']]);
$tasks = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taches'])) {
    $new_task = $_POST['taches'];
    $stmt = $pdo->prepare('INSERT INTO todos (id_utilisateur, taches, status) VALUES (?, ?, "à faire")');
    $stmt->execute([$_SESSION['user_id'], $new_task]);
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        .btn { padding: 5px; margin-right: 5px; }
        .active { color: black; }
        .inactive { color: grey; }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your To-Do App</title>
    <link rel="stylesheet" href="style.css">
    <?php if (isset($_SESSION['dark_mode']) && $_SESSION['dark_mode']): ?>
        <link rel="stylesheet" href="dark_mode.css">
    <?php endif; ?>
</head>
<body>
    <h1>Vos tâches</h1>
    <form action="dashboard.php" method="post">
        <input type="text" name="taches" required>
        <input type="submit" value="Ajouter">
    </form>
    <ul>
    <?php foreach ($tasks as $task): ?>
        <li>
            <?= htmlspecialchars($task['taches']) ?> - <?= htmlspecialchars($task['status']) ?>
            <a href="update_task.php?id=<?= $task['id'] ?>&status=en cours" class="btn <?= $task['status'] == 'en cours' ? 'active' : 'inactive' ?>">En cours</a>
            <a href="update_task.php?id=<?= $task['id'] ?>&status=fini" class="btn <?= $task['status'] == 'fini' ? 'active' : 'inactive' ?>">Fini</a>
            <a href="delete_task.php?id=<?= $task['id'] ?>" class="btn">Supprimer</a>
        </li>
    <?php endforeach; ?>
    </ul>
    <p><a href="logout.php">Déconnexion</a></p>
    <form action="dark_mode.php" method="POST" style="position: absolute; top: 10px; right: 10px;">
        <input type="hidden" name="dark_mode" value="<?php echo isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] ? 'false' : 'true'; ?>">
        <button type="submit"><?php echo isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] ? 'Light Mode' : 'Dark Mode'; ?></button>
    </form>
</body>
</html>