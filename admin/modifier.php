<?php
require_once '../includes/database.php';
require_once '../includes/auth.php';

requireLogin();

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Récupérer les informations de l'utilisateur
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Utilisateur non trouvé.");
    }

    // Modifier les informations si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $statut = $_POST['statut'];

        // Hashage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Mettre à jour l'utilisateur
        $updateQuery = "UPDATE users SET username = :username, password = :password, role = :role, statut = :statut WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':username', $username);
        $updateStmt->bindParam(':password', $hashedPassword);
        $updateStmt->bindParam(':role', $role);
        $updateStmt->bindParam(':statut', $statut);
        $updateStmt->bindParam(':id', $userId, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Erreur lors de la mise à jour de l'utilisateur.";
        }
    }
} else {
    die("ID non spécifié.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid mt-5">
    <div class="row vh-100">

        <?php include('../includes/sidebar.php'); ?>

        <div class="col-md-9 col-lg-10 offset-md-3 offset-lg-2">
            <div class="container">
                <h2>Modifier l'utilisateur</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select id="role" name="role" class="form-select">
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Utilisateur</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut</label>
                        <select id="statut" name="statut" class="form-select">
                            <option value="actif" <?= $user['statut'] == 'actif' ? 'selected' : '' ?>>Actif</option>
                            <option value="inactif" <?= $user['statut'] == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
