<?php

require_once 'scripts_php/init.php';


// Fonction pour récupérer les informations supplémentaires de l'utilisateur si disponibles
function getUserDetails($username) {
    if (!file_exists(USERS_FILE)) {
        return null;
    }
    
    $data = file_get_contents(USERS_FILE);
    $users = json_decode($data, true) ?: [];
    
    foreach ($users as $user) {
        if ($user['username'] === $username) {
            return $user;
        }
    }
    
    return null;
}

// Récupérer les informations détaillées si disponibles
$userDetails = getUserDetails($username);

// Définir des valeurs par défaut pour les informations supplémentaires
$phone = isset($userDetails['phone']) ? htmlspecialchars($userDetails['phone']) : "Non renseigné";
$language = isset($userDetails['language']) ? htmlspecialchars($userDetails['language']) : "Français";
$notifications = isset($userDetails['notifications']) ? $userDetails['notifications'] ? "Activées" : "Désactivées" : "Activées";
$status = isset($userDetails['status']) ? htmlspecialchars($userDetails['status']) : "Aventurier Débutant";
$activities = isset($userDetails['activities']) ? intval($userDetails['activities']) : 0;
$badges = isset($userDetails['badges']) ? intval($userDetails['badges']) : 0;
$points = isset($userDetails['points']) ? intval($userDetails['points']) : 0;
$avatar = isset($userDetails['avatar']) ? htmlspecialchars($userDetails['avatar']) : "img/default-avatar.jpg";



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
    <meta name="description" content="Votre profil d'utilisateur avec vos planifications de voyage.">

    <!-- Titre du navigateur -->
    <title>KYS - Profile de <?php echo $username; ?></title>

    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">

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
                <a href="scripts_php/logout.php" class="btn nav-btn">Se déconnecter</a>
                <i class="fas fa-user-circle"></i>
                
        </div>
    </nav>

    <!-- Contenu-->
    <main class="profile-container">
        <!-- En-tête du profil -->
        <section class="profile-header">
            <div class="profile-cover">
                <div class="profile-avatar">
                    <img src="<?php echo $avatar; ?>" alt="Photo de profil" id="profile-image">
                    <button class="edit-avatar">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
            </div>
            <div class="profile-info">
                <h1><?php echo $username; ?></h1>
                <p class="profile-status"><?php echo $status; ?></p>
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?php echo $activities; ?></span>
                        <span class="stat-label">Activités</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo $badges; ?></span>
                        <span class="stat-label">Badges</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo $points; ?></span>
                        <span class="stat-label">Points</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Menu de navigation du profil -->
        <nav class="profile-nav">
            <ul>
                <a href="#informations">Informations</a></li>
                <li><a href="#activites">Mes Activités</a></li>
                <li><a href="#reservations">Réservations</a></li>
                <li><a href="#badges">Badges</a></li>
                <?php if ($admin === true) { ?>
                    <li><a href="admin.php">Administrateur</a></li>
                <?php } ?>
            </ul>
        </nav>

        <!-- Section Informations -->
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
                            <p><?php echo $email; ?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <label>Téléphone</label>
                            <p><?php echo $phone; ?></p>
                        </div>
                    </div>
                </div>
                <div class="info-group">
                    <h3>Préférences</h3>
                    <div class="info-item">
                        <i class="fas fa-language"></i>
                        <div>
                            <label>Langue</label>
                            <p><?php echo $language; ?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-bell"></i>
                        <div>
                            <label>Notifications</label>
                            <p><?php echo $notifications; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section Activités -->
        <section id="activites" class="profile-section">
            <div class="section-header">
                <h2>Mes Activités Récentes</h2>
                <a href="recherche.php" class="btn btn-base">Découvrir plus</a>
            </div>
            <div class="activities-grid">
                <?php if (isset($userDetails['recentActivities']) && !empty($userDetails['recentActivities'])): ?>
                    <?php foreach ($userDetails['recentActivities'] as $activity): ?>
                        <div class="activity-card">
                            <div class="activity-image">
                                <img src="<?php echo htmlspecialchars($activity['image']); ?>" alt="<?php echo htmlspecialchars($activity['title']); ?>">
                                <span class="activity-date"><?php echo htmlspecialchars($activity['date']); ?></span>
                            </div>
                            <div class="activity-content">
                                <h3><?php echo htmlspecialchars($activity['title']); ?></h3>
                                <p class="activity-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($activity['location']); ?></p>
                                <div class="activity-rating">
                                    <?php for ($i = 0; $i < $activity['rating']; $i++): ?>
                                        <i class="fas fa-star"></i>
                                    <?php endfor; ?>
                                    <?php for ($i = $activity['rating']; $i < 5; $i++): ?>
                                        <i class="far fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data-message">Aucune activité récente à afficher.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Section Réservations -->
        <section id="reservations" class="profile-section">
            <div class="section-header">
                <h2>Mes Réservations</h2>
            </div>
            <div class="reservations-list">
                <?php if (isset($userDetails['reservations']) && !empty($userDetails['reservations'])): ?>
                    <?php foreach ($userDetails['reservations'] as $reservation): ?>
                        <div class="reservation-card <?php echo htmlspecialchars($reservation['status']); ?>">
                            <div class="reservation-status">
                                <span class="status-badge">
                                    <?php 
                                    $statusText = "Planifié";
                                    if ($reservation['status'] == 'upcoming') {
                                        $statusText = "À venir";
                                    } elseif ($reservation['status'] == 'completed') {
                                        $statusText = "Complété";
                                    } elseif ($reservation['status'] == 'cancelled') {
                                        $statusText = "Annulé";
                                    }
                                    echo $statusText;
                                    ?>
                                </span>
                            </div>
                            <div class="reservation-details">
                                <h3><?php echo htmlspecialchars($reservation['title']); ?></h3>
                                <p><i class="fas fa-calendar"></i> <?php echo htmlspecialchars($reservation['date']); ?></p>
                                <p><i class="fas fa-clock"></i> <?php echo htmlspecialchars($reservation['time']); ?></p>
                                <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($reservation['location']); ?></p>
                            </div>
                            <div class="reservation-actions">
                                <?php if ($reservation['status'] == 'upcoming'): ?>
                                    <button class="btn btn-base">Modifier</button>
                                    <button class="btn btn-transparent">Annuler</button>
                                <?php elseif ($reservation['status'] == 'completed'): ?>
                                    <button class="btn btn-base">Avis</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data-message">Aucune réservation à afficher.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Section Badges -->
        <section id="badges" class="profile-section">
            <div class="section-header">
                <h2>Mes Badges</h2>
            </div>
            <div class="badges-grid">
                <?php if (isset($userDetails['userBadges']) && !empty($userDetails['userBadges'])): ?>
                    <?php foreach ($userDetails['userBadges'] as $badge): ?>
                        <div class="badge-card <?php echo isset($badge['locked']) && $badge['locked'] ? 'locked' : ''; ?>">
                            <div class="badge-icon">
                                <i class="fas <?php echo htmlspecialchars($badge['icon']); ?>"></i>
                            </div>
                            <h3><?php echo htmlspecialchars($badge['title']); ?></h3>
                            <p><?php echo htmlspecialchars($badge['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data-message">Aucun badge à afficher pour le moment.</p>
                <?php endif; ?>
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

</body>
</html>