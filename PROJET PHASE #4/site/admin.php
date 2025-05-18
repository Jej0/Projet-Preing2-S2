<?php
session_start();
if (
    !isset($_SESSION['user']) ||
    empty($_SESSION['user']['admin']) ||
    $_SESSION['user']['admin'] !== true
) {
    header('Location: accueil.php');
    exit();
}
$users = json_decode(file_get_contents('../data/users.json'), true);
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
    <meta name="description" content="⚠️Page Administrateur du site Keep Yourself Safe.⚠️">

    <!-- Titre du navigateur -->
    <title>KYS - Admin</title>

    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <!-- Ajout des icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Très bien mais comment ça marche ? -->
</head>

<body>
    <!-- Haut de page -->

    <!-- Navigation -->
    <nav>
        <!-- Logo et nom (gauche)-->
        <div class="nav-left">
            <a href="accueil.php" class="nav-brand">
                <img src="assets/img/logo.png" alt="Logo">
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

            <button id="theme-toggle" class="nav-btn">
                <i class="fa-solid fa-moon"></i>
            </button>

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
    <main class="admin-container">
        <!-- En-tête Admin -->
        <div class="admin-header">
            <h1>Tableau de bord Administrateur</h1>

        </div>

        <!-- Statistiques -->
        <div class="admin-stats-grid">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3>1,234</h3>
                <p>Utilisateurs inscrits</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-hiking"></i>
                <h3>89</h3>
                <p>Activités en ligne</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-euro-sign"></i>
                <h3>15,230€</h3>
                <p>CA ce mois</p>
            </div>
        </div>

        <!-- Gestion utilisateurs -->
        <section class="admin-section">
            <h2>Gestion des utilisateurs</h2>
            <div class="search-bar">
                <input type="text" placeholder="Rechercher un utilisateur...">
                <button class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['information_personnelles']['prenom'] . ' ' . $user['information_personnelles']['nom']) ?: htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['information_personnelles']['mail']) ?></td>
                            <td>
                                <span class="role-user">
                                    <?= $user['admin'] ? 'Admin' : 'Utilisateur' ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-supprimer ban-btn"
                                    style="padding: 8px 18px; border-radius: 8px; font-weight: bold; font-size: 1rem; border: none; transition: background 0.2s, color 0.2s;"
                                    data-user-id="<?= $user['id_user'] ?>"
                                    data-banned="<?= !empty($user['banni']) ? '1' : '0' ?>">
                                    <?= !empty($user['banni']) ? 'Banni' : 'Bannir' ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Panel de contrôle -->
        <section class="admin-section">
            <h2>Actions rapides</h2>
            <div class="quick-actions">
                <button class="action-card">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter une activité
                </button>
                <button class="action-card">
                    <i class="fas fa-file-invoice"></i>
                    Générer rapport
                </button>
                <button class="action-card">
                    <i class="fas fa-bell"></i>
                    Envoyer notification
                </button>
            </div>
        </section>
    </main>

    <!-- Pied de page -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Keep Yourself Safe</h3>
                <p>L'aventure en toute sécurité.</p>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: contact@kys.fr</p>
                <p>Tél: +33 1 23 45 67 89</p>
            </div>
            <div class="footer-section">
                <h3>Suivez-nous</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Keep Yourself Safe. Tous droits réservés.</p>
        </div>
    </footer>
    <script src="assets/js/sombre.js"></script>
    <script src="assets/js/admin.js"></script>
</body>

</html>