<?php
require('getapikey.php');

$vendeur = "MI-4_H"; // Remplace par ton vrai code vendeur
$api_key = getAPIKey($vendeur);

if (!isset($_GET['trip_id']) || !isset($_GET['price'])) {
    die("Paramètres invalides.");
}

$transaction = bin2hex(random_bytes(12)); // Génération d'un ID de transaction unique
$montant = number_format((float)$_GET['price'], 2, '.', '');
$retour = "http://localhost/accueil.php";
$control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement</title>
    <link rel="stylesheet" href="style.css"> <!-- Assure-toi que ce fichier CSS est bien le même que la page d'origine -->
</head>
<body>
    <div class="payment-container">
        <h2>Procéder au paiement</h2>
        <p>Montant à payer : <strong><?php echo htmlspecialchars($montant); ?> €</strong></p>
        <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
            <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
            <input type="hidden" name="montant" value="<?php echo $montant; ?>">
            <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
            <input type="hidden" name="retour" value="<?php echo htmlspecialchars($retour); ?>">
            <input type="hidden" name="control" value="<?php echo $control; ?>">
            <button type="submit" class="btn-pay">Valider et payer</button>
        </form>
        <a href="accueil.php" class="btn-cancel">Annuler</a>
    </div>
</body>
</html>
