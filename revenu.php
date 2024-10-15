

<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location:login.php");
    exit;
}
?>





<?php
include 'connexion.php';

$cin = $nom_commercial = '';
$searchResults = [];
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['rechercher_combine'])) {
        $cin = isset($_POST['cin']) ? $_POST['cin'] : '';
        $nom_commercial = isset($_POST['nom_commercial']) ? $_POST['nom_commercial'] : '';

        if ($cin && $nom_commercial) {
            $query = "SELECT * FROM exploitant e 
                      JOIN entreprise en ON e.cin = en.cin
                      WHERE e.cin = ? AND en.nom_commercial = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $cin, $nom_commercial);
            $stmt->execute();
            $searchResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } else {
            $errorMessage = "Veuillez entrer à la fois le CIN et le nom commercial.";
        }
    }

    if (isset($_POST['ajouter_montant'])) {
        $cin = isset($_POST['cin']) ? $_POST['cin'] : '';
        $nom_commercial = isset($_POST['nom_commercial']) ? $_POST['nom_commercial'] : '';
        $montant = isset($_POST['montant']) ? $_POST['montant'] : '';

        if ($cin && $nom_commercial && $montant) {
            $query = "UPDATE entreprise SET montant = ? WHERE cin = ? AND nom_commercial = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("dss", $montant, $cin, $nom_commercial);
            if ($stmt->execute()) {
                $successMessage = "Montant ajouté avec succès.";
            } else {
                $errorMessage = "Erreur lors de l'ajout du montant.";
            }
            $stmt->close();

            // Rechercher à nouveau pour afficher les résultats mis à jour
            $query = "SELECT * FROM exploitant e 
                      JOIN entreprise en ON e.cin = en.cin
                      WHERE e.cin = ? AND en.nom_commercial = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $cin, $nom_commercial);
            $stmt->execute();
            $searchResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } else {
            $errorMessage = "Veuillez entrer tous les champs requis.";
        }
    }
}

