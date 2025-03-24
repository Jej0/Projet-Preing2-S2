<?php
require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';
require_once 'classes/Payment.php';
require_once 'scripts_php/init.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: connexion.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $trip_id = $_GET['trip_id'] ?? '';
    $price = floatval($_GET['price'] ?? 0);
    
    // Stockage des informations en session
    $_SESSION['pending_payment'] = [
        'trip_id' => $trip_id,
        'price' => $price
    ];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KYS - Paiement</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .payment-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .payment-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .payment-header h1 {
            color: #333;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .payment-summary {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .payment-summary h2 {
            color: #2c3e50;
            font-size: 1.4em;
            margin-bottom: 15px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .payment-form {
            display: grid;
            grid-gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #3498db;
            outline: none;
        }

        .card-row {
            display: grid;
            grid-template-columns: 2fr 1fr;
            grid-gap: 20px;
        }

        .btn-pay {
            background: #2ecc71;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .btn-pay:hover {
            background: #27ae60;
        }

        .secure-badge {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
        }

        .secure-badge i {
            margin-right: 8px;
            color: #2ecc71;
        }

        .card-icons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .card-icons i {
            font-size: 30px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include 'includes/nav.php'; ?>

    <div class="payment-container">
        <div class="payment-header">
            <h1>Finaliser votre réservation</h1>
            <p>Sécurisez votre aventure en quelques clics</p>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="payment-summary">
            <h2>Récapitulatif de la commande</h2>
            <div class="summary-item">
                <span>Activité</span>
                <span><?= htmlspecialchars($trip_id) ?></span>
            </div>
            <div class="summary-item">
                <span>Prix total</span>
                <strong><?= number_format((float)$price, 2, ',', ' ') ?>€</strong>
            </div>
        </div>

        <form method="POST" action="process-payment.php" class="payment-form" id="payment-form">
            <div class="card-icons">
                <i class="fab fa-cc-visa"></i>
                <i class="fab fa-cc-mastercard"></i>
                <i class="fab fa-cc-amex"></i>
            </div>

            <div class="form-group">
                <label for="card_name">Nom sur la carte</label>
                <input type="text" id="card_name" name="card_name" required 
                       placeholder="Ex: John DOE" autocomplete="cc-name">
            </div>

            <div class="form-group">
                <label for="card_number">Numéro de carte</label>
                <input type="text" id="card_number" name="card_number" required 
                       placeholder="1234 5678 9012 3456" pattern="[0-9]{4}\\s[0-9]{4}\\s[0-9]{4}\\s[0-9]{4}" 
                       maxlength="19" autocomplete="cc-number">
            </div>

            <div class="card-row">
                <div class="form-group">
                    <label for="card_expiry">Date d'expiration</label>
                    <input type="text" id="card_expiry" name="card_expiry" required 
                           placeholder="MM/YY" pattern="(0[1-9]|1[0-2])\/([0-9]{2})" 
                           maxlength="5" autocomplete="cc-exp">
                </div>

                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" required 
                           placeholder="123" pattern="[0-9]{3,4}" 
                           maxlength="4" autocomplete="cc-csc">
                </div>
            </div>

            <button type="submit" class="btn-pay">
                Payer <?= number_format((float)$price, 2, ',', ' ') ?>€
            </button>

            <div class="secure-badge">
                <i class="fas fa-lock"></i>
                Paiement 100% sécurisé
            </div>
        </form>
    </div>

    <script>
        // Formatage de la date d'expiration
        document.getElementById('card_expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.slice(0,2) + '/' + value.slice(2);
            }
            e.target.value = value;
        });

        // Formatage du numéro de carte amélioré
        document.getElementById('card_number').addEventListener('input', function(e) {
            // Supprimer tous les espaces et caractères non numériques
            let value = e.target.value.replace(/\D/g, '');
            
            // Ajouter un espace tous les 4 chiffres
            let formattedValue = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }
            
            // Limiter à 16 chiffres + espaces
            e.target.value = formattedValue.slice(0, 19);
        });

        // Mettre à jour le pattern pour accepter les espaces
        document.getElementById('card_number').pattern = "[0-9]{4}\\s[0-9]{4}\\s[0-9]{4}\\s[0-9]{4}";

        // Formatage du CVV
        document.getElementById('cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '').slice(0, 4);
        });

        // Validation du formulaire
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Confirmez-vous le paiement de <?= number_format((float)$price, 2, ',', ' ') ?>€ ?')) {
                this.submit();
            }
        });
    </script>
</body>
</html>

<?php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_SESSION['pending_payment'])) {
            throw new Exception("Session de paiement expirée");
        }

        $payment = new Payment();
        $result = $payment->processPayment(
            $_SESSION['user']['id'],
            $_SESSION['pending_payment']['trip_id'],
            [
                'card_number' => $_POST['card_number'],
                'card_expiry' => $_POST['card_expiry'],
                'cvv' => $_POST['cvv'],
                'amount' => $_SESSION['pending_payment']['price']
            ]
        );

        // Stocker le résultat pour la page de confirmation
        $_SESSION['payment'] = $result;
        
        // Nettoyer la session
        unset($_SESSION['pending_payment']);
        
        // Rediriger vers la page de confirmation
        header('Location: payment-confirmation.php');
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('Location: ' . $_SERVER['PHP_SELF'] . '?trip_id=' . 
               $_SESSION['pending_payment']['trip_id'] . '&price=' . 
               $_SESSION['pending_payment']['price']);
        exit();
    }
}
?> 