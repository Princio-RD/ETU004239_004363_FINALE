<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
function connecterBDD() {
<<<<<<< HEAD
    return mysqli_connect("localhost", "ETU004239","aITyEZcy", "db_s2_ETU004239");
=======
    return mysqli_connect("localhost", "root","", "objet");
>>>>>>> 98c2b46 (Premier commit sur main)
}
function deconnecterUtilisateur() {
    session_destroy();
    header("Location:../pages/index.php");
}
/* connexion */
function connecterUtilisateur($email, $mdp) {
    $bdd = connecterBDD();
    $requeteVerif = sprintf("SELECT email FROM membre WHERE email = '%s'", $email);
    $resultatVerif = mysqli_query($bdd, $requeteVerif);
    
    $requete = sprintf("SELECT * FROM membre WHERE email = '%s' AND mdp = '%s';", $email, $mdp);
    $resultat = mysqli_query($bdd, $requete);
    
    if ($donnees = mysqli_fetch_assoc($resultat)) {
        session_start();
        $_SESSION['id'] = $donnees['id_membre']; 
        header("Location:../pages/home.php");
    } else {
        session_start();
        $_SESSION['erreur'] = "erreur";
        header("Location: ../pages/index.php");
    }
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
<<<<<<< HEAD

=======
function filtrerObjets($categorie = null, $nom = '', $dispo = false) {
    $bdd = connecterBDD();
    $conditions = [];

    if ($categorie && is_numeric($categorie)) {
        $conditions[] = "o.id_categorie = " . (int)$categorie;
    }

    if (!empty($nom)) {
        $nom = mysqli_real_escape_string($bdd, $nom);
        $conditions[] = "o.nom_objet LIKE '%" . $nom . "%'";
    }

    if ($dispo) {
        $conditions[] = "o.id_objet NOT IN (
            SELECT e.id_objet FROM emprunt e
            WHERE e.date_emprunt <= NOW()
            AND (e.date_retour IS NULL OR e.date_retour > NOW())
        )";
    }

    $where = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

    $sql = "
        SELECT o.*, c.nom_categorie 
        FROM objet o
        JOIN categorie_objet c ON o.id_categorie = c.id_categorie
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

function getMembreById($id) {
    $bdd = connecterBDD();
    $sql = "SELECT * FROM membre WHERE id_membre = " . (int)$id;
    $res = mysqli_query($bdd, $sql);
    return mysqli_fetch_assoc($res);
}

function getObjetsMembreParCategorie($id_membre) {
    $bdd = connecterBDD();
    $sql = "
        SELECT o.nom_objet, c.nom_categorie
        FROM objet o
        JOIN categorie_objet c ON o.id_categorie = c.id_categorie
        WHERE o.id_membre = " . (int)$id_membre . "
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

function rechercherObjets($categorie = null, $nom = null, $disponible = false) {
    $bdd = connecterBDD();
    $conditions = [];
    if ($categorie) {
        $conditions[] = sprintf("ob.id_categorie = %d", (int)$categorie);
    }
    if ($nom) {
        $conditions[] = sprintf("ob.nom_objet LIKE '%%%s%%'", mysqli_real_escape_string($bdd, $nom));
    }
    $where = empty($conditions) ? '' : 'WHERE ' . implode(' AND ', $conditions);
    
    $requete = "
        SELECT ob.*, cat.nom_categorie, mem.nom AS nom_proprietaire,
               (SELECT nom_image FROM images_objet WHERE id_objet = ob.id_objet LIMIT 1) AS image_principale,
               CASE 
                   WHEN ob.id_objet IN (SELECT id_objet FROM emprunt WHERE date_retour > NOW()) THEN 0
                   ELSE 1
               END AS est_disponible
        FROM objet ob
        JOIN categorie_objet cat ON ob.id_categorie = cat.id_categorie
        JOIN membre mem ON ob.id_membre = mem.id_membre
        $where
        ORDER BY ob.nom_objet
    ";
    
    $resultat = mysqli_query($bdd, $requete);
    $objets = [];
    while ($row = mysqli_fetch_assoc($resultat)) {
        $objets[] = $row;
    }
    
    if ($disponible) {
        $objets = array_filter($objets, function($objet) {
            return $objet['est_disponible'] == 1;
        });
    }
    
    return $objets;
}

function getMembreDetails($id_membre) {
    $bdd = connecterBDD();
    $requete = sprintf("SELECT * FROM membre WHERE id_membre = %d", (int)$id_membre);
    $resultat = mysqli_query($bdd, $requete);
    return mysqli_fetch_assoc($resultat);
}

function retournerObjet($id_objet, $etat, $id_membre) {
    $bdd = connecterBDD();
    
    $requete = sprintf("SELECT id_membre FROM objet WHERE id_objet = %d", (int)$id_objet);
    $resultat = mysqli_query($bdd, $requete);
    $objet = mysqli_fetch_assoc($resultat);
    
    if (!$objet || $objet['id_membre'] != $id_membre) {
        return false;
    }
    
    $requete = sprintf(
        "UPDATE emprunt SET date_retour = NOW(), etat = '%s' 
         WHERE id_objet = %d AND date_retour IS NULL",
        mysqli_real_escape_string($bdd, $etat),
        (int)$id_objet
    );
    
    return mysqli_query($bdd, $requete);
}
>>>>>>> 98c2b46 (Premier commit sur main)
?>