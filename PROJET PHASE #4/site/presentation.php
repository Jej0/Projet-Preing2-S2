<?php
session_start();

// Lire le fichier JSON des voyages
$voyages = json_decode(file_get_contents('../data/voyages.json'), true);

$users = json_decode(file_get_contents('../data/users.json'), true);
// Trier les voyages par note moyenne (du plus élevé au plus bas)
usort($voyages, function ($a, $b) {
    return $b['note_moyenne'] <=> $a['note_moyenne'];
});

// Sélectionner les 3 meilleurs voyages
$meilleursVoyages = array_slice($voyages, 0, 3);

// Compter le nombre d'utilisateurs inscrits
$nombreInscrits = count($users);

// Compter le nombre de voyages disponibles
$nombreVoyages = count($voyages);
?>

<!DOCTYPE html>

<html lang="fr">

<head>
    <!--Information de la page web-->
    <meta charset="UTF-8">

    <!-- Optimisation pour le telephone -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">

    <!-- Titre du site -->
    <meta name="title" content="Keep Yourself Safe">

    <!-- Nom de l'agence et auteur du site -->
    <meta name="author" content="Keep Yourself Safe | Alex MIKOLAJEWSKI | Axel ATAGAN | Naïm LACHGAR-BOUACHRA">

    <!-- Description du site -->
    <meta name="description" content="Keep Yourself Safe vous offre des aventures extrêmes et inoubliables en toute sécurité. Nos experts vous trouvent les meilleurs plans et activités pour vous faire sentir vivant. Planifiez dès maintenant !">

    <!-- Titre du navigateur -->
    <title>KYS - Présentation</title>

    <!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <!-- Ajout des icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Très bien mais comment ça marche ? -->
    <script src="assets/js/sombre.js"></script>
</head>

