<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Utilisateur trouvé
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        // Utilisateur non trouvé
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }

    $stmt->close();
    $conn->close();
}
?>
