<?php
require_once '../config/config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once 'init.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../connexion.php');
    exit;
}

$userObj = new User();
$userId = $_SESSION['user']['id'];

// Lire les champs du formulaire
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$language = $_POST['language'] ?? 'Français';
$notifications = isset($_POST['notifications']) ? 1 : 0;

// Gérer l’avatar
$avatarPath = $_SESSION['user']['avatar'] ?? 'img/default-avatar.jpg';

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/avatars/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $filename = 'user_' . $userId . '.' . strtolower($extension);
    $destination = $uploadDir . $filename;

    move_uploaded_file($_FILES['avatar']['tmp_name'], $destination);

    // Chemin relatif pour affichage
    $avatarPath = 'uploads/avatars/' . $filename;
}

// Mettre à jour la base
$stmt = Database::getInstance()->getConnection()->prepare("
    UPDATE users SET email = :email, phone = :phone, language = :language, notifications = :notifications, avatar = :avatar
    WHERE id = :id
");
$stmt->execute([
    ':email' => $email,
    ':phone' => $phone,
    ':language' => $language,
    ':notifications' => $notifications,
    ':avatar' => $avatarPath,
    ':id' => $userId
]);

// Rafraichir la session
$_SESSION['user'] = $userObj->getById($userId);

header('Location: ../profile.php');
exit;