<body>
    <!-- Pop-up de bienvenue -->
    <div id="welcomePopup" class="popup-container">
        <div class="popup-content">
            <h3>Bienvenue sur notre site !</h3>
            <p>Ce site propose des voyages avec des activités extrêmes. Il est donc réservé aux personnes majeures.</p>
            <div class="popup-buttons">
                <button id="acceptBtn" class="btn button btn-base">J'ai +18 ans</button>
                <button id="rejectBtn" class="btn button btn-base">Quitter le site</button>
            </div>
        </div>
    </div>

    <!-- Flou d'arrière-plan -->
    <div id="backgroundBlur" class="background-blur"></div>

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

    <!-- Contenu-->
    <main class="presentation-container">
        <!-- Section héro -->
        <section class="presentation-hero">
            <div class="contenu-hero">
                <h1>Vivez l'Aventure en Toute Sécurité</h1>
                <p>Des expériences extrêmes inoubliables encadrées par des professionnels</p>
            </div>
        </section>

        <!-- Fondateur -->
        <section class="fondateur">
            <h2>Les Fondateur</h2>
            <div class="fondateur-grid">
                <div class="fondateur-card">
                    <img src="assets/img/fondateur1.jpg" alt="image fondateur">
                    <h3>Alex MIKOLAJEWSKI</h3>
                </div>
                <div class="fondateur-card">
                    <img src="assets/img/testimonial2.jpg" alt="image fondateur">
                    <h3>Axel ATAGAN</h3>
                </div>
                <div class="fondateur-card">
                    <img src="assets/img/testimonial1.jpg" alt="image fondateur">
                    <h3>Naïm LACHGAR-BOUACHRA</h3>
                </div>
            </div>
        </section>

        <!-- Section Destinations Populaires -->
        <section class="fondateur">
            <h2>Nos Meilleures Destinations</h2>
            <div class="fondateur-grid">
                <?php foreach ($meilleursVoyages as $voyage): ?>
                    <div class="destination-card">
                        <img src="assets/img/<?php echo $voyage['id_voyage']; ?>.jpg" alt="<?php echo $voyage['titre']; ?>">
                        <div class="destination-info">
                            <h3><?php echo $voyage['titre']; ?></h3>
                            <p><?php echo $voyage['description']; ?></p>
                            <p class="price">À partir de <?php echo $voyage['prix_total']; ?>€</p>

                            <div class="rating-container">
                                <div class="rating">
                                    <?php
                                    $note = $voyage['note_moyenne'];
                                    $etoilesPleines = floor($note);
                                    $etoilesDemi = ($note - $etoilesPleines) >= 0.5 ? 1 : 0;

                                    // Étoiles pleines
                                    for ($i = 0; $i < $etoilesPleines; $i++) {
                                        echo '<i class="fas fa-star"></i>';
                                    }
                                    // Demi-étoile
                                    if ($etoilesDemi) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    }
                                    // Étoiles vides (calcul corrigé)
                                    $etoilesVides = 5 - $etoilesPleines - $etoilesDemi;
                                    for ($i = 0; $i < $etoilesVides; $i++) {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </div>
                                <span class="nb-avis"><?php echo number_format($note, 1); ?> (<?php echo $voyage['nb_avis']; ?> avis)</span>
                                <span class="duration"><i class="far fa-clock"></i> <?php echo $voyage['dates']['duree']; ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Témoignages -->
        <section class="temoignage">
            <h2>Ce que disent nos Aventuriers</h2>
            <div class="fondateur-grid">
                <div class="temoignage-card">
                    <div class="client-info">
                        <img src="assets/img/avis1.jpg" alt="Inoxtag" class="client-avatar">
                        <div class="client-details">
                            <strong class="client-name">Inoxtag</strong>
                            <span class="client-expedition">Ascension de l'Everest</span>
                        </div>
                    </div>
                    <p>"J'ai atteint le Kaizen ! L'équipe KYS a su m'accompagner et m'a permis de gravir l'Everest. Je recommande à 100 % !"</p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="temoignage-card">
                    <div class="client-info">
                        <img src="assets/img/avis2.jpg" alt="Kim Jong-un" class="client-avatar">
                        <div class="client-details">
                            <strong class="client-name">Kim Jong-un</strong>
                            <span class="client-expedition">Rafting extrême dans les gorges du Verdon</span>
                        </div>
                    </div>
                    <p>"Vous pensiez m'avoir eu, mais je suis encore en vie ! 10/10, expérience inoubliable. 🔥💯"</p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <!-- Statistiques -->
        <section class="stats">
            <h2>KYS en Chiffres</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <i class="fas fa-users"></i>
                    <span class="stat-number"><?php echo $nombreInscrits; ?></span>
                    <p>Aventuriers Satisfaits</p>
                </div>
                <div class="stat-item">
                    <i class="fas fa-map-marked-alt"></i>
                    <span class="stat-number"><?php echo $nombreVoyages; ?></span>
                    <p>Sites d'Exception</p>
                </div>
                <div class="stat-item">
                    <i class="fas fa-certificate"></i>
                    <span class="stat-number">100%</span>
                    <p>Sécurité Assurée</p>
                </div>
                <div class="stat-item">
                    <i class="fas fa-award"></i>
                    <span class="stat-number">20+</span>
                    <p>Années d'Expérience</p>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="faq">
            <h2>Questions Fréquentes</h2>
            <div class="fondateur-grid">
                <div class="faq-card">
                    <h3><i class="fas fa-question-circle"></i> Faut-il être sportif ?</h3>
                    <p>Nos activités sont adaptées à tous les niveaux. Une bonne condition physique est recommandée pour certaines activités, mais nous proposons des alternatives pour chaque profil.</p>
                </div>
                <div class="faq-card">
                    <h3><i class="fas fa-question-circle"></i> Les activités sont-elles assurées ?</h3>
                    <p>Oui, toutes nos activités sont couvertes par une assurance complète. Nous travaillons avec les meilleures compagnies d'assurance spécialisées.</p>
                </div>
                <div class="faq-card">
                    <h3><i class="fas fa-question-circle"></i> Quel équipement fournissez-vous ?</h3>
                    <p>Tout l'équipement technique et de sécurité est fourni. Une liste détaillée des effets personnels à prévoir vous sera communiquée avant chaque activité.</p>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="cta">
            <h2>Prêt à Vivre l'Aventure ?</h2>
            <p>Rejoignez-nous pour une expérience qui changera votre vie</p> <br>
            <a href="recherche.php" class="btn btn-base">Découvrir nos Activités</a>
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
    <script src="assets/js/popup.js"></script>
</body>

</html>