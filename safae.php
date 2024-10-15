<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location:login.php");
    exit;
}
?>
 
 

 
 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calcul de la Taxe</title>
    <style>
   @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    background: repeating-linear-gradient(135deg, black 0%, #3e893e 50%);
    overflow-x: hidden;
    font-family: 'Montserrat', sans-serif;
    color: #f0f0f0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    position: relative;
}

.container {
    background: rgba(0, 0, 0, 0.8);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
    width: 100%;
    max-width: 900px;
    position: relative;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    color: #e0e0e0;
}

.form-group {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
}

.form-group > div {
    flex: 1;
    min-width: 0;
}

.form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    align-items: center;
}

.form-row > div {
    flex: 1;
}

label {
    font-weight: bold;
    color: #e0e0e0;
}

input, select {
    width: 100%;
    padding: 12px;
    margin: 5px 0;
    border: 1px solid #555;
    border-radius: 6px;
    background-color: #222;
    color: #f0f0f0;
    font-size: 16px;
}

button {
    background: linear-gradient(135deg, #3e893e, #1d2a1d);;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
    margin: 10px 0;
    font-size: 18px;
    transition: background 0.3s, transform 0.2s, box-shadow 0.2s;
}

button:hover {
    background: linear-gradient(135deg, #3e893e, #1d2a1d);;
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
}

.actions {
    display: flex;
    flex-wrap: wrap; /* Permet aux boutons de revenir à la ligne si l'espace est insuffisant */
    gap: 10px;
}

.actions button {
    flex: 1; /* Assure que tous les boutons prennent la même largeur */
    margin: 5px; /* Ajoute une marge autour des boutons pour éviter qu'ils ne se touchent */
}

.result {
    margin-top: 20px;
    text-align: center;
    display: none;
}

.table-container {
    margin-top: 20px;
    overflow-x: auto;
}

table {
    width: 150%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    border: 1px solid #444;
    text-align: left;
    font-size: 14px;
}

th {
    background-color: #333;
    color: #e0e0e0;
}

table td:first-child {
    font-weight: bold;
}
@media print {
    th:nth-child(1), td:nth-child(1),
    th:nth-child(12), td:nth-child(12) {
        display: none; /* Masque les colonnes 1 (Année) et 12 (Action) lors de l'impression */
    }


    body * {
        visibility: hidden; /* Cache tout le contenu par défaut */
    }

    #header-print, #result, #result * {
        visibility: visible; /* Rend visible seulement la section à imprimer */
    }

    #header-print {
        position: absolute;
        top: 50px; /* Ajuste la distance du haut de la page, augmente si nécessaire */
        left: 0;
        width: 100%;
        margin: 0;
        padding: 10px;
        color: black;
        text-align: left;
        font-size: 18px; /* Ajuste la taille de la police si nécessaire */
    }
    #printDateTime {
                position: absolute;
                top: 50px; /* Ajustez la position verticale si nécessaire */
                right: 0;
                margin: 0;
                padding: 10px;
                color: black;
                text-align: right;
                font-size: 12px; /* Ajuste la taille de la police si nécessaire */
            }

    .container {
        width: 100%; /* Définit la largeur du conteneur à 96% de la largeur de la page */
        margin: 0 auto; /* Centre le conteneur horizontalement */
        position: absolute; /* Positionne le conteneur par rapport à la page */
        top: 100px; /* Ajuste la position verticale de la table pour éviter le chevauchement avec l'en-tête */
        page-break-inside: avoid; /* Évite les coupures de page à l'intérieur du conteneur */
    }

    table {
        color: black;
        border-collapse: collapse;
        width: 100%; /* Utilise toute la largeur du conteneur (96%) */
        border: 1px solid #000;
        table-layout: auto; /* Permet aux colonnes de s'ajuster en fonction du contenu */
    }

    th, td {
        color: black;
        border: 1px solid #000;
        padding: 8px;
        font-size: 12px;
        text-align: left;
        word-wrap: break-word; /* Permet au texte de revenir à la ligne */
        overflow: hidden; /* Cache les débordements pour éviter les problèmes de mise en page */
        white-space: normal; /* Permet le retour à la ligne automatique */
    }

    .delete-btn {
        display: none; /* Masque les boutons de suppression lors de l'impression */
    }

    .actions, .success-message {
        display: none; /* Masque les boutons d'action et les messages de succès lors de l'impression */
    }
    

    @page {
        margin: 0; /* Réduit les marges de la page pour maximiser l'espace d'impression */
    }
    #signature {
                position: absolute;
                bottom: 20px;
                width: 100%;
                text-align: center;
                font-size: 14px;
                color: black;
            }

}

    </style>
