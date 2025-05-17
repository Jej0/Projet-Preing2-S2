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

        $prixTotal = $voyage['prix_total'] + $prixOptions;

        if ($existingReservation) {
            // Mise à jour de la réservation existante
            foreach ($reservations as &$res) {
                if ($res['id_reservation'] == $existingReservation['id_reservation']) {
                    $res['options'] = $options;
                    $res['prix_total'] = $prixTotal;
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
                'prix_total' => $prixTotal,
                'paiement' => false
            ];

            $reservations[] = $newReservation;
            $existingReservation = $newReservation;
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
    <title>KYS - Details</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .option-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .option-group {
            margin-bottom: 15px;
        }

        .option-select {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }

        .price-display {
            font-size: 1.2em;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
        }

        .dynamic-price {
            color: #007bff;
        }

        .etape-card {
            margin-bottom: 30px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
        }

        .actions-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-save {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-save:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <nav>
        <!-- Votre navigation existante -->
    </nav>

    <div class="voyage-details-container">
        <div class="voyage-header">
            <h1><?php echo htmlspecialchars($voyage['titre']); ?></h1>
            <p class="lead"><?php echo htmlspecialchars($voyage['description']); ?></p>

            <div class="price-display">
                Prix total estimé: <span class="dynamic-price" id="total-price"><?php echo number_format($prixTotalAffichage, 0, ',', ' '); ?></span> €
            </div>

            <div class="voyage-badges">
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
            <h2 class="mb-4">Étapes du voyage</h2>

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
                <button type="submit" name="save" class="btn-save">
                    <i class="fas fa-save"></i> Sauvegarder et continuer
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const voyageId = <?php echo $voyageId; ?>;
            const reservationId = <?php echo $existingReservation ? $existingReservation['id_reservation'] : 'null'; ?>;
            const basePrice = <?php echo $voyage['prix_total']; ?>;
            let currentOptions = {};

            // Charger les options initiales si réservation existante
            <?php if ($existingReservation): ?>
                currentOptions = <?php echo json_encode($existingReservation['options']); ?>;
            <?php endif; ?>

            // Fonction pour charger les options d'une étape
            function loadOptionsForEtape(etapeId) {
                fetch(`../scripts_php/get_options.php?etape_id=${etapeId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (!data.success) {
                            console.error('Failed to load options for etape', etapeId);
                            return;
                        }

                        const container = document.querySelector(`#options-${etapeId}`);
                        if (!container) return;

                        container.innerHTML = '';

                        // Pour chaque type d'option (activité, hébergement, etc.)
                        const optionTypes = {
                            'activite': 'Activités',
                            'hebergement': 'Hébergements',
                            'restauration': 'Restauration',
                            'transport': 'Transports'
                        };

                        Object.entries(optionTypes).forEach(([optionType, displayName]) => {
                            if (data.options[optionType] && data.options[optionType].length > 0) {
                                const group = document.createElement('div');
                                group.className = 'option-subgroup';
                                group.innerHTML = `
                                    <h5>${displayName}</h5>
                                    <select class="option-select" 
                                            data-option-type="${optionType}" 
                                            data-etape-id="${etapeId}"
                                            name="${optionType}_${etapeId}">
                                        <option value="">Aucune sélection</option>
                                    </select>
                                `;

                                const select = group.querySelector('select');

                                // Remplir les options
                                data.options[optionType].forEach(option => {
                                    const opt = document.createElement('option');
                                    opt.value = option.id_option;
                                    opt.textContent = `${option.nom} - ${option.description} (${option.prix_par_personne} €)`;
                                    select.appendChild(opt);
                                });

                                // Sélectionner l'option actuelle si elle existe
                                const currentOption = currentOptions[`etape_${etapeId}`]?.[optionType];
                                if (currentOption) {
                                    select.value = currentOption;
                                }

                                // Écouter les changements
                                select.addEventListener('change', updatePrice);

                                container.appendChild(group);
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error loading options:', error);
                    });
            }

            // Fonction pour mettre à jour le prix total
            function updatePrice() {
                const options = {};

                // Récupérer toutes les options sélectionnées
                document.querySelectorAll('.option-select').forEach(select => {
                    const etapeId = select.dataset.etapeId;
                    const optionType = select.dataset.optionType;

                    if (!options[`etape_${etapeId}`]) {
                        options[`etape_${etapeId}`] = {};
                    }

                    if (select.value) {
                        options[`etape_${etapeId}`][optionType] = parseInt(select.value);
                    }
                });

                // Envoyer les options au serveur pour calcul
                fetch('../scripts_php/calculate_price.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            voyage_id: voyageId,
                            options: options,
                            reservation_id: reservationId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            document.getElementById('total-price').textContent =
                                new Intl.NumberFormat('fr-FR').format(data.total_price);
                        } else {
                            console.error('Failed to calculate price');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating price:', error);
                    });
            }

            // Charger les options pour chaque étape
            document.querySelectorAll('.etape-card').forEach(card => {
                const etapeId = card.dataset.etapeId;
                loadOptionsForEtape(etapeId);
            });

            // Initialiser le prix
            updatePrice();
        });
    </script>
</body>

</html>