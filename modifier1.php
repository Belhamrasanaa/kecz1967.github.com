<?php
// Inclure le fichier de connexion
include 'connexion.php';

// Démarrer la session pour les messages
session_start();

// Vérifier si le CIN est passé dans l'URL pour la modification
$cin = isset($_GET['cin']) ? $_GET['cin'] : '';

// Récupérer les données de l'exploitant à modifier si le CIN est présent
if ($cin) {
    $stmt = $conn->prepare("SELECT * FROM exploitant WHERE cin = ?");
    $stmt->bind_param("s", $cin);
    $stmt->execute();
    $result = $stmt->get_result();
    $exploitant = $result->fetch_assoc();
    $stmt->close();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $cin) {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse_exploitant = $_POST['adresse'];
    $email_exploitant = $_POST['email'];
    $type_exploitant = $_POST['type'];

    // Préparer la requête de mise à jour
    $stmt = $conn->prepare("UPDATE exploitant SET nom = ?, prenom = ?, adresse_exploitant = ?, email_exploitant = ?, type_exploitant = ? WHERE cin = ?");
    $stmt->bind_param("ssssss", $nom, $prenom, $adresse_exploitant, $email_exploitant, $type_exploitant, $cin);

    // Exécuter la requête et vérifier les erreurs
    if ($stmt->execute()) {
        $_SESSION['message'] = "Exploitant modifié avec succès !";
    } else {
        $_SESSION['message'] = "Erreur lors de la modification de l'exploitant : " . $stmt->error;
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
    <title>Modifier l'exploitant</title>
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
        <!-- Stars will be generated here by JavaScript -->
    </div>

    <div class="form-container">
        <h2>Modifier l'exploitant</h2>
        <?php
        // Afficher le message s'il y en a un
        if (isset($_SESSION['message'])) {
            $messageClass = strpos($_SESSION['message'], 'Erreur') !== false ? 'message-error' : 'message-success';
            echo "<div class='message $messageClass'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>
        <form action="modifier1.php?cin=<?php echo htmlspecialchars($cin); ?>" method="POST">
            <label for="nom">Nom:</label>
            <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($exploitant['nom']); ?>" required>
            
            <label for="prenom">Prénom:</label>
            <input type="text" name="prenom" id="prenom" value="<?php echo htmlspecialchars($exploitant['prenom']); ?>" required>
            
            <label for="adresse">Adresse:</label>
            <input type="text" name="adresse" id="adresse" value="<?php echo htmlspecialchars($exploitant['adresse_exploitant']); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($exploitant['email_exploitant']); ?>" required>
            
            <label for="type">Type:</label>
            <select name="type" id="type" required>
                <option value="Gerant" <?php echo $exploitant['type_exploitant'] == 'Gerant' ? 'selected' : ''; ?>>Gerant</option>
                <option value="Proprietaire" <?php echo $exploitant['type_exploitant'] == 'Proprietaire' ? 'selected' : ''; ?>>Proprietaire</option>
                <option value="Responsable" <?php echo $exploitant['type_exploitant'] == 'Responsable' ? 'selected' : ''; ?>>Responsable</option>
                <option value="Directeur" <?php echo $exploitant['type_exploitant'] == 'Directeur' ? 'selected' : ''; ?>>Directeur</option>
                <option value="Autre" <?php echo $exploitant['type_exploitant'] == 'Autre' ? 'selected' : ''; ?>>Autre</option>
            </select>
            
            <button type="submit">Modifier</button>
        </form>
    </div>

   
</body>
</html>
