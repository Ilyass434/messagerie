<?php
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];


$queryFriends = "SELECT DISTINCT users.id, users.username FROM friends
                 JOIN users ON (friends.friend_id = users.id OR friends.user_id = users.id)
                 WHERE ((friends.user_id = :user_id AND friends.status = 'accepted')
                        OR (friends.friend_id = :user_id AND friends.status = 'accepted'))
                 AND users.id != :user_id";

$stmtFriends = $pdo->prepare($queryFriends);
$stmtFriends->execute([':user_id' => $user_id]);
$friends = $stmtFriends->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messagerie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f3e5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(90deg, #9c27b0, #e91e63);
            border-radius: 0 0 15px 15px;
        }

        .navbar .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .navbar .nav-link {
            font-size: 1rem;
            padding: 10px 15px;
            transition: background 0.3s ease;
            color: white !important;
        }

        .navbar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            color: white !important;
        }

        .container h3 {
            color: #333;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .list-group-item {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            padding: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .list-group-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background-color: #e91e63;
            border-color: #e91e63;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #d81b60;
            border-color: #d81b60;
        }

        .btn-link {
            color: #333;
            font-weight: bold;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .btn-link:hover {
            color: #d81b60;
        }
    </style>
</head>
<body>

<?php include "../includes/header.php"?>

<div class="container mt-5">
    <h3>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?></h3>

    <div class="list-group">
        <?php foreach ($friends as $friend): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="profile.php?user_id=<?php echo $friend['id']; ?>" class="btn btn-link"><?php echo htmlspecialchars($friend['username']); ?></a>

                <a href="chat.php?friend_id=<?php echo $friend['id']; ?>" class="btn btn-primary btn-sm">Envoyer un message</a>
            </li>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
