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