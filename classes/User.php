<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function register($data) {
        try {
            // Validation des données
            $this->validateRegistrationData($data);

            // Vérification si le login existe déjà
            if ($this->loginExists($data['login'])) {
                throw new Exception("Ce nom d'utilisateur est déjà utilisé");
            }

            // Vérification si l'email existe déjà
            if ($this->emailExists($data['email'])) {
                throw new Exception("Cette adresse email est déjà utilisée");
            }
            
            // Hashage du mot de passe
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Préparation de la requête
            $sql = "INSERT INTO users (login, email, password, firstname, lastname, role, registration_date) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $this->db->prepare($sql);
            
            // Exécution de la requête
            $result = $stmt->execute([
                $data['login'],
                $data['email'],
                $hashedPassword,
                $data['firstname'],
                $data['lastname'],
                'user'  // Rôle par défaut
            ]);

            if (!$result) {
                throw new Exception("Erreur lors de l'inscription");
            }

            return $this->db->lastInsertId();
        } catch (Exception $e) {
            error_log("Erreur dans register() : " . $e->getMessage());
            throw $e;
        }
    }
    
    public function login($login, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE login = ?");
            $stmt->execute([$login]);
            $user = $stmt->fetch();
    
            if ($user && password_verify($password, $user['password'])) {
                // Vérifier si l'utilisateur est banni
                if ($user['role'] === 'banned') {
                    throw new Exception("Accès refusé : votre compte a été banni.");
                }
                
                // Création de la session pour l'utilisateur connecté
                $_SESSION['user'] = [
                    'id'        => $user['id'],
                    'login'     => $user['login'],
                    'email'     => $user['email'],
                    'firstname' => $user['firstname'],
                    'lastname'  => $user['lastname'],
                    'role'      => $user['role']
                ];
                
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Erreur dans login() : " . $e->getMessage());
            throw $e;
        }
    }
    
    public function getAllUsers($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->db->prepare("
            SELECT id, login, role, firstname, lastname, registration_date, last_login 
            FROM users 
            ORDER BY registration_date DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$perPage, $offset]);
        return $stmt->fetchAll();
    }
    
    public function getTotalUsers() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }
    
    public function updateRole($userId, $newRole) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET role = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$newRole, $userId]);
    }
    
    private function validateRegistrationData($data) {
        // Validation du nom d'utilisateur
        if (empty($data['login']) || strlen($data['login']) < 3) {
            throw new Exception("Le nom d'utilisateur doit contenir au moins 3 caractères");
        }

        // Validation de l'email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Adresse email invalide");
        }

        // Validation du mot de passe
        if (empty($data['password']) || strlen($data['password']) < 8) {
            throw new Exception("Le mot de passe doit contenir au moins 8 caractères");
        }

        // Validation du prénom
        if (empty($data['firstname'])) {
            throw new Exception("Le prénom est requis");
        }

        // Validation du nom
        if (empty($data['lastname'])) {
            throw new Exception("Le nom est requis");
        }
    }
    
    private function loginExists($login) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE login = ?");
        $stmt->execute([$login]);
        return $stmt->fetchColumn() > 0;
    }
    
    private function emailExists($email) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function logout() {
        session_destroy();
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user']);
    }
    
    public function getCurrentUser() {
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }
    
    // public function getActivities($userId) {
    //     $stmt = $this->db->prepare("
    //         SELECT * FROM activities 
    //         WHERE user_id = ? 
    //         ORDER BY date DESC 
    //         LIMIT 5
    //     ");
    //     $stmt->execute([$userId]);
    //     return $stmt->fetchAll();
    // }
    
    public function getReservations($userId) {
        $stmt = $this->db->prepare("
            SELECT r.*, v.titre, v.description, v.date_debut, v.date_fin, v.duree, v.prix_total AS voyage_prix_total
            FROM reservations_json r
            JOIN voyages v ON r.id_voyage = v.id_voyage
            WHERE r.id_utilisateur = ?
            ORDER BY r.date_reservation ASC
        ");
        $stmt->execute([$userId]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Chaque résultat contient une clé 'reservation' et une clé 'voyage'
            $results[] = [
                'reservation' => [
                    'id_reservation' => $row['id_reservation'],
                    'date_reservation' => $row['date_reservation'],
                    'options' => $row['options'],
                    'prix_total' => $row['prix_total'],
                    'paiement' => $row['paiement']
                ],
                'voyage' => [
                    'id_voyage' => $row['id_voyage'],
                    'titre' => $row['titre'],
                    'description' => $row['description'],
                    'date_debut' => $row['date_debut'],
                    'date_fin' => $row['date_fin'],
                    'duree' => $row['duree'],
                    'prix_total' => $row['voyage_prix_total']
                ]
            ];
        }
        return $results;
    }
    
    // public function getBadges($userId) {
    //     $stmt = $this->db->prepare("
    //         SELECT b.* 
    //         FROM badges b
    //         JOIN user_badges ub ON b.id = ub.badge_id
    //         WHERE ub.user_id = ?
    //     ");
    //     $stmt->execute([$userId]);
    //     return $stmt->fetchAll();
    // }
    
    // public function getStats($userId) {
    //     $stats = [
    //         'activities' => 0,
    //         'badges' => 0,
    //         'points' => 0
    //     ];
        
    //     // Compter les activités
    //     $stmt = $this->db->prepare("SELECT COUNT(*) FROM activities WHERE user_id = ?");
    //     $stmt->execute([$userId]);
    //     $stats['activities'] = $stmt->fetchColumn();
        
    //     // Compter les badges
    //     $stmt = $this->db->prepare("SELECT COUNT(*) FROM user_badges WHERE user_id = ?");
    //     $stmt->execute([$userId]);
    //     $stats['badges'] = $stmt->fetchColumn();
        
    //     // Calculer les points (exemple : 10 points par activité + 50 points par badge)
    //     $stats['points'] = ($stats['activities'] * 10) + ($stats['badges'] * 50);
        
    //     return $stats;
    // }
} 