<?php
require('../scripts_php/getapikey.php');

$transaction = $_GET['transaction'] ?? '';
$montant = $_GET['montant'] ?? '';
$vendeur = $_GET['vendeur'] ?? '';
$statut = $_GET['status'] ?? '';
$control_recu = $_GET['control'] ?? '';
$reservationId = $_GET['id'] ?? 0;

$api_key = getAPIKey($vendeur);
$control_attendu = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $statut . "#");

if ($control_recu !== $control_attendu) {
    echo "Erreur : contrôle invalide (tentative de modification des données).";
    exit;
}

if ($statut === 'accepted') {
    // Charger les réservations depuis le fichier JSON
    $reservations = json_decode(file_get_contents('../data/reservation.json'), true);

    // Trouver et mettre à jour la réservation
    $found = false;
    foreach ($reservations as &$reservation) {
        if ($reservation['id_reservation'] == $reservationId) {
            $reservation['paiement'] = true;
            $found = true;
            break;
        }
    }

    // Sauvegarder les modifications
    if ($found) {
        file_put_contents('../data/reservation.json', json_encode($reservations, JSON_PRETTY_PRINT));
        echo "✅ Paiement accepté ! Merci pour votre réservation.";
        // Redirection vers la page de confirmation après 3 secondes
        echo '<script>setTimeout(function(){ window.location.href = "confirmation.php?id=' . $reservationId . '"; }, 3000);</script>';
    } else {
        echo "Erreur : réservation non trouvée.";
    }
} else {
    // ❌ Afficher un message d'erreur et des boutons de retour
    echo "❌ Paiement refusé. Veuillez réessayer.";
    echo '<br><a href="recap.php?id=' . $reservationId . '">Modifier le voyage</a>';
}
