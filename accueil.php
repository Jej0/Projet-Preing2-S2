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

	<!-- Référencement -->
	  <meta name="keywords" content="vacances, sport, extrême, adrénaline, destinations, aventure, sensations fortes, séjour">

	<!-- Titre du navigateur -->
    <title>KYS - Accueil</title>

	<!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">

  <!-- Liens vers icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Très bien mais comment ça marche ? -->
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

	<!-- Section Hero Principale -->
	<section class="accueil-hero">
		<div class="contenu-hero">
			<h1>Découvrez l'Aventure en Toute Sécurité</h1>
			<p>Des expériences uniques encadrées par des professionnels passionnés</p>
			<div class="hero-buttons">
				<a href="recherche.php" class="btn btn-base">Explorer nos Activités</a>
				<a href="presentation.php" class="btn btn-transparent">En savoir plus</a>
			</div>
		</div>
	</section>

	<!-- Contenu-->
	<main>
		<!-- Section Actualités -->
		<section class="news-banner">
			<div class="news-slider">
				<div class="news-item">
					<i class="fas fa-bolt"></i>
					<p>Nouvelle activité : Wingsuit dans les Alpes !</p>
				</div>
				<div class="news-item">
					<i class="fas fa-percentage"></i>
					<p>-20% sur les activités en groupe ce mois-ci</p>
				</div>
				<div class="news-item">
					<i class="fas fa-calendar"></i>
					<p>Stages d'été : Inscriptions ouvertes</p>
				</div>
			</div>
		</section>

		<!-- Section Activités Vedettes -->
		<section id="featured" class="featured-activities">
			<h2>Activités du Moment</h2>
			<div class="featured-grid">
				<div class="featured-card large">
					<img src="img/parachute.jpg" alt="Parachutisme">
					<div class="card-content">
						<h3>Saut en Parachute</h3>
						<p>Vivez l'expérience ultime de liberté à 4000m d'altitude</p>
						<div class="card-info">
							<span class="price">À partir de 299€</span>
							<span class="duration"><i class="far fa-clock"></i> 3h</span>
						</div>
						<a href="details.html" class="btn btn-card">Réserver</a>
					</div>
				</div>
				<div class="featured-card">
					<img src="img/plongee.jpg" alt="Plongée">
					<div class="card-content">
						<h3>Plongée Profonde</h3>
						<p>Explorez les fonds marins méditerranéens</p>
						<div class="card-info">
							<span class="price">À partir de 149€</span>
							<span class="duration"><i class="far fa-clock"></i> 4h</span>
						</div>
						<a href="details.html" class="btn btn-card">Réserver</a>
					</div>
				</div>
				<div class="featured-card">
					<img src="img/escalade.jpg" alt="Escalade">
					<div class="card-content">
						<h3>Escalade Alpine</h3>
						<p>Défiez les plus beaux sommets des Alpes</p>
						<div class="card-info">
							<span class="price">À partir de 199€</span>
							<span class="duration"><i class="far fa-clock"></i> 6h</span>
						</div>
						<a href="details.html" class="btn btn-card">Réserver</a>
					</div>
				</div>
			</div>
		</section>

		<!-- Section Avantages -->
		<section class="benefits">
			<h2>Pourquoi Choisir KYS ?</h2>
			<div class="benefits-grid">
				<div class="benefit-item">
					<i class="fas fa-shield-alt"></i>
					<h3>Sécurité Garantie</h3>
					<p>Équipements certifiés et guides expérimentés</p>
				</div>
				<div class="benefit-item">
					<i class="fas fa-medal"></i>
					<h3>Expertise</h3>
					<p>Plus de 15 ans d'expérience</p>
				</div>
				<div class="benefit-item">
					<i class="fas fa-hand-holding-heart"></i>
					<h3>Accompagnement</h3>
					<p>Support personnalisé avant, pendant et après</p>
				</div>
				<div class="benefit-item">
					<i class="fas fa-euro-sign"></i>
					<h3>Prix Compétitifs</h3>
					<p>Meilleur rapport qualité-prix garanti</p>
				</div>
			</div>
		</section>

		<!-- Section Promo -->
		<section class="promo-section">
			<div class="promo-content">
				<h2>Offre Spéciale du Moment</h2>
				<div class="promo-timer">
					<p>Fin de l'offre dans :</p>
					<div class="countdown">
						<span id="days">02</span>j
						<span id="hours">18</span>h
						<span id="minutes">45</span>m
						<span id="seconds">30</span>s
					</div>
				</div>
				<p class="promo-description">Pack Découverte : 3 activités au choix + Photos/Vidéos</p>
				<div class="promo-price">
					<span class="old-price">899€</span>
					<span class="new-price">699€</span>
				</div>
			</div>
		</section>

		<!-- Section Newsletter -->
		<section class="newsletter">
			<div class="newsletter-content">
				<h2>Restez Informé</h2>
				<p>Recevez nos meilleures offres et actualités</p>
				<form class="newsletter-form">
					<input type="email" placeholder="Votre email" required>
					<button type="submit" class="btn btn-base">S'inscrire</button>
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
