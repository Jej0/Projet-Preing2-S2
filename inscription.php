<?php
session_start();
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Redirection si déjà connecté
if (isset($_SESSION['user'])) {
    header('Location: accueil.php');
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user = new User();
        
        // Debug pour voir les données reçues
        error_log("Données reçues : " . print_r($_POST, true));
        
        $userId = $user->register([
            'login' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'firstname' => $_POST['firstname'],
            'lastname' => $_POST['lastname'],
            'role' => 'user'  // Par défaut
        ]);

        if ($userId) {
            // Connexion automatique
            $user->login($_POST['username'], $_POST['password']);
            header('Location: accueil.php');
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log("Erreur d'inscription : " . $e->getMessage());
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
    <meta name="description" content="Inscrivez-vous pour sauvegarder et planifier votre voyage.">

	<!-- Titre du navigateur -->
    <title>KYS - Inscription</title>

	<!-- Lien vers le fichier CSS -->
    <link rel="stylesheet" type="text/css" href="style.css">

	<!-- Ajout des icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
	<!-- Haut de page -->

    <!-- Navigation -->
    <?php include('includes/nav.php'); ?>

	<!-- Contenu-->
	<main>
		<!-- Formulaire d'inscription-->
		<div class="form-container">
      		<img src="img/mountain-logo.png" alt="Logo Montagne" class="mountain-logo">
			<h1>Inscription</h1>

			<?php if (isset($error)): ?>
				<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
			<?php endif; ?>

			<form action="" method="POST" onsubmit="return validateForm()">
				<div class="form-group">
					<label for="firstname">Prénom :</label>
					<input type="text" id="firstname" name="firstname" required>
				</div>

				<div class="form-group">
					<label for="lastname">Nom :</label>
					<input type="text" id="lastname" name="lastname" required>
				</div>

				<div class="form-group">
					<label for="email">Email :</label>
					<input type="email" id="email" name="email" required>
				</div>

				<div class="form-group">
					<label for="username">Nom d'utilisateur :</label>
					<input type="text" id="username" name="username" required>
				</div>

				<div class="form-group">
					<label for="password">Mot de passe :</label>
					<input type="password" id="password" name="password" required>
				</div>

				<div class="form-group">
					<label for="confirm-password">Confirmer le mot de passe :</label>
					<input type="password" id="confirm-password" name="confirm-password" required>
				</div>

				<button type="submit" class="btn-submit">S'inscrire</button>
			</form>
		</div>
	</main>

	<!-- Pas de footer, sinon on peut scroll -->

	<script>
	function validateForm() {
		const password = document.getElementById('password').value;
		const confirmPassword = document.getElementById('confirm-password').value;
		const username = document.getElementById('username').value;
		const email = document.getElementById('email').value;
		
		// Validation du mot de passe
		if (password !== confirmPassword) {
			alert('Les mots de passe ne correspondent pas');
			return false;
		}
		
		if (password.length < 8) {
			alert('Le mot de passe doit contenir au moins 8 caractères');
			return false;
		}

		// Validation du nom d'utilisateur
		if (username.length < 3) {
			alert('Le nom d\'utilisateur doit contenir au moins 3 caractères');
			return false;
		}

		// Validation basique de l'email
		if (!email.includes('@')) {
			alert('Veuillez entrer une adresse email valide');
			return false;
		}
		
		return true;
	}
	</script>
</body>
</html>
