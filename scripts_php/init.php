<?php
// Ne démarrer la session que si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    // Rediriger vers la page de connexion si non connecté
    header('Location: connexion.php');
    exit;
}

// Récupérer les informations de l'utilisateur depuis la session
$user = $_SESSION['user'];
$username = htmlspecialchars($user['login']);
$email = htmlspecialchars($user['email']);
$isAdmin = $user['role'] === 'admin';

// Récupérer les autres informations si nécessaire
$firstname = htmlspecialchars($user['firstname']);
$lastname = htmlspecialchars($user['lastname']);

// Chemin du fichier JSON des utilisateurs
define(constant_name: 'USERS_FILE', value: 'users.json');
?>