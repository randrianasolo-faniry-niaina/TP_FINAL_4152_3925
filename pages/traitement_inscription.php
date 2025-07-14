<?php

require('../inc/fonction.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $date_naissance = $_POST['dtn'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    
    // Validation basique
    if (empty($nom) || empty($email) || empty($date_naissance) || empty($genre) || empty($ville) || empty($mdp)) {
        echo "<div class='alert alert-danger'>Tous les champs sont obligatoires.</div>";
        echo "<a href='inscription.php' class='btn btn-primary'>Retour</a>";
        exit;
    }
    
    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger'>Email invalide.</div>";
        echo "<a href='inscription.php' class='btn btn-primary'>Retour</a>";
        exit;
    }
    
    // Tentative d'inscription
    if (inscription($nom, $email, $date_naissance, $genre, $ville, $mdp)) {
        echo "<div class='alert alert-success'>Inscription réussie ! Vous pouvez maintenant vous connecter.</div>";
        echo "<a href='login.php' class='btn btn-primary'>Login</a>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de l'inscription. L'email existe peut-être déjà.</div>";
        echo "<a href='login.php' class='btn btn-primary'>Retour</a>";
    }
} else {
    header('Location: login.php');
    exit;
}
?>