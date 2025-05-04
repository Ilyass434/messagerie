<?php
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];


if (isset($_GET['friend_id'])) {
    $friend_id = $_GET['friend_id'];

    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :friend_id");
    $stmt->execute(['friend_id' => $friend_id]);
    $friend = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($friend) {
        $friend_username = $friend['username'];
    } else {
        $friend_username = "Utilisateur inconnu";
    }


    $queryMessages = "SELECT messages.id, messages.sender_id, messages.receiver_id, messages.content, messages.sent_at, users.username
                      FROM messages
                      JOIN users ON messages.sender_id = users.id
                      WHERE (messages.sender_id = :user_id AND messages.receiver_id = :friend_id)
                         OR (messages.sender_id = :friend_id AND messages.receiver_id = :user_id)
                      ORDER BY messages.sent_at ASC";

    $stmtMessages = $pdo->prepare($queryMessages);
    $stmtMessages->execute(['user_id' => $user_id, 'friend_id' => $friend_id]);
    $messages = $stmtMessages->fetchAll(PDO::FETCH_ASSOC);
} else {
    die('Aucun ami sélectionné.');
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
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

        .message {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            padding: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .message:hover {
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
    </style>
</head>
<body class="bg-light">

<?php include "../includes/header.php"?>

<div class="container mt-5">

    <h3>Chat avec <?php echo htmlspecialchars($friend_username); ?></h3>

    <div class="messages">
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <strong>
                        <?php echo ($message['sender_id'] == $user_id) ? 'Moi' : htmlspecialchars($message['username']); ?>:
                    </strong>
                    <p><?php echo htmlspecialchars($message['content']); ?></p>
                    <small><?php echo htmlspecialchars(date("d/m/Y H:i", strtotime($message['sent_at']))); ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun message pour l’instant.</p>
        <?php endif; ?>
    </div>

    <form action="send_message.php" method="POST">
        <input type="hidden" name="receiver_id" value="<?php echo $friend_id; ?>">
        <textarea name="content" class="form-control" rows="3" placeholder="Écrire un message..."></textarea>
        <button type="submit" class="btn btn-primary mt-3">Envoyer</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
