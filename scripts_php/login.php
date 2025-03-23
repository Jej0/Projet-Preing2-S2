<?php
session_start();

// Chemin du fichier JSON où sont enregistrés les utilisateurs
define(constant_name: 'USERS_FILE', value: 'users.json');

// Fonction pour récupérer les utilisateurs depuis le fichier JSON
function getUsers(): mixed {
    if (!file_exists(filename: USERS_FILE)) {
        return [];
    }
    
    $data = file_get_contents(filename: USERS_FILE);
    return json_decode(json: $data, associative: true) ?: [];
}

// Variables pour stocker les messages d'erreur et les valeurs des champs
$error = '';
$username = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(string: $_POST['username']);
    $password = trim(string: $_POST['password']);

    if (!empty($username) && !empty($password)) {
        $users = getUsers();
        $user_found = false;
        
        // Vérifier les identifiants
        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify(password: $password, hash: $user['password'])) {
                $user_found = true;
                // Créer une session pour l'utilisateur connecté
                $_SESSION['user'] = [
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'admin' => $user['admin']
                ];
                
                // Rediriger vers la page d'accueil ou le tableau de bord
                header(header: 'Location: ../accueil.php');
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
