<?php
require_once 'config/database.php';
require_once 'classes/User.php';

class AdminController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function listUsers($page = 1) {
        try {
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
                throw new Exception('Accès non autorisé');
            }

            $users = $this->user->getAllUsers($page);
            return [
                'success' => true,
                'data' => $users,
                'page' => $page
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateUserRole($userId, $newRole) {
        try {
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
                throw new Exception('Accès non autorisé');
            }

            if ($this->user->updateRole($userId, $newRole)) {
                return ['success' => true, 'message' => 'Rôle mis à jour avec succès'];
            }
            throw new Exception('Erreur lors de la mise à jour du rôle');
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
} 