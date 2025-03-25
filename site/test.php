<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php?redirect=mes_reservations.php");
    exit();
}

// Chargement des données JSON
$users = json_decode(file_get_contents('../data/users.json'), true);
$reservations = json_decode(file_get_contents('../data/reservation.json'), true);
$voyages = json_decode(file_get_contents('../data/voyages.json'), true);

// Utilisateur connecté
$currentUser = $_SESSION['user'];
$userId = $currentUser['id_user'];

// Récupérer les réservations de l'utilisateur
$userReservations = [];
foreach ($reservations as $reservation) {
    if ($reservation['id_utilisateur'] == $userId) {
        // Trouver le voyage correspondant
        foreach ($voyages as $voyage) {
            if ($voyage['id_voyage'] == $reservation['id_voyage']) {
                $userReservations[] = [
                    'reservation' => $reservation,
                    'voyage' => $voyage
                ];
                break;
            }
        }
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
    <meta name="description" content="Details des réservation.">
    <title>KYS - Réservation</title>
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

    <div class="reservations-container">
        <h1 class="reservations-title">Mes Réservations</h1>

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
                                                <i class="far fa-calendar-alt"></i> Du <?php echo htmlspecialchars($voyage['dates']['debut']); ?> au <?php echo htmlspecialchars($voyage['dates']['fin']); ?>
                                                (<?php echo htmlspecialchars($voyage['dates']['duree']); ?>)
                                            </small>
                                        </p>
                                        <p class="card-text"><?php echo htmlspecialchars(substr($voyage['description'], 0, 100)); ?>...</p>

                                        <div class="reservation-footer">
                                            <div>
                                                <span class="reservation-badge price"><?php echo number_format($voyage['prix_total'], 0, ',', ' '); ?> €</span>
                                                <span class="reservation-badge <?php echo $reservation['paiement'] ? 'paid' : 'pending'; ?> ms-2">
                                                    <?php echo $reservation['paiement'] ? '<i class="fas fa-check-circle"></i> Payé' : '<i class="fas fa-clock"></i> En attente'; ?>
                                                </span>
                                            </div>
                                            <div>
                                                <a href="details.php?id=<?php echo $voyage['id_voyage']; ?>&reservation=<?php echo $reservation['id_reservation']; ?>" class="btn-modifier">
                                                    <i class="fas fa-edit"></i> Modifier
                                                </a>
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
</body>

</html>