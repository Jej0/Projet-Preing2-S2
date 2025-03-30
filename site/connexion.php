<?php
session_start();

// Charger les utilisateurs existants
$usersFile = '../data/users.json';
$users = [];
if (file_exists($usersFile)) {
    $users = json_decode(file_get_contents($usersFile), true);
}

// Gérer la redirection après connexion
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : (isset($_GET['redirect']) ? $_GET['redirect'] : 'profile.php');

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $error = '';

    // Rechercher l'utilisateur
    $foundUser = null;
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $foundUser = $user;
            break;
        }
    }

    if ($foundUser && password_verify($password, $foundUser['mot_de_passe'])) {
        // Mettre à jour la date de connexion
        $foundUser['date']['connexion'] = date('Y-m-d');

        // Mettre à jour le fichier users.json
        foreach ($users as &$u) {
            if ($u['id_user'] === $foundUser['id_user']) {
                $u = $foundUser;
                break;
            }
        }
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

        // Connecter l'utilisateur
        $_SESSION['user'] = $foundUser;
        header('Location: ' . $redirect);
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
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
    <meta name="description" content="Connectez-vous pour accéder à votre profil.">
    <title>KYS - Connexion</title>
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
            <h2 class="h2">Accéder à votre compte</h2>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <form action="connexion.php" method="POST">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">

                <div class="password-reset">
                    <a href="mot-de-passe-oublie.php">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn">Se connecter</button>
            </form>
        </div>
    </main>
</body>

</html>