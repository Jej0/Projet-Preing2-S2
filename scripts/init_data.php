<?php
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Création des administrateurs
$admins = [
    ['admin1', 'Admin1Pass!', 'John', 'Admin'],
    ['admin2', 'Admin2Pass!', 'Jane', 'Admin']
];

foreach ($admins as $admin) {
    $hashedPassword = password_hash($admin[1], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("
        INSERT INTO users (login, password, role, firstname, lastname)
        VALUES (?, ?, 'admin', ?, ?)
    ");
    $stmt->execute([$admin[0], $hashedPassword, $admin[2], $admin[3]]);
}

// Création des utilisateurs normaux
$users = [
    ['user1', 'User1Pass!', 'Alice', 'User'],
    ['user2', 'User2Pass!', 'Bob', 'User'],
    ['user3', 'User3Pass!', 'Charlie', 'User']
];

foreach ($users as $user) {
    $hashedPassword = password_hash($user[1], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("
        INSERT INTO users (login, password, role, firstname, lastname)
        VALUES (?, ?, 'user', ?, ?)
    ");
    $stmt->execute([$user[0], $hashedPassword, $user[2], $user[3]]);
}

echo "Données initiales créées avec succès!\n"; 