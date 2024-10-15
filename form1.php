<?php
// Inclure le fichier de connexion
include 'connexion.php';

// Démarrer la session pour les messages
session_start();

// Initialiser un message de succès ou d'erreur
$message = '';

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire pour l'exploitant
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse_exploitant = $_POST['adresse_exploitant'];
    $email_exploitant = $_POST['email_exploitant'];
    $type_exploitant = $_POST['type_exploitant'];
    $cin = $_POST['cin'];

    // Préparer la requête d'insertion pour l'exploitant
    $stmt = $conn->prepare("INSERT INTO exploitant (cin, nom, prenom, adresse_exploitant, email_exploitant, type_exploitant) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $cin, $nom, $prenom, $adresse_exploitant, $email_exploitant, $type_exploitant);

    // Exécuter la requête et vérifier les erreurs
    if ($stmt->execute()) {
        $message .= "Exploitant ajouté avec succès ! ";
    } else {
        $message .= "Erreur lors de l'ajout de l'exploitant : " . $stmt->error . " ";
    }
    $stmt->close();

    // Récupérer les données du formulaire pour l'entreprise
    $nom_commercial = $_POST['nom_commercial'];
    $adresse = $_POST['adresse'];
    $email = $_POST['email'];
    $type = $_POST['type'];
    $date_creation = $_POST['date_creation'];

    // Préparer la requête d'insertion pour l'entreprise
    $stmt = $conn->prepare("INSERT INTO entreprise (nom_commercial, adresse, email, type, cin, date_creation) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nom_commercial, $adresse, $email, $type, $cin, $date_creation);

    // Exécuter la requête et vérifier les erreurs
    if ($stmt->execute()) {
        $message .= "Entreprise ajoutée avec succès !";
    } else {
        $message .= "Erreur lors de l'ajout de l'entreprise : " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    // Enregistrer le message dans la session
    $_SESSION['message'] = $message;

    // Redirection pour afficher le message
    header("Location: index2.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter l'entreprise et l'exploitant</title>
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
            max-width: 600px; /* Largeur maximale réduite */
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
            grid-template-columns: 1fr 2fr; /* Deux colonnes : 1/3 pour les labels, 2/3 pour les champs */
            gap: 10px; /* Espacement entre les champs et les labels */
            margin-bottom: 20px;
        }

        form .form-group {
            display: contents; /* Permet à la grille de s'adapter à l'intérieur du formulaire */
        }

        form label {
            color: #fff; /* Couleur du texte des labels */
            margin-bottom: 0; /* Enlève l'espace sous le label */
            display: flex;
            align-items: center; /* Centre verticalement les labels avec les champs */
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
    <div class="background"></div>

    <div class="form-container">
        <h2>Ajouter l'entreprise et l'exploitant</h2>
        <?php
        if (isset($_SESSION['message'])) {
            $messageClass = strpos($_SESSION['message'], 'Erreur') !== false ? 'message-error' : 'message-success';
            echo "<div class='message $messageClass'>" . htmlspecialchars($_SESSION['message']) . "</div>";
            unset($_SESSION['message']);
        }
        ?>
        <form action="form1.php" method="POST">
            <!-- Formulaire pour l'exploitant -->
             <br>
            <h3>Ajouter l'exploitant</h3>
            <label for="cin">CIN:</label>
            <input type="text" name="cin" id="cin" required>

            <label for="nom">Nom:</label>
            <input type="text" name="nom" id="nom" required>

            <label for="prenom">Prénom:</label>
            <input type="text" name="prenom" id="prenom" required>

            <label for="adresse_exploitant">Adresse:</label>
            <input type="text" name="adresse_exploitant" id="adresse_exploitant" required>

            <label for="email_exploitant">Email:</label>
            <input type="email" name="email_exploitant" id="email_exploitant" required>

            <label for="type">Type de l'exploitant:</label>
                <select name="type_exploitant" id="type_exploitant" required>
                    <option value="Gerant">Gérant</option>
                    <option value="Proprietaire">Propriétaire</option>
                    <option value="Responsable">Responsable</option>
                    <option value="Directeur">Directeur</option>
                    <option value="Autre">Autre</option>
                </select>

<br>
            <!-- Formulaire pour l'entreprise -->
            <h3>Ajouter l'entreprise</h3>
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


            <label for="date_creation">Date de Création:</label>
            <input type="date" name="date_creation" id="date_creation" required>

            <button type="submit">Ajouter</button>
        </form>
    </div>

  
</body>
</html>
