<?php
session_start();
require_once '../includes/database.php'; // Inclure la connexion à la base de données

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérifier si les champs sont vides
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'Tous les champs doivent être remplis.';
        header('Location: index.php');
        exit();
    }

    try {
        // Rechercher l'utilisateur dans la base de données
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();


        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {

            if ($user['password'] == $password) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['success'] = 'Connexion réussie !';

                // Rediriger vers la page d'accueil ou tableau de bord
                header('Location: dashboarddashboard.php');
                exit();
            } else {
                $_SESSION['error'] = 'Mot de passe incorrect.';
                header('Location: index.php');
                exit();
            }
        } else {
            $_SESSION['error'] = 'Aucun utilisateur trouvé avec ce nom d\'utilisateur.';
            header('Location: index.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erreur de connexion à la base de données.';
        header('Location: index.php');
        exit();
    }
}
?>
