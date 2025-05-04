<?php
session_start();
require_once '../includes/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Recherche des utilisateurs
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

$querySearchUsers = "SELECT id, username, created_at FROM users WHERE username LIKE :searchQuery AND id != :user_id AND role != 'admin'";
$stmtSearch = $pdo->prepare($querySearchUsers);
$stmtSearch->execute([':searchQuery' => '%' . $searchQuery . '%', ':user_id' => $user_id]);
$users = $stmtSearch->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher un utilisateur</title>
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

        h3, h4 {
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
        }

        form input.form-control {
            border-radius: 10px;
            border: 1px solid #ddd;
        }

        form button.btn-primary {
            background-color: #9c27b0;
            border: none;
            border-radius: 10px;
        }

        form button.btn-primary:hover {
            background-color: #7b1fa2;
        }

        .user-card {
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .user-card h5 {
            margin-bottom: 10px;
            font-size: 1.2rem;
            color: #555;
        }

        .btn-info.btn-sm {
            background-color: #03a9f4;
            border: none;
        }

        .btn-info.btn-sm:hover {
            background-color: #0288d1;
        }
    </style>

</head>
<body class="bg-light">

<?php include "../includes/header.php"?>

<div class="container mt-5">
    <h3>Rechercher un utilisateur</h3>

    <form action="rechercher.php" method="POST" class="mb-4">
        <input type="text" name="search" class="form-control" placeholder="Rechercher un utilisateur..." value="<?php echo htmlspecialchars($searchQuery); ?>" required>
        <button type="submit" class="btn btn-primary mt-3 w-100">Rechercher</button>
    </form>

    <h4>Résultats de recherche</h4>
    <ul class="list-group">
        <?php foreach ($users as $user) : ?>
            <div class="user-card">
                <a href="profile.php?id=<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></a>
            </div>
        <?php endforeach; ?>

    </ul>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
