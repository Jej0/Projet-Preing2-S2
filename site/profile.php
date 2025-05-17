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
$status = 'Membre depuis ' . date('d/m/Y', strtotime($user['date']['inscription']));
$isAdmin = $user['admin'] == 'true';

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

$historiqueVoyages = [];
$allVoyages = [
    [
        "id_reservation" => 102,
        "id_utilisateur" => 2,
        "id_voyage" => 2,
        "date_reservation" => "2025-03-25",
        "options" => [/*...*/],
        "prix_total" => 2500
    ],
    [
        "id_reservation" => 104,
        "id_utilisateur" => 2,
        "id_voyage" => 5,
        "date_reservation" => "2025-03-26",
        "options" => [/*...*/],
        "prix_total" => 8120
    ]
];

if (file_exists('../data/historique_voyages.json')) {
    $allVoyages = json_decode(file_get_contents('../data/historique_voyages.json'), true);
    
    // Filtrer seulement les voyages de cet utilisateur
    if (!empty($user['id_historique_voyages'])) {
        $historiqueVoyages = array_filter($allVoyages, function($voyage) use ($user) {
            return in_array($voyage['id_reservation'], $user['id_historique_voyages']);
        });
    }
}

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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Très bien mais comment ça marche ? -->
	<script src="assets/js/sombre.js"></script>
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

    <main class="profile-container">
        <section class="profile-header">
            <div class="profile-cover">
                <div class="profile-avatar">
                    <?php
                        $profilePicturePath = '../data/profile_picture/' . $user['username'] . '.jpg';
                        $defaultPicturePath = 'assets/img/placeholder.png';
                        $actualProfilePath = realpath($profilePicturePath);
                    ?>
                    <img src="<?= file_exists($actualProfilePath) ? 
                            '../data/profile_picture/' . $user['username'] . '.jpg?' . filemtime($actualProfilePath) : 
                            $defaultPicturePath ?>" 
                        alt="Photo de profil" 
                        class="profile-avatar-img"
                        id="header-profile-picture"> <!-- Ajout d'un ID unique -->
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
                <li><a class="btn" href="#activites">Historique</a></li>
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
                <button id="submit-all-changes" class="btn button hidden"><i class="fas fa-save"></i> Soumettre les modifications</button>
            </div>
            <div class="info-grid">
                <!-- Groupe Identité -->
                <div class="info-group">
                    <h3><i class="fas fa-id-card"></i> Identité</h3>

                    <!-- Champ Nom -->
                    <div class="info-item">
                        <i class="fas fa-signature"></i>
                        <div>
                            <label>Nom</label>
                            <div class="field-wrapper">
                                <p class="field-value"><?= !empty($user['information_personnelles']['nom']) ? htmlspecialchars($user['information_personnelles']['nom']) : 'Non renseigné' ?></p>
                                <input type="text" class="field-input hidden" value="<?= htmlspecialchars($user['information_personnelles']['nom'] ?? '') ?>">
                                <div class="field-actions hidden">
                                    <button class="btn edit-profile-btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn edit-profile-btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-profile-btn edit-field-btn"><i class="fas fa-edit"></i></button>
                        </div>
                    </div>

                    <!-- Champ Prénom -->
                    <div class="info-item">
                        <i class="fas fa-user-tag"></i>
                        <div>
                            <label>Prénom</label>
                            <div class="field-wrapper">
                                <p class="field-value"><?= !empty($user['information_personnelles']['prenom']) ? htmlspecialchars($user['information_personnelles']['prenom']) : 'Non renseigné' ?></p>
                                <input type="text" class="field-input hidden" value="<?= htmlspecialchars($user['information_personnelles']['prenom'] ?? '') ?>">
                                <div class="field-actions hidden">
                                    <button class="btn edit-profile-btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn edit-profile-btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-profile-btn edit-field-btn"><i class="fas fa-edit"></i></button>
                        </div>
                    </div>

                    <!-- Champ Âge -->
                    <div class="info-item">
                        <i class="fas fa-birthday-cake"></i>
                        <div>
                            <label>Âge</label>
                            <div class="field-wrapper">
                                <p class="field-value"><?= !empty($user['information_personnelles']['naissance']) ? htmlspecialchars($user['information_personnelles']['naissance']) : 'Non renseigné' ?></p>
                                <input type="text" class="field-input hidden" value="<?= htmlspecialchars($user['information_personnelles']['naissance'] ?? '') ?>">
                                <div class="field-actions hidden">
                                    <button class="btn edit-profile-btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn edit-profile-btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-profile-btn edit-field-btn"><i class="fas fa-edit"></i></button>
                        </div>
                    </div>
                </div>

                <!-- Groupe Coordonnées -->
                <div class="info-group">
                    <h3><i class="fas fa-address-book"></i> Coordonnées</h3>

                    <!-- Champ Email -->
                    <div class="info-item">
                        <i class="fas fa-at"></i>
                        <div>
                            <label>Email</label>
                            <div class="field-wrapper">
                                <p class="field-value"><?= $user['information_personnelles']['mail'] ?></p>
                                <input type="email" class="field-input hidden" value="<?= htmlspecialchars($user['information_personnelles']['mail'] ?? '') ?>">
                                <div class="field-actions hidden">
                                    <button class="btn edit-profile-btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn edit-profile-btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-profile-btn edit-field-btn"><i class="fas fa-edit"></i></button>
                        </div>
                    </div>

                    <!-- Champ Téléphone -->
                    <div class="info-item">
                        <i class="fas fa-mobile-alt"></i>
                        <div>
                            <label>Téléphone</label>
                            <div class="field-wrapper">
                                <p class="field-value"><?= !empty($user['information_personnelles']['telephone']) ? htmlspecialchars($user['information_personnelles']['telephone']) : 'Non renseigné' ?></p>
                                <input type="email" class="field-input hidden" value="<?= htmlspecialchars($user['information_personnelles']['telephone'] ?? '') ?>">
                                <div class="field-actions hidden">
                                    <button class="btn edit-profile-btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn edit-profile-btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-profile-btn edit-field-btn"><i class="fas fa-edit"></i></button>
                        </div>
                    </div>

                    <!-- Champ Adresse -->
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <label>Adresse</label>
                            <div class="field-wrapper">
                                <p class="field-value"><?= !empty($user['information_personnelles']['adresse']) ? htmlspecialchars($user['information_personnelles']['adresse']) : 'Non renseigné' ?></p>
                                <input type="email" class="field-input hidden" value="<?= htmlspecialchars($user['information_personnelles']['adresse'] ?? '') ?>">
                                <div class="field-actions hidden">
                                    <button class="btn edit-profile-btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn edit-profile-btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-profile-btn edit-field-btn"><i class="fas fa-edit"></i></button>
                        </div>
                    </div>

                </div>
            </div>








            <div class="info-grid">
                <!-- Groupe Photo de profil -->
                <div class="info-group">
                    <h3><i class="fas fa-portrait"></i> Photo de profil</h3>

                    <!-- Champ Photo de Profil -->
                    <div class="info-item">
                        <i class="fas fa-camera"></i>
                        <div>
                            <label>Photo de Profil</label>
                            <div class="field-wrapper">
                                <?php
                                    $profilePicturePath = '../data/profile_picture/' . $user['username'] . '.jpg';
                                    $defaultPicturePath = 'assets/img/placeholder.png';
                                    $actualProfilePath = realpath($profilePicturePath);
                                ?>
                                <img src="<?= file_exists($actualProfilePath) ? 
                                        '../data/profile_picture/' . $user['username'] . '.jpg?' . filemtime($actualProfilePath) : 
                                        $defaultPicturePath ?>" 
                                    alt="Photo de profil" 
                                    class="profile-picture"
                                    id="profile-picture-preview">
                                <form method="POST" action="../scripts_php/upload_profile_picture.php" enctype="multipart/form-data" class="upload-form">
                                    <input type="file" id="profile-picture-input" name="profile_picture" accept="image/jpeg,image/png" class="hidden">
                                    <label for="profile-picture-input" class="btn edit-profile-btn edit-field-btn btn-upload"><i class="fas fa-upload"></i></label>
                                    <button type="submit" class="btn btn-save hidden">Enregistrer</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>








                
                <!-- Groupe Contrat -->
                <div class="info-group">
                    <h3><i class="fas fa-book-dead"></i> Contrat</h3>

                    <!-- Champ Contrat -->
                    <div class="info-item">
                        <i class="fas fa-file-signature"></i>
                        <div>
                            <label>Statut du Contrat</label>
                            <div class="field-wrapper">
                                <?php if ($user['information_personnelles']['contrat']): ?>
                                    <p>Contrat signé</p>
                                <?php else: ?>
                                    <p>Contrat non signé</p>
                                    <a href="contrat.php" class="btn btn-sign-contract edit-profile-btn edit-field-btn">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>















        <section id="historique" class="profile-section">
            <div class="section-header">
                <h2>Mon Historique de Voyage</h2>
                <?php if (count($historiqueVoyages) > 3): ?>
                    <button id="show-all-btn" class="btn btn-base">Voir tout</button>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($historiqueVoyages)): ?>
                <div class="activities-grid" id="voyages-container">
                    <?php foreach (array_slice($historiqueVoyages, 0, 3) as $voyage): ?>
                        <div class="activity-card">
                            <div class="activity-detail-card">
                                <div class="activity-header">
                                    <i class="fas fa-suitcase"></i>
                                    <h3>Réservation #<?= $voyage['id_reservation'] ?></h3>
                                </div>
                                <ul class="activity-list">
                                    <li><strong>Date:</strong> <?= $voyage['date_reservation'] ?></li>
                                    <li><strong>Prix total:</strong> <?= $voyage['prix_total'] ?> €</li>
                                </ul>
                                <a href="details_voyage.php?id=<?= $voyage['id_reservation'] ?>" class="btn btn-base">Voir détails</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-data-message">Aucun voyage dans votre historique.</p>
            <?php endif; ?>
        </section>




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
                                                <span class="reservation-badge price"><?php echo number_format($reservation['prix_total'], 0, ',', ' '); ?> €</span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submitAllBtn = document.getElementById('submit-all-changes');
            let modifiedFields = new Map();

            // Gestion de l'édition des champs
            document.querySelectorAll('.edit-field-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const fieldItem = this.closest('.info-item');
                    const wrapper = fieldItem.querySelector('.field-wrapper');
                    const valueEl = wrapper.querySelector('.field-value');
                    const inputEl = wrapper.querySelector('.field-input');
                    const actionsEl = wrapper.querySelector('.field-actions');

                    // Activer le mode édition
                    valueEl.classList.add('hidden');
                    inputEl.classList.remove('hidden');
                    actionsEl.classList.remove('hidden');
                    this.classList.add('hidden');

                    // Stocker la valeur originale
                    if (!inputEl.dataset.originalValue) {
                        inputEl.dataset.originalValue = inputEl.value;
                    }
                });
            });

            // Gestion des annulations
            document.querySelectorAll('.cancel-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const fieldItem = this.closest('.info-item');
                    resetField(fieldItem);
                });
            });

            // Gestion des validations
            document.querySelectorAll('.save-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const fieldItem = this.closest('.info-item');
                    const wrapper = fieldItem.querySelector('.field-wrapper');
                    const valueEl = wrapper.querySelector('.field-value');
                    const inputEl = wrapper.querySelector('.field-input');
                    const editBtn = fieldItem.querySelector('.edit-field-btn');
                    const fieldName = fieldItem.querySelector('label').textContent.trim().toLowerCase();

                    // Validation du champ
                    if (!validateField(fieldName, inputEl.value)) {
                        return;
                    }

                    // Mettre à jour la valeur affichée
                    valueEl.textContent = inputEl.value || 'Non renseigné';

                    // Désactiver le mode édition
                    valueEl.classList.remove('hidden');
                    inputEl.classList.add('hidden');
                    this.closest('.field-actions').classList.add('hidden');
                    editBtn.classList.remove('hidden');

                    // Marquer comme modifié seulement si la valeur a changé
                    if (inputEl.value !== inputEl.dataset.originalValue) {
                        modifiedFields.set(fieldItem, {
                            name: getFieldKey(fieldName),
                            value: inputEl.value
                        });
                    } else {
                        modifiedFields.delete(fieldItem);
                    }

                    // Correction : Toujours vérifier s'il reste des modifications à valider
                    updateSubmitButton();
                });
            });

            // Fonction pour réinitialiser un champ
            function resetField(fieldItem) {
                const wrapper = fieldItem.querySelector('.field-wrapper');
                const valueEl = wrapper.querySelector('.field-value');
                const inputEl = wrapper.querySelector('.field-input');
                const actionsEl = wrapper.querySelector('.field-actions');
                const editBtn = fieldItem.querySelector('.edit-field-btn');

                // Revenir à la valeur originale
                inputEl.value = inputEl.dataset.originalValue;

                // Désactiver le mode édition
                valueEl.classList.remove('hidden');
                inputEl.classList.add('hidden');
                actionsEl.classList.add('hidden');
                editBtn.classList.remove('hidden');

                // Retirer des modifications si présent
                modifiedFields.delete(fieldItem);

                // Correction : Toujours vérifier s'il reste des modifications à valider
                updateSubmitButton();
            }

            // Validation des champs
            function validateField(fieldName, value) {
                const validations = {
                    'nom': v => v === '' || v.length <= 50,
                    'prénom': v => v === '' || v.length <= 50,
                    'âge': v => v === '' || (/^\d+$/.test(v) && parseInt(v) > 0 && parseInt(v) < 120),
                    'email': v => v === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v),
                    'téléphone': v => v === '' || /^[\d\s+-]{10,15}$/.test(v),
                    'adresse': v => v === '' || v.length <= 255
                };

                if (!validations[fieldName](value)) {
                    alert(`Format invalide pour le champ ${fieldName}`);
                    return false;
                }
                return true;
            }

            // Conversion des noms de champs frontend -> backend
            function getFieldKey(fieldName) {
                const fieldMap = {
                    'nom': 'nom',
                    'prénom': 'prenom',
                    'âge': 'naissance',
                    'email': 'mail',
                    'téléphone': 'telephone',
                    'adresse': 'adresse'
                };
                return fieldMap[fieldName] || fieldName;
            }

            // Mise à jour du bouton de soumission
            function updateSubmitButton() {
                submitAllBtn.classList.toggle('hidden', modifiedFields.size === 0);
            }

            // Soumission finale des modifications (AJAX)
            submitAllBtn.addEventListener('click', async function() {
                if (modifiedFields.size === 0) return;

                const payload = {
                    user_id: <?= json_encode($user['id_user']) ?>
                };

                // Ajouter les champs modifiés
                modifiedFields.forEach((fieldData, fieldItem) => {
                    payload[fieldData.name] = fieldData.value;
                });

                try {
                    const response = await fetch('../scripts_php/update_profile.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        credentials: 'include', // Important pour la session
                        body: JSON.stringify(payload)
                    });

                    // Vérification du code HTTP AVANT de parser le JSON
                    if (response.status === 401) {
                        alert('Erreur: Vous devez être connecté pour modifier votre profil.');
                        return;
                    }

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Erreur lors de la sauvegarde');
                    }

                    // Mettre à jour les valeurs originales
                    modifiedFields.forEach((fieldData, fieldItem) => {
                        const inputEl = fieldItem.querySelector('.field-input');
                        inputEl.dataset.originalValue = inputEl.value;
                    });

                    modifiedFields.clear();
                    updateSubmitButton();
                    alert('Modifications sauvegardées avec succès!');
                } catch (error) {
                    console.error('Erreur:', error);
                    alert('Erreur: ' + error.message);
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showAllBtn = document.getElementById('show-all-btn');
            if (!showAllBtn) return;

            const voyagesContainer = document.getElementById('voyages-container');
            const allVoyages = <?= json_encode($historiqueVoyages) ?>;
            let showAll = false;

            showAllBtn.addEventListener('click', function() {
                showAll = !showAll;
                this.textContent = showAll ? 'Voir moins' : 'Voir tout';
                
                voyagesContainer.innerHTML = '';
                const voyagesToShow = showAll ? allVoyages : allVoyages.slice(0, 3);
                
                voyagesToShow.forEach(voyage => {
                    
                    const card = document.createElement('div');
                    card.className = 'activity-card';
                    card.innerHTML = `
                        <div class="activity-detail-card">
                            <div class="activity-header">
                                <i class="fas fa-suitcase"></i>
                                <div>
                                    <h3>Réservation #${voyage.id_reservation}</h3>
                                </div>
                            </div>
                            <ul class="activity-list">
                                <li><strong>Date:</strong> ${voyage.date_reservation}</li>
                                <li><strong>Prix total:</strong> ${voyage.prix_total} €</li>
                            </ul>
                            <a href="details_voyage.php?id=${voyage.id_reservation}" class="btn btn-base">Voir détails</a>
                        </div>
                    `;
                    voyagesContainer.appendChild(card);
                });
            });
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    // Éléments du formulaire
    const profilePictureInput = document.getElementById('profile-picture-input');
    const profilePicturePreview = document.getElementById('profile-picture-preview');
    const headerProfilePicture = document.getElementById('header-profile-picture'); // Nouvel élément
    const uploadForm = document.querySelector('.upload-form');
    const saveButton = document.querySelector('.btn-save');

    // Fonction pour mettre à jour toutes les images de profil
    function updateAllProfilePictures(newSrc) {
        // Mettre à jour l'image dans le formulaire
        if (profilePicturePreview) {
            profilePicturePreview.src = newSrc;
        }
        
        // Mettre à jour l'image dans l'en-tête si elle existe
        if (headerProfilePicture) {
            headerProfilePicture.src = newSrc;
        }
    }

    // Prévisualisation de la nouvelle image
    profilePictureInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                // Afficher la prévisualisation
                updateAllProfilePictures(event.target.result);
                saveButton.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Soumission du formulaire
    uploadForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Afficher un indicateur de chargement
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
        saveButton.disabled = true;
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Mise à jour des images avec cache-busting
                const newSrc = '../data/profile_picture/<?= $user['username'] ?>.jpg?' + Date.now();
                updateAllProfilePictures(newSrc);
                
                // Réinitialiser le bouton
                saveButton.innerHTML = 'Enregistrer';
                saveButton.classList.add('hidden');
                saveButton.disabled = false;
                
                // Message de succès
                showAlert('success', 'Photo mise à jour avec succès');
            } else {
                throw new Error(data.message || 'Erreur lors de la mise à jour');
            }
        } catch (error) {
            console.error('Erreur:', error);
            showAlert('error', error.message);
            
            // Réinitialiser le bouton en cas d'erreur
            saveButton.innerHTML = 'Enregistrer';
            saveButton.disabled = false;
        }
    });

    // Fonction pour afficher les messages d'alerte
    function showAlert(type, message) {
        // Supprimer les anciennes alertes
        const oldAlerts = uploadForm.querySelectorAll('.upload-alert');
        oldAlerts.forEach(alert => alert.remove());
        
        // Créer la nouvelle alerte
        const alertDiv = document.createElement('div');
        alertDiv.className = `upload-alert alert-${type}`;
        alertDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i> 
            ${message}
        `;
        
        // Styles de base pour les alertes
        const alertStyles = `
            .upload-alert {
                padding: 10px 15px;
                border-radius: 5px;
                margin: 10px 0;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .alert-success {
                background-color: #d4edda;
                color: #155724;
                border: 1px solid #c3e6cb;
            }
            .alert-error {
                background-color: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
        `;
        
        // Ajouter les styles si nécessaire
        if (!document.getElementById('alert-styles')) {
            const styleTag = document.createElement('style');
            styleTag.id = 'alert-styles';
            styleTag.textContent = alertStyles;
            document.head.appendChild(styleTag);
        }
        
        uploadForm.appendChild(alertDiv);
        
        // Supprimer après 5 secondes
        setTimeout(() => alertDiv.remove(), 5000);
    }
});
    </script>
</body>

</html>