<?php
// connexion.php

$servername = "localhost"; // Adresse du serveur MySQL
$username = "root";        // Nom d'utilisateur MySQL
$password = "";            // Mot de passe MySQL
$dbname = "boissons3"; // Nom de la base de données

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>
