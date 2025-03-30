<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php?redirect=profile.php");
    exit();
}

// Charger les données utilisateur depuis la session
$user = $_SESSION['user'];
$username = $user['prenom'] . ' ' . $user['nom'];
$email = $user['email'];
$phone = $user['telephone'] ?? 'Non renseigné';
$status = 'Membre depuis ' . date('d/m/Y', strtotime($user['date_inscription']));

// Données factices pour l'exemple (à remplacer par vos données réelles)
$activities = $user['nb_activites'] ?? 0;
$badges = $user['nb_badges'] ?? 0;
$points = $user['points'] ?? 0;
$language = 'Français';
$notifications = 'Activées';
$isAdmin = $user['role'] === 'admin';

// Données factices pour les activités
$userActivities = [
    [
        'title' => 'Randonnée en montagne',
        'image' => 'https://source.unsplash.com/random/300x200/?mountain',
        'location' => 'Alpes, France',
        'date' => '15/06/2023',
        'rating' => 4
    ],
    [
        'title' => 'Plongée sous-marine',
        'image' => 'https://source.unsplash.com/random/300x200/?diving',
        'location' => 'Méditerranée',
        'date' => '22/07/2023',
        'rating' => 5
    ]
];

// Données factices pour les réservations
$userReservations = [
    [
        'id' => 1,
        'title' => 'Expédition Amazonie',
        'date' => '15/09/2023',
        'time' => '09:00',
        'location' => 'Brésil',
        'status' => 'upcoming'
    ],
    [
        'id' => 2,
        'title' => 'Safari Tanzanie',
        'date' => '10/05/2023',
        'time' => '14:00',
        'location' => 'Tanzanie',
        'status' => 'completed'
    ]
];

// Données factices pour les badges
$userBadges = [
    [
        'title' => 'Explorateur',
        'icon' => 'fa-compass',
        'description' => 'A complété 5 activités',
        'date_obtained' => '2023-01-15'
    ],
    [
        'title' => 'Aventurier',
        'icon' => 'fa-mountain',
        'description' => 'Première randonnée',
        'date_obtained' => '2023-03-22'
    ]
];
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
    <title>KYS - Profil de <?php echo htmlspecialchars($username); ?></title>
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
            <a href="../scripts_php/deconnexion.php" class="btn nav-btn">Déconnexion</a>
            <a href="profile.php" class="profile-icon">
                <i class="fas fa-user-circle"></i>
            </a>
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
                <h1><?php echo htmlspecialchars($username); ?></h1>
                <p class="profile-status"><?php echo htmlspecialchars($status); ?></p>
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number"><?php echo htmlspecialchars($activities); ?></span>
                        <span class="stat-label">Activités</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo htmlspecialchars($badges); ?></span>
                        <span class="stat-label">Badges</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo htmlspecialchars($points); ?></span>
                        <span class="stat-label">Points</span>
                    </div>
                </div>
            </div>
        </section>

        <nav class="profile-nav">
            <ul>
                <li class="active"><a href="#informations">Informations</a></li>
                <li><a href="#activites">Activités</a></li>
                <li><a href="#reservations">Réservations</a></li>
                <li><a href="#badges">Badges</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="admin.php">Administrateur</a></li>
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
                            <p><?php echo htmlspecialchars($email); ?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <label>Téléphone</label>
                            <p><?php echo htmlspecialchars($phone); ?></p>
                        </div>
                    </div>
                </div>
                <div class="info-group">
                    <h3>Préférences</h3>
                    <div class="info-item">
                        <i class="fas fa-language"></i>
                        <div>
                            <label>Langue</label>
                            <p><?php echo htmlspecialchars($language); ?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-bell"></i>
                        <div>
                            <label>Notifications</label>
                            <p><?php echo htmlspecialchars($notifications); ?></p>
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
                <?php if (!empty($userActivities)): ?>
                    <?php foreach ($userActivities as $activity): ?>
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
                    <p class="no-data-message"><i class="far fa-calendar-times"></i> Aucune activité récente à afficher.</p>
                <?php endif; ?>
            </div>
        </section>

        <section id="reservations" class="profile-section">
            <div class="section-header">
                <h2>Mes Réservations</h2>
            </div>
            <div class="reservations-list">
                <?php if (!empty($userReservations)): ?>
                    <?php foreach ($userReservations as $reservation): ?>
                        <div class="reservation-card">
                            <div class="reservation-status">
                                <span class="status-badge <?php echo htmlspecialchars($reservation['status']); ?>">
                                    <?php
                                    $statusText = match ($reservation['status']) {
                                        'upcoming' => 'À venir',
                                        'completed' => 'Complété',
                                        'cancelled' => 'Annulé',
                                        default => 'Planifié'
                                    };
                                    echo htmlspecialchars($statusText);
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
                                <?php if ($reservation['status'] === 'upcoming'): ?>
                                    <button class="btn btn-base" onclick="modifyReservation(<?php echo $reservation['id']; ?>)">Modifier</button>
                                    <button class="btn btn-transparent" onclick="cancelReservation(<?php echo $reservation['id']; ?>)">Annuler</button>
                                <?php elseif ($reservation['status'] === 'completed'): ?>
                                    <button class="btn btn-base" onclick="addReview(<?php echo $reservation['id']; ?>)">Avis</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data-message"><i class="far fa-calendar-times"></i> Aucune réservation à afficher.</p>
                <?php endif; ?>
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
                                <i class="fas <?php echo htmlspecialchars($badge['icon']); ?>"></i>
                            </div>
                            <h3><?php echo htmlspecialchars($badge['title']); ?></h3>
                            <p><?php echo htmlspecialchars($badge['description']); ?></p>
                            <small>Obtenu le <?php echo date('d/m/Y', strtotime($badge['date_obtained'])); ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-data-message"><i class="far fa-star"></i> Aucun badge à afficher pour le moment.</p>
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