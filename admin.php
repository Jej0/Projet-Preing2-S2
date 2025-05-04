<?php
// Démarrer la session pour vérifier si l'utilisateur est connecté et admin
require_once 'config/config.php';

// Charger les classes nécessaires
require_once 'classes/Database.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit;
}

// Vérifier si l'utilisateur est un administrateur (ajustez selon votre logique d'authentification)
// if (!$isAdmin) {
//     header('Location: connexion.php');
//     exit();
// }

// Utilisation de la connexion à la base de données via la classe Database
$db = Database::getInstance();
$pdo = $db->getConnection();

// Traitement des actions de modification de rôle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update_role') {
        $userId = intval($_POST['user_id']);
        $newRole = $_POST['role'];
        $stmt = $pdo->prepare("UPDATE users SET role = :role WHERE id = :id");
        $stmt->execute([':role' => $newRole, ':id' => $userId]);
        header("Location: admin.php");
        exit();
    }
}

// Pagination
$usersPerPage = 10; // Nombre d'utilisateurs par page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);

// Recherche d'utilisateurs
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Requête de base pour compter les utilisateurs
$countQuery = "SELECT COUNT(*) FROM users WHERE 1=1";
$userQuery = "SELECT * FROM users WHERE 1=1";
$params = [];

if (!empty($searchTerm)) {
    $countQuery .= " AND (login LIKE :search OR firstname LIKE :search OR lastname LIKE :search OR email LIKE :search)";
    $userQuery .= " AND (login LIKE :search OR firstname LIKE :search OR lastname LIKE :search OR email LIKE :search)";
    $params[':search'] = "%$searchTerm%";
}

$countStmt = $pdo->prepare($countQuery);
$countStmt->execute($params);
$totalUsers = $countStmt->fetchColumn();

$totalPages = ceil($totalUsers / $usersPerPage);
$page = min($page, max(1, $totalPages));

$userQuery .= " ORDER BY registration_date DESC LIMIT :offset, :limit";
$params[':offset'] = ($page - 1) * $usersPerPage;
$params[':limit'] = $usersPerPage;

$userStmt = $pdo->prepare($userQuery);
foreach ($params as $key => $value) {
    if (is_int($value)) {
        $userStmt->bindValue($key, $value, PDO::PARAM_INT);
    } else {
        $userStmt->bindValue($key, $value, PDO::PARAM_STR);
    }
}
$userStmt->execute();
$currentPageUsers = $userStmt->fetchAll(PDO::FETCH_ASSOC);

// Statistiques
$totalUsersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$newUsersThisMonth = $pdo->query("SELECT COUNT(*) FROM users WHERE MONTH(registration_date) = MONTH(CURRENT_DATE()) AND YEAR(registration_date) = YEAR(CURRENT_DATE())")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYS - Admin Utilisateurs</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <nav>
        <!-- Navigation similaire à votre code précédent -->
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
            <button id="theme-toggle" class="nav-btn">
                <i class="fa-solid fa-moon"></i>
            </button>
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

    <main class="admin-container">
        <div class="admin-header">
            <h1>Tableau de bord Administrateur</h1>
        </div>

        <div class="admin-stats-grid">
            <div class="stat-card">
                <i class="fas fa-users"></i>
                <h3><?php echo $totalUsersCount; ?></h3>
                <p>Utilisateurs inscrits</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-user-plus"></i>
                <h3><?php echo $newUsersThisMonth; ?></h3>
                <p>Nouveaux utilisateurs ce mois</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-chart-line"></i>
                <h3><?php echo number_format(($newUsersThisMonth / $totalUsersCount * 100), 1); ?>%</h3>
                <p>Croissance utilisateurs</p>
            </div>
        </div>

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
                        <th>Login</th>
                        <th>Nom Complet</th>
                        <th>Email</th>
                        <th>Date Inscription</th>
                        <th>Rôle</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($currentPageUsers as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['login']); ?></td>
                        <td><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($user['registration_date'])); ?></td>
                        <td>
                            <?php 
                            switch($user['role']) {
                                case 'admin':
                                    echo '<span class="role-admin">Administrateur</span>';
                                    break;
                                case 'banned':
                                    echo '<span class="role-banned">Banni</span>';
                                    break;
                                default:
                                    echo '<span class="role-user">Utilisateur</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="action" value="update_role">
                                <select name="role" style="width:120px; height:30px;">
                                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>Utilisateur</option>
                                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Administrateur</option>
                                    <option value="banned" <?php echo $user['role'] === 'banned' ? 'selected' : ''; ?>>Banni</option>
                                </select>
                                <button type="submit" class="btn btn-edit" style="width:120px; height:30px;">
                                    Modifier
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php if ($totalPages > 1) { ?>
            <ul class="pagination">
                <?php if ($page > 1) { ?>
                <li><a href="?page=<?php echo $page - 1; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>">
                    <i class="fas fa-chevron-left"></i> Précédent
                </a></li>
                <?php } ?>
                
                <?php
                // Pagination logic (similar to your previous implementation)
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

        <section class="admin-section">
            <h2>Actions rapides</h2>
            <div class="quick-actions">
                <button class="action-card">
                    <i class="fas fa-plus-circle"></i>
                    Ajouter un utilisateur
                </button>
                <button class="action-card">
                    <i class="fas fa-file-export"></i>
                    Exporter liste
                </button>
                <button class="action-card">
                    <i class="fas fa-envelope"></i>
                    Envoyer notification
                </button>
            </div>
        </section>
    </main>

    <footer>
        <!-- Footer content remains the same as in your previous code -->
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