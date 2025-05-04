<?php
// Inclure le fichier d'authentification
require_once '../includes/auth.php';

// Détruire la session
session_start();
session_unset();  // Supprimer toutes les variables de session
session_destroy(); // Détruire la session

// Rediriger l'utilisateur vers la page de connexion
header('Location: login.php');
exit();
?>