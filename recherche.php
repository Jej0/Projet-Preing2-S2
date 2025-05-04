<?php
session_start();

require_once 'config/config.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

// Connexion à la base de données
$db = Database::getInstance();
$pdo = $db->getConnection();

// Requête pour récupérer les voyages avec toutes les données nécessaires
$query = "SELECT v.*, 
          (SELECT ville FROM etapes WHERE id_voyage = v.id_voyage LIMIT 1) AS ville,
          (SELECT COUNT(*) FROM etapes WHERE id_voyage = v.id_voyage) AS nb_etapes 
          FROM voyages v";
$stmt = $pdo->query($query);
$voyages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparation des données pour le tri client-side
foreach ($voyages as &$voyage) {
    // Conversion de la durée en nombre de jours
    preg_match('/\d+/', $voyage['duree'], $matches);
    $voyage['duree_jours'] = isset($matches[0]) ? (int)$matches[0] : 0;
    
    // Conversion des prix en numérique
    $voyage['prix_total'] = (float)$voyage['prix_total'];
    $voyage['note_moyenne'] = (float)$voyage['note_moyenne'];
}
unset($voyage);

// Récupération des spécificités
$stmt = $pdo->query("SELECT * FROM specificites");
$specificitesRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$specificitesByVoyage = [];
foreach ($specificitesRows as $row) {
    $specificitesByVoyage[$row['id_voyage']][] = $row['specificite'];
}

// Fusion des données
foreach ($voyages as &$voyage) {
    $voyage['specificites'] = $specificitesByVoyage[$voyage['id_voyage']] ?? [];
}
unset($voyage);

// Filtrage initial
$searchTerm = isset($_GET['search']) ? strtolower(string: trim($_GET['search'])) : '';
$location = isset($_GET['location']) ? strtolower(trim($_GET['location'])) : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

