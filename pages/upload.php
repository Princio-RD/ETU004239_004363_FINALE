<?php
include "../inc/function.php";
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../pages/index.php");
    exit();
}

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['photo']['tmp_name'];
    $fileName = $_FILES['photo']['name'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($fileExtension, $allowedExtensions)) {
        $uploadFileDir = '../uploads/profils/';
        if (!file_exists($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }
        
        $destPath = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $membre = getMembreDetails($_SESSION['id']);
            if (!empty($membre['image_profil'])) {
                $oldFilePath = $uploadFileDir . $membre['image_profil'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $bdd = connecterBDD();
            $requete = sprintf(
                "UPDATE membre SET image_profil = '%s' WHERE id_membre = %d",
                mysqli_real_escape_string($bdd, $newFileName),
                (int)$_SESSION['id']
            );
            
            if (mysqli_query($bdd, $requete)) {
                header("Location: profil.php");
                exit();
            }
        }
    }
}

header("Location: profil.php?error=upload");
exit();
?>