<?php
require_once '../includes/auth_middleware.php';
// checkAuth();
// checkAdmin();

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$stmt = $db->prepare("
    SELECT * FROM users 
    ORDER BY registration_date DESC 
    LIMIT ? OFFSET ?
");
$stmt->execute([$limit, $offset]);
$users = $stmt->fetchAll();

// Get total users for pagination
$total = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des utilisateurs</title>
</head>
<body>
    <h1>Liste des utilisateurs</h1>
    
    <table>
        <tr>
            <th>Login</th>
            <th>Rôle</th>
            <th>Date d'inscription</th>
            <th>Dernière connexion</th>
        </tr>
        <?php foreach($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['login']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td><?= $user['registration_date'] ?></td>
                <td><?= $user['last_login'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    
    <!-- Pagination -->
    <div class="pagination">
        <?php for($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" <?= $i === $page ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>
    </div>
</body>
</html> 