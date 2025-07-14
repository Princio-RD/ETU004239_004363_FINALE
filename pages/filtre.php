<?php
include '../inc/function.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$categories = getCategories();

// Récupération des filtres
$selected_cat = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;
$nom_objet = isset($_GET['nom_objet']) ? trim($_GET['nom_objet']) : '';
$disponible = isset($_GET['disponible']) ? true : false;

// Récupération des objets filtrés
$objets = filtrerObjets($selected_cat, $nom_objet, $disponible);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtre d'objets</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Filtre d'objets</h1>
            <form method="post" action="home.php">
                <button type="submit" name="logout" class="btn btn-danger">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </button>
            </form>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="home.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour à l'accueil
            </a>
            <a href="profil.php" class="btn btn-primary">
                <i class="bi bi-person"></i> Mon profil
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-funnel"></i> Critères de recherche
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="categorie" class="form-label">Catégorie</label>
                        <select class="form-select" id="categorie" name="categorie">
                            <option value="">Toutes catégories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id_categorie'] ?>" <?= $selected_cat == $cat['id_categorie'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nom_categorie']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="nom_objet" class="form-label">Nom de l'objet</label>
                        <input type="text" class="form-control" id="nom_objet" name="nom_objet" 
                               value="<?= htmlspecialchars($nom_objet) ?>" placeholder="Rechercher par nom">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                   id="disponible" name="disponible" <?= $disponible ? 'checked' : '' ?>>
                            <label class="form-check-label" for="disponible">Disponibles seulement</label>
                        </div>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-search"></i> Appliquer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-list"></i> Résultats (<?= count($objets) ?>)
            </div>
            <div class="card-body">
                <?php if (empty($objets)): ?>
                    <div class="alert alert-info">Aucun objet ne correspond à vos critères de recherche.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Propriétaire</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($objets as $objet): 
                                $statut = $objet['statut'];
                                $date_retour = '';
                                
                                if ($statut === 'Emprunté') {
                                    $emprunt = objet_emprunt($objet['id_objet']);
                                    if (!empty($emprunt)) {
                                        $date_retour = date('d/m/Y', strtotime($emprunt[0]['date_retour']));
                                    }
                                }
                            ?>
                            <tr>
                                <td>
                                    <a href="fiche_objet.php?id=<?= $objet['id_objet'] ?>">
                                        <?= htmlspecialchars($objet['nom_objet']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($objet['nom_categorie']) ?></td>
                                <td><?= htmlspecialchars($objet['nom_proprietaire']) ?></td>
                                <td>
                                    <span class="badge rounded-pill <?= $statut === 'Disponible' ? 'bg-success' : 'bg-warning' ?>">
                                        <?= $statut ?>
                                        <?php if ($statut === 'Emprunté'): ?>
                                            <br><small>Retour: <?= $date_retour ?></small>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($statut === 'Disponible' && $_SESSION['id'] != $objet['id_membre']): ?>
                                        <a href="emprunter.php?id=<?= $objet['id_objet'] ?>" class="btn btn-sm btn-primary">Emprunter</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>