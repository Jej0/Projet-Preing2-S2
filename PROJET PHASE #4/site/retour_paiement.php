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

// Vérification du contrôle de sécurité
if ($control_recu !== $control_attendu) {
    echo "Erreur : contrôle invalide (tentative de modification des données).";
    exit;
}

// Charger la réservation pour obtenir l'ID utilisateur
$userId = null;
$reservations = json_decode(file_get_contents('../data/reservation.json'), true);
foreach ($reservations as $reservation) {
    if ($reservation['id_reservation'] == $reservationId) {
        $userId = $reservation['id_utilisateur'];
        break;
    }
}

// Enregistrer le paiement dans le fichier JSON
$paiementData = [
    'transaction' => $transaction,
    'montant' => $montant,
    'vendeur' => $vendeur,
    'statut' => $statut,
    'date' => date('Y-m-d H:i:s'),
    'reservation_id' => $reservationId,
    'user_id' => $userId,  // Nouveau champ ajouté
    'control' => $control_recu
];

// Charger ou créer le fichier paiement.json
$paiements = [];
if (file_exists('../data/paiement.json')) {
    $paiements = json_decode(file_get_contents('../data/paiement.json'), true);
    if (!is_array($paiements)) {
        $paiements = [];
    }
}

// Ajouter le nouveau paiement
$paiements[] = $paiementData;
file_put_contents('../data/paiement.json', json_encode($paiements, JSON_PRETTY_PRINT));

if ($statut === 'accepted') {
    // Mettre à jour la réservation comme payée
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
        echo '<script>setTimeout(function(){ window.location.href = "profile.php"; }, 3000);</script>';
    } else {
        echo "Erreur : réservation non trouvée.";
    }
} else {
    // ❌ Afficher un message d'erreur et des boutons de retour
    echo "❌ Paiement refusé. Veuillez réessayer.";
    echo '<br><a href="recap.php?id=' . $reservationId . '">Modifier le voyage</a>';
}