</head>
<body>


<div class="container">
    <h2>Calcul de la Taxe en DH</h2>

    <div class="form-group">
        <div>
            <label for="cin">CIN:</label>
            <input type="text" id="cin" placeholder="Entrez le CIN" required>
        </div>
        <div>
            <label for="nomCommercial">Nom Commercial:</label>
            <input type="text" id="nomCommercial" placeholder="Entrez le nom commercial" required>
        </div>
        <button onclick="fetchAmountByCINAndNom()">Verifier</button>
    </div>

    <div class="form-group">
        <div>
            <label for="amount">Montant (en DH):</label>
            <input type="number" id="amount" placeholder="Entrez le montant" required>
        </div>
        <div>
            <label for="revenueType">Type de revenu:</label>
            <select id="revenueType">
                <option value="drinks">Boissons</option>
                <option value="alcohol">Alcool</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <div>
            <label for="trimester">Trimestre :</label>
            <select id="trimester">
                <option value="1">Premier trimestre (Avril)</option>
                <option value="2">Deuxième trimestre (Juillet)</option>
                <option value="3">Troisième trimestre (Octobre)</option>
                <option value="4">Quatrième trimestre (Janvier)</option>
            </select>
        </div>
        <div>
            <label for="paymentDate">Date de paiement:</label>
            <input type="date" id="paymentDate" name="paymentDate" required>
        </div>
        <div>
            <label for="creationDate">Date de création:</label>
            <input type="date" id="creationDate" required>
        </div>
        <div >
    <div>
        <label for="year">Année:</label>
        <input type="number" id="year" placeholder="Entrez l'année" required>
    </div>
</div>

    </div>

    <div class="actions">
    <button onclick="calculateTax()">Calculer la Taxe</button>
    <button onclick="storePayment()">Enregistrer le Paiement</button>
</div>

<div class="actions">
    <button onclick="printResult()">Imprimer</button>
    <button onclick="goBack()">Retour</button>
</div>


    <div id="result" class="result">
        <h3 class="success-message">Résultats des Calculs</h3>
        <div class="table-container">
            <table id="resultTable">
                <thead >
                    <tr>
                    <th>Année</th> <!-- Nouvelle colonne pour l'année -->
                        <th>Montant</th>
                        <th>Type de revenu</th>
                        <th>Trimestre de paiement</th>
                        <th>Date de paiement</th>
                        <th>Date de création</th>
                        <th>Taux de taxe</th>
                        <th>Montant de la taxe</th>
                        <th>Retard (mois)</th>
                        <th>Majoration pour retard</th>
                        <th>Montant total</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Les résultats seront ajoutés ici -->
                </tbody>
            </table>
        </div>
    </div>

    <div id="message" class="message"></div>
     <!-- Élément pour les messages -->
     <div id="printDateTime" style="display:none;"></div>

</div>

<script>
        
