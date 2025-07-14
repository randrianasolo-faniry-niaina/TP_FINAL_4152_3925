<?php

require('../inc/fonction.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $date_naissance = $_POST['dtn'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $mdp = $_POST['mdp'] ?? '';


    if (inscription($nom, $email, $date_naissance, $genre, $ville, $mdp)) {
        header('Location: login.php');
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'inscription. L'email existe peut-être déjà.</div>";
        echo "<a href='login.php' class='btn btn-primary'>Retour</a>";
    }
} else {
    header('Location: login.php');
    exit;
}
