<?php
// Réponse en JSON
header('Content-Type: application/json');

// Récupère l'email
$email = $_POST['email'] ?? '';

// Vérifie si l'email est valide
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email invalide']);
    exit;
}

// Chemin vers le fichier JSON
$file = '../data/newsletter.json';

// Lit les emails existants
$emails = [];
if (file_exists($file)) {
    $emails = json_decode(file_get_contents($file), true) ?: [];
}

// Vérifie si l'email existe déjà
if (in_array($email, $emails)) {
    echo json_encode(['success' => false, 'message' => 'Déjà inscrit']);
    exit;
}

// Ajoute le nouvel email
$emails[] = $email;

// Sauvegarde dans le fichier
if (file_put_contents($file, json_encode($emails))) {
    echo json_encode(['success' => true, 'message' => 'Inscription réussie']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur d\'enregistrement']);
}
?>