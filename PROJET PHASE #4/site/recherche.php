<?php
session_start();

// Charger les données JSON
$voyages = json_decode(file_get_contents('../data/voyages.json'), true);

// Compter le nombre de voyages disponibles
$nombreVoyages = count($voyages);

// Initialiser les variables de recherche
$searchTerm = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
$location = isset($_GET['location']) ? strtolower(trim($_GET['location'])) : '';
$date = isset($_GET['date']) ? $_GET['date'] : '';

// Filtrer les voyages (désormais fait côté JS, mais on garde pour fallback)
$filteredVoyages = array_filter($voyages, function ($voyage) use ($searchTerm, $location, $date) {
    // Filtre par terme de recherche (titre ou description)
    $titleMatch = empty($searchTerm) || strpos(strtolower($voyage['titre']), $searchTerm) !== false;
    $descMatch = empty($searchTerm) || strpos(strtolower($voyage['description']), $searchTerm) !== false;

    // Filtre par localisation (ville dans les étapes)
    $locationMatch = empty($location);
    if (!$locationMatch) {
        foreach ($voyage['etapes'] as $etape) {
            if (strpos(strtolower($etape['position_geographique']['ville']), $location) !== false) {
                $locationMatch = true;
                break;
            }
        }
    }

    // Filtre par date (date de début avant ou égale à la date sélectionnée)
    $dateMatch = empty($date) || (strtotime($voyage['dates']['debut']) <= strtotime($date));

    return ($titleMatch || $descMatch) && $locationMatch && $dateMatch;
});
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <!--Information de la page web-->
    <meta charset="UTF-8">

    <!-- Optimisation pour le telephone -->
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
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <!-- Ajout des icônes Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <!-- Très bien mais comment ça marche ? -->
    <script src="assets/js/sombre.js"></script>
    <script>
        // Injection des données voyages côté JS
        window.voyagesData = <?php echo json_encode($voyages, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
        window.isUserLogged = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
    </script>
    <script>
    // JS pour la recherche et les filtres dynamiques
    document.addEventListener('DOMContentLoaded', function() {
        const voyages = window.voyagesData;
        let filtered = voyages.slice();

        // Pagination
        let currentPage = 1;
        const resultsPerPage = 9;

        // Elements
        const searchInput = document.querySelector('input[name="search"]');
        const locationInput = document.querySelector('input[name="location"]');
        const dateInput = document.querySelector('input[name="date"]');
        const priceRange = document.getElementById('prix-slider');
        const priceValueDisplay = document.getElementById('prix-slider-value');
        const contratBtns = document.querySelectorAll('.filter-group .filter-options .filter-option');
        const dureeBtns = document.querySelectorAll('.filter-group:last-child .filter-options .filter-option');
        const resultsGrid = document.querySelector('.results-grid');
        const resultsHeader = document.querySelector('.results-header h2');
        const resultsLocation = document.querySelector('.results-header p');
        const sortSelect = document.querySelector('.sort-select');
        // Ajout pagination
        let paginationContainer = document.getElementById('pagination');
        if (!paginationContainer) {
            paginationContainer = document.createElement('div');
            paginationContainer.id = 'pagination';
            // Suppression du style inline, on gère tout en CSS
            resultsGrid.parentNode.appendChild(paginationContainer);
        }

        // Etat des filtres
        let filterState = {
            search: '',
            location: '',
            date: '',
            contrat: 'Tous',
            prix: priceRange ? parseInt(priceRange.value, 10) : 10000,
            duree: 'Tous',
            sort: 'rating'
        };

        // Helpers
        function normalize(str) {
            return (str || '').toLowerCase();
        }

        function filterVoyages() {
            filtered = voyages.filter(voyage => {
                // Recherche titre/desc
                const search = normalize(filterState.search);
                const titleMatch = !search || normalize(voyage.titre).includes(search);
                const descMatch = !search || normalize(voyage.description).includes(search);

                // Localisation
                const loc = normalize(filterState.location);
                let locationMatch = !loc;
                if (!locationMatch) {
                    for (const etape of voyage.etapes) {
                        if (normalize(etape.position_geographique.ville).includes(loc)) {
                            locationMatch = true;
                            break;
                        }
                    }
                }

                // Date
                let dateMatch = !filterState.date || (new Date(voyage.dates.debut) <= new Date(filterState.date));

                // Contrat
                let contratMatch = filterState.contrat === 'Tous' ||
                    (filterState.contrat === 'Oui' && voyage.contrat) ||
                    (filterState.contrat === 'Non' && !voyage.contrat);

                // Prix
                let prixMatch = voyage.prix_total <= filterState.prix;

                // Durée
                let dureeMatch = true;
                if (filterState.duree === 'Long') dureeMatch = voyage.dates.duree && voyage.dates.duree.toLowerCase().includes('long');
                if (filterState.duree === 'Cours') dureeMatch = voyage.dates.duree && voyage.dates.duree.toLowerCase().includes('court');

                return (titleMatch || descMatch) && locationMatch && dateMatch && contratMatch && prixMatch && dureeMatch;
            });
            sortVoyages();
            renderResults();
        }

        function sortVoyages() {
            if (filterState.sort === 'rating') {
                filtered.sort((a, b) => b.note_moyenne - a.note_moyenne);
            } else if (filterState.sort === 'price-low') {
                filtered.sort((a, b) => a.prix_total - b.prix_total);
            } else if (filterState.sort === 'price-high') {
                filtered.sort((a, b) => b.prix_total - a.prix_total);
            }
        }

        function renderResults() {
            resultsHeader.textContent = filtered.length + ' Voyages trouvés';
            resultsLocation.textContent = filterState.location ? 'dans ' + filterState.location.charAt(0).toUpperCase() + filterState.location.slice(1) : 'dans le monde entier';

            // Pagination calcul
            const totalPages = Math.ceil(filtered.length / resultsPerPage);
            if (currentPage > totalPages) currentPage = 1;
            const start = (currentPage - 1) * resultsPerPage;
            const end = start + resultsPerPage;
            const pageResults = filtered.slice(start, end);

            resultsGrid.innerHTML = '';
            pageResults.forEach(voyage => {
                const card = document.createElement('div');
                card.className = 'activity-card';
                card.innerHTML = `
                    <div class="activity-image">
                        <img src="assets/img/${voyage.id_voyage}.jpg" alt="${voyage.titre}">
                        <div class="activity-badges">
                            ${voyage.contrat ? '<span class="badge contrat">Contrat</span>' : ''}
                            ${voyage.note_moyenne >= 4.8 ? '<span class="badge populaires">Populaires</span>' : ''}
                        </div>
                        <button class="favorite-btn button"><i class="far fa-heart"></i></button>
                    </div>
                    <div class="activity-content">
                        <h3>${voyage.titre}</h3>
                        <div class="activity-rating">
                            <div class="rating">
                                ${renderStars(voyage.note_moyenne)}
                            </div>
                            <span class="rating-score">${voyage.note_moyenne.toFixed(1)}</span>
                            <span class="rating-count">(${voyage.nb_avis} avis)</span>
                        </div>
                        <div>
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>${voyage.etapes[0].position_geographique.ville}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span>${voyage.dates.duree}</span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-user-friends"></i>
                                <span>${voyage.liste_personnes.max} personnes max</span>
                            </div>
                        </div>
                        <div class="activity-tags">
                            ${voyage.specificites.slice(0,3).map(spec => `<span class="tags"><i class="fas fa-check"></i> ${spec}</span>`).join('')}
                        </div>
                        <div class="activity-bottom">
                            <div class="activity-footer">
                                <div class="price-block">
                                    <span class="price">${voyage.prix_total.toLocaleString('fr-FR', {maximumFractionDigits:0})}€</span>
                                    <span class="price-info">/personne</span>
                                </div>
                                ${window.isUserLogged
                                    ? `<a href="details.php?id=${voyage.id_voyage}" class="btn btn-book">Réserver maintenant</a>`
                                    : `<a href="connexion.php?redirect=details.php?id=${voyage.id_voyage}" class="btn btn-book">Réserver maintenant</a>`
                                }
                            </div>
                        </div>
                    </div>
                `;
                resultsGrid.appendChild(card);
            });

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            if (totalPages <= 1) {
                paginationContainer.innerHTML = '';
                return;
            }
            let html = '';
            // Previous
            html += `<button class="pagination-btn" ${currentPage === 1 ? 'disabled' : ''} data-page="${currentPage - 1}">&lt;</button>`;
            // Pages
            for (let i = 1; i <= totalPages; i++) {
                html += `<button class="pagination-btn${i === currentPage ? ' active' : ''}" data-page="${i}">${i}</button>`;
            }
            // Next
            html += `<button class="pagination-btn" ${currentPage === totalPages ? 'disabled' : ''} data-page="${currentPage + 1}">&gt;</button>`;
            paginationContainer.innerHTML = html;

            // Events
            paginationContainer.querySelectorAll('.pagination-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const page = parseInt(this.getAttribute('data-page'), 10);
                    if (!isNaN(page) && page >= 1 && page <= totalPages && page !== currentPage) {
                        currentPage = page;
                        renderResults();
                        window.scrollTo({top: resultsGrid.offsetTop - 60, behavior: 'smooth'});
                    }
                });
            });
        }

        function renderStars(note) {
            let html = '';
            let pleines = Math.floor(note);
            let demi = (note - pleines) >= 0.5 ? 1 : 0;
            let vides = 5 - pleines - demi;
            for (let i = 0; i < pleines; i++) html += '<i class="fas fa-star"></i>';
            if (demi) html += '<i class="fas fa-star-half-alt"></i>';
            for (let i = 0; i < vides; i++) html += '<i class="far fa-star"></i>';
            return html;
        }

        // Events
        if (searchInput) searchInput.addEventListener('input', e => { filterState.search = e.target.value; currentPage = 1; filterVoyages(); });
        if (locationInput) locationInput.addEventListener('input', e => { filterState.location = e.target.value; currentPage = 1; filterVoyages(); });
        if (dateInput) dateInput.addEventListener('change', e => { filterState.date = e.target.value; currentPage = 1; filterVoyages(); });

        // Contrat boutons
        document.querySelectorAll('.filter-group .filter-options .filter-option').forEach(btn => {
            btn.addEventListener('click', function() {
                // Désactive tous les boutons du même groupe
                this.parentNode.querySelectorAll('.filter-option').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                // Détecte le groupe (Contrat ou Durée)
                const label = this.closest('.filter-group').querySelector('label').textContent.trim();
                if (label === 'Contrat') {
                    filterState.contrat = this.textContent.trim();
                }
                if (label === 'Durée') {
                    filterState.duree = this.textContent.trim();
                }
                currentPage = 1;
                filterVoyages();
            });
        });

        // Prix
        if (priceRange) {
            priceRange.addEventListener('input', function() {
                filterState.prix = parseInt(this.value, 10);
                if (priceValueDisplay) priceValueDisplay.textContent = this.value + '€';
                currentPage = 1;
                filterVoyages();
            });
        }

        // Tri
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                filterState.sort = this.value;
                currentPage = 1;
                sortVoyages();
                renderResults();
            });
        }

        // Empêche le submit du formulaire (tout JS)
        document.querySelector('form[method="GET"]')?.addEventListener('submit', e => e.preventDefault());

        // Initialisation
        filterVoyages();
    });
    </script>
