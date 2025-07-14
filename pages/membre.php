<?php
include '../inc/function.php';

session_start();
$id = $_SESSION['id'];

$membre = getMembreById($id);
$objetsParCategorie = getObjetsMembreParCategorie($id);
if (isset($_POST['logout'])) {
    deconnecterUtilisateur();
}

$objets = objet();

?>




<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Membre</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h1 class="mb-4">Fiche Membre</h1>
    <div class="d-flex justify-content-between align-items-center mb-3">
            <form method="post">
                <button type="submit" name="logout" class="btn btn-danger">Déconnexion</button>
            </form>
        </div>

    <!-- Infos du membre -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Informations personnelles</div>
        <div class="card-body">
            <p><strong>Nom :</strong> <?= htmlspecialchars($membre['nom']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($membre['email']) ?></p>
            <p><strong>Ville :</strong> <?= htmlspecialchars($membre['ville']) ?></p>
            <p><strong>Date de naissance :</strong> <?= htmlspecialchars($membre['date_naissance']) ?> </p>
            <p><strong>Genre :</strong> <?= htmlspecialchars($membre['genre']) ?></p>
        </div>
    </div>

    <!-- Objets du membre par catégorie -->
    <h2>Mes objets</h2>

    <?php if (empty($objets_par_categorie)): ?>
        <div class="alert alert-info">Vous n'avez encore ajouté aucun objet.</div>
    <?php else: ?>
        <?php foreach ($objets_par_categorie as $categorie => $objets): ?>
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white"><?= htmlspecialchars($categorie) ?></div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($objets as $obj): ?>
                        <li class="list-group-item"><?= htmlspecialchars($obj['nom_objet']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
