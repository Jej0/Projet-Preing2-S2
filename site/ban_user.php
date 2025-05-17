<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id_user'])) {
    echo json_encode(['success' => false, 'error' => 'missing id']);
    exit;
}
$id = intval($data['id_user']);
$ban = isset($data['ban']) ? (bool)$data['ban'] : true;
$filepath = '../data/users.json';
$users = json_decode(file_get_contents($filepath), true);
$found = false;
foreach ($users as &$user) {
    if (intval($user['id_user']) === $id) {
        if ($ban) {
            $user['banni'] = true;
        } else {
            unset($user['banni']);
        }
        $found = true;
        break;
    }
}
if ($found && file_put_contents($filepath, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'not found or write error']);
}
