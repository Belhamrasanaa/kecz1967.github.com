<?php
// Inclure le fichier de connexion
include 'connexion.php';

// Démarrer la session pour les messages
session_start();

// Ajouter une entreprise
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom_commercial = $_POST['nom_commercial'];
    $adresse = $_POST['adresse'];
    $email = $_POST['email'];
    $type = $_POST['type'];
    $cin = $_POST['cin'];
    $date_creation = $_POST['date_creation']; // Récupérer la date de création

    // Vérifier si le CIN existe dans la table exploitant
    $checkExploitantStmt = $conn->prepare("SELECT * FROM exploitant WHERE cin = ?");
    $checkExploitantStmt->bind_param("s", $cin);
    $checkExploitantStmt->execute();
    $result = $checkExploitantStmt->get_result();

    if ($result->num_rows > 0) {
        // Insérer dans la table entreprise
        $insertStmt = $conn->prepare("INSERT INTO entreprise (nom_commercial, adresse, email, type, cin, date_creation) VALUES (?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param("ssssss", $nom_commercial, $adresse, $email, $type, $cin, $date_creation);

        if ($insertStmt->execute()) {
            $_SESSION['message'] = "Entreprise ajoutée avec succès !";
        } else {
            $_SESSION['message'] = "Erreur lors de l'ajout de l'entreprise : " . $insertStmt->error;
        }

        $insertStmt->close();
    } else {
        $_SESSION['message'] = "Erreur : Le CIN spécifié n'existe pas dans la table exploitant.";
    }

    $checkExploitantStmt->close();
    header("Location: index.php"); // Redirection pour afficher le message
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de gestion des taxes</title>
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
}

h2 {
    color: #fff; /* Couleur du texte du titre */
    text-align: center;
    margin-bottom: 20px;
}

form {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Deux colonnes égales */
    gap: 20px; /* Espacement entre les colonnes */
}

form label {
    color: #fff; /* Couleur du texte des labels */
    display: block;
    margin-bottom: 5px; /* Réduit l'espace sous le label */
}

form input[type="text"], form input[type="email"], form select, form input[type="date"] {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    background: #2a2a2a; /* Couleur des cases pour un bon contraste */
    color: #fff; /* Couleur du texte des champs */
    box-sizing: border-box; /* Inclut le padding dans la largeur */
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
        <!-- Stars will be generated here by JavaScript -->
    </div>

    <div class="form-container">
        <h2>Ajouter une entreprise</h2>

        <form action="add.php" method="POST">
            <label for="nom_commercial">Nom Commercial:</label>
            <input type="text" name="nom_commercial" id="nom_commercial" required>

            <label for="adresse">Adresse:</label>
            <input type="text" name="adresse" id="adresse" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="type">Type:</label>
            <select name="type" id="type" required>
                <option value="Industrielle">Industrielle</option>
                <option value="Commerciale">Commerciale</option>
                <option value="Agricole">Agricole</option>
                <option value="Autre">Autre</option>
            </select>

            <label for="cin">CIN:</label>
            <input type="text" name="cin" id="cin" required>

            <label for="date_creation">Date de Création:</label>
            <input type="date" name="date_creation" id="date_creation" required>

            <button type="submit">Ajouter</button>
        </form>
    </div>

   
</body>
</html>
