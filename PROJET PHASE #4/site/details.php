<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php?redirect=details.php?id=" . ($_GET['id'] ?? ''));
    exit();
}

// Chargement des données JSON
$users = json_decode(file_get_contents('../data/users.json'), true);
$reservations = json_decode(file_get_contents('../data/reservation.json'), true);
$voyages = json_decode(file_get_contents('../data/voyages.json'), true);

// Utilisateur connecté
$currentUser = $_SESSION['user'];
$userId = $currentUser['id_user'];

// Vérification de l'ID du voyage
$voyageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($voyageId <= 0) {
    header("Location: recherche.php");
    exit();
}

// Trouver le voyage correspondant
$voyage = null;
foreach ($voyages as $v) {
    if ($v['id_voyage'] == $voyageId) {
        $voyage = $v;
        break;
    }
}

if (!$voyage) {
    header("Location: recherche.php");
    exit();
}

$reservationId = isset($_GET['reservation']) ? (int)$_GET['reservation'] : 0;

// Recherche de la réservation existante
$existingReservation = null;
foreach ($reservations as $res) {
    if (($reservationId > 0 && $res['id_reservation'] == $reservationId) ||
        (!$reservationId && $res['id_utilisateur'] == $userId && $res['id_voyage'] == $voyageId)
    ) {
        $existingReservation = $res;
        break;
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        // Vérifier si c'est une nouvelle réservation
        $isNewReservation = !$existingReservation;

        // Sauvegarder les options
        $options = [];
        foreach ($voyage['etapes'] as $etape) {
            $etapeId = $etape['id_etape'];
            $options["etape_$etapeId"] = [
                'activite' => isset($_POST["activite_$etapeId"]) && $_POST["activite_$etapeId"] !== '' ? (int)$_POST["activite_$etapeId"] : null,
                'hebergement' => isset($_POST["hebergement_$etapeId"]) && $_POST["hebergement_$etapeId"] !== '' ? (int)$_POST["hebergement_$etapeId"] : null,
                'restauration' => isset($_POST["restauration_$etapeId"]) && $_POST["restauration_$etapeId"] !== '' ? (int)$_POST["restauration_$etapeId"] : null,
                'transport' => isset($_POST["transport_$etapeId"]) && $_POST["transport_$etapeId"] !== '' ? (int)$_POST["transport_$etapeId"] : null
            ];
        }

        // Calculer le nouveau prix total
        $prixTotal = $voyage['prix_total']; // Initialisation du prix total
        $prixOptions = 0;
        foreach ($voyage['etapes'] as $etape) {
            $etapeId = $etape['id_etape'];
            $etapeOptions = $options["etape_$etapeId"] ?? [];

            // Activité
            if (isset($etapeOptions['activite'])) {
                foreach ($etape['options']['activites'] as $activite) {
                    if ($activite['id_option'] == $etapeOptions['activite']) {
                        $prixOptions += $activite['prix_par_personne'];
                        break;
                    }
                }
            }

            // Hébergement
            if (isset($etapeOptions['hebergement'])) {
                foreach ($etape['options']['hebergements'] as $hebergement) {
                    if ($hebergement['id_option'] == $etapeOptions['hebergement']) {
                        $prixOptions += $hebergement['prix_par_personne'];
                        break;
                    }
                }
            }

            // Restauration
            if (isset($etapeOptions['restauration'])) {
                foreach ($etape['options']['restaurations'] as $restauration) {
                    if ($restauration['id_option'] == $etapeOptions['restauration']) {
                        $prixOptions += $restauration['prix_par_personne'];
                        break;
                    }
                }
            }

            // Transport
            if (isset($etapeOptions['transport'])) {
                foreach ($etape['options']['transports'] as $transport) {
                    if ($transport['id_option'] == $etapeOptions['transport']) {
                        $prixOptions += $transport['prix_par_personne'];
                        break;
                    }
                }
            }
        }

        $prixTotal += $prixOptions; // Ajout des options au prix total

        if ($existingReservation) {
            // Mise à jour de la réservation existante
            foreach ($reservations as &$res) {
                if ($res['id_reservation'] == $existingReservation['id_reservation']) {
                    $res['options'] = $options;
                    $res['prix_total'] = $prixTotal;
                    break;
                }
            }

            // Si c'était une nouvelle réservation, mettre à jour l'utilisateur
            if ($isNewReservation) {
                foreach ($users as &$user) {
                    if ($user['id_user'] == $userId) {
                        $user['id_reservation'][] = $existingReservation['id_reservation'];
                        file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));
                        break;
                    }
                }
            }
        } else {
            // Création d'une nouvelle réservation
            $newReservationId = !empty($reservations) ? max(array_column($reservations, 'id_reservation')) + 1 : 101;

            $newReservation = [
                'id_reservation' => $newReservationId,
                'id_utilisateur' => $userId,
                'id_voyage' => $voyageId,
                'date_reservation' => date('Y-m-d'),
                'options' => $options,
                'prix_total' => $prixTotal,
                'paiement' => false
            ];

            $reservations[] = $newReservation;
            $existingReservation = $newReservation;

            // Mettre à jour l'utilisateur avec la nouvelle réservation
            foreach ($users as &$user) {
                if ($user['id_user'] == $userId) {
                    $user['id_reservation'][] = $newReservationId;
                    file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));
                    break;
                }
            }
        }

        // Sauvegarder les modifications
        file_put_contents('../data/reservation.json', json_encode($reservations, JSON_PRETTY_PRINT));

        // Redirection vers la page de récapitulatif
        header("Location: recap.php?id=" . $existingReservation['id_reservation']);
        exit();
    }
}

