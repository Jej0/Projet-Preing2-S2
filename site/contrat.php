<?php
session_start();

// 1. Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// 2. Vérification si l'utilisateur a déjà signé le contrat
if ($_SESSION['user']['information_personnelles']['contrat'] ?? false) {
    header("Location: profile.php");
    exit();
}

// 3. Traitement du formulaire si case cochée
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_terms'])) {
    // Lecture du fichier JSON
    $usersData = json_decode(file_get_contents('../data/users.json'), true);
    
    // Mise à jour du statut contrat
    foreach ($usersData as &$user) {
        if ($user['username'] === $_SESSION['user']['username']) {
            $user['information_personnelles']['contrat'] = true;
            $_SESSION['user']['information_personnelles']['contrat'] = true;
            break;
        }
    }
    
    // Sauvegarde des modifications
    file_put_contents('../data/users.json', json_encode($usersData, JSON_PRETTY_PRINT));
    
    // Redirection vers le profil
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="title" content="Keep Yourself Safe">
    <meta name="author" content="Keep Yourself Safe | Alex MIKOLAJEWSKI | Axel ATAGAN | Naïm LACHGAR-BOUACHRA">
    <meta name="description" content="Contrat à signer">
    <title>KYS - Contrat</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
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
    
    <div class="presentation-container">
        <div class="contract-header">
            <h1>CONDITIONS GÉNÉRALES DE SERVICE</h1>
            <p>Keep Yourself Safe - Agence de voyage de sport extrême en montagne</p>
        </div>

        <div class="profile-section">
            <h2>PRÉAMBULE</h2>
            <p>Dans le cadre des stipulations présentes et futures, il est expressément défini que l'Agence, dénommée « Keep Yourself Safe », ne saurait être tenue responsable d'éventuelles occurrences préjudiciables, et ce, indépendamment de tout facteur contributif externe ou interne. Le Client reconnaît que toute participation aux activités organisées par l'Agence vaut acceptation inconditionnelle des présentes conditions et de toute stipulation implicite, y compris celles ne relevant pas directement de son appréhension immédiate.</p><br>
            <p>L’Agence « Keep Yourself Safe » rappelle que la vie est une illusion et que toute tentative de lui échapper est vaine. En signant ce contrat, le Client reconnaît que son existence n’a jamais vraiment eu d’importance, et que ses biens (y compris son âme) seront bien plus utiles entre les mains de l’Agence.</p>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 1 - OBJET DU CONTRAT ET CONTEXTE HISTORIQUE</h2>
            <p>Depuis les temps immémoriaux, l'Homme a toujours cherché à conquérir la nature, à gravir les plus hautes montagnes, à défier les éléments dans une quête incessante de dépassement de soi. C'est dans cet esprit que Keep Yourself Safe a été fondée, afin de permettre aux âmes aventureuses de repousser leurs limites tout en acceptant, consciemment ou inconsciemment, les conséquences inhérentes à de telles entreprises.</p>
            <p>De nombreux philosophes antiques, dont Héraclite et Sénèque, ont souligné la vanité des préoccupations humaines face à l'immensité du destin. De même, l'Agence considère que toute tentative d'imputer une quelconque responsabilité à une entité extérieure aux choix du Client relève d'une méconnaissance fondamentale des principes de causalité.</p>
            <p>Ainsi, en adhérant aux présentes conditions, le Client reconnaît l'imprévisibilité de l'existence et la nature éphémère de l'individu dans un monde régi par des forces qui le dépassent. L'Agence ne saurait être tenue pour responsable des décisions prises par le Client au sein d'un environnement dont il a accepté les incertitudes.</p><br>
            <div class="clause">
                <p><strong>1.1.</strong> Le Client accepte que toute activité proposée par l’Agence soit considérée comme une « expérience philosophique » plutôt qu’un simple loisir.</p>
            </div>
            <div class="clause">
                <p><strong>1.2.</strong> En cas de décès, le Client autorise l’Agence à utiliser son histoire comme anecdote marketing, y compris dans des publicités du type : « Comme lui, osez défier la mort… mais échouez mieux. »</p>
            </div>
            <div class="clause">
                <p><strong>1.3.</strong> Le Client renonce à toute forme de postérité, y compris dans les mémoires de ses proches.</p>
            </div>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 2 - ACCEPTATION DES RISQUES</h2>
            <p>Le Client atteste avoir pris connaissance de la nature indéterminée des risques inhérents aux activités, incluant notamment, mais sans s'y limiter :</p>
            <ul class="contrat-ul-marge">
                <li>Les dommages corporels directs et indirects</li>
                <li>Les troubles physiques permanents</li>
                <li>Les incidences collatérales sur ses proches et ayants droit</li>
                <li>La disparition dans un gouffre sans fond.</li>
                <li>La transformation en légende urbaine.</li>
                <li>L’utilisation de son image en tant que « fantôme officiel » de l’Agence.</li>
            </ul>
            <p>Toute réclamation postérieure à un événement découlant desdites Prestations est expressément rendue caduque par l'acceptation des présentes. En cas de survie miraculeuse, le Client s’engage à ne pas en tirer gloire, sous peine de devoir payer une « taxe d’incompétence ».</p>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 3 - CLAUSE D'EXONÉRATION DE RESPONSABILITÉ</h2>
            <p>L'Agence ne pourra en aucun cas être tenue pour responsable de toute forme d'incident, accident, ou événement fortuit résultant d'un facteur interne ou externe, y compris ceux dont l'origine relèverait de la participation volontaire ou involontaire du Client.</p>
            <p>L'Agence se réserve le droit d'invoquer toute disposition supplémentaire visant à limiter son exposition à d'éventuelles contestations.</p>
            <p>L’Agence décline toute responsabilité en cas de :</p>
            <ul class="contrat-ul-marge">
                <li>Réincarnation ratée.</li>
                <li>Apparition dans des rêves traumatisants de ses proches.</li>
                <li>Découverte post-mortem que le Client avait une dette envers l’Agence.</li>
            </ul>
            <p>Si le Client meurt de peur avant même de commencer l’activité, l’Agence conserve l’intégralité des frais d’inscription.</p>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 4 - TRANSFERT DES DROITS PATRIMONIAUX EN CAS DE DÉCÈS</h2>
            <p>Le Client accepte, par dérogation expresse à toute disposition contraire, que l'Agence puisse disposer, en qualité de tiers cessionnaire privilégié, de l'ensemble des biens matériels et immatériels qui pourraient, directement ou indirectement, se rattacher à sa personne en cas d'incident définitif survenant au cours des Prestations.</p>
            <p>Le Client renonce à toute réclamation postérieure visant à contester cette disposition dont la validité est reconnue de plein droit.</p>
            <p>L’Agence devient propriétaire de :</p>
            <ul class="contrat-ul-marge">
                <li>Tous les biens matériels du Client, y compris ceux qu’il n’a pas encore achetés.</li>
                <li>Ses comptes en banque, ses souvenirs d’enfance, et ses identifiants Steam.</li>
                <li>Son âme (transfert irrévocable, voir Article 11).</li>
            </ul>
            <p>Les proches du Client s’engagent à ne pas pleurer trop bruyamment, sous peine de devoir payer une « taxe de perturbation émotionnelle ».</p>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 5 - DISPOSITIONS ANNEXES ET INUTILES</h2>
            <div class="clause">
                <p><strong>5.1.</strong> Toute contestation relative à l'interprétation des clauses présentement énoncées sera définitivement résolue par un comité arbitral désigné par l'Agence.</p>
            </div>
            <div class="clause">
                <p><strong>5.2.</strong> Le Client reconnaît que toute forme d'expression non conforme aux dispositions du contrat ne pourra donner lieu à aucune réclamation.</p>
            </div>
            <div class="clause">
                <p><strong>5.3.</strong> L'Agence se réserve le droit de modifier à tout moment toute stipulation, sans obligation d'en informer préalablement le Client.</p>
            </div>
            <div class="clause">
                <p><strong>5.4.</strong> Le Client s'engage à adopter une attitude compatible avec les objectifs et valeurs implicites de l'Agence.</p>
            </div>
            <div class="clause">
                <p><strong>5.5.</strong> Toute contestation relative à l'interprétation des clauses sera définitivement résolue par un combat à mains nues contre le PDG de l’Agence.</p>
            </div>
            <div class="clause">
                <p><strong>5.6.</strong> Le Client reconnaît que toute tentative de lire ce contrat en entier est un acte de sorcellerie.</p>
            </div>
            <div class="clause">
                <p><strong>5.7.</strong> En cas de litige, le tribunal compétent est celui situé au sommet du Mont Inaccessible.</p>
            </div>
            <div class="clause">
                <p><strong>5.8.</strong> L’Agence se réserve le droit de modifier rétroactivement les termes du contrat, y compris après la mort du Client.</p>
            </div>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 6 - FORCE MAJEURE</h2>
            <p>Les parties reconnaissent que la notion de force majeure inclut, mais sans s'y limiter :</p>
            <ul class="contrat-ul-marge">
                <li>Les catastrophes naturelles</li>
                <li>Événements cosmiques</li>
                <li>Mouvements telluriques</li>
                <li>Les attaques de yetis non assurées.</li>
                <li>Dérèglements sociopolitiques imprévisibles</li>
                <li>Les malédictions ancestrales activées par erreur.</li>
                <li>Toute autre situation dont l'Agence jugerait raisonnablement qu'elle constitue une entrave insurmontable</li>
            </ul>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 7 - DROIT APPLICABLE ET RÉGLEMENT DES LITIGES</h2>
            <p>Tout litige sera régi par les dispositions en vigueur dans la juridiction du siège social de l'Agence et sera tranché selon les « Lois du Chaos », un code juridique inventé par l’Agence. Toute action judiciaire entreprise en dehors de cette juridiction sera considérée comme nulle et non avenue.</p>
            <p>Le Client s'engage à se conformer aux décisions rendues sans possibilité de contestation et renonce à toute forme de procès, y compris dans l’au-delà.</p>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 8 - DISPOSITIONS FINALES ET ACCEPTATION ABSOLUE</h2>
            <div class="clause">
                <p><strong>8.1.</strong> En validant les présentes, le Client confirme qu'il renonce explicitement à toute action ultérieure visant à contester l'interprétation ou l'exécution des conditions présentement décrites.</p>
            </div>
            <div class="clause">
                <p><strong>8.2.</strong> Toute opposition aux présentes conditions sera considérée comme une violation intentionnelle de la stabilité contractuelle et pourra faire l'objet de sanctions définies par l'Agence.</p>
            </div>
            <div class="clause">
                <p><strong>8.3.</strong> Le Client reconnaît que ce contrat est une métaphore sur la futilité de l’existence.</p>
            </div>
            <div class="clause">
                <p><strong>8.4.</strong> Toute opposition aux présentes conditions sera considérée comme une violation de la stabilité contractuelle et punie par une amende de 10 000 € ou 10 000 années de karma négatif (au choix de l’Agence).</p>
            </div>
            <div class="clause">
                <p><strong>8.5.</strong> La persistance de la validité de ces clauses ne saurait être remise en cause par une quelconque interprétation contraire aux intérêts de l'Agence.</p>
            </div>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 9 - CESSION D'ÂME ET ESPRITS MONTAGNARDS</h2>
            <div class="clause">
                <p><strong>9.1. Propriété Éternelle de l'Âme</strong></p>
                <ul class="contrat-ul-marge">
                    <li>Le Client transfère irrévocablement son âme à l'Agence, y compris ses réincarnations et fragments spirituels.</li>
                    <li>L'Agence peut :
                        <ul>
                            <li>Revendre l'âme à des collectionneurs.</li>
                            <li>L'utiliser comme monnaie d'échange avec des entités paranormales.</li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="clause">
                <p><strong>9.2. Collaboration Forcée avec les Esprits</strong></p>
                <p>En cas de décès, le Client deviendra un esprit serviteur de l'Agence, avec pour missions :</p>
                <ul class="contrat-ul-marge">
                    <li>Effrayer les clients récalcitrants.</li>
                    <li>Distribuer des flyers dans l'au-delà.</li>
                </ul>
            </div>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 10 - PACTE AVEC LES DÉITÉS MONTAGNARDES</h2>
            <div class="clause">
                <p><strong>10.1. Sacrifice Symbolique</strong></p>
                <p>Si le Client est choisi comme « offrande » par les esprits locaux, l'Agence perçoit une commission de 15 % sur la valeur spirituelle de son âme.</p>
            </div>
            <div class="clause">
                <p><strong>10.2. Droit de Parrainage Cosmique</strong></p>
                <p>En cas de disparition, le Client sera rebaptisé « Saint Patron des Imprudents » et vendu comme médaillon porte-bonheur.</p>
            </div>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 11 - GESTION DES RÉCLAMATIONS PARANORMALES</h2>
            <div class="clause">
                <p><strong>11.1. Litiges avec l'Au-Delà</strong></p>
                <p>Toute réclamation posthume (via médium ou rêve) sera ignorée.</p>
            </div>
            <div class="clause">
                <p><strong>11.2. Clause de Non-Réapparition Gênante</strong></p>
                <p>Le Client ne hantera pas les locaux de l'Agence, sauf pour des apparitions marketing pré-approuvées.</p>
            </div>
        </div>

        <div class="profile-section">
            <h2>ARTICLE 12 - OPTION D'ACHAT SUR LES PROCHES</h2>
            <div class="clause">
                <p><strong>12.1. Extension aux Familles</strong></p>
                <p>Si le Client meurt de manière spectaculaire, l'Agence peut exiger un membre de sa famille en « remplacement moral ».</p>
            </div>
            <div class="clause">
                <p><strong>12.2. Droit de Préemption sur les Animaux</strong></p>
                <p>Les animaux de compagnie du Client deviendront mascottes de l'Agence (leur âme incluse).</p>
            </div>
        </div>

        <div class="acceptance-box">
            <form method="POST" action="" id="contractForm">
                <div class="checkbox-container">
                    <input type="checkbox" id="accept_terms" name="accept_terms" required>
                    <label for="accept_terms">J'accepte les CONDITIONS GÉNÉRALES DE SERVICE</label>
                </div>
            </form>
        </div>
    </div>
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
    <script>
        document.getElementById('accept_terms').addEventListener('change', function() {
            if(this.checked) {
                document.getElementById('contractForm').submit();
            }
        });
    </script>
</body>
</html>
