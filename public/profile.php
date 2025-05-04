<?php
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];


$profile_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$profile_id) {
    header('Location: rechercher.php');
    exit();
}


$queryGetUser = "SELECT * FROM users WHERE id = :profile_id";
$stmtGetUser = $pdo->prepare($queryGetUser);
$stmtGetUser->execute([':profile_id' => $profile_id]);
$user = $stmtGetUser->fetch(PDO::FETCH_ASSOC);


if (!$user) {
    echo "Utilisateur introuvable.";
    exit();
}


$queryCheckFriendship = "SELECT * FROM friends WHERE (user_id = :user_id AND friend_id = :profile_id) OR (user_id = :profile_id AND friend_id = :user_id)";
$stmtCheckFriendship = $pdo->prepare($queryCheckFriendship);
$stmtCheckFriendship->execute([':user_id' => $user_id, ':profile_id' => $profile_id]);
$isFriend = $stmtCheckFriendship->fetch(PDO::FETCH_ASSOC);


$queryCheckInvitation = "SELECT * FROM friend_requests WHERE (sender_id = :user_id AND receiver_id = :profile_id AND status = 'pending') OR (sender_id = :profile_id AND receiver_id = :user_id AND status = 'pending')";
$stmtCheckInvitation = $pdo->prepare($queryCheckInvitation);
$stmtCheckInvitation->execute([':user_id' => $user_id, ':profile_id' => $profile_id]);
$invitation = $stmtCheckInvitation->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de <?php echo htmlspecialchars($user['username']); ?></title>
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
            color: white !important;
        }

        .navbar .nav-link {
            font-size: 1rem;
            padding: 10px 15px;
            color: white !important;
            transition: background 0.3s ease;
        }

        .navbar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            color: white !important;
        }

        h3 {
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .container p strong {
            color: #555;
        }

        .btn-primary {
            background-color: #9c27b0;
            border: none;
            border-radius: 10px;
        }

        .btn-primary:hover {
            background-color: #7b1fa2;
        }

        .btn-success.btn-sm {
            background-color: #4caf50;
            border: none;
            border-radius: 10px;
        }

        .btn-success.btn-sm:hover {
            background-color: #388e3c;
        }

        .btn-danger.btn-sm {
            background-color: #f44336;
            border: none;
            border-radius: 10px;
        }

        .btn-danger.btn-sm:hover {
            background-color: #d32f2f;
        }
    </style>

</head>
<body class="bg-light">

<?php include "../includes/header.php"?>

<div class="container mt-5">
    <h3>Profil de <?php echo htmlspecialchars($user['username']); ?></h3>

    <div class="mt-4">
        <p><strong>Nom d'utilisateur:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Crée à:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>

        <?php if ($isFriend) : ?>
            <button class="btn btn-primary">Vous êtes amis</button>

            <!-- Bouton pour supprimer l'ami -->
            <form action="process_remove_friend.php" method="POST" class="d-inline">
                <input type="hidden" name="friend_id" value="<?php echo $user['id']; ?>">
                <button type="submit" name="action" value="remove_friend" class="btn btn-danger btn-sm">Supprimer de mes amis</button>
            </form>
        <?php endif; ?>

        <?php if (!$isFriend && !$invitation) : ?>
            <form action="process_invitation.php" method="POST" class="d-inline">
                <input type="hidden" name="receiver_id" value="<?php echo $user['id']; ?>">
                <button type="submit" name="action" value="send_invitation" class="btn btn-success btn-sm">Envoyer une invitation</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
