<?php
include '../inc/function.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../pages/index.php");
    exit();
}

$categories = getCategories();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_objet = $_POST['nom_objet'] ?? '';
    $id_categorie = $_POST['id_categorie'] ?? 0;
    $description = $_POST['description'] ?? '';
    
    if ($nom_objet && $id_categorie) {
        $id_objet = ajouterObjet($nom_objet, $id_categorie, $_SESSION['id'], $description);
        
        if ($id_objet && !empty($_FILES['images'])) {
            $uploadDir = '../uploads/objets/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $fileName = $_FILES['images']['name'][$key];
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $newFileName = uniqid() . '.' . $fileExt;
                    $destPath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($tmp_name, $destPath)) {
                        ajouterImageObjet($id_objet, $newFileName);
                    }
                }
            }
            
            $message = 'Objet ajouté avec succès!';
        }
    } else {
        $message = 'Veuillez remplir tous les champs obligatoires';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un objet</title>
    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Ajouter un nouvel objet</h1>
        
        <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label class="form-label">Nom de l'objet*</label>
                <input type="text" name="nom_objet" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Catégorie*</label>
                <select name="id_categorie" class="form-select" required>
                    <option value="">Choisir une catégorie</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Images (première image sera l'image principale)</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
            </div>
            
            <button type="submit" class="btn btn-primary">Ajouter l'objet</button>
            <a href="home.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>