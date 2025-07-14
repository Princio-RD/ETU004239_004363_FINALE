-- Table membre
CREATE OR REPLACE DATABASE objet;
USE objet;

CREATE TABLE membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    genre ENUM('Homme', 'Femme', 'Autre') NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    ville VARCHAR(100) NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    image_profil VARCHAR(255)
);

-- Table categorie_objet
CREATE TABLE categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL UNIQUE
);


-- Table objet
CREATE TABLE objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100) NOT NULL,
    id_categorie INT NOT NULL,
    id_membre INT DEFAULT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

-- Table emprunt
CREATE TABLE emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    id_membre INT NOT NULL,
    date_emprunt DATETIME NOT NULL,
    date_retour DATETIME,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES membre(id_membre)
);

-- Table images_objet
CREATE TABLE images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    nom_image VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_objet) REFERENCES objet(id_objet) ON DELETE CASCADE
);




-- Insertion des membres
INSERT INTO membre (nom, date_naissance, genre, email, ville, mdp) VALUES
('Jean Dupont', '1985-03-15', 'Homme', 'jean.dupont@email.com', 'Paris', 'mdp123'),
('Marie Martin', '1990-07-22', 'Femme', 'marie.martin@email.com', 'Lyon', 'mdp456'),
('Pierre Lambert', '1982-11-30', 'Homme', 'pierre.lambert@email.com', 'Marseille', 'mdp789'),
('Sophie Leroy', '1995-05-18', 'Femme', 'sophie.leroy@email.com', 'Toulouse', 'mdp101');

-- Insertion des catégories
INSERT INTO categorie_objet (nom_categorie) VALUES
('Esthétique'),
('Bricolage'),
('Mécanique'),
('Cuisine');

-- Membre 1 (Jean Dupont)
INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Sèche-cheveux professionnel', 1, 2),  -- Emprunté par Marie (id 2) -> objet 1
('Lisseur cheveux', 1, NULL),
('Perceuse visseuse', 2, NULL),
('Scie circulaire', 2, NULL),
('Cric hydraulique', 3, 4),             -- Emprunté par Sophie (id 4) -> objet 5
('Valise à outils', 3, NULL),
('Robot multifonction', 4, NULL),
('Machine à pain', 4, NULL),
('Ponceuse électrique', 2, NULL),
('Fer à lisser', 1, NULL);

-- Membre 2 (Marie Martin)
INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Kit maquillage professionnel', 1, 1), -- Emprunté par Jean (id 1) -> objet 12
('Ponceuse à bande', 2, NULL),
('Trousse à outils', 2, NULL),
('Compresseur d\'air', 3, NULL),
('Caisse à outils', 3, NULL),
('Mixeur plongeant', 4, NULL),
('Grille-pain', 4, NULL),
('Épilateur électrique', 1, NULL),
('Pistolets à peinture', 2, NULL),
('Machine à café', 4, 3);               -- Emprunté par Pierre (id 3) -> objet 18

-- Membre 3 (Pierre Lambert)
INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Tondeuse à barbe', 1, 4),             -- Emprunté par Sophie (id 4) -> objet 22
('Défroisseur vapeur', 1, NULL),
('Scie sauteuse', 2, NULL),
('Perforateur burineur', 2, NULL),
('Démonte-pneus', 3, NULL),
('Chargeur de batterie', 3, NULL),
('Raclette électrique', 4, NULL),
('Appareil à fondue', 4, 2),            -- Emprunté par Marie (id 2) -> objet 27
('Pulvérisateur à peinture', 2, NULL),
('Balance de cuisine', 4, NULL);

-- Membre 4 (Sophie Leroy)
INSERT INTO objet (nom_objet, id_categorie, id_membre) VALUES
('Lampe à lumière pulsée', 1, 1),       -- Emprunté par Jean (id 1) -> objet 31
('Appareil de massage', 1, NULL),
('Meuleuse d\'angle', 2, NULL),
('Niveau laser', 2, NULL),
('Oscilloscope', 3, NULL),
('Démarreur de voiture', 3, NULL),
('Machine à glaçons', 4, 3),            -- Emprunté par Pierre (id 3) -> objet 35
('Sorbetière', 4, 2),                   -- Emprunté par Marie (id 2) -> objet 38
('Aspirateur de chantier', 2, NULL),
('Pèse-personne connecté', 1, 1);       -- Emprunté par Jean (id 1) -> objet 40



-- Insertion des emprunts (10 au total)
INSERT INTO emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2023-01-10 14:00:00', '2023-01-17 14:00:00'),  -- Marie emprunte sèche-cheveux
(5, 4, '2023-01-12 10:00:00', '2023-01-19 10:00:00'),  -- Sophie emprunte cric hydraulique
(12, 1, '2023-02-05 16:00:00', '2023-02-12 16:00:00'), -- Jean emprunte kit maquillage
(18, 3, '2023-02-15 11:00:00', '2023-02-22 11:00:00'), -- Pierre emprunte machine à café
(22, 4, '2023-03-01 09:00:00', '2023-03-08 09:00:00'), -- Sophie emprunte tondeuse à barbe
(27, 2, '2023-03-10 15:00:00', '2023-03-17 15:00:00'), -- Marie emprunte appareil à fondue
(31, 1, '2023-04-05 13:00:00', '2023-04-12 13:00:00'), -- Jean emprunte lampe lumière pulsée
(35, 3, '2023-04-20 17:00:00', '2023-04-27 17:00:00'), -- Pierre emprunte machine à glaçons
(38, 2, '2023-05-08 10:00:00', '2023-05-15 10:00:00'), -- Marie emprunte sorbetière
(40, 1, '2023-05-25 14:00:00', '2023-06-01 14:00:00'); -- Jean emprunte pèse-personne

SELECT  FROM objet ob JOIN emprunt em ON ob.id_objet=em.id_objet;

CREATE OR REPLACE VIEW v_objet_emprunt AS
SELECT 
    ob.id_objet,
    ob.nom_objet,
    ob.id_categorie,
    em.id_emprunt,
    em.id_membre AS id_emprunteur,
    em.date_emprunt,
    em.date_retour,
    DATEDIFF(em.date_retour, em.date_emprunt) AS duree_emprunt_jours
FROM 
    objet ob 
JOIN 
    emprunt em ON ob.id_objet = em.id_objet;

SELECT * FROM Objet ;

CREATE OR REPLACE VIEW v_objet_date AS 
SELECT 
    vo.id_objet,
    vo.date_emprunt,
    vo.date_retour 
FROM v_objet_emprunt vo;

SELECT * FROM objet ob JOIN categorie_objet ca ON ob.id_categorie=ca.id_categorie;


SELECT ob.*, cat.nom_categorie, mem.nom AS nom_proprietaire
    FROM objet ob
    JOIN categorie_objet cat ON ob.id_categorie = cat.id_categorie
    JOIN membre mem ON ob.id_membre = mem.id_membre