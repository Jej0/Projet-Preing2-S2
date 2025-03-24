<?php
class Trip {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getAllTrips($page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        
        $stmt = $this->db->prepare("
            SELECT * FROM trips 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchTrips($keyword) {
        $keyword = "%$keyword%";
        
        $stmt = $this->db->prepare("
            SELECT * FROM trips 
            WHERE title LIKE ? 
            OR description LIKE ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$keyword, $keyword]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTripDetails($tripId) {
        // Récupérer les informations du voyage
        $stmt = $this->db->prepare("
            SELECT t.*, 
                   GROUP_CONCAT(DISTINCT s.id) as step_ids,
                   GROUP_CONCAT(DISTINCT o.id) as option_ids
            FROM trips t
            LEFT JOIN steps s ON t.id = s.trip_id
            LEFT JOIN options o ON s.id = o.step_id
            WHERE t.id = ?
            GROUP BY t.id
        ");
        $stmt->execute([$tripId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getUserTrips($userId) {
        $stmt = $this->db->prepare("
            SELECT t.* 
            FROM trips t
            JOIN payments p ON t.id = p.trip_id
            WHERE p.user_id = ? 
            AND p.status = 'completed'
            ORDER BY p.transaction_date DESC
        ");
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 