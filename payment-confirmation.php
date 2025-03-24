<?php
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'scripts_php/init.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}

// Vérifier si on a un message de succès
if (!isset($_SESSION['payment'])) {
    header('Location: accueil.php');
    exit();
}

$payment = $_SESSION['payment'];
unset($_SESSION['payment']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYS - Confirmation de paiement</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="confirmation-container">
        <?php if ($payment['success']): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <h1>Paiement confirmé !</h1>
                <p>Votre paiement de <?= number_format($payment['amount'], 2, ',', ' ') ?> € a été traité avec succès.</p>
                <p>Numéro de transaction : <?= htmlspecialchars($payment['transaction_id']) ?></p>
                <p>Date : <?= date('d/m/Y H:i', strtotime($payment['date'])) ?></p>
                <p>Un email de confirmation vous a été envoyé.</p>
            </div>
        <?php else: ?>
            <div class="error-message">
                <i class="fas fa-times-circle"></i>
                <h1>Échec du paiement</h1>
                <p><?= htmlspecialchars($payment['message']) ?></p>
            </div>
        <?php endif; ?>
        
        <div class="confirmation-actions">
            <a href="accueil.php" class="btn btn-primary">Retour à l'accueil</a>
            <a href="profile.php#reservations" class="btn btn-secondary">Voir mes réservations</a>
        </div>
    </div>
</body>
</html> 