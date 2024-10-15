<?php
// Inclure le fichier de connexion
include 'connexion.php';

// Démarrer la session pour les messages
session_start();

// Variables pour stocker les données de l'entreprise et de l'exploitant
$entreprise = [];
$exploitant = [];

// Récupérer l'ID de l'entreprise et le CIN de l'exploitant depuis l'URL
$id_entreprise = isset($_GET['id_entreprise']) ? intval($_GET['id_entreprise']) : 0;
$cin = isset($_GET['cin']) ? $_GET['cin'] : '';

// Récupérer les données de l'entreprise à modifier si l'ID est présent
if ($id_entreprise) {
    $stmt = $conn->prepare("SELECT * FROM entreprise WHERE id_entreprise = ?");
    $stmt->bind_param("i", $id_entreprise);
    $stmt->execute();
    $result = $stmt->get_result();
    $entreprise = $result->fetch_assoc();
    $stmt->close();
}

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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire pour l'entreprise
    $nom_commercial = $_POST['nom_commercial'];
    $adresse = $_POST['adresse'];
    $email = $_POST['email'];
    $type = $_POST['type'];
    $cin = $_POST['cin'];
    $date_creation = $_POST['date_creation'];

    // Préparer la requête de mise à jour pour l'entreprise
    $stmt = $conn->prepare("UPDATE entreprise SET nom_commercial = ?, adresse = ?, email = ?, type = ?, cin = ?, date_creation = ? WHERE id_entreprise = ?");
    $stmt->bind_param("ssssssi", $nom_commercial, $adresse, $email, $type, $cin, $date_creation, $id_entreprise);

    // Exécuter la requête et vérifier les erreurs
    if ($stmt->execute()) {
        $_SESSION['message'] = "Entreprise modifiée avec succès !";
    } else {
        $_SESSION['message'] = "Erreur lors de la modification de l'entreprise : " . $stmt->error;
    }
    $stmt->close();

    // Récupérer les données du formulaire pour l'exploitant
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $adresse_exploitant = $_POST['adresse_exploitant'];
    $email_exploitant = $_POST['email_exploitant'];
    $type_exploitant = $_POST['type_exploitant'];

    // Préparer la requête de mise à jour pour l'exploitant
    $stmt = $conn->prepare("UPDATE exploitant SET nom = ?, prenom = ?, adresse_exploitant = ?, email_exploitant = ?, type_exploitant = ? WHERE cin = ?");
    $stmt->bind_param("ssssss", $nom, $prenom, $adresse_exploitant, $email_exploitant, $type_exploitant, $cin);

    // Exécuter la requête et vérifier les erreurs
    if ($stmt->execute()) {
        $_SESSION['message'] = "Exploitant modifié avec succès !";
    } else {
        $_SESSION['message'] = "Erreur lors de la modification de l'exploitant : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirection pour afficher le message
    header("Location: index2.php");
    exit();
}
?>