<?php
session_start();
header('Content-Type: application/json');

// Supporte JSON (fetch/AJAX) et POST classique
$input = file_get_contents('php://input');
$data = [];
if (!empty($input)) {
    $data = json_decode($input, true);
}
if (!$data) $data = $_POST;

// 1. Vérification de l'authentification
if (
    !isset($_SESSION['user']) ||
    !isset($data['user_id']) ||
    (
        $_SESSION['user']['id_user'] != $data['user_id']
        && (empty($_SESSION['user']['admin']) || $_SESSION['user']['admin'] !== true)
    )
) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

// 2. Chemin du fichier JSON
$filePath = '../data/users.json';
if (!file_exists($filePath) || !is_writable($filePath)) {
    echo json_encode(['success' => false, 'message' => 'Erreur système']);
    exit;
}

// 3. Charger les données
$users = json_decode(file_get_contents($filePath), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Données corrompues']);
    exit;
}

// 4. Trouver l'utilisateur
$userKey = null;
foreach ($users as $key => $user) {
    if ($user['id_user'] == $data['user_id']) {
        $userKey = $key;
        break;
    }
}

if ($userKey === null) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
    exit;
}

// 5. Mettre à jour les champs
$updated = false;
$allowedFields = ['nom', 'prenom', 'naissance', 'mail', 'telephone', 'adresse'];

foreach ($allowedFields as $field) {
    if (isset($data[$field])) {
        $value = trim($data[$field]);
        $users[$userKey]['information_personnelles'][$field] = $value === '' ? null : $value;
        $updated = true;
    }
}

// 6. Sauvegarder si modifications
if ($updated) {
    $users[$userKey]['date']['connexion'] = date('Y-m-d H:i:s');
    try {
        if (file_put_contents(
            $filePath,
            json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            LOCK_EX
        ) === false) {
            throw new Exception('Erreur d\'écriture');
        }
        // Mettre à jour la session si c'est l'utilisateur connecté
        if ($_SESSION['user']['id_user'] == $users[$userKey]['id_user']) {
            $_SESSION['user'] = $users[$userKey];
        }
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur de sauvegarde']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Aucune modification détectée']);
}
