<?php
include 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cin']) && isset($_POST['montant'])) {
    $cin = $_POST['cin'];
    $montant = $_POST['montant'];

    // Insérer le montant dans la table montant
    $stmt = $conn->prepare("INSERT INTO montant (montant_totale) VALUES (?)");
    $stmt->bind_param("d", $montant);
    
    if ($stmt->execute()) {
        $id_montant = $stmt->insert_id;

        // Insérer le revenu avec le montant ID
        $stmt_revenue = $conn->prepare("INSERT INTO revenue (id_montant, type_revenue) VALUES (?, 'type_de_revenue')");
        $stmt_revenue->bind_param("i", $id_montant);

        if ($stmt_revenue->execute()) {
            echo "Montant ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout du revenu: " . $stmt_revenue->error;
        }

        $stmt_revenue->close();
    } else {
        echo "Erreur lors de l'ajout du montant: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
