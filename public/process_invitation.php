<?php
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Envoi de l'invitation (ajouter dans friend_requests)
        if ($action === 'send_invitation') {
            $receiver_id = $_POST['receiver_id'];

            // Vérifier si l'invitation existe déjà pour éviter doublons
            $checkQuery = "SELECT * FROM friend_requests WHERE sender_id = :sender_id AND receiver_id = :receiver_id";
            $stmtCheck = $pdo->prepare($checkQuery);
            $stmtCheck->execute([':sender_id' => $user_id, ':receiver_id' => $receiver_id]);
            $existingRequest = $stmtCheck->fetch();

            if (!$existingRequest) {
                $querySendInvitation = "INSERT INTO friend_requests (sender_id, receiver_id, status) VALUES (:sender_id, :receiver_id, 'pending')";
                $stmt = $pdo->prepare($querySendInvitation);
                $stmt->execute([':sender_id' => $user_id, ':receiver_id' => $receiver_id]);
                header('Location: rechercher.php');
            } else {
                // Si une demande existe déjà
                header('Location: rechercher.php?error=already_sent');
            }
            exit();
        }


        if ($action === 'accept_invitation') {
            $sender_id = $_POST['sender_id'];
            $queryUpdateRequest = "UPDATE friend_requests SET status = 'accepted' WHERE sender_id = :sender_id AND receiver_id = :receiver_id";
            $stmtUpdate = $pdo->prepare($queryUpdateRequest);
            $stmtUpdate->execute([':sender_id' => $sender_id, ':receiver_id' => $user_id]);

            // Ajouter dans friends
            $queryAddFriend = "INSERT INTO friends (user_id, friend_id, status) VALUES (:user_id, :friend_id, 'accepted')";
            $stmtAddFriend = $pdo->prepare($queryAddFriend);
            $stmtAddFriend->execute([':user_id' => $user_id, ':friend_id' => $sender_id]);
            $stmtAddFriend->execute([':user_id' => $sender_id, ':friend_id' => $user_id]);

            header('Location: invitations.php');
            exit();
        }

        // Rejeter l'invitation
        if ($action === 'reject_invitation') {
            $sender_id = $_POST['sender_id'];
            $queryDeleteRequest = "DELETE FROM friend_requests WHERE sender_id = :sender_id AND receiver_id = :receiver_id";
            $stmtDelete = $pdo->prepare($queryDeleteRequest);
            $stmtDelete->execute([':sender_id' => $sender_id, ':receiver_id' => $user_id]);

            header('Location: invitation.php');
            exit();
        }
    }
}

?>
