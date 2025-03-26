<?php
require('getapikey.php'); // Inclure le fichier pour obtenir la clé API

require_once 'config/config.php';

// Ensuite charger les classes qui en dépendent
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Vérifier que les paramètres requis sont bien présents
if (!isset($_GET['trip_id']) || !isset($_GET['price'])) {
    die('Paramètres invalides.');
}

$db = Database::getInstance();
$pdo = $db->getConnection();

$transaction = bin2hex(random_bytes(12)); // Générer un identifiant de transaction unique
$montant = number_format((float) $_GET['price'], 2, '.', ''); // Convertir le prix au bon format
$vendeur = 'MI-4_H'; // Remplacez par votre identifiant de groupe de projet
$retour = "http://localhost/accueil.php"; // URL de retour après paiement
$api_key = getAPIKey($vendeur); // Obtenir la clé API
$status;
// Vérifier que la clé API est valide
if (!preg_match("/^[0-9a-zA-Z]{15}$/", $api_key)) {
    die('Clé API invalide.');
}

// Générer la valeur de contrôle
$control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement</title>
    <link rel="stylesheet" href="styles.css"> <!-- Inclure le CSS de la page d'origine -->
</head>
<body>
    <div class="payment-container">
        <h2>Procéder au paiement</h2>
        <p>Montant à payer : <strong><?php echo $montant; ?> €</strong></p>
        <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
            <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
            <input type="hidden" name="montant" value="<?php echo $montant; ?>">
            <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
            <input type="hidden" name="retour" value="<?php echo $retour; ?>">
            <input type="hidden" name="control" value="<?php echo $control; ?>">
            <input type="hidden" name="status" value="<?php echo $status; ?>">
            <button type="submit" class="btn-payer">Valider et payer</button>
        </form>
    </div>


</body>
</html>