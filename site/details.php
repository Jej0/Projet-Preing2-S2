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

// Utilisateur connecté - nous travaillons sur une copie
$currentUser = $_SESSION['user'];
$userId = $currentUser['id_user'];

// Initialisation et vérification de l'ID du voyage
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

        if ($existingReservation) {
            // Mise à jour de la réservation existante
            foreach ($reservations as &$res) {
                if ($res['id_reservation'] == $existingReservation['id_reservation']) {
                    $res['options'] = $options;
                    break;
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
                'prix_total' => $voyage['prix_total'],
                'paiement' => false
            ];

            $reservations[] = $newReservation;
            $existingReservation = $newReservation;

            // Ajouter l'ID de réservation à l'utilisateur
            if (!in_array($newReservationId, $currentUser['id_reservation'])) {
                $currentUser['id_reservation'][] = $newReservationId;

                // Mettre à jour l'utilisateur dans le tableau $users
                foreach ($users as &$user) {
                    if ($user['id_user'] == $userId) {
                        $user['id_reservation'] = $currentUser['id_reservation'];
                        break;
                    }
                }

                // Mettre à jour la session
                $_SESSION['user'] = $currentUser;
            }

            // Ajouter l'ID de réservation au voyage
            foreach ($voyages as &$v) {
                if ($v['id_voyage'] == $voyageId) {
                    if (!in_array($newReservationId, $v['liste_personnes']['id_reservation'])) {
                        $v['liste_personnes']['id_reservation'][] = $newReservationId;
                    }
                    break;
                }
            }
        }

        // Sauvegarder les modifications
        file_put_contents('../data/reservation.json', json_encode($reservations, JSON_PRETTY_PRINT));
        file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));
        file_put_contents('../data/voyages.json', json_encode($voyages, JSON_PRETTY_PRINT));

        // Redirection
        header("Location: recherche.php");
        exit();
    } elseif (isset($_POST['cancel'])) {
        // Annuler la réservation
        if ($existingReservation) {
            // Supprimer la réservation
            $reservations = array_filter($reservations, function ($res) use ($existingReservation) {
                return $res['id_reservation'] != $existingReservation['id_reservation'];
            });

            // Supprimer l'ID de réservation de l'utilisateur
            $currentUser['id_reservation'] = array_values(array_filter($currentUser['id_reservation'], function ($id) use ($existingReservation) {
                return $id != $existingReservation['id_reservation'];
            }));

            // Mettre à jour l'utilisateur dans le tableau $users
            foreach ($users as &$user) {
                if ($user['id_user'] == $userId) {
                    $user['id_reservation'] = $currentUser['id_reservation'];
                    break;
                }
            }

            // Mettre à jour la session
            $_SESSION['user'] = $currentUser;

            // Supprimer l'ID de réservation du voyage
            foreach ($voyages as &$v) {
                if ($v['id_voyage'] == $voyageId) {
                    $v['liste_personnes']['id_reservation'] = array_values(array_filter(
                        $v['liste_personnes']['id_reservation'],
                        function ($id) use ($existingReservation) {
                            return $id != $existingReservation['id_reservation'];
                        }
                    ));
                    break;
                }
            }

            // Sauvegarder les modifications
            file_put_contents('../data/reservation.json', json_encode(array_values($reservations), JSON_PRETTY_PRINT));
            file_put_contents('../data/users.json', json_encode($users, JSON_PRETTY_PRINT));
            file_put_contents('../data/voyages.json', json_encode($voyages, JSON_PRETTY_PRINT));
        }

        // Redirection
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
    <meta name="description" content="Details du voyage.">
    <title>KYS - Details</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            <h1><?php echo htmlspecialchars($voyage['titre']); ?></h1>
            <p class="lead"><?php echo htmlspecialchars($voyage['description']); ?></p>

            <div class="row">
                <div class="col-md-4">
                    <p><strong>Note moyenne:</strong> <?php echo htmlspecialchars($voyage['note_moyenne']); ?>/5 (<?php echo htmlspecialchars($voyage['nb_avis']); ?> avis)</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Dates:</strong> Du <?php echo htmlspecialchars($voyage['dates']['debut']); ?> au <?php echo htmlspecialchars($voyage['dates']['fin']); ?> (<?php echo htmlspecialchars($voyage['dates']['duree']); ?>)</p>
                </div>
                <div class="col-md-4">
                    <p><strong>Prix total:</strong> <?php echo number_format($voyage['prix_total'], 0, ',', ' '); ?> €</p>
                </div>
            </div>

            <div class="voyage-badges">
                <?php foreach ($voyage['specificites'] as $spec): ?>
                    <span class="voyage-badge"><?php echo htmlspecialchars($spec); ?></span>
                <?php endforeach; ?>
            </div>

            <?php if ($voyage['contrat']): ?>
                <div class="voyage-alert alert alert-info">
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
                            <strong>Dates:</strong> Du <?php echo htmlspecialchars($etape['dates']['arrivee']); ?> au <?php echo htmlspecialchars($etape['dates']['depart']); ?> (<?php echo htmlspecialchars($etape['dates']['duree']); ?>)<br>
                            <strong>Lieu:</strong> <?php echo htmlspecialchars($etape['position_geographique']['ville']); ?>
                        </p>

                        <?php if (!empty($etape['options'])): ?>
                            <div class="option-section">
                                <h4>Options disponibles</h4>

                                <!-- Activités -->
                                <?php if (!empty($etape['options']['activites'])): ?>
                                    <div class="mb-3">
                                        <h5>Activités</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="activite_<?php echo $etape['id_etape']; ?>" id="activite_<?php echo $etape['id_etape']; ?>_none" value=""
                                                <?php if (!$existingReservation || $existingReservation['options']["etape_{$etape['id_etape']}"]['activite'] === null) echo 'checked'; ?>>
                                            <label class="form-check-label" for="activite_<?php echo $etape['id_etape']; ?>_none">
                                                Aucune activité sélectionnée
                                            </label>
                                        </div>
                                        <?php foreach ($etape['options']['activites'] as $activite): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="activite_<?php echo $etape['id_etape']; ?>" id="activite_<?php echo $etape['id_etape']; ?>_<?php echo $activite['id_option']; ?>" value="<?php echo $activite['id_option']; ?>"
                                                    <?php if ($existingReservation && $existingReservation['options']["etape_{$etape['id_etape']}"]['activite'] == $activite['id_option']) echo 'checked'; ?>>
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
                                            <input class="form-check-input" type="radio" name="hebergement_<?php echo $etape['id_etape']; ?>" id="hebergement_<?php echo $etape['id_etape']; ?>_none" value=""
                                                <?php if (!$existingReservation || $existingReservation['options']["etape_{$etape['id_etape']}"]['hebergement'] === null) echo 'checked'; ?>>
                                            <label class="form-check-label" for="hebergement_<?php echo $etape['id_etape']; ?>_none">
                                                Aucun hébergement sélectionné
                                            </label>
                                        </div>
                                        <?php foreach ($etape['options']['hebergements'] as $hebergement): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="hebergement_<?php echo $etape['id_etape']; ?>" id="hebergement_<?php echo $etape['id_etape']; ?>_<?php echo $hebergement['id_option']; ?>" value="<?php echo $hebergement['id_option']; ?>"
                                                    <?php if ($existingReservation && $existingReservation['options']["etape_{$etape['id_etape']}"]['hebergement'] == $hebergement['id_option']) echo 'checked'; ?>>
                                                <label class="form-check-label" for="hebergement_<?php echo $etape['id_etape']; ?>_<?php echo $hebergement['id_option']; ?>">
                                                    <?php echo htmlspecialchars($hebergement['nom']); ?> - <?php echo htmlspecialchars($hebergement['description']); ?> (<?php echo $hebergement['prix_par_personne']; ?> €)
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
                                            <input class="form-check-input" type="radio" name="restauration_<?php echo $etape['id_etape']; ?>" id="restauration_<?php echo $etape['id_etape']; ?>_none" value=""
                                                <?php if (!$existingReservation || $existingReservation['options']["etape_{$etape['id_etape']}"]['restauration'] === null) echo 'checked'; ?>>
                                            <label class="form-check-label" for="restauration_<?php echo $etape['id_etape']; ?>_none">
                                                Aucune restauration sélectionnée
                                            </label>
                                        </div>
                                        <?php foreach ($etape['options']['restaurations'] as $restauration): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="restauration_<?php echo $etape['id_etape']; ?>" id="restauration_<?php echo $etape['id_etape']; ?>_<?php echo $restauration['id_option']; ?>" value="<?php echo $restauration['id_option']; ?>"
                                                    <?php if ($existingReservation && $existingReservation['options']["etape_{$etape['id_etape']}"]['restauration'] == $restauration['id_option']) echo 'checked'; ?>>
                                                <label class="form-check-label" for="restauration_<?php echo $etape['id_etape']; ?>_<?php echo $restauration['id_option']; ?>">
                                                    <?php echo htmlspecialchars($restauration['nom']); ?> - <?php echo htmlspecialchars($restauration['description']); ?> (<?php echo $restauration['prix_par_personne']; ?> €)
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
                                            <input class="form-check-input" type="radio" name="transport_<?php echo $etape['id_etape']; ?>" id="transport_<?php echo $etape['id_etape']; ?>_none" value=""
                                                <?php if (!$existingReservation || $existingReservation['options']["etape_{$etape['id_etape']}"]['transport'] === null) echo 'checked'; ?>>
                                            <label class="form-check-label" for="transport_<?php echo $etape['id_etape']; ?>_none">
                                                Aucun transport sélectionné
                                            </label>
                                        </div>
                                        <?php foreach ($etape['options']['transports'] as $transport): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="transport_<?php echo $etape['id_etape']; ?>" id="transport_<?php echo $etape['id_etape']; ?>_<?php echo $transport['id_option']; ?>" value="<?php echo $transport['id_option']; ?>"
                                                    <?php if ($existingReservation && $existingReservation['options']["etape_{$etape['id_etape']}"]['transport'] == $transport['id_option']) echo 'checked'; ?>>
                                                <label class="form-check-label" for="transport_<?php echo $etape['id_etape']; ?>_<?php echo $transport['id_option']; ?>">
                                                    <?php echo htmlspecialchars($transport['nom']); ?> - <?php echo htmlspecialchars($transport['description']); ?> (<?php echo $transport['prix_par_personne']; ?> €)
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="voyage-alert alert alert-info">
                                <i class="fas fa-info-circle"></i> Aucune option disponible pour cette étape.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="actions-container">
                <div>
                    <button type="submit" name="cancel" class="btn-action btn-cancel <?php echo ($existingReservation && $existingReservation['paiement']) ? 'btn-disabled' : ''; ?>"
                        <?php echo ($existingReservation && $existingReservation['paiement']) ? 'disabled' : ''; ?>>
                        <i class="fas fa-times"></i> Annuler
                    </button>
                </div>
                <div>
                    <button type="submit" name="save" class="btn-action btn-save <?php echo ($existingReservation && $existingReservation['paiement']) ? 'btn-disabled' : ''; ?>"
                        <?php echo ($existingReservation && $existingReservation['paiement']) ? 'disabled' : ''; ?>>
                        <i class="fas fa-save"></i> Sauvegarder
                    </button>

                    <?php if ($existingReservation && !$existingReservation['paiement']): ?>
                        <a href="paiement.php?id=<?php echo $existingReservation['id_reservation']; ?>" class="btn-action btn-payer">
                            <i class="fas fa-credit-card"></i> Payer
                        </a>
                    <?php elseif ($existingReservation && $existingReservation['paiement']): ?>
                        <span class="badge bg-success ms-2">
                            <i class="fas fa-check-circle"></i> Payé
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</body>

</html>