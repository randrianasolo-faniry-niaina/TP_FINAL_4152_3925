<?php
$id = $_GET['id_objet'] ?? 0;
$id_membre = $_GET['id_membre'] ?? 0;
require_once('../inc/fonction.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="traitement_emprunt.php" method="POST">

        <input type="number" name="nb_jour" placeholder="Nombre de jours" required>
        <input type="hidden" name="id_objet" value="<?= $id ?>">
        <input type="hidden" name="id_membre" value="<?= $id_membre ?>">
        <h3>Emprunter l'objet</h3>
        <input type="submit" value="Valider L'emprunt">
    </form>
</body>
</html>