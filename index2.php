






<?php
// Inclure le fichier de connexion
include 'connexion.php';
// Démarrer la session pour les messages
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location:login.php");
    exit;
}
// Suppression d'un exploitant ou d'une entreprise
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    if (isset($_GET['cin'])) {
        $cin = $_GET['cin'];
        $deleteStmt = $conn->prepare("DELETE FROM exploitant WHERE cin = ?");
        $deleteStmt->bind_param("s", $cin);
        if ($deleteStmt->execute()) {
            $_SESSION['message'] = "   Suppression effectuée avec succès !";
        } else {
            $_SESSION['message'] = "Erreur lors de la suppression : " . $deleteStmt->error;
        }
        $deleteStmt->close();
    } elseif (isset($_GET['id'])) {
        $id_entreprise = $_GET['id'];
        $deleteStmt = $conn->prepare("DELETE FROM entreprise WHERE id_entreprise = ?");
        $deleteStmt->bind_param("i", $id_entreprise);
        if ($deleteStmt->execute()) {
            $_SESSION['message'] = "   Suppression effectuée avec succès !";
        } else {
            $_SESSION['message'] = "Erreur lors de la suppression : " . $deleteStmt->error;
        }
        $deleteStmt->close();
    }
    header("Location: index2.php"); // Redirection pour afficher le message
    exit();
}

// Rechercher exploitants et entreprises
$searchQuery = "";
$params = [];
$types = "";

if (isset($_GET['search_cin']) && !empty($_GET['search_cin'])) {
    $searchTermCin = '%' . $conn->real_escape_string($_GET['search_cin']) . '%';
    $searchQuery .= " e.cin LIKE ?";
    $params[] = $searchTermCin;
    $types .= "s";
}

if (isset($_GET['search_nom_commercial']) && !empty($_GET['search_nom_commercial'])) {
    $searchTermNomCommercial = '%' . $conn->real_escape_string($_GET['search_nom_commercial']) . '%';
    if (!empty($searchQuery)) {
        $searchQuery .= " AND";
    }
    $searchQuery .= " ent.nom_commercial LIKE ?";
    $params[] = $searchTermNomCommercial;
    $types .= "s";
}

// Préparer la requête SQL
$query = "SELECT e.cin, e.nom, e.prenom, e.adresse_exploitant, e.email_exploitant, e.type_exploitant, 
                 ent.id_entreprise, ent.nom_commercial, ent.adresse, ent.email, ent.type, ent.date_creation
          FROM exploitant e 
          LEFT JOIN entreprise ent ON e.cin = ent.cin";

if (!empty($searchQuery)) {
    $query .= " WHERE " . $searchQuery;
}

$stmt = $conn->prepare($query);

// Lier les paramètres si nécessaires
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Exploitants et Entreprises</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
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
    width:990px;
    max-width: 1000px;
    padding: 30px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
    z-index: 1;
    margin: 0 auto;
    text-align: center;
    overflow-x: auto;
}

h2 {
    color: #fff;
}

.search-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.search-container input[type="text"] {
    padding: 10px;
    border: 1px solid #fff;
    border-radius: 8px;
    font-size: 14px;
    color: #000;
}

