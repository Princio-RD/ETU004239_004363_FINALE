<?php
include '../inc/function.php';

$categories = getCategories();
<<<<<<< HEAD
$selected_cat = isset($_GET['categorie']) ? $_GET['categorie'] : null;
$objets = $selected_cat ? getObjetsByCategorie($selected_cat) : [];
=======

// Récupération des filtres depuis l'URL avec des valeurs par défaut
$selected_cat = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;
$nom_objet = isset($_GET['nom_objet']) ? trim($_GET['nom_objet']) : '';
$disponible = isset($_GET['disponible']) ? true : false;

// Récupération des objets filtrés
$objets = filtrerObjets($selected_cat, $nom_objet, $disponible);
>>>>>>> 98c2b46 (Premier commit sur main)
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
<<<<<<< HEAD
    <title>Filtre</title>
=======
    <title>Filtrer les objets</title>
>>>>>>> 98c2b46 (Premier commit sur main)
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-4">
<<<<<<< HEAD
    <h1>Objets filtrés par catégorie</h1>

    <form method="GET" class="mb-4">
        <div class="input-group w-50">
            <label class="input-group-text" for="categorie">Catégorie</label>
            <select class="form-select" id="categorie" name="categorie" onchange="this.form.submit()">
                <option value="">Choisir une catégorie </option>
=======
    <h1 class="mb-4">Filtrer les objets</h1>

    <div class="text-center mt-3">
        <a href="membre.php" class="btn btn-primary">
            <i class="bi bi-funnel"></i> Voir les membres
        </a>
    </div>  

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="categorie" class="form-label">Catégorie</label>
            <select class="form-select" id="categorie" name="categorie">
                <option value="">Toutes les catégories</option>
>>>>>>> 98c2b46 (Premier commit sur main)
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['id_categorie']) ?>" <?= ($selected_cat == $cat['id_categorie']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nom_categorie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
<<<<<<< HEAD
    </form>
    <?php if ($selected_cat): ?>
    <?php if (empty($objets)): ?>
        <p>Aucun objet trouvé dans cette catégorie.</p>
    <?php else: ?>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Nom de l'objet</th>
                    <th>Catégorie</th>
=======

        <div class="col-md-4">
            <label for="nom_objet" class="form-label">Nom de l'objet</label>
            <input type="text" class="form-control" id="nom_objet" name="nom_objet" value="<?= htmlspecialchars($nom_objet) ?>">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="disponible" id="disponible" <?= $disponible ? 'checked' : '' ?>>
                <label class="form-check-label" for="disponible">Disponible uniquement</label>
            </div>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
        </div>
    </form>

    <?php if (empty($objets)): ?>
        <div class="alert alert-info">Aucun objet trouvé.</div>
    <?php else: ?>
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Nom de l'objet</th>
                    <th>Catégorie</th>
                    <th>Disponibilité</th>
>>>>>>> 98c2b46 (Premier commit sur main)
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objets as $objet): ?>
<<<<<<< HEAD
                    <tr>
                        <td><?= htmlspecialchars($objet['nom_objet']) ?></td>
                        <td><?= htmlspecialchars($objet['nom_categorie']) ?></td>
=======
                    <?php
                        $emprunt = objet_emprunt($objet['id_objet']);
                        $statut = empty($emprunt) ? 'Disponible' : 'Emprunté';
                        $badge = empty($emprunt) ? 'bg-success' : 'bg-warning';
                        $image = !empty($objet['image_principale']) 
                            ? "../uploads/objets/" . htmlspecialchars($objet['image_principale']) 
                            : "../assets/images/default-object.jpg";
                    ?>
                    <tr>
                        <td style="width: 120px;">
                            <img src="<?= $image ?>" alt="Image de l'objet" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                        </td>
                        <td>
                            <a href="fiche_objet.php?id=<?= $objet['id_objet'] ?>">
                                <?= htmlspecialchars($objet['nom_objet']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($objet['nom_categorie']) ?></td>
                        <td><span class="badge <?= $badge ?>"><?= $statut ?></span></td>
>>>>>>> 98c2b46 (Premier commit sur main)
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
<<<<<<< HEAD
<?php endif; ?>

</div>
=======
</div>

>>>>>>> 98c2b46 (Premier commit sur main)
<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
