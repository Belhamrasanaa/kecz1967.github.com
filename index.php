






<?php
// Inclure le fichier de connexion
include 'connexion.php';

// Démarrer la session pour les messages
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location:login.php");
    exit;
}
// Suppression d'une entreprise
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id_entreprise = $_GET['id'];
    $deleteStmt = $conn->prepare("DELETE FROM entreprise WHERE id_entreprise = ?");
    $deleteStmt->bind_param("i", $id_entreprise);
    if ($deleteStmt->execute()) {
        $_SESSION['message'] = "Entreprise supprimée avec succès !";
    } else {
        $_SESSION['message'] = "Erreur lors de la suppression : " . $deleteStmt->error;
    }
    $deleteStmt->close();
    header("Location: index.php"); // Redirection pour afficher le message
    exit();
}

// Récupérer les entreprises
$query = "SELECT * FROM entreprise";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Entreprises</title>
   <style>
    

body {
    font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background: radial-gradient(circle at center, #3e893e, black);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
}

.background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: -1;
}



@keyframes twinkling {
    0%, 100% { opacity: 0; }
    50% { opacity: 1; }
}

.container {
    width: 1089px; /* Réduit la largeur pour mieux s'ajuster */
    max-width: 1200px; /* Augmente la largeur maximale du conteneur */
    padding: 30px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
    margin: 0 auto;
    text-align: center;
    overflow-x: auto; /* Ajoute un défilement horizontal si nécessaire */
}

.table-container {
    width: 100%;
    max-width: 1200px; /* Largeur maximale de la table */
    padding: 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Ombre pour donner de la profondeur */
    margin: 20px 0;
    display: flex;
    justify-content: center;
    text-align: left;
}

table {
    border-collapse: collapse;
    width: 111%; /* Utilise toute la largeur disponible */
    background: #134513; /* Couleur de fond de la table */
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Ombre pour donner de la profondeur */
}

th {
    background: #134513;
    color: white;
    padding: 12px 15px;
    text-align: left;
    font-weight: bold;
    border-bottom: 2px solid #134513; /* Ligne de séparation sous l'en-tête */
}

td {
    background-color: black;
    color: white;
    padding: 12px 15px;
    border-bottom: 1px solid #134513; /* Ligne de séparation entre les lignes */
}

tr:nth-child(even) {
    background: #134513; /* Couleur de fond des lignes paires */
}

tr:nth-child(odd) {
    background: black; /* Couleur de fond des lignes impaires */
}

tr:hover {
    background: #134513; /* Couleur de fond au survol des lignes */
}

td.empty {
    color: #aaa;
}

h2 {
    color: #fff;
}

/* Boutons de l'ajout */
.Btn_add button {
            background: linear-gradient(135deg, #3e893e, #1d2a1d);
            border: none;
            padding: 12px 24px;
            color: #f0f0f0;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }

        .Btn_add button:hover {
            background: linear-gradient(135deg, #2f7030, #0d1b0d);
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.7);
        }

        .Btn_add button:active {
            transform: scale(0.98);
        }

        /* Boutons d'actions */
        .actions button {
            background: linear-gradient(135deg, #345b34, #1d2a1d);
            border: 2px solid #fff;
            padding: 10px 20px;
            color: #f0f0f0;
            font-size: 14px;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .actions button:hover {
            background: linear-gradient(135deg, #2f7030, #0d1b0d);
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
        }

        .actions button:active {
            transform: scale(0.98);
        }

/* Bouton Modifier */
.edit-btn {
    border-color: #2ecc71; /* Couleur de bordure spécifique pour le bouton Modifier */
    background: linear-gradient(135deg, #4CAF50, #388E3C); /* Dégradé pour une apparence soignée */
    color: #f0f0f0; /* Texte clair pour le contraste */
}

/* Bouton Supprimer */
.delete-btn {
    background: linear-gradient(135deg, #e74c3c, #c0392b); /* Dégradé pour le bouton Supprimer */
    border-color: #c0392b; /* Couleur de bordure spécifique */
    color: #f0f0f0; /* Texte clair pour le contraste */
}

.delete-btn:hover {
    background: linear-gradient(135deg, #c0392b, #e74c3c); /* Dégradé inversé au survol */
}





.actions {
    display: flex;
    gap: 5px;
    justify-content: center;
}

/* Styles pour le bouton Retour */
.logout-button {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #3e893e, #1d2a1d);
    border: none;
    padding: 12px 24px;
    color: #f0f0f0;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    border-radius: 8px;
    transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-transform: uppercase;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
}

.logout-button:hover {
    background: linear-gradient(135deg, #2f7030, #0d1b0d);
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.7);
}

.logout-button:active {
    transform: scale(0.98);
}

.message {
    color: #fff;
    text-align: center;
    font-size: 14px;
    background-color: transparent;
    margin-bottom: 20px;
}

.message-success {
    color: #0f0;
}

.message-error {
    color: #f00;
}

 /* Ajoutez cette classe pour masquer la colonne */
 .hidden-column {
        display: none;
    }

    </style>
</head>
<body>
    <div class="container">
        <button class="logout-button" onclick="window.location.href='navbar.php'"><strong>RETOUR</strong></button>
        <h2>Liste des Entreprises</h2>
<br>
        <?php
        // Afficher le message s'il y en a un
        if (isset($_SESSION['message'])) {
            $messageClass = strpos($_SESSION['message'], 'Erreur') !== false ? 'message-error' : 'message-success';
            echo "<div class='message $messageClass'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>

<button style="background: linear-gradient(135deg, #3e893e, #1d2a1d); color: #f0f0f0; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer;" onclick="window.location.href='add.php'"><strong>AJOUTER UNE ENTREPRISE</strong></button>

<br>
<br>
        <table>
            <tr>
            <th class="hidden-column">ID</th>
                <th>Nom Commercial</th>
                <th>Adresse</th>
                <th>Email</th>
                <th>Type</th>
                <th>CIN</th>
                <th>Date de Création</th> <!-- Nouvelle colonne pour la date de création -->
                <th>Actions</th>
            </tr>
            <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td class="hidden-column"><?php echo htmlspecialchars($row['id_entreprise']); ?></td> <!-- Ajouter la classe ici -->
                <td><?php echo htmlspecialchars($row['nom_commercial']); ?></td>
                <td><?php echo htmlspecialchars($row['adresse']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['type']); ?></td>
                <td><?php echo htmlspecialchars($row['cin']); ?></td>
                <td><?php echo htmlspecialchars($row['date_creation']); ?></td>
                <td class="actions">
                    <a href="modifier.php?id=<?php echo $row['id_entreprise']; ?>">
                        <button class="edit-btn">Modifier</button>
                    </a>
                    <a href="?action=delete&id=<?php echo $row['id_entreprise']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?');">
                        <button class="delete-btn">Supprimer</button>
                    </a>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='8'>Aucune entreprise trouvée.</td></tr>";
    }
    ?>
</table>
    </div>

    <div class="background">
        <?php for ($i = 0; $i < 200; $i++): ?>
            <div class="star" style="top: <?=rand(0, 100)?>%; left: <?=rand(0, 100)?>%;"></div>
        <?php endfor; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
