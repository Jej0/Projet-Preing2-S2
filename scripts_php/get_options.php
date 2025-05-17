<?php
header('Content-Type: application/json');

// Charger les données des voyages
$voyages = json_decode(file_get_contents('../data/voyages.json'), true);

$etapeId = isset($_GET['etape_id']) ? (int)$_GET['etape_id'] : 0;

$response = [
    'success' => false,
    'options' => []
];

foreach ($voyages as $voyage) {
    foreach ($voyage['etapes'] as $etape) {
        if ($etape['id_etape'] == $etapeId) {
            $response['success'] = true;
            $response['options'] = [
                'activite' => $etape['options']['activites'] ?? [],
                'hebergement' => $etape['options']['hebergements'] ?? [],
                'restauration' => $etape['options']['restaurations'] ?? [],
                'transport' => $etape['options']['transports'] ?? []
            ];
            break 2;
        }
    }
}

echo json_encode($response);
