<?php
session_start();
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Connexion via la classe Database
$db = Database::getInstance();
$pdo = $db->getConnection();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php?redirect=details.php?id=" . ($_GET['id'] ?? ''));
    exit();
}

$currentUser = $_SESSION['user'];
$userId = $currentUser['id'];

// Récupérer l'ID du voyage depuis GET
$voyageId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($voyageId <= 0) {
    header("Location: recherche.php");
    exit();
}

// Récupérer les informations du voyage
$stmt = $pdo->prepare("SELECT * FROM voyages WHERE id_voyage = ?");
$stmt->execute([$voyageId]);
$voyage = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$voyage) {
    header("Location: recherche.php");
    exit();
}

// Récupérer les étapes du voyage
$stmt = $pdo->prepare("SELECT * FROM etapes WHERE id_voyage = ?");
$stmt->execute([$voyageId]);
$etapes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($etapes as &$etape) {
    // Initialiser un tableau vide pour les options
    $etape['options'] = [
        'activites' => [],
        'hebergements' => [],
        'restaurations' => [],
        'transports' => []
    ];

    // Récupérer toutes les options pour cette étape et les organiser par type
    $stmt = $pdo->prepare("SELECT * FROM options_etape WHERE id_etape = ?");
    $stmt->execute([$etape['id_etape']]);
    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($options as $option) {
        switch ($option['type_option']) {
            case 'activite':
                $etape['options']['activites'][] = $option;
                break;
            case 'hebergement':
                $etape['options']['hebergements'][] = $option;
                break;
            case 'restauration':
                $etape['options']['restaurations'][] = $option;
                break;
            case 'transport':
                $etape['options']['transports'][] = $option;
                break;
        }
    }
}
unset($etape);
$voyage['etapes'] = $etapes;

// Pour correspondre à la structure du second code, créer une clé "dates"
$voyage['dates'] = [
    'debut' => $voyage['date_debut'],
    'fin'   => $voyage['date_fin'],
    'duree' => $voyage['duree']
];

// Pour cet exemple, nous ne gérons pas de réservation existante
$existingReservation = null;

