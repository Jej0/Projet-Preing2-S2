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