<?php
require_once 'config/database.php';
require_once 'classes/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register($data) {
        try {
            // Validation des données
            $this->validateRegistrationData($data);
            
            // Inscription
            if ($this->user->register($data)) {
                // Connexion automatique
                $this->user->login($data['login'], $data['password']);
                return ['success' => true, 'message' => 'Inscription réussie'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function login($login, $password) {
        try {
            if ($this->user->login($login, $password)) {
                return ['success' => true, 'message' => 'Connexion réussie'];
            }
            throw new Exception('Login ou mot de passe incorrect');
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function validateRegistrationData($data) {
        if (empty($data['login']) || strlen($data['login']) < 3) {
            throw new Exception('Login invalide (minimum 3 caractères)');
        }
        if (empty($data['password']) || strlen($data['password']) < 8) {
            throw new Exception('Mot de passe trop court (minimum 8 caractères)');
        }
        if ($data['password'] !== $data['password_confirm']) {
            throw new Exception('Les mots de passe ne correspondent pas');
        }
        // Ajoutez d'autres validations selon vos besoins
    }
} 