<?php
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$queryGetInvitations = "SELECT friend_requests.id, users.username, friend_requests.sender_id 
                        FROM friend_requests
                        JOIN users ON friend_requests.sender_id = users.id
                        WHERE friend_requests.receiver_id = :receiver_id AND friend_requests.status = 'pending'";
$stmtGetInvitations = $pdo->prepare($queryGetInvitations);
$stmtGetInvitations->execute([':receiver_id' => $user_id]);
$invitations = $stmtGetInvitations->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitations d'amitié</title>
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
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .list-group-item {
            background: #fff;
            border-radius: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .list-group-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .btn-success {
            background-color: #4caf50;
            border-color: #4caf50;
        }

        .btn-success:hover {
            background-color: #43a047;
            border-color: #43a047;
        }

        .btn-danger {
            background-color: #f44336;
            border-color: #f44336;
        }

        .btn-danger:hover {
            background-color: #e53935;
            border-color: #e53935;
        }
    </style>

</head>
<body class="bg-light">

<?php include "../includes/header.php"?>

<div class="container mt-5">
    <h3>Invitations en attente</h3>

    <?php if (empty($invitations)) : ?>
        <p>Aucune invitation en attente.</p>
    <?php else : ?>
        <ul class="list-group">
            <?php foreach ($invitations as $invitation) : ?>
                <li class="list-group-item">
                    <span><?php echo htmlspecialchars($invitation['username']); ?> vous a envoyé une invitation.</span>
                    <form action="process_invitation.php" method="POST" class="d-inline float-end">
                        <input type="hidden" name="sender_id" value="<?php echo $invitation['sender_id']; ?>">
                        <input type="hidden" name="receiver_id" value="<?php echo $user_id; ?>">
                        <button type="submit" name="action" value="accept_invitation" class="btn btn-success btn-sm">Accepter</button>
                        <button type="submit" name="action" value="reject_invitation" class="btn btn-danger btn-sm">Rejeter</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
