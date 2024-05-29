<?php
// Connexion à la base de données
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_name = $_POST['task_name'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = 'a faire';

    $sql = "INSERT INTO todos (task_name, description, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $task_name, $description, $start_date, $end_date, $status);
    
    if ($stmt->execute()) {
        echo "Nouvelle tâche créée avec succès!";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
