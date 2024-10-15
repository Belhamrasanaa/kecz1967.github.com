<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inclure le fichier de connexion
    include 'connexion.php';
    session_start();

    // Récupérer les données du formulaire
    $cin = $_POST['CIN'];
    $nom = $_POST['NOM'];
    $prenom = $_POST['PRENOM'];
    $adresse_exploitant = $_POST['adresse'];
    $email_exploitant = $_POST['email'];
    $type_exploitant = $_POST['type'];

    // Préparer la requête d'insertion
    $stmt = $conn->prepare("INSERT INTO exploitant (cin, nom, prenom, adresse_exploitant, email_exploitant, type_exploitant) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $cin, $nom, $prenom, $adresse_exploitant, $email_exploitant, $type_exploitant);

    // Exécuter la requête et vérifier les erreurs
    if ($stmt->execute()) {
        $_SESSION['message'] = "Exploitant ajouté avec succès !";
    } else {
        $_SESSION['message'] = "Erreur lors de l'ajout de l'exploitant : " . $stmt->error;
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();

    // Redirection pour afficher le message
    header("Location: index1.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire d'ajout d'exploitant</title>
    <style>
  body {
    background: repeating-linear-gradient(135deg, black 0%, #3e893e 50%);
    font-family: 'Poppins', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    color: #fff;
    overflow: auto; /* Permet le défilement de la page entière */
}

.form-container {
    background: #000; /* Fond du formulaire noir */
    padding: 20px; /* Réduit le padding */
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
    width: 90%; /* Réduit la largeur du formulaire */
    max-width: 500px; /* Largeur maximale réduite */
    position: relative;
    z-index: 1; /* Assure que le formulaire est au-dessus des étoiles */
    margin: 0 auto; /* Centre le formulaire horizontalement */
    box-sizing: border-box; /* Inclut le padding dans la largeur totale */
}

h2 {
    color: #fff; /* Couleur du texte du titre */
    text-align: center;
    margin-bottom: 20px;
}

form {
    display: grid;
    grid-template-columns: 1fr 2fr; /* Deux colonnes avec proportion différente */
    gap: 10px;
    align-items: center; /* Aligne les éléments au centre verticalement */
}

.form-group {
    display: contents; /* Permet au groupe d'utiliser la grille parent */
}

form label {
    color: #fff; /* Couleur du texte des labels */
    margin: 0;
    display: block;
}

form input[type="text"], form input[type="email"], form select, form input[type="date"] {
    width: 100%; /* Champs remplissent l'espace restant */
    padding: 10px;
    border: none;
    border-radius: 5px;
    background: #2a2a2a; /* Couleur des cases pour un bon contraste */
    color: #fff; /* Couleur du texte des champs */
    box-sizing: border-box; /* Inclut le padding dans la largeur */
}

.form-group:nth-of-type(1) {
    grid-column: span 2; /* CIN occupe toute la largeur */
}

.form-group:nth-of-type(2) {
    grid-column: span 2; /* NOM et PRENOM sur la même ligne */
    
}
.form-group:nth-of-type(3){
    grid-column: span 2; /* NOM et PRENOM sur la même ligne */
}
.form-group:nth-of-type(4) {
    grid-column: span 2; /* Adresse occupe toute la largeur */
}

.form-group:nth-of-type(5) {
    grid-column: span 2; /* Email et Type sur la même ligne */
}

form button {
    grid-column: span 2; /* Le bouton occupe toute la largeur */
    padding: 10px;
    border: none;
    border-radius: 5px;
    background: linear-gradient(to right, #345b34, #b8d8b0); /* Gradient pour le bouton */
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

form button:hover {
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.7);
}

.message {
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 5px;
    color: #fff;
    text-align: center;
}

.message-success {
    background-color: transparent;
    color: #0f0; /* Texte en vert pour le succès */
}

.message-error {
    background-color: transparent;
    color: #f00; /* Texte en rouge pour l'erreur */
}

.background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 0; /* Assure que les étoiles sont derrière le formulaire */
}

@keyframes twinkling {
    0%, 100% { opacity: 0; }
    50% { opacity: 1; }
}


    </style>
</head>
<body>
<div class="background">
        <!-- Les étoiles seront générées ici par JavaScript -->
    </div>

    <div class="form-container">
        <h2>Ajouter un exploitant</h2>
        <form action="add1.php" method="POST">
            <div class="form-group">
                <label for="CIN">CIN:</label>
                <input type="text" name="CIN" id="CIN" required>
            </div>

            <div class="form-group">
                <label for="NOM">NOM:</label>
                <input type="text" name="NOM" id="NOM" required>

                <label for="PRENOM">PRENOM:</label>
                <input type="text" name="PRENOM" id="PRENOM" required>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse:</label>
                <input type="text" name="adresse" id="adresse" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="type">Type de l'exploitant:</label>
                <select name="type" id="type" required>
                    <option value="Gerant">Gerant</option>
                    <option value="Proprietaire">Proprietaire</option>
                    <option value="Responsable">Responsable</option>
                    <option value="Directeur">Directeur</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>

            <button type="submit">Ajouter</button>
        </form>
    </div>
</body>
</html>