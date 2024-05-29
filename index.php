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
        $stmt = $pdo->prepare('SELECT id, mot_de_passe FROM utilisateurs WHERE nom_utilisateur = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $message = 'Login failed';
        }
    } else {
        $message = 'Please fill both fields';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="index.php" method="post">
        Non d'utilisateur: <input type="text" name="nom_utilisateur"><br>
        Mot de passe: <input type="password" name="mot_de_passe"><br>
        <input type="submit" value="Connection">
    </form>
    <p><?= $message ?></p>
    <p>Pas de compte ? <a href="register.php">Créez-en un dès maintenant!</a></p>
</body>
</html>