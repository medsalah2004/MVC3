<?php
include '../../controleur/clientcontroleur.php';
$clientControleur = new ClientControleur();
$clientControleur->deleteclient($_GET["id"]);
header('Location:signup2.php');
?>