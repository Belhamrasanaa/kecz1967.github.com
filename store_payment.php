<?php
include 'connexion.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cin = $_POST['cin'];
    $nomCommercial = $_POST['nomCommercial'];
    $amount = $_POST['amount'];
    $revenueType = $_POST['revenueType'];
    $trimester = $_POST['trimester'];
    $paymentDate = $_POST['paymentDate'];
    $creationDate = $_POST['creationDate'];
    $totalAmount = $_POST['totalAmount'];
    $year = $_POST['year']; // Nouvelle ligne pour l'année

    // Définir le taux en fonction du type de revenu
    if ($revenueType === 'alcohol') {
        $taux = 10.0; // Taux de 10% pour l'alcool
    } else {
        $taux = 3.0; // Taux de 3% pour les boissons non alcoolisées
    }

    // Vérifier si les champs requis sont remplis
    if (empty($cin) || empty($nomCommercial) || empty($paymentDate) || empty($trimester) || empty($totalAmount) || empty($year)) {
        echo json_encode(array('success' => false, 'message' => 'Veuillez remplir tous les champs requis.'));
        exit;
    }

    // Préparer la requête d'insertion dans la table payment_history
    $sql = "INSERT INTO payment_history (cin, nom_commercial, date_de_paiement, trimiste, taux, total_amount, annee) VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssdds", $cin, $nomCommercial, $paymentDate, $trimester, $taux, $totalAmount, $year);

        if ($stmt->execute()) {
            echo json_encode(array('success' => true, 'message' => 'Paiement enregistré avec succès !'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Erreur lors de l\'enregistrement du paiement.'));
        }

        $stmt->close();
    } else {
        echo json_encode(array('success' => false, 'message' => 'Erreur de préparation de la requête.'));
    }
}

$conn->close();
?>
