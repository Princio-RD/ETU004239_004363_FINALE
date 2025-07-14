<?php 
include '../inc/function.php';
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="login">
        <div class="container mt-5">
            <form action="traitement.php" method="post" class="card p-4 shadow mx-auto" style="max-width: 500px;">
                <h2 class="text-center mb-4">Inscription</h2>

                <div class="mb-3">
                    <label class="form-label">Entrer votre nom:</label>
                    <input type="text" name="nom" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Entrer votre date de naissance:</label>
                    <input type="date" name="datenaissance" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">E-mail:</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ville:</label>
                    <input type="text" name="ville" value="" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Genre:</label>
                    <select name="genre" class="form-select">
                        <option value="Homme">Homme</option>
                        <option value="Femme">Femme</option>
                        <option value="Autres">Autres</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe:</label>
                    <input type="password" name="mdp" class="form-control">
                </div>

                <div class="d-grid">
                    <input type="submit" value="Inscription" class="btn btn-primary">
                </div>
            </form>

            <p class="text-center mt-3">
                <a href="index.php">Login</a>
            </p>
        </div>
    </div>
</body>
</html>
