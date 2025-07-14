<?php
include '../inc/function.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_image'], $_POST['id_objet'])) {
    $id_image = (int)$_POST['id_image'];
    $id_objet = (int)$_POST['id_objet'];
    $objet = getObjetDetails($id_objet);
    if ($objet && $_SESSION['id'] == $objet['id_membre']) {
        $bdd = connecterBDD();
        $requete = sprintf("SELECT nom_image FROM images_objet WHERE id_image = %d", $id_image);
        $resultat = mysqli_query($bdd, $requete);
        $image = mysqli_fetch_assoc($resultat);
        
        if ($image && supprimerImageObjet($id_image)) {
            $filePath = '../uploads/objets/' . $image['nom_image'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
    
    header("Location: fiche_objet.php?id=$id_objet");
    exit();
}

header("Location: home.php");
exit();
?>