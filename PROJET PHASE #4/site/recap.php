<?php
session_start();

require('../scripts_php/getapikey.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifier l'ID de réservation
$reservationId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($reservationId <= 0) {
    header("Location: recherche.php");
    exit();
}

// Charger les données
$reservations = json_decode(file_get_contents('../data/reservation.json'), true);
$voyages = json_decode(file_get_contents('../data/voyages.json'), true);

// Trouver la réservation
$reservation = null;
foreach ($reservations as $res) {
    if ($res['id_reservation'] == $reservationId && $res['id_utilisateur'] == $_SESSION['user']['id_user']) {
        $reservation = $res;
        break;
    }
}

if (!$reservation) {
    header("Location: recherche.php");
    exit();
}

// Trouver le voyage correspondant
$voyage = null;
foreach ($voyages as $v) {
    if ($v['id_voyage'] == $reservation['id_voyage']) {
        $voyage = $v;
        break;
    }
}

if (!$voyage) {
    header("Location: recherche.php");
    exit();
}

// Calculer le prix total avec options
$prixTotal = $voyage['prix_total'];
$optionsDetails = [];

foreach ($voyage['etapes'] as $etape) {
    $etapeId = $etape['id_etape'];
    $etapeOptions = $reservation['options']["etape_$etapeId"] ?? [];
    $etapeDetails = [
        'titre' => $etape['titre'],
        'options' => []
    ];

    // Activité
    if (isset($etapeOptions['activite'])) {
        foreach ($etape['options']['activites'] as $activite) {
            if ($activite['id_option'] == $etapeOptions['activite']) {
                $prixTotal += $activite['prix_par_personne'];
                $etapeDetails['options'][] = [
                    'type' => 'Activité',
                    'nom' => $activite['nom'],
                    'description' => $activite['description'],
                    'prix' => $activite['prix_par_personne']
                ];
                break;
            }
        }
    }

    // Hébergement
    if (isset($etapeOptions['hebergement'])) {
        foreach ($etape['options']['hebergements'] as $hebergement) {
            if ($hebergement['id_option'] == $etapeOptions['hebergement']) {
                $prixTotal += $hebergement['prix_par_personne'];
                $etapeDetails['options'][] = [
                    'type' => 'Hébergement',
                    'nom' => $hebergement['nom'],
                    'description' => $hebergement['description'],
                    'prix' => $hebergement['prix_par_personne']
                ];
                break;
            }
        }
    }

    // Restauration
    if (isset($etapeOptions['restauration'])) {
        foreach ($etape['options']['restaurations'] as $restauration) {
            if ($restauration['id_option'] == $etapeOptions['restauration']) {
                $prixTotal += $restauration['prix_par_personne'];
                $etapeDetails['options'][] = [
                    'type' => 'Restauration',
                    'nom' => $restauration['nom'],
                    'description' => $restauration['description'],
                    'prix' => $restauration['prix_par_personne']
                ];
                break;
            }
        }
    }

    // Transport
    if (isset($etapeOptions['transport'])) {
        foreach ($etape['options']['transports'] as $transport) {
            if ($transport['id_option'] == $etapeOptions['transport']) {
                $prixTotal += $transport['prix_par_personne'];
                $etapeDetails['options'][] = [
                    'type' => 'Transport',
                    'nom' => $transport['nom'],
                    'description' => $transport['description'],
                    'prix' => $transport['prix_par_personne']
                ];
                break;
            }
        }
    }

    if (!empty($etapeDetails['options'])) {
        $optionsDetails[] = $etapeDetails;
    }
}

$vendeur = "MI-4_H"; // Remplace par ton vrai code vendeur
$api_key = getAPIKey($vendeur);

$transaction = bin2hex(random_bytes(12)); // Génération d'un ID de transaction unique
$montant = number_format($reservation['prix_total'], 2, '.', '');
$retour = 'http://localhost:3000/site/retour_paiement.php?id=' . $_GET['id'];
$control = md5($api_key
    . "#" . $transaction
    . "#" . $montant
    . "#" . $vendeur
    . "#" . $retour . "#");

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="title" content="Keep Yourself Safe">
    <meta name="author" content="Keep Yourself Safe | Alex MIKOLAJEWSKI | Axel ATAGAN | Naïm LACHGAR-BOUACHRA">
    <meta name="description" content="Page récapitulative de votre voyage.">
    <title>KYS - Récapitulatif</title>
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

    <main class="recap-container">
        <div class="profile-section">
            <h1>Récapitulatif de votre voyage</h1>
            <h2><?php echo htmlspecialchars($voyage['titre']); ?></h2>
            <p><?php echo htmlspecialchars($voyage['description']); ?></p>

            <div class="price-summary">
                <h3>Détail du prix</h3>
                <p>Prix de base: <?php echo number_format($voyage['prix_total'], 0, ',', ' '); ?> €</p>
                <p>Options: <?php echo number_format($prixTotal - $voyage['prix_total'], 0, ',', ' '); ?> €</p>
                <p class="total-price">Total: <?php echo number_format($prixTotal, 0, ',', ' '); ?> €</p>
            </div>

            <div class="options-summary">
                <h3>Options sélectionnées</h3>
                <?php foreach ($optionsDetails as $etape): ?>
                    <div class="etape-options">
                        <h4><?php echo htmlspecialchars($etape['titre']); ?></h4>
                        <?php foreach ($etape['options'] as $option): ?>
                            <div class="option-item">
                                <strong><?php echo htmlspecialchars($option['type']); ?>:</strong>
                                <?php echo htmlspecialchars($option['nom']); ?> -
                                <?php echo htmlspecialchars($option['description']); ?>
                                (<?php echo $option['prix']; ?> €)
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="payment-actions">
                <?php if (!$reservation['paiement']): ?>
                    <a href="details.php?id=<?php echo $voyage['id_voyage']; ?>&reservation=<?php echo $reservationId; ?>" class="btn btn-modifier">
                        <i class="fas fa-edit"></i> Modifier les options
                    </a>
                    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
                        <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
                        <input type="hidden" name="montant" value="<?php echo $montant; ?>">
                        <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
                        <input type="hidden" name="retour" value="<?php echo htmlspecialchars($retour); ?>">
                        <input type="hidden" name="control" value="<?php echo $control; ?>">
                        <button type="submit" class="btn btn-save"><i class="fas fa-credit-card"></i> Valider et payer</button>
                    </form>
                <?php else: ?>
                    <p class="paid-badge"><i class="fas fa-check-circle"></i> Déjà payé</p>
                <?php endif; ?>
            </div>
        </div>
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
</body>

</html>