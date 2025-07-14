
<?php
session_start();
require_once('../inc/fonction.php');



// Vérifier si le formulaire a été soumis
if ($_POST && isset($_POST['rendre_objet'])) {
    $id_emprunt = $_POST['id_emprunt'];
    $etat_objet = $_POST['etat_objet'];
    $nom_membre = $_POST['nom_membre'];
    
    // Valider les données
    if (empty($id_emprunt) || empty($etat_objet) || empty($nom_membre)) {
        $_SESSION['error'] = "Données manquantes pour traiter le retour.";
        header('Location: fiche_membre.php?membre=' . urlencode($nom_membre));
        exit();
    }
    
    // Traiter le retour
    if (rendreObjet($id_emprunt, $etat_objet)) {
        $_SESSION['success'] = "Objet rendu avec succès ! État: " . $etat_objet;
    } else {
        $_SESSION['error'] = "Erreur lors du retour de l'objet.";
    }
    
    // Rediriger vers la fiche membre
    header('Location: fiche_membre.php?membre=' . urlencode($nom_membre));
    exit();
} else {
    // Si pas de POST, rediriger vers la liste des objets
    header('Location: list_objet.php');
    exit();
}
?>