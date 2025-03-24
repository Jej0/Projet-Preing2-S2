<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'projet_naim');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuration des chemins
define('BASE_PATH', dirname(__DIR__));
define('UPLOAD_PATH', BASE_PATH . '/uploads');

// Ne démarrer la session que si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    // Configuration de session
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
} 