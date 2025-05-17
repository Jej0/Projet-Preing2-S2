<?php
header('Content-Type: application/json');

$voyageId = isset($_GET['voyage_id']) ? (int)$_GET['voyage_id'] : 0;
$etapeId = isset($_GET['etape_id']) ? (int)$_GET['etape_id'] : 0;

if ($voyageId <= 0 || $etapeId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Paramètres invalides']);
    exit();
}

$voyages = json_decode(file_get_contents('../data/voyages.json'), true);

$options = [];
$voyageFound = false;
$etapeFound = false;

foreach ($voyages as $voyage) {
    if ($voyage['id_voyage'] == $voyageId) {
        $voyageFound = true;
        foreach ($voyage['etapes'] as $etape) {
            if ($etape['id_etape'] == $etapeId) {
                $etapeFound = true;
                if (isset($etape['options']) && is_array($etape['options'])) {
                    // Structure cohérente même si pas d'options
                    $options = [
                        'activite' => $etape['options']['activites'] ?? [],
                        'hebergement' => $etape['options']['hebergements'] ?? [],
                        'restauration' => $etape['options']['restaurations'] ?? [],
                        'transport' => $etape['options']['transports'] ?? []
                    ];
                }
                break 2;
            }
        }
    }
}

if (!$voyageFound) {
    echo json_encode(['success' => false, 'message' => 'Voyage non trouvé']);
} elseif (!$etapeFound) {
    echo json_encode(['success' => false, 'message' => 'Étape non trouvée']);
} else {
    echo json_encode(['success' => true, 'options' => $options]);
}