$conn->close(); // Fermer la connexion une seule fois après toutes les opérations
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche Exploitant</title>
    <style>
    body {
    background: repeating-linear-gradient(135deg, black 0%, #3e893e 50%);
    font-family: 'Poppins', sans-serif;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    
}
.logout-button {
    position: fixed; /* Position fixe pour qu'il reste visible lors du défilement */
    top: 20px;
    right: 20px; /* Aligne le bouton à droite */
    background-color: transparent;
    border: none;
    color: white;
    font-size: 12px; /* Réduire la taille du texte */
    cursor: pointer;
    padding: 5px 10px; /* Réduire la taille du bouton */
    border-radius: 5px;
    transition: background-color 0.3s ease;
    z-index: 10;
    /* Définir une largeur fixe si nécessaire */
    width: 80px; /* Ajustez cette valeur selon vos besoins */
    text-align: center; /* Centre le texte dans le bouton */
}

.logout-button:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

.conteneur {
    background: rgba(255, 255, 255, 0.1);
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
    width: 100%;
    max-width: 800px;
    overflow: auto; /* Masquer les débordements */
    position: relative; /* Nécessaire pour le positionnement de l'élément enfant */

    
}


h2 {
    color: #fff;
    text-align: center;
    margin-bottom: 20px;
}

.card {
    background: rgba(0, 0, 0, 0.7);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
}

.card h3 {
    margin: 0;
    padding-bottom: 10px;
    border-bottom: 1px solid #fff;
    font-size: 20px;
}

.card p {
    margin: 10px 0;
    font-size: 16px;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

.form-inline {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 15px; /* Espace entre les éléments */
}

.form-inline .form-group {
    flex: 1;
    margin-bottom: 0;
}

.form-inline button {
    margin-top: 25px;
    flex-shrink: 0;
}

.form-group label {
    margin-bottom: 5px;
    display: block;
    font-weight: bold;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    background: rgb(120, 120, 104);
    color: #fff;
}

button {
    padding: 10px;
    border: none;
    border-radius: 5px;
    background: linear-gradient(135deg, #3e893e, #1d2a1d);;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
    width: 100%;
}

.message {
    padding: 10px;
    border-radius: 5px;
    text-align: center;
    margin-bottom: 20px;
}

.message-success {
    color: #0f0;
}

.message-error {
    color: #f00;
}


.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px; /* Espacement entre les colonnes */
}


.form-group {
    flex: 1;
    min-width: 250px; /* Largeur minimale pour les champs */
    margin-bottom: 20px;
}

.form-group label {
    margin-bottom: 5px;
        display: block;
        font-weight: bold;
        color: black;
}

.form-group input {
    width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background: #f9f9f9;
        color: #333;
        font-size: 14px;
        box-sizing: border-box;
}
.form-group button {
    padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background: linear-gradient(135deg, #3e893e, #1d2a1d);;
        color: white;
        font-size: 16px;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.3s ease;
    
}

.form-group button:hover {
    background: linear-gradient(135deg, #3e893e, #1d2a1d);;
}

/* Centrer la case du montant */
.sara {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 20px;
}

.sara input {
    text-align: center; /* Centre le texte à l'intérieur de l'input */
    width: 50%; /* Ajustez la largeur du champ selon vos préférences */
    padding: 10px;
    border: none;
    border-radius: 5px;
    background: rgb(120, 120, 104);
    color: #fff;
}

.saad{
    display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 15px;
}
    </style>
</head>
<body>

    <div class="conteneur">
    <button class="logout-button" onclick="window.location.href='navbar.php'"><strong>RETOUR</strong></button>
        <h2>Recherche Exploitant</h2>

        <?php if ($errorMessage): ?>
            <div class="message message-error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>
        
        <?php if ($successMessage): ?>
            <div class="message message-success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>

        <form method="post" action="">
    <div class="form-row">
        <div class="form-group">
            <label for="cin">CIN:</label>
            <input type="text" id="cin" name="cin" value="<?php echo htmlspecialchars($cin); ?>">
        </div>
        <div class="form-group">
            <label for="nom_commercial">Nom Commercial:</label>
            <input type="text" id="nom_commercial" name="nom_commercial" value="<?php echo htmlspecialchars($nom_commercial); ?>">
        </div>
        <div class="saad">
            <button type="submit" name="rechercher_combine">Rechercher</button>
        </div>
    </div>
</form>



        <?php if ($searchResults): ?>
            <h2>Informations de l'Exploitant et de l'Entreprise</h2>
            <?php foreach ($searchResults as $row): ?>
                <div class="card">
                    <h3 align="center"><?php echo htmlspecialchars($row['nom_commercial']); ?> </h3>
                   
                    <p><strong>Nom:</strong> <?php echo htmlspecialchars($row['nom']); ?></p>
                    <p><strong>Prenom:</strong> <?php echo htmlspecialchars($row['prenom']); ?></p>
                    <p><strong>CIN:</strong> <?php echo htmlspecialchars($row['cin']); ?></p>
                    <p><strong>Adresse Exploitant:</strong> <?php echo htmlspecialchars($row['adresse_exploitant']); ?></p>
                    <p><strong>Email Exploitant:</strong> <?php echo htmlspecialchars($row['email_exploitant']); ?></p>
                    <p><strong>Type Exploitant:</strong> <?php echo htmlspecialchars($row['type_exploitant']); ?></p>
                    <p><strong>Adresse Entreprise:</strong> <?php echo htmlspecialchars($row['adresse']); ?></p>
                    <p><strong>Email Entreprise:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                    <p><strong>Type Entreprise:</strong> <?php echo htmlspecialchars($row['type']); ?></p>
                    <p><strong>Montant en dh :</strong> <?php echo isset($row['montant']) ? htmlspecialchars($row['montant']) : 'Non spécifié'; ?></p>
                    <p><strong>Date de Création:</strong> <?php echo isset($row['date_creation']) ? htmlspecialchars($row['date_creation']) : 'Non spécifié'; ?></p>
                </div>
            <?php endforeach; ?>

            <form method="post" action="">
                <h2>Ajouter Montant</h2>
                <input type="hidden" name="cin" value="<?php echo htmlspecialchars($cin); ?>">
                <input type="hidden" name="nom_commercial" value="<?php echo htmlspecialchars($nom_commercial); ?>">
                <div class="sara">
                    <label for="montant">Montant:</label>
                    <input type="number" id="montant" name="montant" step="0.01" min="0">
                </div>
                <button type="submit" name="ajouter_montant">Ajouter Montant</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