function fetchAmountByCINAndNom() {
    const cin = document.getElementById('cin').value;
    const nomCommercial = document.getElementById('nomCommercial').value;

    if (!cin || !nomCommercial) {
        alert('Veuillez entrer le CIN et le nom commercial.');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'fetch_amount.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                document.getElementById('amount').value = response.amount;
                document.getElementById('creationDate').value = response.creation_date;
            } else {
                alert('Erreur lors de la récupération du montant.');
            }
        }
    };

    xhr.send(`cin=${encodeURIComponent(cin)}&nomCommercial=${encodeURIComponent(nomCommercial)}`);
}
function calculateTax() {
    const amount = parseFloat(document.getElementById('amount').value);
    const revenueType = document.getElementById('revenueType').value;
    const trimester = parseInt(document.getElementById('trimester').value);
    const paymentDate = new Date(document.getElementById('paymentDate').value);
    const creationDate = new Date(document.getElementById('creationDate').value);
    const year = parseInt(document.getElementById('year').value);

    if (isNaN(amount) || isNaN(trimester) || isNaN(paymentDate.getTime()) || isNaN(creationDate.getTime()) || isNaN(year)) {
        alert('Veuillez entrer tous les champs correctement.');
        return;
    }

    // Vérification si l'année saisie est inférieure à l'année de création
    if (year < creationDate.getFullYear()) {
        addResultToTable(amount, revenueType, trimester, paymentDate.toISOString().split('T')[0], creationDate.toISOString().split('T')[0], 0, 0, 0, 0, 0);
        document.getElementById('result').style.display = 'block';
        return;
    }

    // Déterminer le mois de début et l'année de début
    let startYear = year;
    let startMonth = 0; // Janvier par défaut

    if (trimester === 1) {
        startMonth = 3; // Avril
    } else if (trimester === 2) {
        startMonth = 6; // Juillet
    } else if (trimester === 3) {
        startMonth = 9; // Octobre
    } else if (trimester === 4) {
        startMonth = 0; // Janvier de l'année suivante
        startYear++;
    }

    const startDate = new Date(startYear, startMonth, 1);

    // Calculer le nombre total de mois entre la date de début et la date de paiement
    const totalMonths = (paymentDate.getFullYear() - startDate.getFullYear()) * 12 + (paymentDate.getMonth() - startDate.getMonth());
    
    // Calcul des mois de retard
    const lateMonths = Math.max(totalMonths, 0);

    let taxRate = 0;
    let lateFeeRate = 0;

    if (revenueType === 'drinks') {
        taxRate = 0.03; // 3% pour les boissons
        lateFeeRate = 0.005; // 0.5% par mois de retard
    } else if (revenueType === 'alcohol') {
        taxRate = 0.10; // 10% pour l'alcool
        lateFeeRate = 0.005; // 0.5% par mois de retard
    }

    const taxAmount = amount * taxRate;

    // Calcul des frais de retard
    let lateFee = 0;
    for (let i = 1; i <= lateMonths; i++) {
        lateFee += taxAmount * (0.15 + lateFeeRate * (i - 1)); // Majoration de 0,5% par mois de retard supplémentaire
    }

    // Montant total à payer
    const totalAmount = taxAmount + lateFee;

    addResultToTable(year,amount, revenueType, trimester, paymentDate.toISOString().split('T')[0], creationDate.toISOString().split('T')[0], taxRate * 100, taxAmount.toFixed(2), lateMonths, lateFee.toFixed(2), totalAmount.toFixed(2));

    // Afficher la table des résultats
    document.getElementById('result').style.display = 'block';
}



function addResultToTable(year,amount, revenueType, trimester, paymentDate, creationDate, taxRate, taxAmount, lateMonths, lateFee, totalAmount) {
    const tableBody = document.querySelector('#resultTable tbody');
    const row = document.createElement('tr');

    row.innerHTML = `
    <th>${year}</th>
        <th>${amount}</th>
        <th>${revenueType}</th>
        <th>${trimester}</th>
        <th>${paymentDate}</th>
        <th>${creationDate}</th>
        <th>${taxRate}%</th>
        <th>${taxAmount} DH</th>
        <th>${lateMonths} mois</th>
        <th>${lateFee} DH</th>
        <th>${totalAmount} DH</th>
        <th><button class="delete-btn" onclick="deleteRow(this)" style="margin-left: 10px;">Supprimer</button></th>
    `;

    tableBody.appendChild(row);
}

function storePayment() {
    const cin = document.getElementById('cin').value;
    const nomCommercial = document.getElementById('nomCommercial').value;
    const amount = parseFloat(document.getElementById('amount').value);
    const revenueType = document.getElementById('revenueType').value;
    const trimester = document.getElementById('trimester').value;
    const paymentDate = document.getElementById('paymentDate').value;
    const creationDate = document.getElementById('creationDate').value;
    const year = parseInt(document.getElementById('year').value); // Nouvelle ligne pour l'année

    // Validation des champs
    if (!cin || !nomCommercial || isNaN(amount) || !revenueType || !trimester || !paymentDate || !creationDate || isNaN(year)) {
        alert('Veuillez remplir tous les champs du formulaire.');
        return;
    }

    // Récupération du montant total à partir de la dernière ligne de la table
    const lastRow = document.querySelector('#resultTable tbody tr:last-child');
    const totalAmountCell = lastRow ? lastRow.cells[lastRow.cells.length - 2] : null;
    const totalAmount = totalAmountCell ? parseFloat(totalAmountCell.textContent.trim().replace(' DH', '')) : NaN;

    if (isNaN(totalAmount)) {
        alert('Erreur dans le calcul du montant total.');
        return;
    }

    // Envoi des données via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'store_payment.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            alert('Paiement enregistré avec succès !');
            document.getElementById('result').innerHTML += '<p class="success-message">Paiement enregistré avec succès !</p>';
        } else {
            alert('Une erreur est survenue lors de l\'enregistrement du paiement.');
        }
    };

    // Envoi des données au serveur
    xhr.send('cin=' + encodeURIComponent(cin) +
             '&nomCommercial=' + encodeURIComponent(nomCommercial) +
             '&amount=' + encodeURIComponent(amount) +
             '&revenueType=' + encodeURIComponent(revenueType) +
             '&trimester=' + encodeURIComponent(trimester) +
             '&paymentDate=' + encodeURIComponent(paymentDate) +
             '&creationDate=' + encodeURIComponent(creationDate) +
             '&totalAmount=' + encodeURIComponent(totalAmount) +
             '&year=' + encodeURIComponent(year)); // Nouvelle ligne pour l'année
}


