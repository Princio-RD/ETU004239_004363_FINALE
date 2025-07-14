<?php
include '../inc/function.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$id_objet = (int)$_GET['id'];
$objet = getObjetDetails($id_objet);
$images = getImagesObjet($id_objet);
$historique = getHistoriqueEmprunts($id_objet);

if (!$objet) {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($objet['nom_objet']) ?></title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <a href="home.php" class="btn btn-secondary mb-3">Retour</a>
        
        <h1><?= htmlspecialchars($objet['nom_objet']) ?></h1>
        <p class="text-muted">Catégorie: <?= htmlspecialchars($objet['nom_categorie']) ?></p>
        <p>Propriétaire: <?= htmlspecialchars($objet['nom_proprietaire']) ?></p>
        
        <?php if (!empty($objet['description'])): ?>
        <div class="mb-4">
            <h3>Description</h3>
            <p><?= nl2br(htmlspecialchars($objet['description'])) ?></p>
        </div>
        <?php endif; ?>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <?php if (!empty($images)): ?>
                    <img src="../uploads/objets/<?= htmlspecialchars($images[0]['nom_image']) ?>" 
                         class="img-fluid rounded mb-3" alt="Image principale">
                <?php else: ?>
                    <img src="../assets/images/default-object.jpg" 
                         class="img-fluid rounded mb-3" alt="Image par défaut">
                <?php endif; ?>
            </div>
            
            <?php if (count($images) > 1): ?>
            <div class="col-md-6">
                <h4>Autres images</h4>
                <div class="row">
                    <?php foreach (array_slice($images, 1) as $image): ?>
                    <div class="col-4 mb-3">
                        <img src="../uploads/objets/<?= htmlspecialchars($image['nom_image']) ?>" 
                             class="img-thumbnail" alt="Image de l'objet">
                        <?php if ($_SESSION['id'] == $objet['id_membre']): ?>
                        <form method="post" action="supprimer_image.php" class="mt-2">
                            <input type="hidden" name="id_image" value="<?= $image['id_image'] ?>">
                            <input type="hidden" name="id_objet" value="<?= $id_objet ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <h3>Historique des emprunts</h3>
        <?php if (!empty($historique)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Emprunteur</th>
                    <th>Date d'emprunt</th>
                    <th>Date de retour</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historique as $emprunt): ?>
                <tr>
                    <td><?= htmlspecialchars($emprunt['nom_emprunteur']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($emprunt['date_emprunt'])) ?></td>
                    <td><?= $emprunt['date_retour'] ? date('d/m/Y H:i', strtotime($emprunt['date_retour'])) : 'Non retourné' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Aucun emprunt enregistré pour cet objet.</p>
        <?php endif; ?>
    </div>
</body>
</html>