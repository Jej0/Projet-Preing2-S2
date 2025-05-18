<?php
session_start();
if (
    !isset($_SESSION['user']) ||
    empty($_SESSION['user']['admin']) ||
    $_SESSION['user']['admin'] !== true
) {
    header('Location: accueil.php');
    exit();
}
$users = json_decode(file_get_contents('../data/users.json'), true);

// Tri des utilisateurs par nom
usort($users, function ($a, $b) {
    $nameA = $a['information_personnelles']['nom'] ?? $a['username'];
    $nameB = $b['information_personnelles']['nom'] ?? $b['username'];
    return strcmp($nameA, $nameB);
});
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="title" content="Keep Yourself Safe">
    <meta name="author" content="Keep Yourself Safe | Alex MIKOLAJEWSKI | Axel ATAGAN | Naïm LACHGAR-BOUACHRA">
    <meta name="description" content="⚠️Page Administrateur du site Keep Yourself Safe.⚠️">
    <title>KYS - Admin</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <nav>
        <div class="nav-left">
            <a href="accueil.php" class="nav-brand">
                <img src="assets/img/logo.png" alt="Logo">
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
            <div class="admin-stats">
                <span class="admin-stat-item"><i class="fas fa-users"></i> <?= count($users) ?> utilisateurs</span>
                <span class="admin-stat-item"><i class="fas fa-ban"></i> <?= count(array_filter($users, fn($u) => !empty($u['banni']))) ?> bannis</span>
            </div>
        </div>

        <section class="admin-section">
            <h2>Gestion des utilisateurs</h2>
            <div class="search-bar">
                <input type="text" id="user-search" placeholder="Rechercher un utilisateur..." onkeyup="searchUsers()">
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nom <i class="fas fa-sort sort-icon" onclick="sortTable(0)"></i></th>
                            <th>Email <i class="fas fa-sort sort-icon" onclick="sortTable(1)"></i></th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        <?php foreach (array_slice($users, 0, 10) as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['information_personnelles']['prenom'] . ' ' . $user['information_personnelles']['nom']) ?: htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['information_personnelles']['mail']) ?></td>
                                <td>
                                    <span class="role-badge <?= $user['admin'] ? 'role-admin' : 'role-user' ?>">
                                        <?= $user['admin'] ? 'Admin' : 'Utilisateur' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge <?= !empty($user['banni']) ? 'status-banned' : 'status-active' ?>">
                                        <?= !empty($user['banni']) ? 'Banni' : 'Actif' ?>
                                    </span>
                                </td>
                                <td class="actions-cell">
                                    <button class="btn btn-action ban-btn <?= !empty($user['banni']) ? 'btn-unban' : 'btn-ban' ?>"
                                        data-user-id="<?= $user['id_user'] ?>"
                                        data-banned="<?= !empty($user['banni']) ? '1' : '0' ?>">
                                        <i class="fas <?= !empty($user['banni']) ? 'fa-unlock' : 'fa-ban' ?>"></i>
                                        <?= !empty($user['banni']) ? 'Débannir' : 'Bannir' ?>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <button id="prev-page" class="page-btn" disabled><i class="fas fa-chevron-left"></i></button>
                <span id="page-info">Page 1 sur <?= ceil(count($users) / 10) ?></span>
                <button id="next-page" class="page-btn" <?= count($users) <= 10 ? 'disabled' : '' ?>><i class="fas fa-chevron-right"></i></button>
            </div>
        </section>
    </main>

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
    <script src="assets/js/sombre.js"></script>
    <script src="assets/js/admin.js"></script>
</body>

</html>