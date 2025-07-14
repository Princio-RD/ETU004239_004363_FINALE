<?php 
include "../inc/function.php";
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
session_start();

if (isset($_POST['connexion'])) {
    connecterUtilisateur($_POST['connexion'], $_POST['mdp']);
} elseif (isset($_POST['nom'])) {
    inscrireUtilisateur($_POST['nom'], $_POST['datenaissance'], $_POST['genre'], $_POST['email'], $_POST['ville'], $_POST['mdp']);
}
?>
