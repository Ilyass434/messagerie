<?php
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'block_user') {
        $blocked_user_id = $_POST['blocked_user_id'];

        // Ajouter l'utilisateur dans la table des utilisateurs bloqués
        $queryBlockUser = "INSERT INTO blocked_users (user_id, blocked_user_id) VALUES (:user_id, :blocked_user_id)";
        $stmt = $pdo->prepare($queryBlockUser);
        $stmt->execute([':user_id' => $user_id, ':blocked_user_id' => $blocked_user_id]);

        // Rediriger vers la page de profil
        header("Location: profil.php?id=$blocked_user_id");
        exit();
    }
}
