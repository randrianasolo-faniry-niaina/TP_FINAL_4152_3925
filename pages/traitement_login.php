<?php
session_start();
require('../inc/fonction.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    
    // Validation basique
    if (empty($email) || empty($mdp)) {
        echo "<div class='alert alert-danger'>Tous les champs sont obligatoires.</div>";
        echo "<a href='login.php' class='btn btn-primary'>Retour</a>";
        exit;
    }
    
    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger'>Email invalide.</div>";
        echo "<a href='login.php' class='btn btn-primary'>Retour</a>";
        exit;
    }
    
    // Tentative de connexion
    $user = login($email, $mdp);
    
    if ($user) {
        // Connexion réussie
        $_SESSION['user_id'] = $user['id_membre'];
        
        header('Location: ../index.php');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Email ou mot de passe incorrect.</div>";
        echo "<a href='login.php' class='btn btn-primary'>Retour</a>";
    }
} else {
    header('Location: list_objet.php');
    exit;
}
?>