.search-container button {
    background: linear-gradient(135deg, #2f7030, #0d1b0d);
    border: none;
    padding: 10px 20px;
    color: white;
    font-size: 14px;
    cursor: pointer;
    border-radius: 8px;
    transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
}

.search-container button:hover {
    transform: scale(1.05);
    box-shadow: 0 0 25px rgba(255, 255, 255, 0.7);
}

.Btn_add_container {
    display: flex;
    justify-content: flex-start;
    gap: 20px;
    margin-bottom: 20px;
}

.Btn_add a {
    text-decoration: none;
    
}

.Btn_add button, .print-btn {
    background: linear-gradient(to right, #345b34, #b8d8b0);
    border: none;
    padding: 15px 25px;
    color: white;
    font-size: 12px;
    cursor: pointer;
    border-radius: 8px;
    transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
}

.Btn_add button:hover, .print-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 0 25px rgba(255, 255, 255, 0.7);
}

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

/* Styles pour la table */
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
    width: 177%; /* Utilise toute la largeur disponible */
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
    padding: 12px 12px;
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

/* Styles pour les boutons dans les actions */
.actions {
    display: flex;
    gap: 5px;
    justify-content: center;
}

.actions button {
    background: linear-gradient(to right, #345b34, #b8d8b0);
    border: 2px solid #fff;
    padding: 5px 10px;
    color: white;
    font-size: 12px;
    cursor: pointer;
    border-radius: 8px;
    transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

/* Styles pour les boutons modifiés et supprimés */
.actions button:hover {
    transform: scale(1.05);
    box-shadow: 0 0 25px rgba(255, 255, 255, 0.7);
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

.hidden-column {
    display: none;
}
/* Styles pour les boutons d'action */
.actions button, .Btn_add button, .print-btn, .edit-btn, .delete-btn {
    background: linear-gradient(135deg, #2f7030, #0d1b0d);
    border: none;
    padding: 10px 20px; /* Ajustez la taille des boutons pour qu'ils soient de la même taille */
    color: white;
    font-size: 14px; /* Assurez-vous que la taille de la police est uniforme */
    cursor: pointer;
    border-radius: 8px;
    transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.actions button:hover, .Btn_add button:hover, .print-btn:hover, .edit-btn:hover, .delete-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 0 25px rgba(255, 255, 255, 0.7);
}

/* Styles spécifiques pour les boutons Modifier et Supprimer */
.edit-btn {
    background: linear-gradient(135deg, #2f7030, #0d1b0d);
    color: #f0f0f0;
    font-size: 16px;
    font-weight: 600;
}

.delete-btn {
    background: linear-gradient(135deg, #2f7030, #0d1b0d); /* Couleur d'arrière-plan spécifique pour le bouton Supprimer */
    color: #fff;
    font-size: 16px;
    font-weight: 600;
}

    </style>
</head>
<body>
<div class="container">
        <button class="logout-button" onclick="window.location.href='navbar.php'"><strong>RETOUR</strong></button>
        <h2>Liste des Exploitants et Entreprises</h2>
        <?php
        // Afficher le message s'il y en a un
        if (isset($_SESSION['message'])) {
            $messageClass = strpos($_SESSION['message'], 'Erreur') !== false ? 'message-error' : 'message-success';
            echo "<div class='message $messageClass'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']);
        }
        ?>

<div class="search-container">
    <form method="get" action="">
        <input type="text" name="search_cin" placeholder="CIN..." value="<?php echo isset($_GET['search_cin']) ? htmlspecialchars($_GET['search_cin']) : ''; ?>">
        <input type="text" name="search_nom_commercial" placeholder="Nom Commercial..." value="<?php echo isset($_GET['search_nom_commercial']) ? htmlspecialchars($_GET['search_nom_commercial']) : ''; ?>">
        <button  class="edit-btn" type="submit">Rechercher</button>
    </form>
</div>

<a href="form1.php">
    <button class="edit-btn">Ajouter</button>
</a>
<br>
<br>
        <table>
    <tr>
        <th>CIN</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Adresse Exploitant</th>
        <th>Email Exploitant</th>
        <th>Type Exploitant</th>
        <th>Nom Commercial</th>
        <th>Adresse Entreprise</th>
        <th>Email Entreprise</th>
        <th>Type Entreprise</th>
        <th>Date de Création</th>
        <th class="hidden-column">ID Entreprise</th> <!-- Nouvelle colonne ajoutée avec classe -->
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['cin']); ?></td>
                <td><?php echo htmlspecialchars($row['nom']); ?></td>
                <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                <td><?php echo htmlspecialchars($row['adresse_exploitant']); ?></td>
                <td><?php echo htmlspecialchars($row['email_exploitant']); ?></td>
                <td><?php echo htmlspecialchars($row['type_exploitant']); ?></td>
                <td><?php echo htmlspecialchars($row['nom_commercial']); ?></td>
                <td><?php echo htmlspecialchars($row['adresse']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['type']); ?></td>
                <td><?php echo htmlspecialchars($row['date_creation']); ?></td>
                <td class="hidden-column"><?php echo htmlspecialchars($row['id_entreprise']); ?></td> <!-- Affichage de l'ID Entreprise avec classe -->
                <td class="actions">
    <a href="form.php?cin=<?php echo urlencode($row['cin']); ?>&id_entreprise=<?php echo urlencode($row['id_entreprise']); ?>">
        <button class="edit-btn">Modifier</button>
    </a>
    <button class="delete-btn" onclick="confirmDelete('<?php echo urlencode($row['cin']); ?>', '<?php echo urlencode($row['id_entreprise']); ?>')">Supprimer</button>
</td>

            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='12'>Aucun exploitant trouvé.</td></tr>";
    }
    ?>
</table>


    </div>
    <div class="background">
        
    </div>
    <script>
function confirmDelete(cin, id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet exploitant et cette entreprise ?')) {
        // Rediriger vers la page de traitement de la suppression
        window.location.href = `index2.php?action=delete&cin=${cin}&id=${id}`;
    }
}
</script>

</body>
</html>