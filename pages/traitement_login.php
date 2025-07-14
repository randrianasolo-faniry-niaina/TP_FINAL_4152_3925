<?php
session_start();
require('../inc/fonction.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    
    
    if (empty($email) || empty($mdp)) {
        echo "<div class='alert alert-danger'>Tous les champs sont obligatoires.</div>";
        echo "<a href='login.php' class='btn btn-primary'>Retour</a>";
        exit;
    }
    $user = login($email, $mdp);
    
    if ($user) {
        
        $_SESSION['user_id'] = $user['id_membre'];
        
        header('Location: list_objet.php');
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