// Calculer le prix total actuel pour l'affichage
$prixTotalAffichage = $voyage['prix_total'];
if ($existingReservation) {
    $prixOptions = 0;
    foreach ($voyage['etapes'] as $etape) {
        $etapeId = $etape['id_etape'];
        $etapeOptions = $existingReservation['options']["etape_$etapeId"] ?? [];

        // Activité
        if (isset($etapeOptions['activite'])) {
            foreach ($etape['options']['activites'] as $activite) {
                if ($activite['id_option'] == $etapeOptions['activite']) {
                    $prixOptions += $activite['prix_par_personne'];
                    break;
                }
            }
        }

        // Hébergement
        if (isset($etapeOptions['hebergement'])) {
            foreach ($etape['options']['hebergements'] as $hebergement) {
                if ($hebergement['id_option'] == $etapeOptions['hebergement']) {
                    $prixOptions += $hebergement['prix_par_personne'];
                    break;
                }
            }
        }

        // Restauration
        if (isset($etapeOptions['restauration'])) {
            foreach ($etape['options']['restaurations'] as $restauration) {
                if ($restauration['id_option'] == $etapeOptions['restauration']) {
                    $prixOptions += $restauration['prix_par_personne'];
                    break;
                }
            }
        }

        // Transport
        if (isset($etapeOptions['transport'])) {
            foreach ($etape['options']['transports'] as $transport) {
                if ($transport['id_option'] == $etapeOptions['transport']) {
                    $prixOptions += $transport['prix_par_personne'];
                    break;
                }
            }
        }
    }
    $prixTotalAffichage += $prixOptions;
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
    <meta name="description" content="Les details du voyages">
    <title>KYS - Details</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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

            <button id="theme-toggle" class="nav-btn">
                <i class="fa-solid fa-moon"></i>
            </button>

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

    <div class="voyage-details-container">
        <div class="voyage-header">
            <div class="col-md-4">
                <img src="assets/img/<?php echo $voyage['id_voyage']; ?>.jpg" class="voyage-image" alt="<?php echo htmlspecialchars($voyage['titre']); ?>">
            </div>
            <h1><?php echo htmlspecialchars($voyage['titre']); ?></h1>
            <p class="lead"><?php echo htmlspecialchars($voyage['description']); ?></p>
            <i class="far fa-calendar-alt"></i> Du <?php echo htmlspecialchars($voyage['dates']['debut']); ?> au <?php echo htmlspecialchars($voyage['dates']['fin']); ?>
            (<?php echo htmlspecialchars($voyage['dates']['duree']); ?>)

            <div class="price-display">
                Prix total estimé: <span class="dynamic-price" id="total-price"><?php echo number_format($prixTotalAffichage, 0, ',', ' '); ?></span> €
            </div>

            <div>
                <?php foreach ($voyage['specificites'] as $spec): ?>
                    <span class="voyage-badge"><?php echo htmlspecialchars($spec); ?></span>
                <?php endforeach; ?>
            </div>

            <?php if ($voyage['contrat']): ?>
                <div class="voyage-alert alert">
                    <i class="fas fa-info-circle"></i> Ce voyage nécessite la signature du contrat.
                </div>
            <?php endif; ?>
        </div>

        <form method="post" id="voyage-form">
            <h2 class="mb-4">Étapes du voyage</h2><br>

            <?php foreach ($voyage['etapes'] as $etape): ?>
                <div class="etape-card" data-etape-id="<?php echo $etape['id_etape']; ?>">
                    <h3><?php echo htmlspecialchars($etape['titre']); ?></h3>
                    <p>
                        <strong>Dates:</strong> Du <?php echo htmlspecialchars($etape['dates']['arrivee']); ?> au <?php echo htmlspecialchars($etape['dates']['depart']); ?> (<?php echo htmlspecialchars($etape['dates']['duree']); ?>)
                    </p>

                    <?php if (!empty($etape['options'])): ?>
                        <div class="option-section">
                            <div class="option-group" id="options-<?php echo $etape['id_etape']; ?>">
                                <!-- Contenu chargé dynamiquement -->
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="voyage-alert">
                            <i class="fas fa-info-circle"></i> Aucune option disponible pour cette étape.
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div class="actions-container">
                <button type="submit" name="save" class="btn btn-save">
                    <i class="fas fa-save"></i> Sauvegarder et continuer
                </button>
            </div>
        </form>
    </div>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initVoyageOptions({
                voyageId: <?php echo $voyageId; ?>,
                reservationId: <?php echo $existingReservation ? $existingReservation['id_reservation'] : 'null'; ?>,
                basePrice: <?php echo $voyage['prix_total']; ?>,
                currentTotalPrice: <?php echo $prixTotalAffichage; ?>,
                currentOptions: <?php echo $existingReservation ? json_encode($existingReservation['options']) : '{}'; ?>
            });
        });
    </script>
    <script src="assets/js/voyage-options.js"></script>
    <script src="assets/js/sombre.js"></script>
</body>

</html>