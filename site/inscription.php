<?php
session_start();

// Charger les utilisateurs existants
$usersFile = '../data/users.json';
$users = [];
if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';

    // Validation
    $errors = [];

    // Validation du nom d'utilisateur
    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est requis.";
    } elseif (strlen($username) < 3) {
        $errors[] = "Le nom d'utilisateur doit contenir au moins 3 caractères.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = "Le nom d'utilisateur ne peut contenir que des lettres, chiffres et underscores.";
    }

    // Validation de l'email
    if (empty($email)) {
        $errors[] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    // Validation du mot de passe
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins une majuscule.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins un chiffre.";
    } elseif (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins un caractère spécial.";
    }

    // Vérification de la confirmation du mot de passe
    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérifier si l'utilisateur existe déjà
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $errors[] = "Ce nom d'utilisateur est déjà pris.";
            break;
        }
        if ($user['information_personnelles']['mail'] === $email) {
            $errors[] = "Cet email est déjà utilisé.";
            break;
        }
    }

    if (empty($errors)) {
        // Créer un nouvel utilisateur
        $newUser = [
            "id_user" => count($users) + 1,
            "username" => $username,
            "mot_de_passe" => password_hash($password, PASSWORD_DEFAULT),
            "admin" => false,
            "newsletter" => true,
            "information_personnelles" => [
                "nom" => "",
                "prenom" => "",
                "naissance" => "",
                "mail" => $email,
                "adresse" => "",
                "telephone" => "",
                "contrat" => false
            ],
            "date" => [
                "inscription" => date('Y-m-d'),
                "connexion" => date('Y-m-d')
            ],
            "id_historique_voyages" => [],
            "id_reservation" => []
        ];

        // Ajouter le nouvel utilisateur
        $users[] = $newUser;
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

        // Connecter l'utilisateur
        $_SESSION['user'] = $newUser;
        header('Location: profile.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="title" content="Keep Yourself Safe">
    <meta name="author" content="Keep Yourself Safe | Alex MIKOLAJEWSKI | Axel ATAGAN | Naïm LACHGAR-BOUACHRA">
    <meta name="description" content="Inscrivez-vous pour sauvegarder et planifier votre voyage.">
    <title>KYS - Inscription</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
    <nav>
        <div class="nav-left">
            <a href="accueil.php" class="nav-brand">
                <img src="assets/img/logo.png" alt="Logo">
                Keep Yourself Safe
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="presentation.php">Présentation</a></li>
            <li><a href="recherche.php">Rechercher</a></li>
            <li><a href="mailto:contact@kys.fr">Contact</a></li>
        </ul>
        <div class="nav-right">
            <?php if (!isset($_SESSION['user'])) { ?>
                <a href="connexion.php" class="btn nav-btn">Se connecter</a>
                <a href="inscription.php" class="btn nav-btn">S'inscrire</a>
            <?php } ?>
            <?php if (isset($_SESSION['user'])) { ?>
                <a href="../scripts_php/deconnexion.php" class="btn nav-btn">Déconnexion</a>
                <a href="profile.php" class="profile-icon">
                    <i class="fas fa-user-circle"></i>
                </a>
            <?php } ?>
        </div>
    </nav>

    <main>
        <div class="form-container">
            <img src="assets/img/logo.png" alt="Logo" class="logo">
            <h2 class="h2">Inscription</h2>

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="inscription.php" method="POST" id="register-form">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe (min. 8 caractères) :</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" class="password-input" required maxlength="32" oninput="updateCharCounter('password', 'password-counter')">
                        <i class="fas fa-eye password-toggle" id="password-toggle" onclick="togglePasswordVisibility('password', 'password-toggle')"></i>
                    </div>
                    <div class="char-counter" id="password-counter">32 caractères restants</div>
                    <div class="password-rules">
                        <p>Le mot de passe doit contenir :</p>
                        <ul>
                            <li id="req-length">8 caractères minimum</li>
                            <li id="req-uppercase">1 majuscule</li>
                            <li id="req-number">1 chiffre</li>
                            <li id="req-special">1 caractère spécial</li>
                        </ul>
                        <p id="password-match" style="margin-top: 5px;"></p>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <label for="confirm-password">Confirmer le mot de passe :</label>
                    <div class="password-container">
                        <input type="password" id="confirm-password" name="confirm-password" class="password-input" required maxlength="32" oninput="updateCharCounter('confirm-password', 'confirm-password-counter')">
                        <i class="fas fa-eye password-toggle" id="confirm-password-toggle" onclick="togglePasswordVisibility('confirm-password', 'confirm-password-toggle')"></i>
                    </div>
                    <div class="char-counter" id="confirm-password-counter">32 caractères restants</div>
                </div>

                <button type="submit" class="btn button">S'inscrire</button>
            </form>
        </div>
    </main>

    <script src="assets/js/inscription.js"></script>
</body>

</html>