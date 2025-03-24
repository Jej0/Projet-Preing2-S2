<?php
require_once 'config/database.php';
require_once 'classes/Trip.php';

class TripController {
    private $db;
    private $trip;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->trip = new Trip($this->db);
    }

    public function listTrips($page = 1) {
        try {
            $trips = $this->trip->getAllTrips($page);
            return [
                'success' => true,
                'data' => $trips,
                'page' => $page
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function search($keyword) {
        try {
            $results = $this->trip->searchTrips($keyword);
            return [
                'success' => true,
                'data' => $results
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getTripDetails($tripId) {
        try {
            $details = $this->trip->getTripDetails($tripId);
            if (!$details) {
                throw new Exception('Voyage non trouvé');
            }
            return [
                'success' => true,
                'data' => $details
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function getUserTrips($userId) {
        try {
            $trips = $this->trip->getUserTrips($userId);
            return [
                'success' => true,
                'data' => $trips
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
} 