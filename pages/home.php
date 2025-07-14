<?php
include '../inc/function.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['logout'])) {
    deconnecterUtilisateur();
}

$objets = objet();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Partage d'objets</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <form method="post">
                <button type="submit" name="logout" class="btn btn-danger">Déconnexion</button>
            </form>
        </div>

        <h1 class="mb-4 text-center">Liste des objets disponibles</h1>

        <div class="text-center mt-3">
            <a href="filtre.php" class="btn btn-primary">
                <i class="bi bi-funnel"></i> Filtres
            </a>
<<<<<<< HEAD
        </div>     
=======
        </div>    
        
        <div class="text-center mt-3">
            <a href="retourner.php" class="btn btn-primary">
                <i class="bi bi-funnel"></i> Retourner les objets 
            </a>
        </div>   
>>>>>>> 98c2b46 (Premier commit sur main)
        <br>

        <?php if (empty($objets)): ?>
            <div class="alert alert-info">Aucun objet disponible pour le moment.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Statut</th>
                            <th>Date retour</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($objets as $objet): 
                            $emprunt = objet_emprunt($objet['id_objet']);
                            $statut = empty($emprunt) ? 'Disponible' : 'Emprunté';
                            $date_retour = '';

                            if (!empty($emprunt)) {
                                $date_retour = date('d/m/Y', strtotime($emprunt[0]['date_retour']));
                            }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($objet['nom_objet']) ?></td>
                            <td>
                                <span class="badge <?= $statut === 'Disponible' ? 'bg-success' : 'bg-warning' ?>">
                                    <?= $statut ?>
                                </span>
                            </td>
                            <td><?= $date_retour ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
<<<<<<< HEAD
=======

<div class="text-center mt-3">
    <a href="ajout_objet.php" class="btn btn-success me-2">
        <i class="bi bi-plus"></i> Ajouter un objet
    </a>
    <a href="filtre.php" class="btn btn-primary">
        <i class="bi bi-funnel"></i> Filtres
    </a>
</div>

<td>
    <a href="fiche_objet.php?id=<?= $objet['id_objet'] ?>">
        <?= htmlspecialchars($objet['nom_objet']) ?>
    </a>
</td>
>>>>>>> 98c2b46 (Premier commit sur main)
</body>
</html>
