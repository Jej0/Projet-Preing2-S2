<?php
session_start();

$id_voyage = $_GET['id'];
$voyages = json_decode(file_get_contents('voyages.json'), true);

$voyage = null;
foreach ($voyages as $v) {
    if ($v['id_voyage'] == $id_voyage) {
        $voyage = $v;
        break;
    }
}

if (!$voyage) {
    die("Voyage non trouvé.");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- ... (votre code existant) ... -->
</head>

<body>
    <!-- ... (votre code existant jusqu'à la section principale) ... -->

    <main>
        <section class="voyage-details">
            <h2><?php echo $voyage['titre']; ?></h2>
            <p><?php echo $voyage['description']; ?></p>
            <div class="voyage-info">
                <p><strong>Dates :</strong> Du <?php echo $voyage['dates']['debut']; ?> au <?php echo $voyage['dates']['fin']; ?></p>
                <p><strong>Durée :</strong> <?php echo $voyage['dates']['duree']; ?></p>
                <p><strong>Prix total :</strong> <?php echo $voyage['prix_total']; ?>€</p>
                <p><strong>Statut :</strong> <?php echo $voyage['statut']; ?></p>
            </div>
            <h3>Étapes :</h3>
            <?php foreach ($voyage['etapes'] as $etape): ?>
                <div class="etape">
                    <h4><?php echo $etape['titre']; ?></h4>
                    <p><strong>Dates :</strong> Du <?php echo $etape['dates']['arrivee']; ?> au <?php echo $etape['dates']['depart']; ?></p>
                    <p><strong>Lieu :</strong> <?php echo $etape['position_geographique']['ville']; ?></p>
                    <h5>Options :</h5>
                    <ul>
                        <?php foreach ($etape['options'] as $option): ?>
                            <li>
                                <strong>Activité :</strong> <?php echo $option['activite']; ?><br>
                                <strong>Hébergement :</strong> <?php echo $option['hebergement']; ?><br>
                                <strong>Restauration :</strong> <?php echo $option['restauration']; ?><br>
                                <strong>Transport :</strong> <?php echo $option['transport']; ?><br>
                                <strong>Nombre de personnes :</strong> <?php echo $option['nombre_personnes']; ?><br>
                                <strong>Prix par personne :</strong> <?php echo $option['prix_par_personne']; ?>€
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </section>
    </main>

    <!-- ... (le reste de votre code existant) ... -->
</body>

</html>