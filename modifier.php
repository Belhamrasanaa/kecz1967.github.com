<?php
// Inclure le fichier de connexion
include 'connexion.php';

// Démarrer la session pour les messages
session_start();

// Vérifier si l'ID est passé dans l'URL pour la modification
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les données de l'entreprise à modifier si l'ID est présent
$entreprise = [];
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM entreprise WHERE id_entreprise = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $entreprise = $result->fetch_assoc();
    $stmt->close();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id) {
    // Récupérer les données du formulaire
    $nom_commercial = $_POST['nom_commercial'];
    $adresse = $_POST['adresse'];
    $email = $_POST['email'];
    $type = $_POST['type'];
    $cin = $_POST['cin'];
    $date_creation = $_POST['date_creation']; // Ajouter la date de création

    // Préparer la requête de mise à jour
    $stmt = $conn->prepare("UPDATE entreprise SET nom_commercial = ?, adresse = ?, email = ?, type = ?, cin = ?, date_creation = ? WHERE id_entreprise = ?");
    $stmt->bind_param("ssssssi", $nom_commercial, $adresse, $email, $type, $cin, $date_creation, $id);

    // Exécuter la requête et vérifier les erreurs
    if ($stmt->execute()) {
        $_SESSION['message'] = "Entreprise modifiée avec succès !";
    } else {
        $_SESSION['message'] = "Erreur lors de la modification de l'entreprise : " . $stmt->error;
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();

    // Redirection pour afficher le message
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'entreprise</title>
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
        <h2>Modifier l'entreprise</h2>
        <?php
        // Afficher le message s'il y en a un
        if (isset($_SESSION['message'])) {
            $messageClass = strpos($_SESSION['message'], 'Erreur') !== false ? 'message-error' : 'message-success';
            echo "<div class='message $messageClass'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>
        <form action="modifier.php?id=<?php echo htmlspecialchars($id); ?>" method="POST">
            
            <label for="nom_commercial">Nom Commercial:</label>
            <input type="text" name="nom_commercial" id="nom_commercial" value="<?php echo htmlspecialchars($entreprise['nom_commercial'] ?? ''); ?>" required>
            
            <label for="adresse">Adresse:</label>
            <input type="text" name="adresse" id="adresse" value="<?php echo htmlspecialchars($entreprise['adresse'] ?? ''); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($entreprise['email'] ?? ''); ?>" required>
            
            <label for="type">Type:</label>
            <select name="type" id="type" required>
                <option value="Industrielle" <?php echo (isset($entreprise['type']) && $entreprise['type'] == 'Industrielle') ? 'selected' : ''; ?>>Industrielle</option>
                <option value="Commerciale" <?php echo (isset($entreprise['type']) && $entreprise['type'] == 'Commerciale') ? 'selected' : ''; ?>>Commerciale</option>
                <option value="Agricole" <?php echo (isset($entreprise['type']) && $entreprise['type'] == 'Agricole') ? 'selected' : ''; ?>>Agricole</option>
                <option value="Autre" <?php echo (isset($entreprise['type']) && $entreprise['type'] == 'Autre') ? 'selected' : ''; ?>>Autre</option>
            </select>
            
            <label for="cin">CIN:</label>
            <input type="text" name="cin" id="cin" value="<?php echo htmlspecialchars($entreprise['cin'] ?? ''); ?>" required>
            
            <label for="date_creation">Date de Création:</label>
            <input type="date" name="date_creation" id="date_creation" value="<?php echo htmlspecialchars($entreprise['date_creation'] ?? ''); ?>" required>
            
            <button type="submit">Modifier</button>
        </form>
    </div>

</body>
</html>
