<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['profile_picture'])) {
    echo json_encode(['success' => false, 'message' => 'Requête invalide']);
    exit();
}

$username = $_SESSION['user']['username'];
$uploadDir = '../data/profile_picture/';

// Créer le dossier s'il n'existe pas
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Vérifier le type de fichier
$allowedTypes = ['image/jpeg', 'image/png'];
if (!in_array($_FILES['profile_picture']['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Seuls les formats JPEG et PNG sont acceptés']);
    exit();
}

// Chemin de destination
$destination = $uploadDir . $username . '.jpg';

// Convertir en JPEG si c'est un PNG
if ($_FILES['profile_picture']['type'] === 'image/png') {
    $image = imagecreatefrompng($_FILES['profile_picture']['tmp_name']);
    imagejpeg($image, $destination, 90);
    imagedestroy($image);
} else {
    move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination);
}

// Redimensionner l'image
list($width, $height) = getimagesize($destination);
$newWidth = 200;
$newHeight = 200;

$image = imagecreatefromjpeg($destination);
$resizedImage = imagecreatetruecolor($newWidth, $newHeight);
imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
imagejpeg($resizedImage, $destination, 90);
imagedestroy($image);
imagedestroy($resizedImage);

echo json_encode(['success' => true, 'timestamp' => time()]);
?>