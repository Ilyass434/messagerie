<?php
$dsn = "pgsql:host=db.gzhutesetekhnnhjmetz.supabase.co;port=5432;dbname=postgres";
$username = "postgres";  // Nom d'utilisateur par défaut pour PostgreSQL
$password = "Ilyasshayar123";  // Remplace avec ton mot de passe réel

try {
    $pdo = new PDO($dsn, $username, $password);
    echo "Connexion réussie à la base de données !";
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>
