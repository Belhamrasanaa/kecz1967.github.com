<?php
include 'connexion.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cin = $_POST['cin'];
    $nomCommercial = $_POST['nomCommercial'];
    $paymentDate = $_POST['paymentDate']; // Date de paiement saisie par l'utilisateur
    $trimester = $_POST['trimester'];
    $revenueType = $_POST['revenueType']; // Nouveau champ pour le type de revenu

    // Définir le taux en fonction du type de revenu
    $taux = ($revenueType === 'alcohol') ? 10.0 : 3.0; // Taux de 10% pour l'alcool, 3% pour les autres

    // Vérifier si les champs requis sont remplis
    if (empty($cin) || empty($nomCommercial) || empty($paymentDate) || empty($trimester)) {
        echo json_encode(array('success' => false, 'message' => 'Veuillez remplir tous les champs requis.'));
        exit;
    }

    // Récupérer le montant et la date de création à partir de la table entreprise
    $sql = "SELECT montant, date_creation FROM entreprise WHERE cin = ? AND nom_commercial = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $cin, $nomCommercial);
        $stmt->execute();
        $stmt->bind_result($amount, $creationDate);

        if ($stmt->fetch()) {
            // Insertion des détails du paiement dans la table payment_history
            $sql = "INSERT INTO payment_history (cin, nom_commercial, date_de_paiement, trimiste, montant, taux) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("sssdid", $cin, $nomCommercial, $paymentDate, $trimester, $amount, $taux);

                if ($stmt->execute()) {
                    echo json_encode(array('success' => true, 'message' => 'Paiement enregistré avec succès !'));
                } else {
                    echo json_encode(array('success' => false, 'message' => 'Erreur lors de l\'enregistrement du paiement.'));
                }

                $stmt->close();
            } else {
                echo json_encode(array('success' => false, 'message' => 'Erreur de préparation de la requête d\'insertion.'));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'Aucune donnée trouvée pour le CIN et le nom commercial.'));
        }

        $stmt->close();
    } else {
        echo json_encode(array('success' => false, 'message' => 'Erreur de préparation de la requête de sélection.'));
    }

    $conn->close();
}
?>
