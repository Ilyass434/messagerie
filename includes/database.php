<?php
// Récupérer l'URL de la base de données depuis les variables d'environnement de Heroku
$databaseUrl = getenv('DATABASE_URL');

// Parse l'URL de la base de données
$parsedUrl = parse_url($databaseUrl);

// Récupérer les informations de connexion à partir de l'URL
$host = $parsedUrl['host'];
$port = $parsedUrl['port'];
$dbname = ltrim($parsedUrl['path'], '/');
$username = $parsedUrl['user'];
$password = $parsedUrl['pass'];

// Créer la connexion PDO
try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    // Définir les attributs de connexion (optionnel mais recommandé)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
