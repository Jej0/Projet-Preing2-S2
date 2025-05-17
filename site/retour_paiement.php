<?php
require('../scripts_php/getapikey.php');

$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$statut = $_GET['status'] ?? '';
$control_recu = $_GET['control'] ?? '';

$api_key = getAPIKey($vendeur);
$control_attendu = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut . "#");

if ($control_recu !== $control_attendu) {
    echo "Erreur : contrôle invalide (tentative de modification des données).";
    exit;
}

if ($statut === 'accepted') {
    // 💾 Enregistrer la commande dans ta base de données
    echo "✅ Paiement accepté ! Merci pour votre réservation.";
} else {
    // ❌ Afficher un message d'erreur et des boutons de retour
    echo "❌ Paiement refusé. Veuillez réessayer.";
    echo '<br><a href="page_paiement.php">Retour au paiement</a>';
    echo '<br><a href="recapitulatif.php">Modifier le voyage</a>';
}
