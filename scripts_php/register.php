<?php
session_start();

// Chemin du fichier JSON où seront enregistrés les utilisateurs
define('USERS_FILE', 'users.json');

// Fonction pour récupérer les utilisateurs depuis le fichier JSON
function getUsers() {
    if (!file_exists(USERS_FILE)) {
        return [];
    }
    
    $data = file_get_contents(USERS_FILE);
    return json_decode($data, true) ?: [];
}

// Fonction pour enregistrer les utilisateurs dans le fichier JSON
function saveUsers($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm-password']);

    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'Adresse email invalide !';
            exit;
        }

        if ($password !== $confirm_password) {
            echo 'Les mots de passe ne correspondent pas !';
            exit;
        }
        
        $users = getUsers();
        
        // Vérifier si l'utilisateur ou l'email existe déjà
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                echo 'Nom d\'utilisateur déjà pris !';
                exit;
            }
            if ($user['email'] === $email) {
                echo 'Email déjà utilisé !';
                exit;
            }
        }
        
        // Ajouter le nouvel utilisateur
        $users[] = ['username' => $username, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)];
        saveUsers($users);
        echo 'Inscription réussie !';
    } else {
        echo 'Veuillez remplir tous les champs !';
    }
}

?>