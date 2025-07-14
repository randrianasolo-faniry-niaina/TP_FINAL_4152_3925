use emprunt;

CREATE TABLE IF NOT EXISTS emp_membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    date_de_naissance DATE NOT NULL,
    genre CHAR,
    ville VARCHAR(100) NOT NULL,
    mdp TEXT NOT NULL,
    image_profil TEXT
);

Create Table if not exists emp_categorie_objet(
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(255) NOT NULL
);
Create Table if not exists emp_objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet TEXT NOT NULL,
    id_categorie INT NOT NULL,
    id_membre INT NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES emp_categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES emp_membre(id_membre)
);

CREATE TABLE IF NOT EXISTS emp_image(
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    nom_image TEXT NOT NULL,
    FOREIGN KEY (id_objet) REFERENCES emp_objet(id_objet)   
);

CREATE TABLE IF NOT EXISTS emp_emprunt(
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    id_membre INT NOT NULL,
    date_emprunt DATE NOT NULL,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES emp_objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES emp_membre(id_membre)
);


CREATE or REPLACE view v_emp_objet_image_categorie_membre as
SELECT o.id_objet id_objet,
       o.nom_objet nom_objet,
       c.nom_categorie nom_categorie,
        o.id_membre id_membre,
        m.nom nom_proprio,
       m.email email_membre,
       m.date_de_naissance date_de_naissance_membre,
        m.genre genre_membre,
       m.ville ville_membre,
       i.nom_image nom_image
FROM emp_objet o
JOIN emp_image i ON o.id_objet = i.id_objet
JOIN emp_categorie_objet c ON o.id_categorie = c.id_categorie
JOIN emp_membre m ON o.id_membre = m.id_membre;

-- CREATE or REPLACE view v_emp_objet_membre as
-- SELECT o.id_objet id_objet,
--        o.nom_objet nom_objet,
--        o.id_membre id_membre,
--        m.nom nom_membre,
--        m.email email_membre,
--        m.date_de_naissance date_de_naissance_membre,
--         m.genre genre_membre,
--        m.ville ville_membre
-- FROM emp_objet o
-- JOIN emp_membre m ON o.id_membre = m.id_membre;

CREATE or replace view v_emp_emprunt_objet_membre as
SELECT e.id_emprunt id_emprunt,
       e.date_emprunt date_emprunt,
       e.date_retour date_retour,
       o.id_objet id_objet,
       o.nom_objet nom_objet,
        o.nom_image nom_image,
        o.nom_categorie nom_categorie,
        o.nom_proprio nom_proprio,
       m.id_membre id_membre,
       m.nom nom_emprunteur,
       m.email email_membre,
       m.date_de_naissance date_de_naissance_membre,
       m.genre genre_membre,
       m.ville ville_membre
FROM emp_emprunt e
JOIN v_emp_objet_image_categorie_membre o ON e.id_objet = o.id_objet
JOIN emp_membre m ON e.id_membre = m.id_membre;


-- ...existing code...

-- Insertion des membres
INSERT INTO emp_membre (nom, email, date_de_naissance, genre, ville, mdp, image_profil) VALUES
('Jean Dupont', 'jean.dupont@email.com', '1985-03-15', 'M', 'Paris', 'password123', 'jean.jpg'),
('Marie Martin', 'marie.martin@email.com', '1990-07-22', 'F', 'Lyon', 'password456', 'marie.jpg'),
('Pierre Durand', 'pierre.durand@email.com', '1988-11-08', 'M', 'Marseille', 'password789', 'pierre.jpg'),
('Sophie Lemoine', 'sophie.lemoine@email.com', '1992-05-12', 'F', 'Toulouse', 'password321', 'sophie.jpg');

-- Insertion des catégories
INSERT INTO emp_categorie_objet (nom_categorie) VALUES
('esthétique'),
('bricolage'),
('mécanique'),
('cuisine');

