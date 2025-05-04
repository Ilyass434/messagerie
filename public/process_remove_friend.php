<?php
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Vérifier si le bouton a été cliqué
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove_friend') {
    // Récupérer l'ID de l'ami à supprimer
    $friend_id = isset($_POST['friend_id']) ? $_POST['friend_id'] : null;

    if ($friend_id) {
        // Supprimer l'ami de la table `friends`
        $queryRemoveFriend = "DELETE FROM friends WHERE (user_id = :user_id AND friend_id = :friend_id) OR (user_id = :friend_id AND friend_id = :user_id)";
        $stmtRemoveFriend = $pdo->prepare($queryRemoveFriend);
        $stmtRemoveFriend->execute([':user_id' => $user_id, ':friend_id' => $friend_id]);

        // Rediriger vers la page de recherche après la suppression
        header('Location: rechercher.php');
        exit();
    }
}
?>