$filteredVoyages = array_filter($voyages, function ($voyage) use ($searchTerm, $location, $date) {
    $titleMatch = empty($searchTerm) || stripos($voyage['titre'], $searchTerm) !== false;
    $descMatch = empty($searchTerm) || stripos($voyage['description'], $searchTerm) !== false;
    $locationMatch = empty($location) || (stripos($voyage['ville'], $location) !== false);
    $dateMatch = empty($date) || (strtotime($voyage['date_debut']) <= strtotime($date));

    return ($titleMatch || $descMatch) && $locationMatch && $dateMatch;
});
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Information de la page web -->
    <meta charset="UTF-8">
    <!-- Optimisation pour le téléphone -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <!-- Titre du site -->
    <meta name="title" content="Keep Yourself Safe">
    <!-- Nom de l'agence et auteur du site -->
    <meta name="author" content="Keep Yourself Safe | Alex MIKOLAJEWSKI | Axel ATAGAN | Naïm LACHGAR-BOUACHRA">
    <!-- Description du site -->
    <meta name="description" content="Recherchez vos destinations avec vos activités préférées.">
    <!-- Titre du navigateur -->
    <title>KYS - Recherche</title>
    <!-- Lien vers le fichier CSS -->
    <script src="sombre.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Ajout des icônes Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <!-- Navigation -->
    <nav>
        <!-- Logo et nom (gauche) -->
        <div class="nav-left">
            <a href="accueil.php" class="nav-brand">
                <img src="img/logo.png" alt="Logo">
                Keep Yourself Safe
            </a>
        </div>
        <!-- Liens (centre) -->
        <ul class="nav-links">
            <li><a href="presentation.php">Présentation</a></li>
            <li><a href="recherche.php">Rechercher</a></li>
            <li><a href="mailto:contact@kys.fr">Contact</a></li>
        </ul>
        <!-- Profil et connexion (droite) -->
        <div class="nav-right">
            <?php if (!isset($_SESSION['user'])) { ?>
                <a href="connexion.php" class="btn nav-btn">Se connecter</a>
                <a href="inscription.php" class="btn nav-btn">S'inscrire</a>
            <?php } ?>
            <?php if (isset($_SESSION['user'])) { ?>
                <a href="scripts_php/logout.php" class="btn nav-btn">Déconnexion</a>
                <a href="profile.php" class="profile-icon">
                    <i class="fas fa-user-circle"></i>
                </a>
            <?php } ?>
        </div>
    </nav>


    <main class="search-container">
        <section class="recherche-hero">
            <div class="contenu-hero">
                <h1>Découvrez Votre Prochaine Aventure</h1>
                <p>Plus de <?= count($voyages) ?> activités disponibles</p>
                <form method="GET" action="">
                    <!-- [Le formulaire de recherche reste identique] -->
                </form>
            </div>
        </section>

        <!-- Section de tri -->
        <section class="sorting-section">
            <div class="results-header">
                <div>
                    <h2 id="results-count"><?= count($filteredVoyages) ?> Voyages trouvés</h2>
                    <p id="results-location">
                        <?= !empty($location) ? "dans ".ucfirst($location) : "dans le monde entier" ?>
                    </p>
                </div>
                <div class="sorting-controls">
                    <select class="sort-select" id="sort-select">
                        <option value="note_moyenne">Mieux notés</option>
                        <option value="prix-asc">Prix croissant</option>
                        <option value="prix-desc">Prix décroissant</option>
                        <option value="duree">Durée croissante</option>
                        <option value="etapes">Nombre d'étapes</option>
                        <option value="date">Date de début</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Conteneur des résultats -->
        <div class="results-grid" id="results-container"></div>
    </main>

    <footer>
        <!-- [Le pied de page reste identique] -->
    </footer>

    <script>
    // Données initiales
    window.voyagesData = <?= 
        json_encode(
            array_values($filteredVoyages), 
            JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT
        )
    ?>;

    // Fonction de rendu des voyages
    function renderVoyages(data) {
        const container = document.getElementById('results-container');
        container.innerHTML = data.map(voyage => `
            <div class="activity-card">
                <div class="activity-image">
                    <img src="img/${voyage.id_voyage}.jpg" alt="${voyage.titre}">
                    ${voyage.contrat ? '<span class="badge contrat">Contrat</span>' : ''}
                    ${voyage.note_moyenne >= 4.8 ? '<span class="badge populaires">Populaires</span>' : ''}
                    <button class="favorite-btn"><i class="far fa-heart"></i></button>
                </div>
                <div class="activity-content">
                    <h3>${voyage.titre}</h3>
                    <div class="activity-meta">
                        <div class="rating">
                            ${Array.from({length: 5}, (_, i) => 
                                i < Math.floor(voyage.note_moyenne) ? 
                                '<i class="fas fa-star"></i>' : 
                                (i === Math.floor(voyage.note_moyenne) && voyage.note_moyenne % 1 >= 0.5) ?
                                '<i class="fas fa-star-half-alt"></i>' : 
                                '<i class="far fa-star"></i>'
                            ).join('')}
                            <span>${voyage.note_moyenne.toFixed(1)}</span>
                        </div>
                        <div class="activity-details">
                            <div><i class="fas fa-map-marker-alt"></i> ${voyage.ville}</div>
                            <div><i class="fas fa-clock"></i> ${voyage.duree}</div>
                            <div><i class="fas fa-route"></i> ${voyage.nb_etapes} étapes</div>
                        </div>
                    </div>
                    <div class="activity-tags">
                        ${voyage.specificites.slice(0, 3).map(spec => `
                            <span class="tag"><i class="fas fa-check"></i> ${spec}</span>
                        `).join('')}
                    </div>
                    <div class="activity-footer">
                        <div class="price">
                            <span>${voyage.prix_total.toLocaleString('fr-FR')}€</span>
                            <small>/personne</small>
                        </div>
                        <a href="details.php?id=${voyage.id_voyage}" class="btn btn-primary">
                            Voir l'offre
                        </a>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Fonction de tri
    function sortVoyages(data, key) {
        return [...data].sort((a, b) => {
            switch(key) {
                case 'prix-asc': return a.prix_total - b.prix_total;
                case 'prix-desc': return b.prix_total - a.prix_total;
                case 'duree': return a.duree_jours - b.duree_jours;
                case 'etapes': return a.nb_etapes - b.nb_etapes;
                case 'date': return new Date(a.date_debut) - new Date(b.date_debut);
                default: return b.note_moyenne - a.note_moyenne;
            }
        });
    }

    // Gestionnaire d'événements pour le tri
    document.getElementById('sort-select').addEventListener('change', (e) => {
        const sortedData = sortVoyages(window.voyagesData, e.target.value);
        renderVoyages(sortedData);
    });

    // Rendu initial
    renderVoyages(sortVoyages(window.voyagesData, 'note_moyenne'));
    </script>
</body>

</html>