<?php
include 'connexion.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cin = $_POST['cin'];
    $nomCommercial = $_POST['nomCommercial'];

    $sql = "SELECT montant, date_creation FROM entreprise WHERE cin = ? AND nom_commercial = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ss", $cin, $nomCommercial);

        $stmt->execute();
        $stmt->bind_result($amount, $creationDate);

        if ($stmt->fetch()) {
            $response = array(
                'success' => true,
                'cin' => $cin,
                'nom_commercial' => $nomCommercial,
                'amount' => $amount,
                'creation_date' => $creationDate
            );
        } else {
            $response = array(
                'success' => false,
                'cin' => $cin,
                'nom_commercial' => $nomCommercial
            );
        }

        $stmt->close();
        echo json_encode($response);
    } else {
        echo json_encode(array('success' => false));
    }
}

$conn->close();
?>
