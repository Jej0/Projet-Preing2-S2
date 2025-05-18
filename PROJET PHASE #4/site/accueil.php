<?php
session_start();

// Lire le fichier JSON des voyages
$voyages = json_decode(file_get_contents('../data/voyages.json'), true);

// Lire le fichier JSON des utilisateurs
$users = json_decode(file_get_contents('../data/users.json'), true);

// Lire le fichier JSON des email
$newsletter = json_decode(file_get_contents('../data/newsletter.json'), true);

// Compter le nombre d'utilisateurs inscrits
$nombreInscrits = count($users);

// Compter le nombre de voyages disponibles
$nombreVoyages = count($voyages);

// Trouver le meilleur utilisateur (celui avec le plus de voyages dans son historique)
$meilleurUtilisateur = null;
$maxVoyages = 0;

foreach ($users as $user) {
	$nombreVoyagesHistorique = count($user['id_historique_voyages']);
	if ($nombreVoyagesHistorique > $maxVoyages) {
		$maxVoyages = $nombreVoyagesHistorique;
		$meilleurUtilisateur = $user;
	}
}
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
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">

	<!-- Liens vers icônes Font Awesome -->
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
				<!-- Meilleur voyage à l'Everest -->
				<div class="news-item">
					<i class="fas fa-star"></i>
					<p>Meilleur voyage : Ascension de l'Everest !</p>
				</div>
				<!-- Meilleur utilisateur -->
				<?php if ($meilleurUtilisateur): ?>
					<div class="news-item">
						<i class="fas fa-trophy"></i>
						<p>Meilleur aventurier : <strong><?php echo $meilleurUtilisateur['username']; ?></strong> avec <strong><?php echo $maxVoyages; ?></strong> voyages !</p>
					</div>
				<?php endif; ?>
				<!-- Nombre de voyages disponibles -->
				<div class="news-item">
					<i class="fas fa-globe"></i>
					<p>Découvrez nos <strong><?php echo $nombreVoyages; ?></strong> voyages disponibles !</p>
				</div>
				<!-- Nombre d'utilisateurs inscrits -->
				<div class="news-item">
					<i class="fas fa-users"></i>
					<p>Déjà <strong><?php echo $nombreInscrits; ?></strong> aventuriers inscrits !</p>
				</div>
			</div>
		</section>

		<!-- Section Activités Vedettes -->
		<section class="featured-activities">
			<h2>Activités du Moment</h2>
			<div class="featured-grid">
				<?php
				shuffle($voyages);
				$random_voyages = array_slice($voyages, 0, 4);

				foreach ($random_voyages as $voyage): ?>
					<div class="featured-card">
						<img src="assets/img/<?php echo $voyage['id_voyage']; ?>.jpg" alt="<?php echo $voyage['titre']; ?>">
						<div class="card-content">
							<h3><?php echo $voyage['titre']; ?></h3>
							<p><?php echo $voyage['description']; ?></p>
							<div class="card-info">
								<span class="price">À partir de <?php echo $voyage['prix_total']; ?>€</span>
								<span class="duration"><i class="far fa-clock"></i> <?php echo $voyage['dates']['duree']; ?></span>
							</div>
							<a href="details.php?id=<?php echo $voyage['id_voyage']; ?>" class="btn btn-card">Réserver</a>
						</div>
					</div>
				<?php endforeach; ?>
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
					<p>Plus de 20 ans d'expérience</p>
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
			<div class="promo-badge">OFFRE LIMITÉE</div>
			<h2 class="rating">Cadeau Mystère</h2>

			<div id="timeErreur" class="hidden">
				<h3>Mince ! Votre cadeau s'est enfui.</h3>
				<p>Merci de patienter pendant que nous essayons de rattraper votre cadeau:</p>
			</div>

			<p id="debloquageText">Votre cadeau sera débloqué dans :</p>

			<div class="countdown">
				<div class="countdown-segment">
					<span id="days">02</span>
					jours
				</div>
				<div class="countdown-segment">
					<span id="hours">18</span>
					heures
				</div>
				<div class="countdown-segment">
					<span id="minutes">45</span>
					minutes
				</div>
				<div class="countdown-segment">
					<span id="seconds">30</span>
					secondes
				</div>
			</div>

			<div class="gift-icon">
				<i class="fas fa-gift"></i>
			</div>
			<p class="gift-hint">Restez connecté pour découvrir votre surprise !</p>
		</section>

		<!-- Section Newsletter -->
		<section class="newsletter">
			<div id="newsletter">
				<h2>Restez Informé</h2>
				<p>Recevez nos meilleures offres et actualités</p>

				<form id="simpleNewsletterForm" class="newsletter-form">
					<input type="email" id="simpleEmailInput" placeholder="Votre email" required>
					<button type="submit" class="btn button btn-base">S'inscrire</button>
				</form>
				<p id="simpleMessage"></p>
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
	<script src="assets/js/accueil.js"></script>
	<script src="assets/js/popup.js"></script>
</body>

</html>