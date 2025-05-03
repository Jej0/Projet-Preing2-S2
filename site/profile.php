<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php?redirect=profile.php");
    exit();
}

// Charger les données utilisateur depuis la session
$user = $_SESSION['user'];
$username = $user['username'];
$name = $user['information_personnelles']['prenom'] . ' ' . $user['information_personnelles']['nom'];
$email = $user['information_personnelles']['mail'];
$phone = $user['information_personnelles']['telephone'] ?? 'Non renseigné';
$status = 'Membre depuis ' . date('d/m/Y', strtotime($user['date']['inscription']));
$isAdmin = $user['admin'] == 'true';

// Données factices pour l'exemple (à remplacer par vos données réelles)
//$activities = $user['nb_activites'] ?? 0;
//$badges = $user['nb_badges'] ?? 0;
//$language = 'Français';
//$notifications = 'Activées';



// Fonction pour vérifier les badges basés sur les conditions
function getUserBadges($userData)
{
    $badges = [];
    $currentDate = new DateTime();

    // Badges d'anniversaire d'inscription
    $inscriptionDate = new DateTime($userData['date']['inscription']);
    $yearsSinceInscription = $currentDate->diff($inscriptionDate)->y;

    for ($i = 1; $i <= min($yearsSinceInscription, 5); $i++) {
        $badges[] = [
            'icon' => 'fa-calendar',
            'title' => 'Membre depuis ' . $i . ' an' . ($i > 1 ? 's' : ''),
            'description' => 'Fidélité - Inscrit depuis ' . $i . ' an' . ($i > 1 ? 's' : ''),
        ];
    }

    // Badges de voyages
    $voyagesCount = count($userData['id_historique_voyages']);

    if ($voyagesCount >= 1) {
        $badges[] = [
            'icon' => 'fa-map-marked-alt',
            'title' => 'Premier voyage',
            'description' => 'A effectué son premier voyage',
        ];
    }

    if ($voyagesCount >= 5) {
        $badges[] = [
            'icon' => 'fa-map-marked-alt',
            'title' => 'Voyageur confirmé',
            'description' => 'A effectué 5 voyages',
        ];
    }

    if ($voyagesCount >= 10) {
        $badges[] = [
            'icon' => 'fa-map-marked-alt',
            'title' => 'Voyageur expert',
            'description' => 'A effectué 10 voyages',
        ];
    }

    // Badges spéciaux
    if ($userData['information_personnelles']['contrat'] == true) {
        $badges[] = [
            'icon' => 'fa-book-dead',
            'title' => 'Contrat accepté',
            'description' => 'Vous avez signé le contrat',
        ];
    }

    $hasClimbedEverest = false; // À modifier pour vérifier si le user a fait le voyage

    if ($hasClimbedEverest) {
        $badges[] = [
            'icon' => 'fa-mountain',
            'title' => 'Ascension de l\'Everest',
            'description' => 'A réussi l\'ascension de l\'Everest',
            'date_obtained' => $userData['date']['connexion'] // Date approximative
        ];
    }

    return $badges;
}

// Récupérer les badges de l'utilisateur en vérifiant les conditions
$userBadges = getUserBadges($user); // $userData doit contenir les données de l'utilisateur

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="title" content="Keep Yourself Safe">
    <meta name="author" content="Keep Yourself Safe | Alex MIKOLAJEWSKI | Axel ATAGAN | Naïm LACHGAR-BOUACHRA">
    <meta name="description" content="Votre profil d'utilisateur">
    <title>KYS - Profil de <?php echo $username; ?></title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
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


    <main class="profile-container">
        <section class="profile-header">
            <div class="profile-cover">
                <div class="profile-avatar">
                    <img src="assets/img/testimonial2.jpg" alt="Photo de profil">
                </div>
            </div>
            <div class="profile-info">
                <h1><?php echo $username; ?></h1>
                <p class="profile-status"><?php echo $status; ?></p>
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?php echo count($user['id_historique_voyages']); ?></span>
                        <span class="stat-label">Activités</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo count($userBadges); ?></span>
                        <span class="stat-label">Badges</span>
                    </div>
                </div>
            </div>
        </section>

        <nav class="profile-nav">
            <ul>
                <li><a class="btn" href="#informations">Informations</a></li>
                <li><a class="btn" href="#activites">Activités</a></li>
                <li><a class="btn" href="#reservations">Réservations</a></li>
                <li><a class="btn" href="#badges">Badges</a></li>
                <?php if ($isAdmin): ?>
                    <li><a class="btn" href="admin.php">Administrateur</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <section id="informations" class="profile-section">
            <div class="section-header">
                <h2>Informations Personnelles</h2>
                <button class="edit-btn"><i class="fas fa-edit"></i> Modifier</button>
            </div>
            <div class="info-grid">
                <div class="info-group">
                    <h3>Coordonnées</h3>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <label>Email</label>
                            <p><?php echo 'none'; ?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <label>Téléphone</label>
                            <p><?php echo 'none'; ?></p>
                        </div>
                    </div>
                </div>
                <div class="info-group">
                    <h3>Préférences</h3>
                    <div class="info-item">
                        <i class="fas fa-language"></i>
                        <div>
                            <label>Langue</label>
                            <p><?php echo 'none'; ?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-bell"></i>
                        <div>
                            <label>Notifications</label>
                            <p><?php echo 'none'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="activites" class="profile-section">
            <div class="section-header">
                <h2>Mes Activités Récentes</h2>
                <a href="recherche.php" class="btn btn-base">Découvrir plus</a>
            </div>
            <div class="activities-grid">
                ??????
            </div>
        </section>

        <section id="reservations" class="profile-section">
            <div class="section-header">
                <h2>Mes Réservations</h2>
            </div>
            <div class="reservations-list">
                ?????
            </div>
        </section>





        <section id="badges" class="profile-section">
            <div class="section-header">
                <h2>Mes Badges</h2>
            </div>
            <div class="badges-grid">
                <?php if (!empty($userBadges)): ?>
                    <?php foreach ($userBadges as $badge): ?>
                        <div class="badge-card">
                            <div class="badge-icon">
                                <i class="fas <?= $badge['icon'] ?>"></i>
                            </div>
                            <h3><?php echo $badge['title']; ?></h3>
                            <p><?php echo $badge['description']; ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data-message"> Aucun badge à afficher pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>




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
</body>

</html>