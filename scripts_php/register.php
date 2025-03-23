<?php
session_start();

// Chemin du fichier JSON où seront enregistrés les utilisateurs
define('USERS_FILE', 'users.json');

// Fonction pour récupérer les utilisateurs depuis le fichier JSON
function getUsers(): mixed {
    if (!file_exists(filename: USERS_FILE)) {
        return [];
    }
    
    $data = file_get_contents(filename: USERS_FILE);
    return json_decode(json: $data, associative: true) ?: [];
}

// Fonction pour enregistrer les utilisateurs dans le fichier JSON
function saveUsers($users): void {
    file_put_contents(filename: USERS_FILE, data: json_encode(value: $users, flags: JSON_PRETTY_PRINT));
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(string: $_POST['username']);
    $email = trim(string: $_POST['email']);
    $password = trim(string: $_POST['password']);
    $confirm_password = trim(string: $_POST['confirm-password']);

    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        if (!filter_var(value: $email, filter: FILTER_VALIDATE_EMAIL)) {
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
        $users[] = ['username' => $username, 'email' => $email, 'admin' => False, 'password' => password_hash(password: $password, algo: PASSWORD_DEFAULT)];
        saveUsers(users: $users);

        $_SESSION['user'] = [
            'username' => $user['username'],
            'email' => $user['email'],
            'admin' => $user['admin']
        ];
        
        // Rediriger vers la page d'accueil ou le tableau de bord
        header(header: 'Location: ../accueil.php');
        exit;

    } else {
        echo 'Veuillez remplir tous les champs !';
    }
}

?>