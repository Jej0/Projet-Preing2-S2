<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    // Rediriger vers la page de connexion si non connecté
    header(header: 'Location: connexion.php');
    exit;
}

// Récupérer les informations de l'utilisateur depuis la session
$user = $_SESSION['user'];
$username = htmlspecialchars($user['username']);
$email = htmlspecialchars($user['email']);
$admin = $user['admin'];

// Chemin du fichier JSON des utilisateurs
define(constant_name: 'USERS_FILE', value: 'users.json');
?>