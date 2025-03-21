<?php
session_start();
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
    <link rel="stylesheet" type="text/css" href="style.css">

	<!-- Ajout des icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!-- Haut de page -->

    <!-- Navigation -->
    <nav>
        <!-- Logo et nom (gauche)-->
        <div class="nav-left">
            <a href="accueil.php" class="nav-brand">
                <img src="img/logo.png" alt="Logo">
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

        <!-- Nos services -->
        <section class="services">
            <h2>Nos Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <img src="img/parachute.jpg" alt="Parachutisme">
                    <h3>Parachutisme</h3>
                    <p>Découvrez la liberté absolue en chute libre avec nos instructeurs certifiés.</p>
                </div>
                <div class="service-card">
                    <img src="img/escalade.jpg" alt="Escalade">
                    <h3>Escalade</h3>
                    <p>Repoussez vos limites sur les plus belles parois naturelles.</p>
                </div>
                <div class="service-card">
                    <img src="img/plongee.jpg" alt="Plongée">
                    <h3>Plongée</h3>
                    <p>Explorez les profondeurs marines en toute sécurité.</p>
                </div>
            </div>
        </section>

        <!-- Destinations Populaires -->
        <section class="destinations">
            <h2>Nos Destinations d'Exception</h2>
            <div class="destinations-slider">
                <div class="destination-card">
                    <img src="img/alpes.jpg" alt="Les Alpes">
                    <div class="destination-info">
                        <h3>Les Alpes Françaises</h3>
                        <p>Escalade, ski extrême et parapente</p>
                        <span class="destination-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </span>
                    </div>
                </div>
                <div class="destination-card">
                    <img src="img/corse.jpg" alt="Corse">
                    <div class="destination-info">
                        <h3>Les Calanques Corses</h3>
                        <p>Plongée, canyoning et escalade</p>
                        <span class="destination-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half"></i>
                        </span>
                    </div>
                </div>
                <div class="destination-card">
                    <img src="img/pyrenees.jpg" alt="Pyrénées">
                    <div class="destination-info">
                        <h3>Pyrénées Sauvages</h3>
                        <p>Randonnée extrême et alpinisme</p>
                        <span class="destination-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Activités Détaillées -->
        <section class="activities-detail">
            <h2>Nos Activités en Détail</h2>
            <div class="activities-grid">
                <div class="activity-detail-card">
                    <div class="activity-header">
                        <i class="fas fa-parachute-box"></i>
                        <h3>Sports Aériens</h3>
                    </div>
                    <ul class="activity-list">
                        <li>Parachutisme (à partir de 3000m)</li>
                        <li>Parapente biplace</li>
                        <li>Wingsuit</li>
                        <li>Saut en tandem</li>
                        <li>Formation PAC</li>
                    </ul>
                    <a href="recherche.php?category=aerien" class="activity-link">Découvrir</a>
                </div>
                <div class="activity-detail-card">
                    <div class="activity-header">
                        <i class="fas fa-mountain"></i>
                        <h3>Sports de Montagne</h3>
                    </div>
                    <ul class="activity-list">
                        <li>Escalade haute montagne</li>
                        <li>Alpinisme</li>
                        <li>Via ferrata</li>
                        <li>Ski hors-piste</li>
                        <li>Cascade de glace</li>
                    </ul>
                    <a href="recherche.php?category=montagne" class="activity-link">Découvrir</a>
                </div>
                <div class="activity-detail-card">
                    <div class="activity-header">
                        <i class="fas fa-water"></i>
                        <h3>Sports Aquatiques</h3>
                    </div>
                    <ul class="activity-list">
                        <li>Plongée profonde</li>
                        <li>Canyoning</li>
                        <li>Rafting extrême</li>
                        <li>Hydrospeed</li>
                        <li>Kitesurf</li>
                    </ul>
                    <a href="recherche.php?category=aquatique" class="activity-link">Découvrir</a>
                </div>
            </div>
        </section>

        <!-- Témoignages -->
        <section class="testimonials">
            <h2>Ce que disent nos Aventuriers</h2>
            <div class="testimonials-carousel">
                <div class="testimonial-card">
                    <img src="img/testimonial1.jpg" alt="Sophie" class="testimonial-img">
                    <blockquote>
                        "Une expérience incroyable ! L'équipe de KYS a su me mettre en confiance pour mon premier saut en parachute. Je recommande à 100% !"
                    </blockquote>
                    <p class="testimonial-author">Sophie D. - Parachutisme</p>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="testimonial-card">
                    <img src="img/testimonial2.jpg" alt="Marc" class="testimonial-img">
                    <blockquote>
                        "Guides professionnels et passionnés. Une ascension du Mont Blanc inoubliable en toute sécurité."
                    </blockquote>
                    <p class="testimonial-author">Marc L. - Alpinisme</p>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
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
                    <span class="stat-number">10,000+</span>
                    <p>Aventuriers Satisfaits</p>
                </div>
                <div class="stat-item">
                    <i class="fas fa-map-marked-alt"></i>
                    <span class="stat-number">50+</span>
                    <p>Sites d'Exception</p>
                </div>
                <div class="stat-item">
                    <i class="fas fa-certificate"></i>
                    <span class="stat-number">100%</span>
                    <p>Sécurité Assurée</p>
                </div>
                <div class="stat-item">
                    <i class="fas fa-award"></i>
                    <span class="stat-number">15+</span>
                    <p>Années d'Expérience</p>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="faq">
            <h2>Questions Fréquentes</h2>
            <div class="faq-grid">
                <div class="faq-item">
                    <h3><i class="fas fa-question-circle"></i> Faut-il être sportif ?</h3>
                    <p>Nos activités sont adaptées à tous les niveaux. Une bonne condition physique est recommandée pour certaines activités, mais nous proposons des alternatives pour chaque profil.</p>
                </div>
                <div class="faq-item">
                    <h3><i class="fas fa-question-circle"></i> Les activités sont-elles assurées ?</h3>
                    <p>Oui, toutes nos activités sont couvertes par une assurance complète. Nous travaillons avec les meilleures compagnies d'assurance spécialisées.</p>
                </div>
                <div class="faq-item">
                    <h3><i class="fas fa-question-circle"></i> Quel équipement fournissez-vous ?</h3>
                    <p>Tout l'équipement technique et de sécurité est fourni. Une liste détaillée des effets personnels à prévoir vous sera communiquée avant chaque activité.</p>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta">
            <h2>Prêt à Vivre l'Aventure ?</h2>
            <p>Rejoignez-nous pour une expérience qui changera votre vie</p>
            <a href="recherche.php" class="cta-button">Découvrir nos Activités</a>
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

</body>
</html>
