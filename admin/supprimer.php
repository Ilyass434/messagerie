<?php
require_once '../includes/database.php';
require_once '../includes/auth.php';

requireLogin();

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Supprimer l'utilisateur de la base de données
    $deleteQuery = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($deleteQuery);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: dashboard.php');
        exit();
    } else {
        die("Erreur lors de la suppression de l'utilisateur.");
    }
} else {
    die("ID non spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer l'utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid mt-5">
    <div class="row vh-100">

        <?php include('../includes/sidebar.php'); ?>

        <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2">
            <div class="container">
                <h2>Utilisateur supprimé avec succès</h2>
                <p>L'utilisateur a été supprimé. Vous allez être redirigé vers le tableau de bord.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
