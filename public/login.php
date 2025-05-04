<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #9c27b0, #e91e63);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            background-color: #fff;
        }

        .card-title {
            font-weight: bold;
            color: #9c27b0;
        }

        .form-label {
            color: #555;
            font-weight: 500;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .btn-primary {
            background-color: #9c27b0;
            border: none;
            border-radius: 10px;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #7b1fa2;
        }

        a {
            color: #9c27b0;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .form-check-label {
            font-size: 0.9rem;
        }
    </style>

</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card" style="width: 30rem;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Se connecter</h3>

            <form action="login_process.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>

            <div class="mt-3 text-center">
                <p>Pas encore de compte? <a href="register.php">S'inscrire</a></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
