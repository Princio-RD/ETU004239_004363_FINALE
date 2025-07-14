<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function connecterBDD() {
    return mysqli_connect("localhost", "root", "", "objet");
}

function deconnecterUtilisateur() {
    session_destroy();
    header("Location: ../pages/index.php");
    exit();
}

function connecterUtilisateur($email, $mdp) {
    $bdd = connecterBDD();
    $email = mysqli_real_escape_string($bdd, $email);
    $mdp = mysqli_real_escape_string($bdd, $mdp);
    
    $requete = "SELECT * FROM membre WHERE email = '$email' AND mdp = '$mdp'";
    $resultat = mysqli_query($bdd, $requete);
    
    if ($donnees = mysqli_fetch_assoc($resultat)) {
        session_start();
        $_SESSION['id'] = $donnees['id_membre']; 
        header("Location: ../pages/home.php");
    } else {
        session_start();
        $_SESSION['erreur'] = "Email ou mot de passe incorrect";
        header("Location: ../pages/index.php");
    }
    exit();
}


function inscrireUtilisateur($nom, $dateNaissance, $genre, $email, $ville, $mdp) {
    $bdd = connecterBDD();

    $requete = sprintf(
        "INSERT INTO membre(nom, date_naissance, genre, email, ville, mdp)
         VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
        $nom, $dateNaissance, $genre, $email, $ville, $mdp
    );

    if (mysqli_query($bdd, $requete)) {
        header("Location: index.php");
    } else {
        header("Location: formulaire.php");
        exit();
    }
}

function objet(){
    $bdd=connecterBDD();
    $requete="SELECT * FROM objet";
    $insertion=mysqli_query($bdd,$requete);
    $r=[];
    while ($donnees=mysqli_fetch_assoc($insertion)){
        $r[]=$donnees;
    }
    return $r;
}

function objet_emprunt($id_objet) {
    $bdd=connecterBDD();
    $requete=sprintf("SELECT * FROM v_objet_date WHERE id_objet='%s'",$id_objet);
    $insertion=mysqli_query($bdd,$requete);
    $d=[];
    while ($donnees=mysqli_fetch_assoc($insertion)){
        $d[]=$donnees;
    }
    return $d;
}

function getCategories() {
    $bdd = connecterBDD();
    $res = mysqli_query($bdd, "SELECT * FROM categorie_objet ORDER BY nom_categorie");
    $cats = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $cats[] = $row;
    }
    return $cats;
}

function getObjetsByCategorie($id_categorie) {
    $bdd = connecterBDD();
    $id_categorie = (int)$id_categorie; 
    $sql = "
        SELECT ob.*, cat.nom_categorie, mem.nom AS nom_proprietaire
        FROM objet ob
        JOIN categorie_objet cat ON ob.id_categorie = cat.id_categorie
        JOIN membre mem ON ob.id_membre = mem.id_membre
        WHERE ob.id_categorie = $id_categorie
        ORDER BY ob.nom_objet
    ";
    $res = mysqli_query($bdd, $sql);
    $objs = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $objs[] = $row;
    }
    return $objs;
}


function ajouterObjet($nom_objet, $id_categorie, $id_membre, $description = '') {
    $bdd = connecterBDD();
    $requete = sprintf(
        "INSERT INTO objet(nom_objet, id_categorie, id_membre, description) 
         VALUES ('%s', %d, %d, '%s')",
        mysqli_real_escape_string($bdd, $nom_objet),
        (int)$id_categorie,
        (int)$id_membre,
        mysqli_real_escape_string($bdd, $description)
    );

    if (mysqli_query($bdd, $requete)) {
        return mysqli_insert_id($bdd);
    }
    return false;
}

function ajouterImageObjet($id_objet, $nom_image) {
    $bdd = connecterBDD();
    $requete = sprintf(
        "INSERT INTO images_objet(id_objet, nom_image) 
         VALUES (%d, '%s')",
        (int)$id_objet,
        mysqli_real_escape_string($bdd, $nom_image)
    );
    return mysqli_query($bdd, $requete);
}

function supprimerImageObjet($id_image) {
    $bdd = connecterBDD();
    $requete = sprintf("DELETE FROM images_objet WHERE id_image = %d", (int)$id_image);
    return mysqli_query($bdd, $requete);
}

function getImagesObjet($id_objet) {
    $bdd = connecterBDD();
    $requete = sprintf("SELECT * FROM images_objet WHERE id_objet = %d", (int)$id_objet);
    $resultat = mysqli_query($bdd, $requete);
    $images = [];
    while ($row = mysqli_fetch_assoc($resultat)) {
        $images[] = $row;
    }
    return $images;
}

