<?php
include '../inc/function.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../pages/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['retourner'])) {
    $id_objet = (int)$_POST['id_objet'];
    $etat = $_POST['etat'];
    $id_membre = $_SESSION['id'];

    if (retournerObjet($id_objet, $etat, $id_membre)) {
        $message = "L'objet a été retourné avec succès (état: " . ($etat === 'ok' ? 'OK' : 'Abîmé') . ")";
    } else {
        $message = "Erreur lors du retour de l'objet";
    }
}

$objets = filtrerObjets(null, '', false); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Retourner les objets</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container mt-4">
    <h1 class="mb-4">Retourner les objets</h1>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Liste des objets avec formulaire de retour -->
    <?php if (!empty($objets)): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Statut</th>
                        <th>Retour</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($objets as $objet): 
                        $emprunt = objet_emprunt($objet['id_objet']);
                        $statut = empty($emprunt) ? 'Disponible' : 'Emprunté';
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($objet['nom_objet']) ?></td>
                        <td>
                            <span class="badge <?= $statut === 'Disponible' ? 'bg-success' : 'bg-warning' ?>">
                                <?= $statut ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($statut === 'Emprunté'): ?>
                            <form method="POST" class="d-flex">
                                <input type="hidden" name="id_objet" value="<?= $objet['id_objet'] ?>">
                                <select name="etat" class="form-select me-2" required>
                                    <option value="ok">OK</option>
                                    <option value="abime">Abîmé</option>
                                </select>
                                <button type="submit" name="retourner" class="btn btn-primary">Retourner</button>
                            </form>
                            <?php else: ?>
                            <span class="text-muted"></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">Aucun objet à afficher</div>
    <?php endif; ?>
</div>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>