-- Insertion des objets (10 par membre)
-- Objets de Jean Dupont (id_membre = 1)
INSERT INTO emp_objet (nom_objet, id_categorie, id_membre) VALUES
('Séchoir à cheveux', 1, 1),
('Fer à lisser', 1, 1),
('Perceuse électrique', 2, 1),
('Scie circulaire', 2, 1),
('Clé à molette', 3, 1),
('Tournevis électrique', 3, 1),
('Mixeur plongeant', 4, 1),
('Robot pâtissier', 4, 1),
('Tondeuse à barbe', 1, 1),
('Marteau', 2, 1);

-- Objets de Marie Martin (id_membre = 2)
INSERT INTO emp_objet (nom_objet, id_categorie, id_membre) VALUES
('Miroir de maquillage', 1, 2),
('Brosse chauffante', 1, 2),
('Niveau à bulle', 2, 2),
('Ponceuse', 2, 2),
('Cric hydraulique', 3, 2),
('Compresseur d\'air', 3, 2),
('Blender', 4, 2),
('Centrifugeuse', 4, 2),
('Épilateur', 1, 2),
('Visseuse', 2, 2);

-- Objets de Pierre Durand (id_membre = 3)
INSERT INTO emp_objet (nom_objet, id_categorie, id_membre) VALUES
('Défrisoir', 1, 3),
('Brosse nettoyante visage', 1, 3),
('Meuleuse', 2, 3),
('Défonceuse', 2, 3),
('Cliquet', 3, 3),
('Clé dynamométrique', 3, 3),
('Multicuiseur', 4, 3),
('Machine à café', 4, 3),
('Lisseur barbe', 1, 3),
('Serre-joints', 2, 3);

-- Objets de Sophie Lemoine (id_membre = 4)
INSERT INTO emp_objet (nom_objet, id_categorie, id_membre) VALUES
('Boucleur automatique', 1, 4),
('Nettoyeur de pores', 1, 4),
('Cloueuse', 2, 4),
('Scie sauteuse', 2, 4),
('Pince multiprise', 3, 4),
('Extracteur de roulement', 3, 4),
('Friteuse', 4, 4),
('Yaourtière', 4, 4),
('Rasoir électrique', 1, 4),
('Lime électrique', 2, 4);

-- Insertion des images pour chaque objet

INSERT INTO emp_image (id_objet, nom_image) VALUES
(1, 'c.png'), (2, 'c.png'), (3, 'c.png'), (4, 'c.png'), (5, 'c.png'),
(6, 'c.png'), (7, 'c.png'), (8, 'c.png'), (9, 'c.png'), (10, 'c.png'),
(11, 'c.png'), (12, 'c.png'), (13, 'c.png'), (14, 'c.png'), (15, 'c.png'),
(16, 'c.png'), (17, 'c.png'), (18, 'c.png'), (19, 'c.png'), (20, 'c.png'),
(21, 'c.png'), (22, 'c.png'), (23, 'c.png'), (24, 'c.png'), (25, 'c.png'),
(26, 'c.png'), (27, 'c.png'), (28, 'c.png'), (29, 'c.png'), (30, 'c.png'),
(31, 'c.png'), (32, 'c.png'), (33, 'c.png'), (34, 'c.png'), (35, 'c.png'),
(36, 'c.png'), (37, 'c.png'), (38, 'c.png'), (39, 'c.png'), (40, 'c.png');
-- Insertion des emprunts
-- Insertion des emprunts
INSERT INTO emp_emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2025-01-15', '2025-01-20'),
(5, 3, '2025-02-01', '2025-02-05'),
(11, 1, '2025-02-10', '2025-07-20'),
(17, 4, '2025-02-15', '2025-02-20'),
(23, 2, '2025-03-01', '2025-07-25'),
(28, 1, '2025-03-05', '2025-03-10'),
(33, 3, '2025-03-15', '2025-07-30'),
(37, 2, '2025-04-01', '2025-04-05'),
(3, 4, '2025-04-10', '2025-08-05'),
(15, 1, '2025-04-15', '2025-08-10');
SELECT * from v_emp_emprunt_objet_membre;