function getObjetDetails($id_objet) {
    $bdd = connecterBDD();
    $requete = sprintf(
        "SELECT ob.*, cat.nom_categorie, mem.nom AS nom_proprietaire
         FROM objet ob
         JOIN categorie_objet cat ON ob.id_categorie = cat.id_categorie
         JOIN membre mem ON ob.id_membre = mem.id_membre
         WHERE ob.id_objet = %d",
        (int)$id_objet
    );
    $resultat = mysqli_query($bdd, $requete);
    return mysqli_fetch_assoc($resultat);
}

function getHistoriqueEmprunts($id_objet) {
    $bdd = connecterBDD();
    $requete = sprintf(
        "SELECT em.*, mem.nom AS nom_emprunteur
         FROM emprunt em
         JOIN membre mem ON em.id_membre = mem.id_membre
         WHERE em.id_objet = %d
         ORDER BY em.date_emprunt DESC",
        (int)$id_objet
    );
    $resultat = mysqli_query($bdd, $requete);
    $emprunts = [];
    while ($row = mysqli_fetch_assoc($resultat)) {
        $emprunts[] = $row;
    }
    return $emprunts;
}

function getObjetsMembre($id_membre) {
    $bdd = connecterBDD();
    $requete = sprintf(
        "SELECT ob.*, cat.nom_categorie
         FROM objet ob
         JOIN categorie_objet cat ON ob.id_categorie = cat.id_categorie
         WHERE ob.id_membre = %d
         ORDER BY cat.nom_categorie, ob.nom_objet",
        (int)$id_membre
    );
    $resultat = mysqli_query($bdd, $requete);
    $objets = [];
    while ($row = mysqli_fetch_assoc($resultat)) {
        $objets[] = $row;
    }
    return $objets;
}


function getMembreDetails($id_membre) {
    $bdd = connecterBDD();
    $requete = sprintf("SELECT * FROM membre WHERE id_membre = %d", (int)$id_membre);
    $resultat = mysqli_query($bdd, $requete);
    return mysqli_fetch_assoc($resultat);
}


function filtrerObjets($categorie = null, $nom = '', $dispo = false) {
    $bdd = connecterBDD();
    $conditions = [];

    if ($categorie && is_numeric($categorie)) {
        $conditions[] = "o.id_categorie = " . (int)$categorie;
    }

    if (!empty($nom)) {
        $nom = mysqli_real_escape_string($bdd, $nom);
        $conditions[] = "o.nom_objet LIKE '%$nom%'";
    }

    if ($dispo) {
        $conditions[] = "o.id_objet NOT IN (
            SELECT e.id_objet FROM emprunt e
            WHERE e.date_retour IS NULL OR e.date_retour > NOW()
        )";
    }

    $where = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

    $sql = "
        SELECT o.*, c.nom_categorie, m.nom AS nom_proprietaire,
        CASE 
            WHEN EXISTS (
                SELECT 1 FROM emprunt e 
                WHERE e.id_objet = o.id_objet 
                AND (e.date_retour IS NULL OR e.date_retour > NOW())
            ) THEN 'Emprunté'
            ELSE 'Disponible'
        END AS statut
        FROM objet o
        JOIN categorie_objet c ON o.id_categorie = c.id_categorie
        JOIN membre m ON o.id_membre = m.id_membre
        $where
        ORDER BY o.nom_objet ASC
    ";

    $res = mysqli_query($bdd, $sql);
    $objets = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $objets[] = $row;
    }
    return $objets;
}

function ajout_emprunte($id_objet,$id_personne){
    $bdd=connecterBDD();
    $requete=sprintf("UPDATE objet()");
}


function getMembreById($id) {
    $bdd = connecterBDD();
    $id = (int)$id;
    $sql = "SELECT * FROM membre WHERE id_membre = $id";
    $res = mysqli_query($bdd, $sql);
    return mysqli_fetch_assoc($res);
}

function getObjetsMembreParCategorie($id_membre) {
    $bdd = connecterBDD();
    $id_membre = (int)$id_membre;
    $sql = "
        SELECT o.*, c.nom_categorie
        FROM objet o
        JOIN categorie_objet c ON o.id_categorie = c.id_categorie
        WHERE o.id_membre = $id_membre
        ORDER BY c.nom_categorie, o.nom_objet
    ";

    $res = mysqli_query($bdd, $sql);
    $objets_par_cat = [];

    while ($row = mysqli_fetch_assoc($res)) {
        $cat = $row['nom_categorie'];
        if (!isset($objets_par_cat[$cat])) {
            $objets_par_cat[$cat] = [];
        }
        $objets_par_cat[$cat][] = $row;
    }

    return $objets_par_cat;
}

?>