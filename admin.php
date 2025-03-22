<?php
// Démarrer la session pour vérifier si l'utilisateur est connecté et admin
session_start();


// Pagination
$usersPerPage = 5; // Nombre d'utilisateurs par page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // S'assurer que la page est toujours >= 1

// Charger les utilisateurs depuis le fichier JSON
$usersJson = file_get_contents('scripts_php/users.json');
$users = json_decode($usersJson, true);

// Recherche d'utilisateurs
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
if (!empty($searchTerm)) {
    $filteredUsers = array_filter($users, function($user) use ($searchTerm) {
        return (stripos($user['username'], $searchTerm) !== false || 
                stripos($user['email'], $searchTerm) !== false);
    });
    $users = $filteredUsers;
}

// Calculer le nombre total de pages
$totalUsers = count($users);
$totalPages = ceil($totalUsers / $usersPerPage);

// S'assurer que la page demandée existe
$page = min($page, max(1, $totalPages));

// Obtenir les utilisateurs pour la page actuelle
$startIndex = ($page - 1) * $usersPerPage;
$currentPageUsers = array_slice($users, $startIndex, $usersPerPage);
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
    <meta name="description" content="⚠️Page Administrateur du site Keep Yourself Safe.⚠️">

    <!-- Titre du navigateur -->
    <title>KYS - Admin</title>

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
    <main class="admin-container">
        <!-- En-tête Admin -->
        <div class="admin-header">
            <h1>Tableau de bord Administrateur</h1>
        </div>

        <!-- Statistiques -->
        <div class="admin-stats-grid">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3><?php echo $totalUsers; ?></h3>
                <p>Utilisateurs inscrits</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-hiking"></i>
                <h3>89</h3>
                <p>Activités en ligne</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-euro-sign"></i>
                <h3>15,230€</h3>
                <p>CA ce mois</p>
            </div>
        </div>

        <!-- Gestion utilisateurs -->
        <section class="admin-section">
            <h2>Gestion des utilisateurs</h2>
            <form method="GET" action="admin.php" class="search-bar">
                <input type="text" name="search" placeholder="Rechercher un utilisateur..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nom d'utilisateur</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($currentPageUsers as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="role-<?php echo $user['admin'] ? 'admin' : 'user'; ?>">
                                <?php echo $user['admin'] ? 'Administrateur' : 'Utilisateur'; ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-edit" data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-supprimer" data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1) { ?>
            <ul class="pagination">
                <?php if ($page > 1) { ?>
                <li><a href="?page=<?php echo $page - 1; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>">
                    <i class="fas fa-chevron-left"></i> Précédent
                </a></li>
                <?php } ?>
                
                <?php
                // Afficher un nombre limité de liens de page
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $page + 2);
                
                if ($startPage > 1) {
                    echo '<li><a href="?page=1' . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '') . '">1</a></li>';
                    if ($startPage > 2) {
                        echo '<li>...</li>';
                    }
                }
                
                for ($i = $startPage; $i <= $endPage; $i++) {
                    $activeClass = ($i === $page) ? ' class="active"' : '';
                    echo '<li><a href="?page=' . $i . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '') . '"' . $activeClass . '>' . $i . '</a></li>';
                }
                
                if ($endPage < $totalPages) {
                    if ($endPage < $totalPages - 1) {
                        echo '<li>...</li>';
                    }
                    echo '<li><a href="?page=' . $totalPages . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '') . '">' . $totalPages . '</a></li>';
                }
                ?>
                
                <?php if ($page < $totalPages) { ?>
                <li><a href="?page=<?php echo $page + 1; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>">
                    Suivant <i class="fas fa-chevron-right"></i>
                </a></li>
                <?php } ?>
            </ul>
            <?php } ?>
        </section>

        <!-- Panel de contrôle -->
        <section class="admin-section">
            <h2>Actions rapides</h2>
            <div class="quick-actions">
                <button class="action-card">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter une activité
                </button>
                <button class="action-card">
                    <i class="fas fa-file-invoice"></i>
                    Générer rapport
                </button>
                <button class="action-card">
                    <i class="fas fa-bell"></i>
                    Envoyer notification
                </button>
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