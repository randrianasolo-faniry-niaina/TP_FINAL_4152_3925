<?php
ini_set("display_errors", "1");
require('connection.php');

function inscription($nom, $email, $date_naissance, $genre, $ville, $mdp)
{

    $check_sql = "SELECT id_membre FROM emp_membre WHERE email = '$email'";
    $check_result = mysqli_query(dbconnect(), $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        return false;
    }


    $sql = "INSERT INTO emp_membre (nom, email, date_de_naissance, genre, ville, mdp, image_profil) 
            VALUES ('$nom', '$email', '$date_naissance', '$genre', '$ville', '$mdp', 'default.png')";

    $result = mysqli_query(dbconnect(), $sql);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

function login($email, $mdp)
{
    $sql = "SELECT id_membre, nom, email, mdp FROM emp_membre WHERE email = '$email'";
    $result = mysqli_query(dbconnect(), $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($mdp == $user['mdp']) {
            return $user;
        }
    }

    return false;
}
function getListObjet($categorie = "tous")
{
    if ($categorie == "tous") {
        $sql = "SELECT * FROM v_emp_objet_image_categorie_membre Order by nom_objet ASC";
    } else {
        $sql = "SELECT * FROM v_emp_objet_image_categorie_membre WHERE nom_categorie = '$categorie' Order by nom_objet ASC";
    }

    $result = mysqli_query(dbconnect(), $sql);
    $listObjet = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $listObjet[] = $row;
    }
    return $listObjet;
}
function getEmprunt()
{
    $sql = "SELECT * FROM v_emp_emprunt_objet_membre";
    $result = mysqli_query(dbconnect(), $sql);
    $listObjet = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $listObjet[] = $row;
    }
    return $listObjet;
}


function verifEmprunt($emp, $id_objet)
{
    $dernierEmprunt = null;

    foreach ($emp as $emprunt) {
        if ($emprunt['id_objet'] == $id_objet) {
            if ($dernierEmprunt == null || $emprunt['date_emprunt'] > $dernierEmprunt['date_emprunt']) {
                $dernierEmprunt = $emprunt;
            }
        }
    }
    return $dernierEmprunt !== null ? $dernierEmprunt : false;
}
function isObjetEmprunte($emprunt)
{
    if ($emprunt == false || $emprunt['date_retour'] == null) {
        return false;
    }

    $dateRetour = new DateTime($emprunt['date_retour']);
    $aujourdhui = new DateTime();

    return $dateRetour > $aujourdhui;
}
