<?php
class Payment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function processPayment($userId, $tripId, $paymentData) {
        try {
            // Nettoyer le numéro de carte des espaces
            $paymentData['card_number'] = str_replace(' ', '', $paymentData['card_number']);

            // Validation des données de carte
            $this->validateCardData($paymentData);

            // Préparation des données pour la base de données
            $cardNumber = $this->encryptCardData($paymentData['card_number']);
            $cardExpiry = date('Y-m-d', strtotime('01-' . $paymentData['card_expiry']));
            
            // Insertion dans la base de données
            $stmt = $this->db->prepare("
                INSERT INTO payments (user_id, trip_id, amount, card_number, card_expiry, transaction_date, status)
                VALUES (?, ?, ?, ?, ?, NOW(), 'pending')
            ");

            $stmt->execute([
                $userId,
                $tripId,
                $paymentData['amount'],
                $cardNumber,
                $cardExpiry
            ]);

            $paymentId = $this->db->lastInsertId();

            // Simuler le traitement du paiement
            $success = $this->simulatePaymentProcessing();

            // Mettre à jour le statut du paiement
            $newStatus = $success ? 'completed' : 'failed';
            $this->updatePaymentStatus($paymentId, $newStatus);

            return [
                'success' => $success,
                'message' => $success ? 'Paiement traité avec succès' : 'Échec du paiement',
                'transaction_id' => $paymentId,
                'amount' => $paymentData['amount'],
                'date' => date('Y-m-d H:i:s')
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getPaymentHistory($userId) {
        $stmt = $this->db->prepare("
            SELECT id as transaction_id, amount, transaction_date as date, status
            FROM payments
            WHERE user_id = ?
            ORDER BY transaction_date DESC
        ");
        
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    private function validateCardData($paymentData) {
        // Nettoyer le numéro de carte
        $cardNumber = str_replace(' ', '', $paymentData['card_number']);
        
        // Vérification basique du numéro de carte
        if (empty($cardNumber) || !preg_match('/^[0-9]{16}$/', $cardNumber)) {
            throw new Exception("Numéro de carte invalide - doit contenir 16 chiffres");
        }
        
        // Vérification de la date d'expiration
        if (empty($paymentData['card_expiry']) || !preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $paymentData['card_expiry'])) {
            throw new Exception("Date d'expiration invalide - format MM/YY requis");
        }
        
        // Vérification du CVV
        if (empty($paymentData['cvv']) || !preg_match('/^[0-9]{3,4}$/', $paymentData['cvv'])) {
            throw new Exception("CVV invalide - 3 ou 4 chiffres requis");
        }

        // Vérification de la date d'expiration
        $expiry = explode('/', $paymentData['card_expiry']);
        $expiryDate = DateTime::createFromFormat('y-m', $expiry[1] . '-' . $expiry[0]);
        $now = new DateTime();
        
        if ($expiryDate < $now) {
            throw new Exception("Carte expirée");
        }
    }

    private function encryptCardData($cardNumber) {
        // En production, utilisez une vraie méthode de cryptage
        return 'XXXX-XXXX-XXXX-' . substr($cardNumber, -4);
    }

    private function simulatePaymentProcessing() {
        // Simuler un délai de traitement
        sleep(1);
        // 90% de chance de succès
        return (rand(1, 100) <= 90);
    }

    private function updatePaymentStatus($paymentId, $status) {
        $stmt = $this->db->prepare("
            UPDATE payments 
            SET status = ?
            WHERE id = ?
        ");
        return $stmt->execute([$status, $paymentId]);
    }
} 