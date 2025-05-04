<?php
session_start();
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Gérer la redirection après connexion
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : (isset($_GET['redirect']) ? $_GET['redirect'] : 'profile.php');

// Redirection si déjà connecté
if (isset($_SESSION['user'])) {
    header('Location: ' . $redirect);
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = new User();
        if ($user->login($_POST['username'], $_POST['password'])) {
            header('Location: accueil.php');
            exit();
        } else {
            $error = "Identifiants incorrects";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <!--Information de la page web-->
    <meta charset="UTF-8">

    <!-- Optimisation pour le telephone -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">

    <!-- Titre du site -->
    <meta name="title" content="Keep Yourself Safe">

    <!-- Nom de l'agence et auteur du site -->
    <meta name="author" content="Keep Yourself Safe | Alex MIKOLAJEWSKI | Axel ATAGAN | Naïm LACHGAR-BOUACHRA">

    <!-- Description du site -->
    <meta name="description" content="Connectez-vous pour accéder à votre profil.">

    <!-- Titre du navigateur -->
    <title>KYS - Connexion</title>

    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="sombre.js"></script>
    <!-- Ajout des icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!-- Haut de page -->

    <!-- Navigation -->
    <nav>
        <!-- Logo et nom (gauche)-->
        <div class="nav-left">
            <a href="accueil.php" class="nav-brand">
                <img src="img/logo.png" alt="Logo">
                Keep Yourself Safe
            </a>
        </div>

        <!-- Liens (centre)-->
        <ul class="nav-links">
            <li><a href="presentation.php">Présentation</a></li>
            <li><a href="recherche.php">Rechercher</a></li>
            <li><a href="mailto:contact@kys.fr">Contact</a></li>
        </ul>

        <!-- Profil et connexion(droite)-->
        <div class="nav-right">
            <?php if (!isset($_SESSION['user'])) { ?>
                <a href="connexion.php" class="btn nav-btn">Se connecter</a>
                <a href="inscription.php" class="btn nav-btn">S'inscrire</a>
            <?php } ?>
            <?php if (isset($_SESSION['user'])) { ?>
                <a href="profile.php" class="profile-icon">
                    <i class="fas fa-user-circle"></i>
                </a>
            <?php } ?>
        </div>
    </nav>

    <!-- Contenu-->
    <main>
        <!-- Formulaire de connexion-->
        <div class="form-container">
            <img src="img/logo.png" alt="Logo" class="logo">
            <h2 class="h2">Accéder à votre compte</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="password-reset">
                    <a href="mot-de-passe-oublie.php">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn">Se connecter</button>
            </form>
        </div>
    </main>

    <!-- Pas de footer, sinon on peut scroll -->
</body>

</html>