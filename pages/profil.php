<?php
include '../inc/function.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$membre = getMembreDetails($_SESSION['id']);
$objets = getObjetsMembre($_SESSION['id']);
$objetsParCategorie = [];

// Grouper les objets par catégorie
foreach ($objets as $objet) {
    $categorie = $objet['nom_categorie'];
    if (!isset($objetsParCategorie[$categorie])) {
        $objetsParCategorie[$categorie] = [];
    }
    $objetsParCategorie[$categorie][] = $objet;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <?php if (!empty($membre['image_profil'])): ?>
                            <img src="../uploads/profils/<?= htmlspecialchars($membre['image_profil']) ?>" 
                            class="rounded-circle mb-3" width="150" height="150">
                        <?php else: ?>
                            <img src="../assets/images/default-profile.jpg" 
                                class="rounded-circle mb-3" width="150" height="150">
                        <?php endif; ?>
                        
                        <h3><?= htmlspecialchars($membre['nom']) ?></h3>
                        <p class="text-muted"><?= htmlspecialchars($membre['email']) ?></p>
                        
                        <form action="upload.php" method="post" enctype="multipart/form-data" class="mb-3">
                            <div class="mb-3">
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary">Changer de photo</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h4>Informations personnelles</h4>
                        <p><strong>Ville:</strong> <?= htmlspecialchars($membre['ville']) ?></p>
                        <p><strong>Date de naissance:</strong> <?= date('d/m/Y', strtotime($membre['date_naissance'])) ?></p>
                        <p><strong>Genre:</strong> <?= htmlspecialchars($membre['genre']) ?></p>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h4>Mes objets</h4>
                        
                        <?php if (empty($objetsParCategorie)): ?>
                            <p>Vous n'avez pas encore ajouté d'objets.</p>
                        <?php else: ?>
                            <?php foreach ($objetsParCategorie as $categorie => $objets): ?>
                                <h5 class="mt-3"><?= htmlspecialchars($categorie) ?></h5>
                                <ul class="list-group">
                                    <?php foreach ($objets as $objet): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <a href="fiche_objet.php?id=<?= $objet['id_objet'] ?>">
                                                <?= htmlspecialchars($objet['nom_objet']) ?>
                                            </a>
                                            <span class="badge bg-<?= empty(objet_emprunt($objet['id_objet'])) ? 'success' : 'warning' ?>">
                                                <?= empty(objet_emprunt($objet['id_objet'])) ? 'Disponible' : 'Emprunté' ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>