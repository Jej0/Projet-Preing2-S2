<?php
session_start();
header('Content-Type: application/json');

// 1. Vérification de l'authentification
if (!isset($_SESSION['user']) || $_SESSION['user']['id_user'] != ($_POST['user_id'] ?? null)) {
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
    if ($user['id_user'] == $_POST['user_id']) {
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
    if (isset($_POST[$field])) {
        $value = trim($_POST[$field]);
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

        // Mettre à jour la session
        $_SESSION['user'] = $users[$userKey];

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur de sauvegarde']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Aucune modification détectée']);
}
