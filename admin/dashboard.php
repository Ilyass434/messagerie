<?php
require_once '../includes/database.php';
require_once '../includes/auth.php';

requireLogin();

// Récupérer le nombre total d'utilisateurs
$queryUsers = "SELECT COUNT(*) as total_users FROM users";
$stmtUsers = $pdo->prepare($queryUsers);
$stmtUsers->execute();
$rowUsers = $stmtUsers->fetch(PDO::FETCH_ASSOC);
$totalUsers = $rowUsers['total_users'];

// Récupérer le nombre total de messages
$queryMessagesTotal = "SELECT COUNT(*) as total_messages FROM messages";
$stmtMessagesTotal = $pdo->prepare($queryMessagesTotal);
$stmtMessagesTotal->execute();
$rowMessagesTotal = $stmtMessagesTotal->fetch(PDO::FETCH_ASSOC);
$totalMessages = $rowMessagesTotal['total_messages'];


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid mt-5">
    <div class="row vh-100">

        <?php include('../includes/sidebar.php'); ?>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2">
            <div class="container">
                <h2 class="my-4">Bienvenue, <?php echo $_SESSION['username']; ?> !</h2>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <h5 class="card-title">Utilisateurs</h5>
                                <p class="card-text">Total des utilisateurs inscrits</p>
                                <h3 class="card-text"><?php echo $totalUsers; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h5 class="card-title">Messages récents</h5>
                                <p class="card-text">Nombre total de messages</p>
                                <h3 class="card-text"><?php echo $totalMessages; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-12">
                        <h4>Utilisateurs enregistrés</h4>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nom d'utilisateur</th>
                                <th>Password</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            $queryUsersList = "SELECT id, username,password, role, statut FROM users";
                            $stmtUsersList = $pdo->prepare($queryUsersList);
                            $stmtUsersList->execute();
                            while ($user = $stmtUsersList->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($user['password']) . "</td>";
                                echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                                echo "<td>" . htmlspecialchars($user['statut']) . "</td>";
                                echo "<td>
                                        <a href='modifier.php?id=" . htmlspecialchars($user['id']) . "' class='btn btn-warning btn-sm'>Modifier</a>
                                        <a href='supprimer.php?id=" . htmlspecialchars($user['id']) . "' class='btn btn-danger btn-sm'>Supprimer</a>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
