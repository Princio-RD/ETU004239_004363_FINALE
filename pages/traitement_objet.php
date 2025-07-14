<?php
include '../inc/function.php';
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../pages/index.php");
    exit();
}

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
            
            $_SESSION['message'] = 'Objet ajouté avec succès!';
            header("Location: fiche_objet.php?id=$id_objet");
            exit();
        }
    } else {
        $_SESSION['message'] = 'Veuillez remplir tous les champs obligatoires';
        }
    }
    
    header("Location: ajout_objet.php");
    exit();