// Fonction de calcul du prix total des options
function calculerPrixOptions($voyage, $options)
{
    $prixOptions = 0;
    foreach ($voyage['etapes'] as $etape) {
        $etapeId = $etape['id_etape'];
        $etapeOptions = isset($options["etape_$etapeId"]) ? $options["etape_$etapeId"] : [];

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
    return $prixOptions;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel'])) {
        // En cas d'annulation, rediriger vers la page de recherche
        header("Location: recherche.php");
        exit();
    } elseif (isset($_POST['save'])) {
        // Construire le tableau d'options
        $options = [];
        foreach ($voyage['etapes'] as $etape) {
            $etapeId = $etape['id_etape'];
            $options["etape_$etapeId"] = [
                'activite'    => isset($_POST["activite_$etapeId"]) && $_POST["activite_$etapeId"] !== '' ? (int)$_POST["activite_$etapeId"] : null,
                'hebergement' => isset($_POST["hebergement_$etapeId"]) && $_POST["hebergement_$etapeId"] !== '' ? (int)$_POST["hebergement_$etapeId"] : null,
                'restauration'=> isset($_POST["restauration_$etapeId"]) && $_POST["restauration_$etapeId"] !== '' ? (int)$_POST["restauration_$etapeId"] : null,
                'transport'   => isset($_POST["transport_$etapeId"]) && $_POST["transport_$etapeId"] !== '' ? (int)$_POST["transport_$etapeId"] : null
            ];
        }
        
        // Calculer le coût total des options et le prix final
        $prixOptions = calculerPrixOptions($voyage, $options);
        $prixTotal = $voyage['prix_total'] + $prixOptions;
        
        // Insertion dans la table reservations_json (nouvelle réservation)
        $stmt = $pdo->prepare("INSERT INTO reservations_json (id_utilisateur, id_voyage, date_reservation, options, prix_total, paiement) VALUES (?, ?, ?, ?, ?, ?)");
        $dateReservation = date('Y-m-d');
        $jsonOptions = json_encode($options);
        $paiement = 0; // non payé
        $stmt->execute([$userId, $voyageId, $dateReservation, $jsonOptions, $prixTotal, $paiement]);
        
        header("Location: recherche.php");
        exit();
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
    <meta name="description" content="Détails du voyage et réservation.">
    <title>KYS - Details</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Font Awesome -->
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
            <a href="scripts_php/logout.php" class="btn nav-btn">Déconnexion</a>
            <a href="profile.php" class="profile-icon">
                <i class="fas fa-user-circle"></i>
            </a>
        </div>
    </nav>

    <div class="voyage-details-container">
        <div class="voyage-header">
            <h1><?php echo htmlspecialchars($voyage['titre']); ?></h1>
            <p class="lead"><?php echo htmlspecialchars($voyage['description']); ?></p>
            <div class="row">
                <div class="col-md-4">
                    <p><strong>Dates :</strong> Du <?php echo htmlspecialchars($voyage['dates']['debut']); ?> au <?php echo htmlspecialchars($voyage['dates']['fin']); ?><br>
                    (<?php echo htmlspecialchars($voyage['dates']['duree']); ?>)</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Prix de base :</strong> <?php echo number_format($voyage['prix_total'], 0, ',', ' '); ?> €</p>
                </div>
            </div>
            <div class="voyage-badges">
                <?php if (!empty($voyage['specificites'])): ?>
                    <?php foreach ($voyage['specificites'] as $spec): ?>
                        <span class="voyage-badge"><?php echo htmlspecialchars($spec); ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php if ($voyage['contrat']): ?>
                <div class="voyage-alert alert">
                    <i class="fas fa-info-circle"></i> Ce voyage nécessite la signature d'un contrat.
                </div>
            <?php endif; ?>
        </div>

        <form method="post">
            <h2 class="mb-4">Étapes du voyage</h2>
            <?php foreach ($voyage['etapes'] as $etape): ?>
                <div class="etape-card">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($etape['titre']); ?></h3>
                        <p class="card-text">
                            <strong>Dates :</strong> Du <?php echo htmlspecialchars($etape['date_arrivee']); ?> au <?php echo htmlspecialchars($etape['date_depart']); ?><br>
                            <strong>Lieu :</strong> <?php echo htmlspecialchars($etape['ville']); ?>
                        </p>
                        <?php if (!empty($etape['options'])): ?>
                            <div class="option-section">
                                <h4>Options disponibles</h4>

                                <!-- Activités -->
                                <?php if (!empty($etape['options']['activites'])): ?>
                                    <div class="mb-3">
                                        <h5>Activités</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="activite_<?php echo $etape['id_etape']; ?>" id="activite_<?php echo $etape['id_etape']; ?>_none" value="" checked>
                                            <label class="form-check-label" for="activite_<?php echo $etape['id_etape']; ?>_none">
                                                Aucune activité sélectionnée
                                            </label>
                                        </div>
                                        <?php foreach ($etape['options']['activites'] as $activite): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="activite_<?php echo $etape['id_etape']; ?>" id="activite_<?php echo $etape['id_etape']; ?>_<?php echo $activite['id_option']; ?>" value="<?php echo $activite['id_option']; ?>">
                                                <label class="form-check-label" for="activite_<?php echo $etape['id_etape']; ?>_<?php echo $activite['id_option']; ?>">
                                                    <?php echo htmlspecialchars($activite['nom']); ?> - <?php echo htmlspecialchars($activite['description']); ?> (<?php echo $activite['prix_par_personne']; ?> €)
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Hébergements -->
                                <?php if (!empty($etape['options']['hebergements'])): ?>
                                    <div class="mb-3">
                                        <h5>Hébergements</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="hebergement_<?php echo $etape['id_etape']; ?>" id="hebergement_<?php echo $etape['id_etape']; ?>_none" value="" checked>
                                            <label class="form-check-label" for="hebergement_<?php echo $etape['id_etape']; ?>_none">
                                                Aucun hébergement sélectionné
                                            </label>
                                        </div>
                                        <?php foreach ($etape['options']['hebergements'] as $heb): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="hebergement_<?php echo $etape['id_etape']; ?>" id="hebergement_<?php echo $etape['id_etape']; ?>_<?php echo $heb['id_option']; ?>" value="<?php echo $heb['id_option']; ?>">
                                                <label class="form-check-label" for="hebergement_<?php echo $etape['id_etape']; ?>_<?php echo $heb['id_option']; ?>">
                                                    <?php echo htmlspecialchars($heb['nom']); ?> - <?php echo htmlspecialchars($heb['description']); ?> (<?php echo $heb['prix_par_personne']; ?> €)
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Restaurations -->
                                <?php if (!empty($etape['options']['restaurations'])): ?>
                                    <div class="mb-3">
                                        <h5>Restauration</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="restauration_<?php echo $etape['id_etape']; ?>" id="restauration_<?php echo $etape['id_etape']; ?>_none" value="" checked>
                                            <label class="form-check-label" for="restauration_<?php echo $etape['id_etape']; ?>_none">
                                                Aucune restauration sélectionnée
                                            </label>
                                        </div>
                                        <?php foreach ($etape['options']['restaurations'] as $rest): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="restauration_<?php echo $etape['id_etape']; ?>" id="restauration_<?php echo $etape['id_etape']; ?>_<?php echo $rest['id_option']; ?>" value="<?php echo $rest['id_option']; ?>">
                                                <label class="form-check-label" for="restauration_<?php echo $etape['id_etape']; ?>_<?php echo $rest['id_option']; ?>">
                                                    <?php echo htmlspecialchars($rest['nom']); ?> - <?php echo htmlspecialchars($rest['description']); ?> (<?php echo $rest['prix_par_personne']; ?> €)
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Transports -->
                                <?php if (!empty($etape['options']['transports'])): ?>
                                    <div class="mb-3">
                                        <h5>Transports</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="transport_<?php echo $etape['id_etape']; ?>" id="transport_<?php echo $etape['id_etape']; ?>_none" value="" checked>
                                            <label class="form-check-label" for="transport_<?php echo $etape['id_etape']; ?>_none">
                                                Aucun transport sélectionné
                                            </label>
                                        </div>
                                        <?php foreach ($etape['options']['transports'] as $trans): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="transport_<?php echo $etape['id_etape']; ?>" id="transport_<?php echo $etape['id_etape']; ?>_<?php echo $trans['id_option']; ?>" value="<?php echo $trans['id_option']; ?>">
                                                <label class="form-check-label" for="transport_<?php echo $etape['id_etape']; ?>_<?php echo $trans['id_option']; ?>">
                                                    <?php echo htmlspecialchars($trans['nom']); ?> - <?php echo htmlspecialchars($trans['description']); ?> (<?php echo $trans['prix_par_personne']; ?> €)
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="voyage-alert">
                                <i class="fas fa-info-circle"></i> Aucune option disponible pour cette étape.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="actions-container">
                <div>
                    <button type="submit" name="cancel" class="btn-action btn-cancel">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                </div>
                <div>
                    <button type="submit" name="save" class="btn-action btn-save">
                        <i class="fas fa-save"></i> Sauvegarder et réserver
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
