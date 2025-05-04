<?php
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];


$receiver_id = isset($_POST['receiver_id']) ? $_POST['receiver_id'] : null;
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

if ($receiver_id && !empty($content)) {

    $querySendMessage = "INSERT INTO messages (sender_id, receiver_id, content, sent_at) 
                         VALUES (:sender_id, :receiver_id, :content, NOW())";

    $stmt = $pdo->prepare($querySendMessage);
    $stmt->execute([
        ':sender_id' => $user_id,
        ':receiver_id' => $receiver_id,
        ':content' => $content
    ]);


    header("Location: chat.php?friend_id=$receiver_id");
    exit();
} else {
    echo "Le message ne peut pas être vide.";
}
