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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                    <img src="assets/img/testimonial2.jpg" alt="Photo de profil">
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
                <li><a class="btn" href="#activites">Activités</a></li>
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
                <button id="submit-all-changes" class="btn hidden"><i class="fas fa-save"></i> Soumettre les modifications</button>
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
                                    <button class="btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-field-btn"><i class="fas fa-edit"></i></button>
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
                                    <button class="btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-field-btn"><i class="fas fa-edit"></i></button>
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
                                    <button class="btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-field-btn"><i class="fas fa-edit"></i></button>
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
                                    <button class="btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-field-btn"><i class="fas fa-edit"></i></button>
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
                                    <button class="btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-field-btn"><i class="fas fa-edit"></i></button>
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
                                    <button class="btn save-btn"><i class="fas fa-check"></i></button>
                                    <button class="btn cancel-btn"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                            <button class="btn edit-field-btn"><i class="fas fa-edit"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </section>



        <section id="activites" class="profile-section">
            <div class="section-header">
                <h2>Mon Historique de Voyage</h2>
                <a href="recherche.php" class="btn btn-base">Découvrir plus</a>
            </div>
            <div class="activities-grid">
                ??????
            </div>
        </section>

        <section id="reservations" class="profile-section">
            <div class="section-header">
                <h2>Mes Réservations</h2>
            </div>
            <div class="reservations-list">
                ?????
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

            // Soumission finale des modifications
            submitAllBtn.addEventListener('click', async function() {
                if (modifiedFields.size === 0) return;

                const formData = new FormData();
                formData.append('user_id', <?= $user['id_user'] ?>);

                // Ajouter les champs modifiés
                modifiedFields.forEach((fieldData, fieldItem) => {
                    formData.append(fieldData.name, fieldData.value);
                });

                try {
                    const response = await fetch('../scripts_php/update_profile.php', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Erreur serveur');
                    }

                    if (data.success) {
                        // Mettre à jour les valeurs originales
                        modifiedFields.forEach((fieldData, fieldItem) => {
                            const inputEl = fieldItem.querySelector('.field-input');
                            inputEl.dataset.originalValue = inputEl.value;
                        });

                        modifiedFields.clear();
                        updateSubmitButton();
                        alert('Modifications sauvegardées avec succès!');
                    } else {
                        throw new Error(data.message || 'Erreur lors de la sauvegarde');
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    alert('Erreur: ' + error.message);
                }
            });
        });
    </script>
</body>

</html>