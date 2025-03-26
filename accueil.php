<?php
session_start();

// Inclusion des fichiers de configuration et des classes nécessaires
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Utilisation de la connexion à la base de données via la classe Database
$db = Database::getInstance();
$pdo = $db->getConnection();

// Récupérer les voyages depuis la table 'voyages'
$stmt = $pdo->query("SELECT * FROM voyages");
$voyages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les utilisateurs depuis la table 'users'
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compter le nombre d'utilisateurs inscrits
$nombreInscrits = count($users);

// Compter le nombre de voyages disponibles
$nombreVoyages = count($voyages);

// Récupérer les 3 voyages les mieux notés (note_moyenne décroissante)
$query = "SELECT * FROM voyages ORDER BY note_moyenne DESC LIMIT 3";
$stmt = $pdo->query($query);
$meilleursVoyages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex, nofollow">
	<meta name="title" content="Keep Yourself Safe">
	<meta name="author" content="Keep Yourself Safe | Alex MIKOLAJEWSKI | Axel ATAGAN | Naïm LACHGAR-BOUACHRA">
	<meta name="description" content="Keep Yourself Safe vous offre des aventures extrêmes et inoubliables en toute sécurité. Nos experts vous trouvent les meilleurs plans et activités pour vous faire sentir vivant. Planifiez dès maintenant !">
	<meta name="keywords" content="vacances, sport, extrême, adrénaline, destinations, aventure, sensations fortes, séjour">
	<title>KYS - Accueil</title>
	<link rel="stylesheet" type="text/css" href="style.css">
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
			<?php if (!isset($_SESSION['user'])): ?>
				<a href="connexion.php" class="btn nav-btn">Se connecter</a>
				<a href="inscription.php" class="btn nav-btn">S'inscrire</a>
			<?php else: ?>
				<a href="scripts_php/logout.php" class="btn nav-btn">Déconnexion</a>
				<a href="profile.php" class="profile-icon">
					<i class="fas fa-user-circle"></i>
				</a>
			<?php endif; ?>
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

	<!-- Contenu -->
	<main>
		<!-- Section Actualités -->
		<section class="news-banner">
			<div class="news-slider">
				<div class="news-item">
					<i class="fas fa-star"></i>
					<p>Meilleur voyage : Ascension de l'Everest !</p>
				</div>
				<div class="news-item">
					<i class="fas fa-globe"></i>
					<p>Découvrez nos <strong><?php echo $nombreVoyages; ?></strong> voyages disponibles !</p>
				</div>
				<div class="news-item">
					<i class="fas fa-users"></i>
					<p>Déjà <strong><?php echo $nombreInscrits; ?></strong> aventuriers inscrits !</p>
				</div>
			</div>
		</section>

		<!-- Section Destinations Populaires -->
		<section class="fondateur">
			<h2>Nos Meilleures Destinations</h2>
			<div class="fondateur-grid">
				<?php foreach ($meilleursVoyages as $voyage): ?>
					<div class="destination-card">
						<img src="img/<?php echo htmlspecialchars($voyage['id_voyage']); ?>.jpg" alt="<?php echo htmlspecialchars($voyage['titre']); ?>">
						<div class="destination-info">
							<h3><?php echo htmlspecialchars($voyage['titre']); ?></h3>
							<p><?php echo htmlspecialchars($voyage['description']); ?></p>
							<p class="price">À partir de <?php echo htmlspecialchars($voyage['prix_total']); ?>€</p>
							<div class="rating-container">
								<div class="rating">
									<?php
									$note = $voyage['note_moyenne'];
									$etoilesPleines = floor($note);
									$etoilesDemi = ($note - $etoilesPleines) >= 0.5 ? 1 : 0;
									// Afficher les étoiles pleines
									for ($i = 0; $i < $etoilesPleines; $i++) {
										echo '<i class="fas fa-star"></i>';
									}
									// Afficher une demi-étoile si nécessaire
									if ($etoilesDemi) {
										echo '<i class="fas fa-star-half-alt"></i>';
									}
									// Afficher les étoiles vides
									$etoilesVides = 5 - $etoilesPleines - $etoilesDemi;
									for ($i = 0; $i < $etoilesVides; $i++) {
										echo '<i class="far fa-star"></i>';
									}
									?>
								</div>
								<span class="nb-avis"><?php echo number_format($note, 1); ?> (<?php echo htmlspecialchars($voyage['nb_avis']); ?> avis)</span>
								<span class="duration"><i class="far fa-clock"></i> <?php echo htmlspecialchars($voyage['duree']); ?></span>
							</div>
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
			<h2>Restez Informé</h2>
			<p>Recevez nos meilleures offres et actualités</p>
			<form class="newsletter-form">
				<input type="email" placeholder="Votre email" required>
				<button type="submit" class="btn btn-base">S'inscrire</button>
			</form>
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