function printResult() {
    // Create the div for the header information
    const headerInfo = document.createElement('div');
    headerInfo.id = 'header-info';
    headerInfo.innerHTML = `
        <div>
        <br>
            ROYAUME DU MAROC<br>
            COMMUNE SAFI<br>
            REGIE DE RECETTES-COMMUNE URBAINE DE SAFI (11900600)<br>
            <br><br><br><br><br><br><br><br><br><br><br><div style="text-align: center;">
    <span style="font-size: 15px; font-style: italic;font-weight: bold; font-family: Arial, Helvetica, sans-serif;">
        Annee de paiement: ${document.getElementById('year').value}
    </span>
</div>
<br><br><br><br>
            <span style="font-size: 15px;font-style: italic; font-weight: bold;font-family: Arial, Helvetica, sans-serif;margin-left: 26px;"> La Carte d'Identité Nationale: ${document.getElementById('cin').value}</span><br><br>
            <span style="font-size: 15px; font-style: italic;font-weight: bold;font-family: Arial, Helvetica, sans-serif;margin-left: 26px;">Nom Commercial: ${document.getElementById('nomCommercial').value}</span><br><br><br><br>
<div style="position: fixed; bottom: 140px; left: 68%; transform: translateX(-50%); font-size: 16px; font-weight: bold;">
    Signature:
</div>

        </div>
    `;
    headerInfo.style.margin = '0';
    headerInfo.style.padding = '15px';
    headerInfo.style.color = 'black';
    headerInfo.style.textAlign = 'left';
    headerInfo.style.fontSize = '12px';
    headerInfo.style.position = 'fixed';
    headerInfo.style.top = '13px';
    headerInfo.style.left = '0';
    headerInfo.style.width = '110%';
    headerInfo.style.backgroundColor = 'white';
    headerInfo.style.zIndex = '1000';

   
   
    // Create the div for the signature
    const signatureDiv = document.createElement('div');
    signatureDiv.id = 'signature';
    signatureDiv.innerHTML = `
       <div style="text-align: right; margin-top: 300px; font-size: 16px; font-weight: bold;">Signature:</div>
        </div>
    `;
    signatureDiv.style.position = 'fixed';
    signatureDiv.style.right = '20px';
    signatureDiv.style.bottom = '20px';
    signatureDiv.style.color = 'black';
    signatureDiv.style.backgroundColor = 'white';
    signatureDiv.style.padding = '10px';
    signatureDiv.style.zIndex = '1000';

    // Insert the header info div before the table
    const table = document.querySelector('table');
    if (table) {
        table.parentNode.insertBefore(headerInfo, table);
    }

    // Add the date and time div to the beginning of the body
    document.body.insertBefore(printDateTime, document.body.firstChild);

    // Add the signature div to the end of the body
    document.body.appendChild(signatureDiv);

    // Ensure the layout is updated before printing
    requestAnimationFrame(() => {
        // Print the page
        window.print();

        // Remove the elements after printing
        document.body.removeChild(printDateTime);
        document.body.removeChild(signatureDiv);
        if (headerInfo.parentNode) {
            headerInfo.parentNode.removeChild(headerInfo);
        }
    });
}


function deleteRow(button) {
    const row = button.closest('tr');
    row.remove();
}


function goBack() {
    window.location.href = 'navbar.php'; // Remplacez par la page vers laquelle vous souhaitez rediriger
}


   
    </script>
</body>
</html>
