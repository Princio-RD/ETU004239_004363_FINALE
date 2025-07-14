<?php 
include "../inc/function.php";
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
session_start();
if (isset($_SESSION['erreur'])) {
    echo '<div class="alert alert-danger text-center">'.$_SESSION['erreur'].'</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Connexion</h1>
                        <form action="traitement.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="connexion" required>
                            </div>
                            <div class="mb-3">
                                <label for="mdp" class="form-label">Mot de passe</label>
                                <input type="password" class="form-control" id="mdp" name="mdp" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Valider</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <a href="formulaire.php">Créer un compte</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</body>
</html>
