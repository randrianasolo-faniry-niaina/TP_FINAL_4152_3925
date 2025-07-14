<?php
ini_set("display_errors", "1");
require('connection.php');

function inscription($nom, $email, $date_naissance, $genre, $ville, $mdp) {
    // Vérifier si l'email existe déjà
    $check_sql = "SELECT id_membre FROM emp_membre WHERE email = '$email'";
    $check_result = mysqli_query(dbconnect(), $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        return false; // Email déjà existant
    }
    
    // Insérer le nouveau membre (sans encoder le mot de passe)
    $sql = "INSERT INTO emp_membre (nom, email, date_de_naissance, genre, ville, mdp, image_profil) 
            VALUES ('$nom', '$email', '$date_naissance', '$genre', '$ville', '$mdp', 'default.png')";
    
    $result = mysqli_query(dbconnect(), $sql);
    
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function login($email, $mdp) {
    $sql = "SELECT id_membre, nom, email, mdp FROM emp_membre WHERE email = '$email'";
    $result = mysqli_query(dbconnect(), $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Vérifier le mot de passe directement (sans hash)
        if ($mdp == $user['mdp']) {
            return $user;
        }
    }
    
    return false;
}