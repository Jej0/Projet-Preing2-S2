<?php
// D'abord charger la configuration
require_once 'config/config.php';

// Ensuite charger les classes qui en dépendent
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Puis charger l'initialisation qui utilise ces classes
require_once 'scripts_php/init.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

// Initialiser la classe User
$userObj = new User();

// Récupérer les informations de l'utilisateur depuis la session
$user = $_SESSION['user'];
$userId = $user['id'];
$username = htmlspecialchars($user['login']);
$email = htmlspecialchars($user['email']);
$firstname = htmlspecialchars($user['firstname']);
$lastname = htmlspecialchars($user['lastname']);
$isAdmin = $user['role'] === 'admin';

// Récupérer les statistiques (en supposant que getStats() est adapté à la nouvelle BD)




// Récupérer les réservations en utilisant la nouvelle BD via une requête JOIN
// La méthode getReservations doit retourner un tableau d'éléments contenant 'reservation' et 'voyage'
$userReservations = $userObj->getReservations($userId);

// Définir des valeurs par défaut pour les autres informations
$phone = "Non renseigné";
$language = "Français";
$notifications = "Activées";
// $status = $points >= 1000 ? "Aventurier Expert" : "Aventurier Débutant";
$avatar = "img/default-avatar.jpg";

// Fonction pour récupérer les informations supplémentaires de l'utilisateur si disponibles (optionnel)
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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!--Information de la page web-->
    <meta charset="UTF-8">
    <!-- Optimisation pour le téléphone -->
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
    <script src="sombre.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-left">
            <a href="accueil.php" class="nav-brand">
                <img src="img/logo.png" alt="Logo">
                Keep Yourself Safe
            </a>
        </div>
        <ul class="nav-links">
            <li><a href="presentation.php">Présentation</a></li>
            <li><a href="recherche.php">Rechercher</a></li>
            <li><a href="mailto:contact@kys.fr">Contact</a></li>
        </ul>
        <div class="nav-right">
            <a href="scripts_php/logout.php" class="btn nav-btn">Se déconnecter</a>
            <i class="fas fa-user-circle"></i>
        </div>
    </nav>

    <!-- Contenu -->
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
                <!-- <p class="profile-status"><?php echo $status; ?></p> -->
                <div class="profile-stats">
                    <!-- <div class="stat-item">
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
                    </div> -->
                </div>
            </div>
        </section>

        <!-- Menu de navigation du profil -->
        <nav class="profile-nav">
            <ul>
                <li><a href="#informations">Informations</a></li>
                <li><a href="#activites">Mes Activités</a></li>
                <li><a href="#reservations">Réservations</a></li>
                <li><a href="#badges">Badges</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="admin.php">Administrateur</a></li>
                <?php endif; ?>
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
                <?php if (!empty($userActivities)): ?>
                    <?php foreach ($userActivities as $activity): ?>
                        <div class="activity-card">
                            <div class="activity-image">
                                <img src="<?php echo htmlspecialchars($activity['image']); ?>" alt="<?php echo htmlspecialchars($activity['title']); ?>">
                                <span class="activity-date"><?php echo htmlspecialchars($activity['date']); ?></span>
                            </div>
                            <div class="activity-content">
                                <h3><?php echo htmlspecialchars($activity['title']); ?></h3>
                                <p class="activity-location">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($activity['location']); ?>
                                </p>
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
            <div class="reservations-container">
                <?php if (empty($userReservations)): ?>
                    <div class="empty-state">
                        <h2><i class="far fa-calendar-check"></i> Vous n'avez aucune réservation</h2>
                        <p class="lead">Parcourez nos voyages et réservez votre prochaine aventure !</p>
                        <a href="recherche.php" class="btn btn-primary">
                            <i class="fas fa-search"></i> Voir les voyages disponibles
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($userReservations as $item):
                            $reservation = $item['reservation'];
                            $voyage = $item['voyage'];
                        ?>
                            <div class="col-md-6">



                            <div class="reservation-card">
    <div class="row g-0">
        <div class="col-md-4">
            <img src="https://source.unsplash.com/random/300x200/?mountain,travel,<?php echo urlencode($voyage['titre']); ?>" class="voyage-image" alt="<?php echo htmlspecialchars($voyage['titre']); ?>">
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($voyage['titre']); ?></h5>
                <p class="card-text text-muted">
                    <small>
                        <i class="far fa-calendar-alt"></i> Du <?php echo htmlspecialchars($voyage['date_debut']); ?> au <?php echo htmlspecialchars($voyage['date_fin']); ?>
                        (<?php echo htmlspecialchars($voyage['duree']); ?>)
                    </small>
                </p>
                <p class="card-text"><?php echo htmlspecialchars(substr($voyage['description'], 0, 100)); ?>...</p>
                <div class="reservation-footer d-flex justify-content-between align-items-center">
                    <div>
                        <span class="reservation-badge price"><?php echo number_format($reservation['prix_total'], 0, ',', ' '); ?> €</span>
                        <span class="reservation-badge <?php echo $reservation['paiement'] ? 'paid' : 'pending'; ?> ms-2">
                            <?php echo $reservation['paiement'] ? '<i class="fas fa-check-circle"></i> Payé' : '<i class="fas fa-clock"></i> En attente'; ?>
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="details.php?id=<?php echo $voyage['id_voyage']; ?>&reservation=<?php echo $reservation['id_reservation']; ?>" class="btn-modifier me-2">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <?php if (!$reservation['paiement']): ?>
                        <a href="http://localhost/process-payment.php?trip_id=<?php echo urlencode($voyage['id_voyage']); ?>&price=<?php echo urlencode($reservation['prix_total']); ?>" class="btn btn-primary">
                            Payer la réservation
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                                
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- Section Badges -->
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

    <!-- JavaScript pour les actions -->
    <script>
    function modifyReservation(id) {
        window.location.href = `modifier-reservation.php?id=${id}`;
    }

    function cancelReservation(id) {
        if (confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) {
            fetch(`api/cancel-reservation.php?id=${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Erreur lors de l\'annulation de la réservation');
                }
            });
        }
    }

    function addReview(id) {
        window.location.href = `ajouter-avis.php?id=${id}`;
    }
    </script>
</body>
</html>
