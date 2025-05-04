<?php
require_once '../includes/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];


    $queryCheckUsername = "SELECT COUNT(*) FROM users WHERE username = :username";
    $stmtCheckUsername = $pdo->prepare($queryCheckUsername);
    $stmtCheckUsername->bindParam(':username', $username);
    $stmtCheckUsername->execute();
    $usernameTaken = $stmtCheckUsername->fetchColumn();

    if ($usernameTaken > 0) {
        echo "Ce nom d'utilisateur est déjà pris. Essayez un autre.";
        exit;
    }


    if ($password !== $confirmPassword) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }


    $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) {
        echo "Inscription réussie !";
        header("Location: login.php");
        exit;
    } else {
        echo "Erreur lors de l'inscription.";
    }
}
?>
