<?php
require('../inc/fonction.php');
$idobjet=$_POST['id_objet'] ?? 0;
$idmembre=$_POST['id_membre'] ?? 0;
$nbjour=$_POST['nb_jour'] ?? 0;
$date_emprunt = date('Y-m-d');
$date_retour = date('Y-m-d', strtotime("+$nbjour days"));
insererEmprunt($idobjet, $idmembre, $date_emprunt, $date_retour);
header("Location: list_objet.php");

?>