<?php
header('Content-Type: application/json');

// Charger les données des voyages
$voyages = json_decode(file_get_contents('../data/voyages.json'), true);

$data = json_decode(file_get_contents('php://input'), true);
$voyageId = $data['voyage_id'] ?? 0;
$options = $data['options'] ?? [];
$reservationId = $data['reservation_id'] ?? null;

$response = [
    'success' => false,
    'total_price' => 0
];

// Trouver le voyage
$voyage = null;
foreach ($voyages as $v) {
    if ($v['id_voyage'] == $voyageId) {
        $voyage = $v;
        break;
    }
}

if ($voyage) {
    $totalPrice = $voyage['prix_total'];

    // Calculer le prix des options
    foreach ($voyage['etapes'] as $etape) {
        $etapeId = $etape['id_etape'];
        $etapeOptions = $options["etape_$etapeId"] ?? [];

        // Activité
        if (isset($etapeOptions['activite'])) {
            foreach ($etape['options']['activites'] as $activite) {
                if ($activite['id_option'] == $etapeOptions['activite']) {
                    $totalPrice += $activite['prix_par_personne'];
                    break;
                }
            }
        }

        // Hébergement
        if (isset($etapeOptions['hebergement'])) {
            foreach ($etape['options']['hebergements'] as $hebergement) {
                if ($hebergement['id_option'] == $etapeOptions['hebergement']) {
                    $totalPrice += $hebergement['prix_par_personne'];
                    break;
                }
            }
        }

        // Restauration
        if (isset($etapeOptions['restauration'])) {
            foreach ($etape['options']['restaurations'] as $restauration) {
                if ($restauration['id_option'] == $etapeOptions['restauration']) {
                    $totalPrice += $restauration['prix_par_personne'];
                    break;
                }
            }
        }

        // Transport
        if (isset($etapeOptions['transport'])) {
            foreach ($etape['options']['transports'] as $transport) {
                if ($transport['id_option'] == $etapeOptions['transport']) {
                    $totalPrice += $transport['prix_par_personne'];
                    break;
                }
            }
        }
    }

    $response['success'] = true;
    $response['total_price'] = $totalPrice;
}

echo json_encode($response);