</head>
<body>
    <!-- Haut de page -->

    <!-- Navigation -->
    <nav>
        <!-- Logo et nom (gauche)-->
        <div class="nav-left">
            <a href="accueil.php" class="nav-brand">
                <img src="assets/img/logo.png" alt="Logo">
                Keep Yourself Safe
            </a>
        </div>

        <!-- Liens (centre)-->
        <ul class="nav-links">
            <li><a href="presentation.php">Présentation</a></li>
            <li><a href="recherche.php">Rechercher</a></li>
            <li><a href="mailto:contact@kys.fr">Contact</a></li>
        </ul>

        <!-- Profil et connexion(droite)-->
        <div class="nav-right">
			<button id="theme-toggle" class="nav-btn">
				<i class="fa-solid fa-moon"></i>
			</button>
            <?php if (!isset($_SESSION['user'])) { ?>
                <a href="connexion.php" class="btn nav-btn">Se connecter</a>
                <a href="inscription.php" class="btn nav-btn">S'inscrire</a>
            <?php } ?>
            <?php if (isset($_SESSION['user'])) { ?>
                <a href="../scripts_php/deconnexion.php" class="btn nav-btn">Déconnexion</a>
                <a href="profile.php" class="profile-icon">
                    <i class="fas fa-user-circle"></i>
                </a>
            <?php } ?>
        </div>
    </nav>

    <!-- Contenu-->
    <main class="search-container">
        <!-- Hero Section avec vidéo de fond -->
        <section class="recherche-hero">
            <div class="contenu-hero">
                <h1>Découvrez Votre Prochaine Aventure</h2>
                    <p>Plus de <?php echo $nombreVoyages; ?> activités extrêmes encadrées par des professionnels</p>
                    <!-- Barre de recherche principale -->
                    <form method="GET" action="">
                        <div class="search-box">
                            <div class="search-form">
                                <div class="search-row">
                                    <div class="search-group">
                                        <i class="fas fa-search"></i>
                                        <input type="text" name="search" placeholder="Rechercher un voyage" value="<?= htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '') ?>">
                                    </div>
                                    <div class="search-group">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <input type="text" name="location" placeholder="Rechercher un endroit" value="<?= htmlspecialchars(isset($_GET['location']) ? $_GET['location'] : '') ?>">
                                    </div>
                                    <div class="search-group">
                                        <i class="fas fa-calendar"></i>
                                        <input type="date" name="date" value="<?= htmlspecialchars(isset($_GET['date']) ? $_GET['date'] : '') ?>">
                                    </div>
                                    <button type="submit" class="btn button search-submit">Rechercher</button>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>
        </section>

        <!-- Filtres avancés -->
        <section class="advanced-filters">
            <div class="filter-container">
                <div class="filter-group">
                    <label>Contrat</label>
                    <div class="filter-options">
                        <button class="btn button filter-option active">Tous</button>
                        <button class="btn button filter-option">Oui</button>
                        <button class="btn button filter-option">Non</button>
                    </div>
                </div>
                <div class="filter-group">
                    <label>Prix</label>
                    <div class="price-range">
                        <input type="range" min="0" max="10000" value="5000" step="100" id="prix-slider">
                        <span id="prix-slider-value">5000€</span>
                        <div class="price-values">
                            <span>0€</span>
                            <span>10000€</span>
                        </div>
                    </div>
                </div>
                <div class="filter-group">
                    <label>Durée</label>
                    <div class="filter-options">
                        <button class="btn button filter-option active">Tous</button>
                        <button class="btn button filter-option">Long</button>
                        <button class="btn button filter-option">Cours</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Résultats de recherche -->
        <section>
            <div class="results-header">
                <div>
                    <h2><?= count($filteredVoyages) ?> Voyages trouvés</h2>
                    <p>
                        <?php
                        if (!empty($location)) {
                            echo "dans " . ucfirst($location);
                        } else {
                            echo "dans le monde entier";
                        }
                        ?>
                    </p>
                </div>
                <div>
                    <select class="sort-select">
                        <option value="rating">Mieux notés</option>
                        <option value="price-low">Prix croissant</option>
                        <option value="price-high">Prix décroissant</option>
                    </select>
                </div>
            </div>
            <div class="results-grid">
                <?php foreach ($filteredVoyages as $voyage): ?>
                    <div class="activity-card">
                        <div class="activity-image">
                            <img src="assets/img/<?php echo $voyage['id_voyage']; ?>.jpg" alt="<?php echo $voyage['titre']; ?>">
                            <div class="activity-badges">
                                <?php if ($voyage['contrat'] == true): ?>
                                    <span class="badge contrat">Contrat</span>
                                <?php endif; ?>
                                <?php if ($voyage['note_moyenne'] >= 4.8): ?>
                                    <span class="badge populaires">Populaires</span>
                                <?php endif; ?>
                            </div>
                            <button class="favorite-btn button"><i class="far fa-heart"></i></button>
                        </div>
                        <div class="activity-content">
                            <h3><?php echo $voyage['titre']; ?></h3>
                            <div class="activity-rating">
                                <div class="rating">
                                    <?php
                                    $note = $voyage['note_moyenne'];
                                    $etoilesPleines = floor($note);
                                    $etoilesDemi = ($note - $etoilesPleines) >= 0.5 ? 1 : 0;

                                    // Étoiles pleines
                                    for ($i = 0; $i < $etoilesPleines; $i++) {
                                        echo '<i class="fas fa-star"></i>';
                                    }
                                    // Demi-étoile
                                    if ($etoilesDemi) {
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                    }
                                    // Étoiles vides (calcul corrigé)
                                    $etoilesVides = 5 - $etoilesPleines - $etoilesDemi;
                                    for ($i = 0; $i < $etoilesVides; $i++) {
                                        echo '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                </div>
                                <span class="rating-score"><?= number_format($voyage['note_moyenne'], 1) ?></span>
                                <span class="rating-count">(<?= $voyage['nb_avis'] ?> avis)</span>
                            </div>
                            <div>
                                <div class="detail-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo $voyage['etapes'][0]['position_geographique']['ville'] ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo $voyage['dates']['duree'] ?></span>
                                </div>
                                <div class="detail-item">
                                    <i class="fas fa-user-friends"></i>
                                    <span><?php echo $voyage['liste_personnes']['max'] ?> personnes max</span>
                                </div>
                            </div>
                            <div class="activity-tags">
                                <?php foreach (array_slice($voyage['specificites'], 0, 3) as $spec): ?>
                                    <span class="tags"><i class="fas fa-check"></i> <?php echo $spec ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="activity-bottom">
                                <div class="activity-footer">
                                    <div class="price-block">
                                        <span class="price"><?php echo number_format($voyage['prix_total'], 0, ',', ' ') ?>€</span>
                                        <span class="price-info">/personne</span>
                                    </div>
                                    <?php if (isset($_SESSION['user'])): ?>
                                        <a href="details.php?id=<?php echo $voyage['id_voyage'] ?>" class="btn btn-book">Réserver maintenant</a>
                                    <?php else: ?>
                                        <a href="connexion.php?redirect=details.php?id=<?php echo $voyage['id_voyage'] ?>" class="btn btn-book">Réserver maintenant</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Ajout du conteneur de pagination -->
            <div id="pagination"></div>
        </section>
    </main>

    <!-- Pied de page -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Keep Yourself Safe</h3>
                <p>L'aventure en toute sécurité.</p>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: contact@kys.fr</p>
                <p>Tél: +33 1 23 45 67 89</p>
            </div>
            <div class="footer-section">
                <h3>Suivez-nous</h3>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Keep Yourself Safe. Tous droits réservés.</p>
        </div>
    </footer>
    <style>
        /* Cache les résultats PHP si JS actif */
        .results-grid:empty + script ~ .results-grid { display: none; }

        /* Centrage et espacement pagination */
        #pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin: 32px 0 0 0;
        }
    </style>
</body>
</html>