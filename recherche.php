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
    <meta name="description" content="Recherchez vos destinations avec vos activités préférées.">

	<!-- Titre du navigateur -->
    <title>KYS - Recherche</title>

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
    <main class="search-container">
        <!-- Hero Section avec vidéo de fond -->
        <section class="recherche-hero">
            <div class="contenu-hero">
                <h1>Découvrez Votre Prochaine Aventure</h2>
                <p>Plus de 100 activités extrêmes encadrées par des professionnels</p>

                <!-- Barre de recherche principale -->
                <div class="search-box">
                    <div class="search-tabs">
                        <button class="tab-btn active">Toutes les Activités</button>
                        <button class="tab-btn">Sports Aériens</button>
                        <button class="tab-btn">Sports Nautiques</button>
                        <button class="tab-btn">Sports de Montagne</button>
                    </div>
                    <div class="search-form">
                        <div class="search-row">
                            <div class="search-group">
                                <i class="fas fa-search"></i>
                                <input type="text" placeholder="Quelle activité recherchez-vous ?">
                            </div>
                            <div class="search-group">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" placeholder="Où ?">
                            </div>
                            <div class="search-group">
                                <i class="fas fa-calendar"></i>
                                <input type="date" placeholder="Quand ?">
                            </div>
                            <button class="search-submit">Rechercher</button>
                        </div>
                    </div>
                </div>
			</div>
        </section>

        <!-- Filtres avancés -->
        <section class="advanced-filters">
            <div class="filter-container">
                <div class="filter-group">
                    <label>Niveau</label>
                    <div class="filter-options">
                        <button class="filter-option active">Tous</button>
                        <button class="filter-option">Débutant</button>
                        <button class="filter-option">Intermédiaire</button>
                        <button class="filter-option">Expert</button>
                    </div>
                </div>
                <div class="filter-group">
                    <label>Prix</label>
                    <div class="price-range">
                        <input type="range" min="0" max="1000" value="500">
                        <div class="price-values">
                            <span>0€</span>
                            <span>500€</span>
                        </div>
                    </div>
                </div>
                <div class="filter-group">
                    <label>Durée</label>
                    <div class="filter-options">
                        <button class="filter-option">½ Journée</button>
                        <button class="filter-option">1 Jour</button>
                        <button class="filter-option">2+ Jours</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Résultats de recherche -->
        <section class="search-results">
            <div class="results-header">
                <div class="results-info">
                    <h2>24 Activités trouvées</h2>
                    <p>dans les Alpes Françaises</p>
                </div>
                <div class="results-controls">
                    <div class="view-options">
                        <button class="view-btn active"><i class="fas fa-th-large"></i></button>
                        <button class="view-btn"><i class="fas fa-list"></i></button>
                    </div>
                    <select class="sort-select">
                        <option value="popular">Plus populaires</option>
                        <option value="price-low">Prix croissant</option>
                        <option value="price-high">Prix décroissant</option>
                        <option value="rating">Mieux notés</option>
                    </select>
                </div>
            </div>

            <div class="results-grid">
                <!-- Carte Activité 1 -->
                <div class="activity-card premium">
                    <div class="activity-image">
                        <img src="img/parachute.jpg" alt="Parachutisme">
                        <div class="activity-badges">
                            <span class="badge premium">Premium</span>
                            <span class="badge popular">Top Ventes</span>
                        </div>
                        <button class="favorite-btn"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="activity-content">
                        <div class="activity-category">Sport Aérien</div>
                        <h3>Saut en Parachute Tandem</h3>
                        <div class="activity-rating">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="rating-score">4.8</span>
                            <span class="rating-count">(256 avis)</span>
                        </div>
                        <div class="activity-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Annecy, Haute-Savoie</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span>3 heures</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-user-friends"></i>
                                <span>2-8 personnes</span>
                            </div>
                        </div>
                        <div class="activity-highlights">
                            <span class="highlight"><i class="fas fa-check"></i> Matériel inclus</span>
                            <span class="highlight"><i class="fas fa-check"></i> Vidéo souvenir</span>
                            <span class="highlight"><i class="fas fa-check"></i> Assurance incluse</span>
                        </div>
                        <div class="activity-footer">
                            <div class="price-block">
                                <span class="price">299€</span>
                                <span class="price-info">/personne</span>
                            </div>
                            <a href="details.html" class="btn btn-book">Réserver maintenant</a>
                        </div>
                    </div>
                </div>

                <!-- Carte Activité 2 -->
                <div class="activity-card">
                    <div class="activity-image">
                        <img src="img/plongee.jpg" alt="Plongée">
                        <div class="activity-badges">
                            <span class="badge new">Nouveau</span>
                        </div>
                        <button class="favorite-btn"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="activity-content">
                        <div class="activity-category">Sport Nautique</div>
                        <h3>Plongée Découverte</h3>
                        <div class="activity-rating">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="rating-score">4.0</span>
                            <span class="rating-count">(124 avis)</span>
                        </div>
                        <div class="activity-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Côte d'Azur</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span>4 heures</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-user-friends"></i>
                                <span>1-4 personnes</span>
                            </div>
                        </div>
                        <div class="activity-highlights">
                            <span class="highlight"><i class="fas fa-check"></i> Équipement fourni</span>
                            <span class="highlight"><i class="fas fa-check"></i> Moniteur certifié</span>
                        </div>
                        <div class="activity-footer">
                            <div class="price-block">
                                <span class="price">89€</span>
                                <span class="price-info">/personne</span>
                            </div>
                            <a href="details.html" class="btn btn-book">Réserver maintenant</a>
                        </div>
                    </div>
                </div>

                <!-- Ajouter plus de cartes d'activités similaires -->
            </div>

            <!-- Pagination -->
        </section>

        <!-- Section Newsletter -->
        <section class="newsletter-section">
            <div class="newsletter-content">
                <h2>Ne Manquez Aucune Aventure</h2>
                <p>Inscrivez-vous pour recevoir nos meilleures offres et nouveautés</p>
                <form class="newsletter-form">
                    <button type="submit">S'inscrire</button>
                </form>
            </div>
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
