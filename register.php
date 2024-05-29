<?php
session_start();
require 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['nom_utilisateur'];
    $password = $_POST['mot_de_passe'];

    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE nom_utilisateur = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe) VALUES (?, ?)');
            $stmt->execute([$username, $hashed_password]);
            $message = 'Enregistrement réussi ! Vous pouvez maintenant vous <a href="index.php">login</a>.';
        } else {
            $message = 'Cet utilisateur existe déjà !';
        }
    } else {
        $message = 'Remplissez tout les champs !';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form action="register.php" method="post">
        Nom d'utilisateur: <input type="text" name="nom_utilisateur" required><br>
        Mot de passe: <input type="password" name="mot_de_passe" required><br>
        <input type="submit" value="Enregistrement">
    </form>
    <p><?= $message ?></p>
</body>
</html>