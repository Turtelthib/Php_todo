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
            $message = 'Connection échoué.';
        }
    } else {
        $message = 'Remplissez tous les champs !';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your To-Do App</title>
    <link rel="stylesheet" href="style.css">
    <?php if (isset($_SESSION['dark_mode']) && $_SESSION['dark_mode']): ?>
        <link rel="stylesheet" href="dark_mode.css">
    <?php endif; ?>
</head>
<body>
    <h1>Connection</h1>
    <form action="index.php" method="post">
        Non d'utilisateur: <input type="text" name="nom_utilisateur"><br>
        Mot de passe: <input type="password" name="mot_de_passe"><br>
        <input type="submit" value="Connection">
    </form>
    <p><?= $message ?></p>
    <p>Pas de compte ? <a href="register.php">Créez-en un dès maintenant!</a></p>

    <form action="dark_mode.php" method="POST" style="position: absolute; top: 10px; right: 10px;">
        <input type="hidden" name="dark_mode" value="<?php echo isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] ? 'false' : 'true'; ?>">
        <button type="submit"><?php echo isset($_SESSION['dark_mode']) && $_SESSION['dark_mode'] ? 'Light Mode' : 'Dark Mode'; ?></button>
    </form>
</body>
</html>