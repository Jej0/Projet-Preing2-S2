<?php
session_start();

// Chemin du fichier JSON où sont enregistrés les utilisateurs
define('USERS_FILE', 'users.json');

// Fonction pour récupérer les utilisateurs depuis le fichier JSON
function getUsers(): mixed {
    if (!file_exists(USERS_FILE)) {
        return [];
    }
    
    $data = file_get_contents(USERS_FILE);
    return json_decode($data, true) ?: [];
}

// Variables pour stocker les messages d'erreur et les valeurs des champs
$error = '';
$username = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $users = getUsers();
        $user_found = false;
        
        // Vérifier les identifiants
        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password'])) {
                $user_found = true;
                // Vérifier si l'utilisateur est banni
                if (isset($user['role']) && $user['role'] === 'banned') {
                    $error = 'Accès refusé : votre compte a été banni.';
                    break;
                }
                
                // Créer une session pour l'utilisateur connecté
                $_SESSION['user'] = [
                    'username' => $user['username'],
                    'email'    => $user['email'],
                    'admin'    => $user['admin']
                ];
                
                // Rediriger vers la page d'accueil ou le tableau de bord
                header('Location: ../accueil.php');
                exit;
            }
        }
        
        if (!$user_found) {
            $error = 'Nom d\'utilisateur ou mot de passe incorrect !';
        }
    } else {
        $error = 'Veuillez remplir tous les champs !';
    }
}
?>