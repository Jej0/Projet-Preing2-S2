-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 26 mars 2025 à 22:04
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet_naim`
--

-- --------------------------------------------------------

--
-- Structure de la table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `badges`
--

INSERT INTO `badges` (`id`, `title`, `description`, `icon`) VALUES
(1, 'Premier Pas', 'Première activité réalisée', 'fa-walking'),
(2, 'Aventurier', 'Participation à 5 activités', 'fa-mountain'),
(3, 'Expert', 'Obtention de 1000 points', 'fa-star'),
(4, 'Guide', 'A aidé d\'autres utilisateurs', 'fa-compass');

-- --------------------------------------------------------

--
-- Structure de la table `etapes`
--

CREATE TABLE `etapes` (
  `id_etape` int(11) NOT NULL,
  `id_voyage` int(11) DEFAULT NULL,
  `titre` varchar(255) DEFAULT NULL,
  `date_arrivee` date DEFAULT NULL,
  `date_depart` date DEFAULT NULL,
  `duree` varchar(50) DEFAULT NULL,
  `gps` varchar(50) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etapes`
--

INSERT INTO `etapes` (`id_etape`, `id_voyage`, `titre`, `date_arrivee`, `date_depart`, `duree`, `gps`, `ville`) VALUES
(1, 1, 'Arrivée à Katmandou', '2024-04-01', '2024-04-02', '1 jour', '27.7172, 85.3240', 'Katmandou'),
(2, 1, 'Acclimatation à Namche Bazaar', '2024-04-03', '2024-04-05', '3 jours', '27.8050, 86.7100', 'Namche Bazaar'),
(3, 1, 'Ascension vers le camp de base de l\'Everest', '2024-04-06', '2024-04-10', '5 jours', '27.9881, 86.9250', 'Camp de base de l\'Everest'),
(4, 1, 'Ascension vers le camp 4 (Col Sud)', '2024-04-11', '2024-04-15', '5 jours', '27.9800, 86.9200', 'Col Sud'),
(5, 1, 'Sommet de l\'Everest et retour', '2024-04-16', '2024-04-21', '6 jours', '27.9881, 86.9253', 'Sommet de l\'Everest'),
(6, 2, 'Arrivée à Chamonix', '2024-07-01', '2024-07-02', '1 jour', '45.9237, 6.8694', 'Chamonix'),
(7, 2, 'Vol en parapente au-dessus du Mont Blanc', '2024-07-03', '2024-07-04', '2 jours', '45.8326, 6.8652', 'Saint-Gervais-les-Bains'),
(8, 2, 'Descente en parapente vers Annecy', '2024-07-05', '2024-07-05', '1 jour', '45.8992, 6.1294', 'Annecy'),
(9, 3, 'Arrivée à Chamonix', '2024-08-01', '2024-08-02', '1 jour', '45.9237, 6.8694', 'Chamonix'),
(10, 3, 'Ascension vers le refuge du Goûter', '2024-08-03', '2024-08-04', '2 jours', '45.8556, 6.8275', 'Refuge du Goûter'),
(11, 3, 'Ascension finale vers le sommet du Mont Blanc', '2024-08-05', '2024-08-06', '2 jours', '45.8326, 6.8652', 'Sommet du Mont Blanc'),
(12, 3, 'Retour à Chamonix', '2024-08-07', '2024-08-07', '1 jour', '45.9237, 6.8694', 'Chamonix'),
(13, 4, 'Arrivée à Anchorage', '2025-03-10', '2025-03-11', '1 jour', '61.2181, -149.9003', 'Anchorage'),
(14, 4, 'Ski hors-piste dans les montagnes de Chugach', '2025-03-12', '2025-03-14', '3 jours', '61.1678, -149.0934', 'Montagnes de Chugach'),
(15, 4, 'Exploration des glaciers', '2025-03-15', '2025-03-16', '2 jours', '61.0450, -147.0934', 'Glacier de Matanuska'),
(16, 4, 'Retour à Anchorage', '2025-03-17', '2025-03-17', '1 jour', '61.2181, -149.9003', 'Anchorage'),
(17, 5, 'Arrivée à Anchorage', '2025-06-01', '2025-06-02', '1 jour', '61.2181, -149.9003', 'Anchorage'),
(18, 5, 'Transfert vers Talkeetna', '2025-06-03', '2025-06-03', '1 jour', '62.3236, -150.1094', 'Talkeetna'),
(19, 5, 'Vol vers le glacier de Kahiltna', '2025-06-04', '2025-06-04', '1 jour', '62.5000, -151.2000', 'Glacier de Kahiltna'),
(20, 5, 'Ascension vers le camp 3', '2025-06-05', '2025-06-08', '4 jours', '63.0694, -151.0072', 'Camp 3'),
(21, 5, 'Ascension finale vers le sommet du Denali', '2025-06-09', '2025-06-12', '4 jours', '63.0694, -151.0072', 'Sommet du Denali'),
(22, 5, 'Retour à Anchorage', '2025-06-13', '2025-06-15', '3 jours', '61.2181, -149.9003', 'Anchorage'),
(23, 6, 'Arrivée à Cortina d\'Ampezzo', '2024-09-01', '2024-09-02', '1 jour', '46.5367, 12.1386', 'Cortina d\'Ampezzo'),
(24, 6, 'Via Ferrata sur le Tre Cime di Lavaredo', '2024-09-03', '2024-09-04', '2 jours', '46.6189, 12.3025', 'Tre Cime di Lavaredo'),
(25, 6, 'Via Ferrata sur le Sentiero delle Bocchette', '2024-09-05', '2024-09-06', '2 jours', '46.2000, 10.8500', 'Sentiero delle Bocchette'),
(26, 6, 'Retour à Cortina d\'Ampezzo', '2024-09-07', '2024-09-07', '1 jour', '46.5367, 12.1386', 'Cortina d\'Ampezzo'),
(27, 7, 'Arrivée à Luchon', '2024-07-10', '2024-07-11', '1 jour', '42.7900, 0.5931', 'Bagnères-de-Luchon'),
(28, 7, 'Canyoning dans le canyon de la Frau', '2024-07-12', '2024-07-13', '2 jours', '42.8500, 0.7000', 'Canyon de la Frau'),
(29, 7, 'Retour à Luchon', '2024-07-14', '2024-07-14', '1 jour', '42.7900, 0.5931', 'Bagnères-de-Luchon'),
(30, 8, 'Arrivée à Mendoza', '2024-10-01', '2024-10-02', '1 jour', '-32.8908, -68.8272', 'Mendoza'),
(31, 8, 'Traversée des hauts plateaux', '2024-10-03', '2024-10-05', '3 jours', '-33.0000, -69.5000', 'Hauts plateaux des Andes'),
(32, 8, 'Descente vers le Chili', '2024-10-06', '2024-10-07', '2 jours', '-33.5000, -70.0000', 'Vallées chiliennes'),
(33, 8, 'Arrivée à Santiago', '2024-10-08', '2024-10-09', '2 jours', '-33.4489, -70.6693', 'Santiago'),
(34, 9, 'Arrivée à Castellane', '2024-08-01', '2024-08-02', '1 jour', '43.8467, 6.5131', 'Castellane'),
(35, 9, 'Rafting dans les gorges du Verdon', '2024-08-03', '2024-08-04', '2 jours', '43.7500, 6.3667', 'Gorges du Verdon'),
(36, 9, 'Retour à Castellane', '2024-08-05', '2024-08-05', '1 jour', '43.8467, 6.5131', 'Castellane'),
(37, 10, 'Arrivée à Arusha', '2024-11-01', '2024-11-02', '1 jour', '-3.3675, 36.6831', 'Arusha'),
(38, 10, 'Départ vers le camp de base de Machame', '2024-11-03', '2024-11-03', '1 jour', '-3.0667, 37.3500', 'Camp de base de Machame'),
(39, 10, 'Ascension vers le camp Shira', '2024-11-04', '2024-11-05', '2 jours', '-3.0500, 37.3000', 'Camp Shira'),
(40, 10, 'Ascension vers le camp Barranco', '2024-11-06', '2024-11-07', '2 jours', '-3.0333, 37.3167', 'Camp Barranco'),
(41, 10, 'Ascension finale vers le sommet du Kilimandjaro', '2024-11-08', '2024-11-09', '2 jours', '-3.0758, 37.3533', 'Sommet du Kilimandjaro'),
(42, 10, 'Retour à Arusha', '2024-11-10', '2024-11-10', '1 jour', '-3.3675, 36.6831', 'Arusha'),
(43, 11, 'Arrivée à Mendoza', '2025-01-10', '2025-01-11', '1 jour', '-32.8908, -68.8272', 'Mendoza'),
(44, 11, 'Transfert vers le camp de base de Plaza de Mulas', '2025-01-12', '2025-01-13', '2 jours', '-32.6531, -70.0114', 'Plaza de Mulas'),
(45, 11, 'Ascension vers le camp Canada', '2025-01-14', '2025-01-16', '3 jours', '-32.6500, -70.0100', 'Camp Canada'),
(46, 11, 'Ascension finale vers le sommet de l\'Aconcagua', '2025-01-17', '2025-01-20', '4 jours', '-32.6536, -70.0114', 'Sommet de l\'Aconcagua'),
(47, 11, 'Retour à Mendoza', '2025-01-21', '2025-01-25', '5 jours', '-32.8908, -68.8272', 'Mendoza'),
(48, 12, 'Arrivée à Bergen', '2025-02-15', '2025-02-16', '1 jour', '60.3913, 5.3221', 'Bergen'),
(49, 12, 'Ski de randonnée dans les fjords', '2025-02-17', '2025-02-19', '3 jours', '60.5000, 6.0000', 'Fjords norvégiens'),
(50, 12, 'Exploration des glaciers', '2025-02-20', '2025-02-21', '2 jours', '60.8000, 6.5000', 'Glaciers norvégiens'),
(51, 12, 'Retour à Bergen', '2025-02-22', '2025-02-22', '1 jour', '60.3913, 5.3221', 'Bergen'),
(52, 13, 'Arrivée à Chamonix', '2024-07-15', '2024-07-16', '1 jour', '45.9237, 6.8694', 'Chamonix'),
(53, 13, 'Ascension des Drus', '2024-07-17', '2024-07-19', '3 jours', '45.9333, 6.9500', 'Les Drus'),
(54, 13, 'Retour à Chamonix', '2024-07-20', '2024-07-20', '1 jour', '45.9237, 6.8694', 'Chamonix'),
(55, 14, 'Arrivée à Uyuni', '2024-09-10', '2024-09-11', '1 jour', '-20.4607, -66.8250', 'Uyuni'),
(56, 14, 'Traversée du désert de sel d\'Uyuni', '2024-09-12', '2024-09-14', '3 jours', '-20.1333, -67.5167', 'Désert de sel d\'Uyuni'),
(57, 14, 'Exploration des lagunes colorées', '2024-09-15', '2024-09-16', '2 jours', '-22.5000, -67.5000', 'Lagunes colorées'),
(58, 14, 'Retour à Uyuni', '2024-09-17', '2024-09-17', '1 jour', '-20.4607, -66.8250', 'Uyuni'),
(59, 15, 'Arrivée à Anchorage', '2025-05-01', '2025-05-02', '1 jour', '61.2181, -149.9003', 'Anchorage'),
(60, 15, 'Transfert vers Talkeetna', '2025-05-03', '2025-05-03', '1 jour', '62.3236, -150.1094', 'Talkeetna'),
(61, 15, 'Vol vers le glacier de Kahiltna', '2025-05-04', '2025-05-04', '1 jour', '62.5000, -151.2000', 'Glacier de Kahiltna'),
(62, 15, 'Ascension vers le camp 3', '2025-05-05', '2025-05-10', '6 jours', '63.0694, -151.0072', 'Camp 3'),
(63, 15, 'Ascension finale vers le sommet du Denali', '2025-05-11', '2025-05-15', '5 jours', '63.0694, -151.0072', 'Sommet du Denali'),
(64, 15, 'Retour à Anchorage', '2025-05-16', '2025-05-20', '5 jours', '61.2181, -149.9003', 'Anchorage');

-- --------------------------------------------------------

--
-- Structure de la table `options_etape`
--

CREATE TABLE `options_etape` (
  `id_option` int(11) NOT NULL,
  `id_etape` int(11) DEFAULT NULL,
  `type_option` enum('activite','hebergement','restauration','transport') DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `prix_par_personne` decimal(10,2) DEFAULT NULL,
  `nombre_personnes_max` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `options_etape`
--

INSERT INTO `options_etape` (`id_option`, `id_etape`, `type_option`, `nom`, `description`, `prix_par_personne`, `nombre_personnes_max`) VALUES
(1, 1, 'activite', 'Visite de Katmandou', 'Découverte des temples et de la culture népalaise', 30.00, 10),
(2, 1, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel confortable avec vue sur la ville', 80.00, 10),
(3, 1, 'restauration', 'Déner népalais', 'Déner avec des spécialités locales', 25.00, 10),
(4, 1, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport de Lukla', 20.00, 10),
(5, 2, 'activite', 'Randonnée d\'acclimatation', 'Randonnée pour s\'acclimater à l\'altitude', 50.00, 10),
(6, 2, 'hebergement', 'Lodge de montagne', 'Lodge confortable avec vue sur les montagnes', 60.00, 10),
(7, 2, 'restauration', 'Repas local', 'Repas avec des produits locaux', 20.00, 10),
(8, 2, 'transport', 'Transfert à pied', 'Randonnée vers Namche Bazaar', 0.00, 10),
(9, 3, 'activite', 'Vol d\'initiation en parapente', 'Découverte du parapente avec un instructeur', 100.00, 8),
(10, 3, 'activite', 'Randonnée dans les Aiguilles Rouges', 'Randonnée facile avec vue sur le Mont Blanc', 30.00, 10),
(11, 3, 'activite', 'Visite de Chamonix', 'Découverte de la ville et de ses boutiques', 20.00, 10),
(12, 3, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur les montagnes', 120.00, 10),
(13, 3, 'hebergement', 'Chalet de montagne', 'Chalet traditionnel avec cheminée', 80.00, 6),
(14, 3, 'hebergement', 'Auberge de jeunesse', 'Auberge conviviale pour les voyageurs', 40.00, 15),
(15, 3, 'restauration', 'Déner savoyard', 'Déner avec des spécialités locales (fondue, raclette)', 35.00, 10),
(16, 3, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 25.00, 10),
(17, 3, 'restauration', 'Buffet international', 'Buffet avec des plats du monde entier', 40.00, 10),
(18, 3, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport de Genève', 30.00, 10),
(19, 3, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 80.00, 4),
(20, 3, 'transport', 'Location de vélo électrique', 'Location pour explorer les environs', 50.00, 10),
(21, 4, 'activite', 'Vol en parapente au lever du soleil', 'Vol matinal avec vue spectaculaire sur le Mont Blanc', 150.00, 8),
(22, 4, 'activite', 'Randonnée glaciaire', 'Randonnée sur le glacier du Mont Blanc', 70.00, 10),
(23, 4, 'activite', 'Cours de photographie en vol', 'Apprenez à capturer les meilleurs clichés en parapente', 60.00, 6),
(24, 4, 'hebergement', 'Refuge de montagne', 'Refuge confortable avec vue sur les glaciers', 60.00, 10),
(25, 4, 'hebergement', 'Tente de luxe', 'Tente spacieuse avec lit confortable', 100.00, 4),
(26, 4, 'hebergement', 'Hôtel 3 étoiles', 'Hôtel économique avec petit-déjeuner inclus', 70.00, 10),
(27, 4, 'restauration', 'Repas énergétique', 'Repas riche en calories pour l\'effort', 30.00, 10),
(28, 4, 'restauration', 'Pique-nique en altitude', 'Pique-nique avec vue sur les montagnes', 25.00, 10),
(29, 4, 'restauration', 'Buffet de montagne', 'Buffet avec des plats chauds et froids', 40.00, 10),
(30, 4, 'transport', 'Transfert en téléphérique', 'Transfert vers le site de décollage', 20.00, 10),
(31, 4, 'transport', 'Transfert en hélicoptère', 'Transfert rapide en hélicoptère', 300.00, 4),
(32, 4, 'transport', 'Location de VTT', 'Location pour explorer les environs', 50.00, 10),
(33, 5, 'activite', 'Vol en parapente au-dessus du lac d\'Annecy', 'Vol avec vue imprenable sur le lac et les montagnes', 120.00, 8),
(34, 5, 'activite', 'Visite d\'Annecy', 'Découverte de la Venise des Alpes', 20.00, 10),
(35, 5, 'activite', 'Croisière sur le lac d\'Annecy', 'Balade en bateau sur le lac', 30.00, 10),
(36, 5, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur le lac', 150.00, 10),
(37, 5, 'hebergement', 'Chambre d\'hôtes', 'Chambre confortable avec petit-déjeuner inclus', 80.00, 6),
(38, 5, 'hebergement', 'Auberge de jeunesse', 'Auberge conviviale pour les voyageurs', 40.00, 15),
(39, 5, 'restauration', 'Repas au bord du lac', 'Déner avec vue sur le lac d\'Annecy', 40.00, 10),
(40, 5, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 25.00, 10),
(41, 5, 'restauration', 'Buffet international', 'Buffet avec des plats du monde entier', 50.00, 10),
(42, 5, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport de Genève', 30.00, 10),
(43, 5, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 80.00, 4),
(44, 5, 'transport', 'Location de vélo électrique', 'Location pour explorer les environs', 50.00, 10),
(45, 6, 'activite', 'Visite de Chamonix', 'Découverte de la ville et de ses boutiques', 20.00, 10),
(46, 6, 'activite', 'Randonnée d\'acclimatation', 'Randonnée facile pour s\'acclimater à l\'altitude', 30.00, 10),
(47, 6, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur les montagnes', 120.00, 10),
(48, 6, 'hebergement', 'Chalet de montagne', 'Chalet traditionnel avec cheminée', 80.00, 6),
(49, 6, 'restauration', 'Déner savoyard', 'Déner avec des spécialités locales (fondue, raclette)', 35.00, 10),
(50, 6, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 25.00, 10),
(51, 6, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport de Genève', 30.00, 10),
(52, 6, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 80.00, 4),
(53, 7, 'activite', 'Randonnée glaciaire', 'Randonnée sur le glacier du Mont Blanc', 50.00, 10),
(54, 7, 'hebergement', 'Refuge du Goûter', 'Refuge confortable avec vue sur les montagnes', 60.00, 10),
(55, 7, 'restauration', 'Repas énergétique', 'Repas riche en calories pour l\'effort', 30.00, 10),
(56, 7, 'transport', 'Transfert à pied', 'Randonnée vers le refuge', 0.00, 10),
(57, 9, 'activite', 'Célébration de l\'ascension', 'Déner de célébration avec l\'équipe', 50.00, 10),
(58, 9, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur les montagnes', 120.00, 10),
(59, 9, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 60.00, 10),
(60, 9, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport de Genève', 30.00, 10),
(61, 10, 'activite', 'Visite d\'Anchorage', 'Découverte de la ville et de ses environs', 30.00, 10),
(62, 10, 'activite', 'Randonnée en raquettes', 'Randonnée facile pour s\'acclimater au froid', 40.00, 10),
(63, 10, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel confortable avec vue sur la ville', 150.00, 10),
(64, 10, 'hebergement', 'Lodge de montagne', 'Lodge rustique avec ambiance chaleureuse', 100.00, 8),
(65, 10, 'restauration', 'Déner alaskien', 'Déner avec des spécialités locales (saumon, gibier)', 50.00, 10),
(66, 10, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 30.00, 10),
(67, 10, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport', 20.00, 10),
(68, 10, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 80.00, 4),
(69, 11, 'activite', 'Ski hors-piste', 'Ski sur des pentes raides et sauvages', 200.00, 8),
(70, 11, 'activite', 'Héliski', 'Ski après un largage en hélicoptère', 500.00, 6),
(71, 11, 'hebergement', 'Lodge de montagne', 'Lodge confortable avec vue sur les montagnes', 120.00, 10),
(72, 11, 'hebergement', 'Tente de luxe', 'Tente spacieuse avec lit confortable', 150.00, 4),
(73, 11, 'restauration', 'Repas énergétique', 'Repas riche en calories pour l\'effort', 40.00, 10),
(74, 11, 'restauration', 'Pique-nique en montagne', 'Pique-nique avec vue sur les montagnes', 30.00, 10),
(75, 11, 'transport', 'Transfert en hélicoptère', 'Transfert vers les sites de ski', 300.00, 6),
(76, 11, 'transport', 'Transfert en motoneige', 'Transfert rapide en motoneige', 100.00, 4),
(77, 12, 'activite', 'Randonnée sur glacier', 'Randonnée guidée sur le glacier de Matanuska', 80.00, 10),
(78, 12, 'activite', 'Exploration en motoneige', 'Exploration des glaciers en motoneige', 120.00, 6),
(79, 12, 'hebergement', 'Refuge de montagne', 'Refuge confortable avec vue sur les glaciers', 100.00, 10),
(80, 12, 'restauration', 'Repas local', 'Repas avec des produits locaux', 40.00, 10),
(81, 12, 'transport', 'Transfert en motoneige', 'Transfert vers les glaciers', 100.00, 6),
(82, 13, 'activite', 'Célébration de l\'aventure', 'Déner de célébration avec l\'équipe', 60.00, 10),
(83, 13, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur la ville', 150.00, 10),
(84, 13, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 70.00, 10),
(85, 13, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport', 20.00, 10),
(86, 14, 'activite', 'Visite d\'Anchorage', 'Découverte de la ville et de ses environs', 30.00, 10),
(87, 14, 'activite', 'Randonnée en forêt', 'Randonnée facile pour s\'acclimater au climat', 40.00, 10),
(88, 14, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel confortable avec vue sur la ville', 150.00, 10),
(89, 14, 'hebergement', 'Lodge de montagne', 'Lodge rustique avec ambiance chaleureuse', 100.00, 8),
(90, 14, 'restauration', 'Déner alaskien', 'Déner avec des spécialités locales (saumon, gibier)', 50.00, 10),
(91, 14, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 30.00, 10),
(92, 14, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport', 20.00, 10),
(93, 14, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 80.00, 4),
(94, 15, 'activite', 'Visite de Talkeetna', 'Découverte de ce village pittoresque', 20.00, 10),
(95, 15, 'hebergement', 'Lodge de montagne', 'Lodge confortable avec vue sur les montagnes', 100.00, 10),
(96, 15, 'restauration', 'Repas local', 'Repas avec des produits locaux', 30.00, 10),
(97, 15, 'transport', 'Transfert en bus', 'Transfert vers Talkeetna', 40.00, 10),
(98, 16, 'activite', 'Vol en avion de brousse', 'Vol spectaculaire vers le glacier de Kahiltna', 300.00, 6),
(99, 16, 'hebergement', 'Camp de base', 'Tentes sur le glacier', 0.00, 10),
(100, 16, 'restauration', 'Repas lyophilisés', 'Repas légers pour l\'expédition', 20.00, 10),
(101, 16, 'transport', 'Vol en avion de brousse', 'Transfert vers le glacier de Kahiltna', 300.00, 6),
(102, 19, 'activite', 'Célébration de l\'ascension', 'Déner de célébration avec l\'équipe', 60.00, 10),
(103, 19, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur la ville', 150.00, 10),
(104, 19, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 70.00, 10),
(105, 19, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport', 20.00, 10),
(106, 20, 'activite', 'Visite de Cortina d\'Ampezzo', 'Découverte de la ville et de ses environs', 30.00, 10),
(107, 20, 'activite', 'Randonnée d\'acclimatation', 'Randonnée facile pour s\'acclimater à l\'altitude', 40.00, 10),
(108, 20, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel confortable avec vue sur les montagnes', 150.00, 10),
(109, 20, 'hebergement', 'Chalet de montagne', 'Chalet traditionnel avec cheminée', 100.00, 6),
(110, 20, 'restauration', 'Déner italien', 'Déner avec des spécialités locales (pâtes, pizza)', 35.00, 10),
(111, 20, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 25.00, 10),
(112, 20, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport de Venise', 50.00, 10),
(113, 20, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 120.00, 4),
(114, 21, 'activite', 'Via Ferrata', 'Escalade sur les célèbres Tre Cime di Lavaredo', 80.00, 8),
(115, 21, 'activite', 'Randonnée autour des Tre Cime', 'Randonnée avec vue sur les célèbres pics', 40.00, 10),
(116, 21, 'hebergement', 'Refuge de montagne', 'Refuge confortable avec vue sur les montagnes', 60.00, 10),
(117, 21, 'hebergement', 'Tente de luxe', 'Tente spacieuse avec lit confortable', 100.00, 4),
(118, 21, 'restauration', 'Repas énergétique', 'Repas riche en calories pour l\'effort', 30.00, 10),
(119, 21, 'restauration', 'Pique-nique en montagne', 'Pique-nique avec vue sur les montagnes', 25.00, 10),
(120, 21, 'transport', 'Transfert en téléphérique', 'Transfert vers le site de via ferrata', 20.00, 10),
(121, 22, 'activite', 'Via Ferrata', 'Escalade sur le Sentiero delle Bocchette', 80.00, 8),
(122, 22, 'activite', 'Randonnée dans les Dolomites', 'Randonnée avec vue sur les montagnes', 40.00, 10),
(123, 22, 'hebergement', 'Refuge de montagne', 'Refuge confortable avec vue sur les montagnes', 60.00, 10),
(124, 22, 'restauration', 'Repas local', 'Repas avec des produits locaux', 30.00, 10),
(125, 22, 'transport', 'Transfert en téléphérique', 'Transfert vers le site de via ferrata', 20.00, 10),
(126, 23, 'activite', 'Célébration de l\'aventure', 'Déner de célébration avec l\'équipe', 60.00, 10),
(127, 23, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur les montagnes', 150.00, 10),
(128, 23, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 70.00, 10),
(129, 23, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport de Venise', 50.00, 10),
(130, 24, 'activite', 'Visite de Luchon', 'Découverte de la ville et de ses thermes', 20.00, 10),
(131, 24, 'activite', 'Randonnée dans les environs', 'Randonnée facile pour s\'acclimater', 30.00, 10),
(132, 24, 'hebergement', 'Hôtel 3 étoiles', 'Hôtel confortable avec vue sur les montagnes', 80.00, 10),
(133, 24, 'hebergement', 'Chalet de montagne', 'Chalet traditionnel avec cheminée', 100.00, 6),
(134, 24, 'restauration', 'Déner local', 'Déner avec des spécialités régionales', 35.00, 10),
(135, 24, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 25.00, 10),
(136, 24, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport de Toulouse', 40.00, 10),
(137, 24, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 100.00, 4),
(138, 25, 'activite', 'Canyoning', 'Descente du canyon de la Frau avec cascades et toboggans naturels', 80.00, 8),
(139, 25, 'activite', 'Randonnée autour du canyon', 'Randonnée avec vue sur les gorges', 30.00, 10),
(140, 25, 'hebergement', 'Refuge de montagne', 'Refuge confortable avec vue sur les montagnes', 60.00, 10),
(141, 25, 'hebergement', 'Bivouac sous tente', 'Bivouac en pleine nature', 20.00, 10),
(142, 25, 'restauration', 'Repas énergétique', 'Repas riche en calories pour l\'effort', 30.00, 10),
(143, 25, 'restauration', 'Pique-nique en montagne', 'Pique-nique avec vue sur les gorges', 25.00, 10),
(144, 25, 'transport', 'Transfert en 4x4', 'Transfert vers le site de canyoning', 50.00, 8),
(145, 26, 'activite', 'Célébration de l\'aventure', 'Déner de célébration avec l\'équipe', 50.00, 10),
(146, 26, 'hebergement', 'Hôtel 3 étoiles', 'Hôtel confortable avec vue sur les montagnes', 80.00, 10),
(147, 26, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 60.00, 10),
(148, 26, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport de Toulouse', 40.00, 10),
(149, 27, 'activite', 'Visite de Mendoza', 'Découverte de la ville et de ses vignobles', 30.00, 10),
(150, 27, 'activite', 'Randonnée dans les environs', 'Randonnée facile pour s\'acclimater', 40.00, 10),
(151, 27, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel confortable avec vue sur les montagnes', 120.00, 10),
(152, 27, 'hebergement', 'Chambre d\'hôtes', 'Chambre confortable avec petit-déjeuner inclus', 80.00, 6),
(153, 27, 'restauration', 'Déner argentin', 'Déner avec des spécialités locales (asado, empanadas)', 40.00, 10),
(154, 27, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 25.00, 10),
(155, 27, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport', 20.00, 10),
(156, 27, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 80.00, 4),
(157, 28, 'activite', 'VTT sur les hauts plateaux', 'Traversée des paysages désertiques des Andes', 100.00, 10),
(158, 28, 'activite', 'Randonnée pédestre', 'Randonnée pour explorer les environs', 40.00, 10),
(159, 28, 'hebergement', 'Camping en montagne', 'Camping sous tente avec vue sur les montagnes', 30.00, 10),
(160, 28, 'hebergement', 'Refuge de montagne', 'Refuge confortable avec vue sur les montagnes', 60.00, 10),
(161, 28, 'restauration', 'Repas énergétique', 'Repas riche en calories pour l\'effort', 30.00, 10),
(162, 28, 'restauration', 'Pique-nique en montagne', 'Pique-nique avec vue sur les montagnes', 25.00, 10),
(163, 28, 'transport', 'Transfert en 4x4', 'Transfert vers les hauts plateaux', 50.00, 8),
(164, 29, 'activite', 'VTT dans les vallées chiliennes', 'Descente à travers les vallées verdoyantes', 100.00, 10),
(165, 29, 'activite', 'Visite de villages locaux', 'Découverte de la culture chilienne', 30.00, 10),
(166, 29, 'hebergement', 'Lodge de montagne', 'Lodge confortable avec vue sur les vallées', 80.00, 10),
(167, 29, 'restauration', 'Repas local', 'Repas avec des spécialités chiliennes', 35.00, 10),
(168, 29, 'transport', 'Transfert en 4x4', 'Transfert vers les vallées chiliennes', 50.00, 8),
(169, 30, 'activite', 'Visite de Santiago', 'Découverte de la capitale chilienne', 30.00, 10),
(170, 30, 'activite', 'Célébration de l\'aventure', 'Déner de célébration avec l\'équipe', 50.00, 10),
(171, 30, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur la ville', 120.00, 10),
(172, 30, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 60.00, 10),
(173, 30, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport', 20.00, 10),
(174, 31, 'activite', 'Visite de Castellane', 'Découverte de la ville et de ses environs', 20.00, 10),
(175, 31, 'activite', 'Randonnée dans les gorges', 'Randonnée facile pour s\'acclimater', 30.00, 10),
(176, 31, 'hebergement', 'Hôtel 3 étoiles', 'Hôtel confortable avec vue sur les montagnes', 80.00, 10),
(177, 31, 'hebergement', 'Chambre d\'hôtes', 'Chambre confortable avec petit-déjeuner inclus', 60.00, 6),
(178, 31, 'restauration', 'Déner provençal', 'Déner avec des spécialités locales (ratatouille, tapenade)', 35.00, 10),
(179, 31, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 25.00, 10),
(180, 31, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport de Nice', 40.00, 10),
(181, 31, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 100.00, 4),
(182, 32, 'activite', 'Rafting', 'Descente des rapides des gorges du Verdon', 80.00, 8),
(183, 32, 'activite', 'Canyoning', 'Exploration des cascades et toboggans naturels', 60.00, 8),
(184, 32, 'hebergement', 'Camping en bord de rivière', 'Camping sous tente avec vue sur les gorges', 30.00, 10),
(185, 32, 'hebergement', 'Refuge de montagne', 'Refuge confortable avec vue sur les montagnes', 60.00, 10),
(186, 32, 'restauration', 'Repas énergétique', 'Repas riche en calories pour l\'effort', 30.00, 10),
(187, 32, 'restauration', 'Pique-nique en montagne', 'Pique-nique avec vue sur les gorges', 25.00, 10),
(188, 32, 'transport', 'Transfert en 4x4', 'Transfert vers les sites de rafting', 50.00, 8),
(189, 33, 'activite', 'Célébration de l\'aventure', 'Déner de célébration avec l\'équipe', 50.00, 10),
(190, 33, 'hebergement', 'Hôtel 3 étoiles', 'Hôtel confortable avec vue sur les montagnes', 80.00, 10),
(191, 33, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 60.00, 10),
(192, 33, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport de Nice', 40.00, 10),
(193, 34, 'activite', 'Visite d\'Arusha', 'Découverte de la ville et de ses environs', 30.00, 10),
(194, 34, 'activite', 'Randonnée dans les environs', 'Randonnée facile pour s\'acclimater', 40.00, 10),
(195, 34, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel confortable avec vue sur la ville', 120.00, 10),
(196, 34, 'hebergement', 'Lodge de montagne', 'Lodge rustique avec ambiance chaleureuse', 100.00, 8),
(197, 34, 'restauration', 'Déner tanzanien', 'Déner avec des spécialités locales (nyama choma, ugali)', 35.00, 10),
(198, 34, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 25.00, 10),
(199, 34, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport', 20.00, 10),
(200, 34, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 80.00, 4),
(201, 35, 'activite', 'Randonnée d\'acclimatation', 'Randonnée facile pour s\'acclimater à l\'altitude', 40.00, 10),
(202, 35, 'hebergement', 'Tentes de base', 'Tentes confortables avec vue sur les montagnes', 50.00, 10),
(203, 35, 'restauration', 'Repas énergétique', 'Repas riche en calories pour l\'effort', 30.00, 10),
(204, 35, 'transport', 'Transfert en 4x4', 'Transfert vers le camp de base', 50.00, 8),
(205, 39, 'activite', 'Célébration de l\'ascension', 'Déner de célébration avec l\'équipe', 60.00, 10),
(206, 39, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur la ville', 120.00, 10),
(207, 39, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 70.00, 10),
(208, 39, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport', 20.00, 10),
(209, 40, 'activite', 'Visite de Mendoza', 'Découverte de la ville et de ses vignobles', 30.00, 10),
(210, 40, 'activite', 'Randonnée dans les environs', 'Randonnée facile pour s\'acclimater', 40.00, 10),
(211, 40, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel confortable avec vue sur les montagnes', 120.00, 10),
(212, 40, 'hebergement', 'Chambre d\'hôtes', 'Chambre confortable avec petit-déjeuner inclus', 80.00, 6),
(213, 40, 'restauration', 'Déner argentin', 'Déner avec des spécialités locales (asado, empanadas)', 40.00, 10),
(214, 40, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 25.00, 10),
(215, 40, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport', 20.00, 10),
(216, 40, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 80.00, 4),
(217, 41, 'activite', 'Randonnée d\'acclimatation', 'Randonnée facile pour s\'acclimater à l\'altitude', 40.00, 10),
(218, 41, 'hebergement', 'Tentes de base', 'Tentes confortables avec vue sur les montagnes', 50.00, 10),
(219, 41, 'restauration', 'Repas énergétique', 'Repas riche en calories pour l\'effort', 30.00, 10),
(220, 41, 'transport', 'Transfert en 4x4', 'Transfert vers le camp de base', 50.00, 8),
(221, 44, 'activite', 'Célébration de l\'ascension', 'Déner de célébration avec l\'équipe', 60.00, 10),
(222, 44, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur la ville', 120.00, 10),
(223, 44, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 70.00, 10),
(224, 44, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport', 20.00, 10),
(225, 45, 'activite', 'Visite de Bergen', 'Découverte de la ville et de ses environs', 30.00, 10),
(226, 45, 'activite', 'Randonnée dans les environs', 'Randonnée facile pour s\'acclimater', 40.00, 10),
(227, 45, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel confortable avec vue sur la ville', 150.00, 10),
(228, 45, 'hebergement', 'Chambre d\'hôtes', 'Chambre confortable avec petit-déjeuner inclus', 100.00, 6),
(229, 45, 'restauration', 'Déner norvégien', 'Déner avec des spécialités locales (saumon, poisson séché)', 50.00, 10),
(230, 45, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 30.00, 10),
(231, 45, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport', 20.00, 10),
(232, 45, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 80.00, 4),
(233, 46, 'activite', 'Ski de randonnée', 'Exploration des fjords en ski de randonnée', 100.00, 8),
(234, 46, 'activite', 'Randonnée en raquettes', 'Randonnée dans les environs des fjords', 40.00, 10),
(235, 46, 'hebergement', 'Refuge de montagne', 'Refuge confortable avec vue sur les fjords', 80.00, 10),
(236, 46, 'hebergement', 'Bivouac sous tente', 'Bivouac en pleine nature', 30.00, 10),
(237, 46, 'restauration', 'Repas énergétique', 'Repas riche en calories pour l\'effort', 40.00, 10),
(238, 46, 'restauration', 'Pique-nique en montagne', 'Pique-nique avec vue sur les fjords', 25.00, 10),
(239, 46, 'transport', 'Transfert en motoneige', 'Transfert vers les sites de ski', 50.00, 8),
(240, 47, 'activite', 'Randonnée sur glacier', 'Randonnée guidée sur les glaciers', 80.00, 10),
(241, 47, 'activite', 'Exploration en motoneige', 'Exploration des glaciers en motoneige', 120.00, 6),
(242, 47, 'hebergement', 'Refuge de montagne', 'Refuge confortable avec vue sur les glaciers', 100.00, 10),
(243, 47, 'restauration', 'Repas local', 'Repas avec des produits locaux', 40.00, 10),
(244, 47, 'transport', 'Transfert en motoneige', 'Transfert vers les glaciers', 50.00, 8),
(245, 48, 'activite', 'Célébration de l\'aventure', 'Déner de célébration avec l\'équipe', 60.00, 10),
(246, 48, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur la ville', 150.00, 10),
(247, 48, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 70.00, 10),
(248, 48, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport', 20.00, 10),
(249, 49, 'activite', 'Visite de Chamonix', 'Découverte de la ville et de ses environs', 20.00, 10),
(250, 49, 'activite', 'Randonnée d\'acclimatation', 'Randonnée facile pour s\'acclimater à l\'altitude', 30.00, 10),
(251, 49, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel confortable avec vue sur les montagnes', 120.00, 10),
(252, 49, 'hebergement', 'Chalet de montagne', 'Chalet traditionnel avec cheminée', 100.00, 6),
(253, 49, 'restauration', 'Déner savoyard', 'Déner avec des spécialités locales (fondue, raclette)', 35.00, 10),
(254, 49, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 25.00, 10),
(255, 49, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport de Genève', 30.00, 10),
(256, 49, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 80.00, 4),
(257, 50, 'activite', 'Escalade des Drus', 'Ascension technique de la face ouest des Drus', 200.00, 6),
(258, 50, 'activite', 'Bivouac en paroi', 'Nuit en paroi avec vue imprenable sur les Alpes', 100.00, 6),
(259, 50, 'hebergement', 'Bivouac en paroi', 'Nuit en paroi avec équipement technique', 100.00, 6),
(260, 50, 'restauration', 'Repas lyophilisés', 'Repas légers pour l\'ascension', 20.00, 6),
(261, 50, 'transport', 'Transfert en téléphérique', 'Transfert vers le site d\'escalade', 20.00, 10),
(262, 51, 'activite', 'Célébration de l\'ascension', 'Déner de célébration avec l\'équipe', 60.00, 10),
(263, 51, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur les montagnes', 120.00, 10),
(264, 51, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 70.00, 10),
(265, 51, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport de Genève', 30.00, 10),
(266, 52, 'activite', 'Visite d\'Uyuni', 'Découverte de la ville et de ses environs', 20.00, 10),
(267, 52, 'activite', 'Randonnée dans les environs', 'Randonnée facile pour s\'acclimater', 30.00, 10),
(268, 52, 'hebergement', 'Hôtel 3 étoiles', 'Hôtel confortable avec vue sur la ville', 80.00, 10),
(269, 52, 'hebergement', 'Auberge de jeunesse', 'Auberge conviviale pour les voyageurs', 40.00, 15),
(270, 52, 'restauration', 'Déner bolivien', 'Déner avec des spécialités locales (salteñas, quinoa)', 25.00, 10),
(271, 52, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 20.00, 10),
(272, 52, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport', 15.00, 10),
(273, 52, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 60.00, 4),
(274, 53, 'activite', 'VTT dans le désert de sel', 'Traversée du désert de sel en VTT', 100.00, 10),
(275, 53, 'activite', 'Visite de l\'île Incahuasi', 'Découverte de l\'île et de ses cactus géants', 30.00, 10),
(276, 53, 'hebergement', 'Hôtel de sel', 'Hôtel construit en sel avec vue sur le désert', 80.00, 10),
(277, 53, 'hebergement', 'Camping sous tente', 'Camping avec vue sur le désert de sel', 30.00, 10),
(278, 53, 'restauration', 'Repas local', 'Repas avec des produits locaux', 25.00, 10),
(279, 53, 'restauration', 'Pique-nique dans le désert', 'Pique-nique avec vue sur le désert de sel', 20.00, 10),
(280, 53, 'transport', 'Transfert en 4x4', 'Transfert vers les sites de VTT', 50.00, 8),
(281, 54, 'activite', 'VTT autour des lagunes', 'Exploration des lagunes colorées en VTT', 80.00, 10),
(282, 54, 'activite', 'Observation des flamants roses', 'Découverte des flamants roses dans leur habitat naturel', 30.00, 10),
(283, 54, 'hebergement', 'Refuge de montagne', 'Refuge confortable avec vue sur les lagunes', 60.00, 10),
(284, 54, 'restauration', 'Repas local', 'Repas avec des produits locaux', 25.00, 10),
(285, 54, 'transport', 'Transfert en 4x4', 'Transfert vers les lagunes', 50.00, 8),
(286, 55, 'activite', 'Célébration de l\'aventure', 'Déner de célébration avec l\'équipe', 50.00, 10),
(287, 55, 'hebergement', 'Hôtel 3 étoiles', 'Hôtel confortable avec vue sur la ville', 80.00, 10),
(288, 55, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 60.00, 10),
(289, 55, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport', 15.00, 10),
(290, 56, 'activite', 'Visite d\'Anchorage', 'Découverte de la ville et de ses environs', 30.00, 10),
(291, 56, 'activite', 'Randonnée dans les environs', 'Randonnée facile pour s\'acclimater', 40.00, 10),
(292, 56, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel confortable avec vue sur la ville', 150.00, 10),
(293, 56, 'hebergement', 'Lodge de montagne', 'Lodge rustique avec ambiance chaleureuse', 100.00, 8),
(294, 56, 'restauration', 'Déner alaskien', 'Déner avec des spécialités locales (saumon, gibier)', 50.00, 10),
(295, 56, 'restauration', 'Repas léger', 'Repas léger avec des produits locaux', 30.00, 10),
(296, 56, 'transport', 'Transfert en bus', 'Transfert depuis l\'aéroport', 20.00, 10),
(297, 56, 'transport', 'Transfert privé en voiture', 'Transfert confortable en voiture privée', 80.00, 4),
(298, 57, 'activite', 'Visite de Talkeetna', 'Découverte de ce village pittoresque', 20.00, 10),
(299, 57, 'hebergement', 'Lodge de montagne', 'Lodge confortable avec vue sur les montagnes', 100.00, 10),
(300, 57, 'restauration', 'Repas local', 'Repas avec des produits locaux', 30.00, 10),
(301, 57, 'transport', 'Transfert en bus', 'Transfert vers Talkeetna', 40.00, 10),
(302, 58, 'activite', 'Vol en avion de brousse', 'Vol spectaculaire vers le glacier de Kahiltna', 300.00, 6),
(303, 58, 'hebergement', 'Camp de base', 'Tentes sur le glacier', 0.00, 10),
(304, 58, 'restauration', 'Repas lyophilisés', 'Repas légers pour l\'expédition', 20.00, 10),
(305, 58, 'transport', 'Vol en avion de brousse', 'Transfert vers le glacier de Kahiltna', 300.00, 6),
(306, 61, 'activite', 'Célébration de l\'ascension', 'Déner de célébration avec l\'équipe', 60.00, 10),
(307, 61, 'hebergement', 'Hôtel 4 étoiles', 'Hôtel de luxe avec vue sur la ville', 150.00, 10),
(308, 61, 'restauration', 'Repas gastronomique', 'Repas de célébration avec des spécialités locales', 70.00, 10),
(309, 61, 'transport', 'Transfert en bus', 'Transfert vers l\'aéroport', 20.00, 10);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` int(11) NOT NULL,
  `id_voyage` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `id_voyage`) VALUES
(103, 5),
(104, 9);

-- --------------------------------------------------------

--
-- Structure de la table `reservations_json`
--

CREATE TABLE `reservations_json` (
  `id_reservation` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_voyage` int(11) DEFAULT NULL,
  `date_reservation` date DEFAULT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `prix_total` decimal(10,2) DEFAULT NULL,
  `paiement` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations_json`
--

INSERT INTO `reservations_json` (`id_reservation`, `id_utilisateur`, `id_voyage`, `date_reservation`, `options`, `prix_total`, `paiement`) VALUES
(101, 2, 2, '2025-03-25', '{\n        \"etape_1\": {\"activite\": 3, \"hebergement\": null, \"restauration\": null, \"transport\": null},\n        \"etape_2\": {\"activite\": null, \"hebergement\": null, \"restauration\": null, \"transport\": null},\n        \"etape_3\": {\"activite\": null, \"hebergement\": null, \"restauration\": null, \"transport\": null}\n    }', 2500.00, 1),
(103, 2, 5, '2025-03-26', '{\r\n        \"etape_1\": {\"activite\": null, \"hebergement\": null, \"restauration\": null, \"transport\": 8},\r\n        \"etape_2\": {\"activite\": null, \"hebergement\": null, \"restauration\": null, \"transport\": 12},\r\n        \"etape_3\": {\"activite\": null, \"hebergement\": null, \"restauration\": null, \"transport\": null},\r\n        \"etape_4\": {\"activite\": null, \"hebergement\": null, \"restauration\": null, \"transport\": null},\r\n        \"etape_5\": {\"activite\": null, \"hebergement\": null, \"restauration\": null, \"transport\": null},\r\n        \"etape_6\": {\"activite\": null, \"hebergement\": null, \"restauration\": null, \"transport\": null}\r\n    }', 8120.00, 0);

-- --------------------------------------------------------

--
-- Structure de la table `specificites`
--

CREATE TABLE `specificites` (
  `id_specificite` int(11) NOT NULL,
  `id_voyage` int(11) DEFAULT NULL,
  `specificite` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `specificites`
--

INSERT INTO `specificites` (`id_specificite`, `id_voyage`, `specificite`) VALUES
(1, 1, 'Neige'),
(2, 1, 'Haute altitude'),
(3, 2, 'Montagne'),
(4, 2, 'Parapente'),
(5, 3, 'Montagne'),
(6, 3, 'Glacier'),
(7, 4, 'Neige'),
(8, 4, 'Glacier'),
(9, 4, 'Ski extrême'),
(10, 5, 'Neige'),
(11, 5, 'Glacier'),
(12, 5, 'Haute altitude'),
(13, 6, 'Montagne'),
(14, 6, 'Via Ferrata'),
(15, 6, 'Escalade'),
(16, 7, 'Montagne'),
(17, 7, 'Canyoning'),
(18, 7, 'Eau vive'),
(19, 8, 'Montagne'),
(20, 8, 'VTT'),
(21, 8, 'Traversée internationale'),
(22, 9, 'Montagne'),
(23, 9, 'Eau vive'),
(24, 9, 'Rafting'),
(25, 10, 'Montagne'),
(26, 10, 'Haute altitude'),
(27, 10, 'Trekking'),
(28, 11, 'Montagne'),
(29, 11, 'Haute altitude'),
(30, 11, 'Alpinisme'),
(31, 12, 'Montagne'),
(32, 12, 'Glacier'),
(33, 12, 'Ski de randonnée'),
(34, 13, 'Montagne'),
(35, 13, 'Escalade technique'),
(36, 13, 'Bivouac en paroi'),
(37, 14, 'Désert de sel'),
(38, 14, 'VTT'),
(39, 14, 'Lagunes colorées'),
(40, 15, 'Montagne'),
(41, 15, 'Glacier'),
(42, 15, 'Haute altitude');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `registration_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`, `role`, `firstname`, `lastname`, `registration_date`) VALUES
(1, 'admin1', '', '$2y$10$YourHashHere', 'admin', 'Admin', 'One', '2025-03-22 15:45:03'),
(2, 'admin2', '', '$2y$10$YourHashHere', 'admin', 'Admin', 'Two', '2025-03-22 15:45:03'),
(3, 'user1', '', '$2y$10$YourHashHere', 'user', 'User', 'One', '2025-03-22 15:45:03'),
(4, 'user2', '', '$2y$10$YourHashHere', 'user', 'User', 'Two', '2025-03-22 15:45:03'),
(5, 'user3', '', '$2y$10$YourHashHere', 'user', 'User', 'Three', '2025-03-22 15:45:03'),
(11, 'admin_john', '', '$2y$10$8Qu8Hh6EYiYqvZGHBuuH8.PWqz.BI9tDH8Fz.FWGD5GHKoBrwq9Hy', 'admin', 'John', 'Admin', '2025-03-22 15:53:35'),
(12, 'admin_jane', '', '$2y$10$8Qu8Hh6EYiYqvZGHBuuH8.PWqz.BI9tDH8Fz.FWGD5GHKoBrwq9Hy', 'admin', 'Jane', 'Admin', '2025-03-22 15:53:35'),
(13, 'alice_user', '', '$2y$10$8Qu8Hh6EYiYqvZGHBuuH8.PWqz.BI9tDH8Fz.FWGD5GHKoBrwq9Hy', 'user', 'Alice', 'User', '2025-03-22 15:53:35'),
(14, 'bob_user', '', '$2y$10$8Qu8Hh6EYiYqvZGHBuuH8.PWqz.BI9tDH8Fz.FWGD5GHKoBrwq9Hy', 'user', 'Bob', 'User', '2025-03-22 15:53:35'),
(15, 'charlie_user', '', '$2y$10$8Qu8Hh6EYiYqvZGHBuuH8.PWqz.BI9tDH8Fz.FWGD5GHKoBrwq9Hy', 'user', 'Charlie', 'User', '2025-03-22 15:53:35'),
(16, 'kahi', 'kaka1@gmail.com', '$2y$10$cy4BbXm9r80MBgSavuKvyugquK8Uo/EEnqhG7w/BuAWxdIQ6.BeVG', 'user', 'brahim', 'mokhtari', '2025-03-22 16:15:30'),
(17, 'kahil78', 'kahil.mokhtari@gmail.com', '$2y$10$9UR6LUL3uevK7MlUGkJASeUvG49YeW8lfiLvidVs8U/lTHldgLlyO', 'user', 'kahil', 'mokhtari', '2025-03-22 16:18:33'),
(18, 'press', 'azgg@gmail.com', '$2y$10$vHoLjBE8wf3yZTqckFCuf.1bCrc2jxP1F0mCsK15HIfPuNV85a.Dy', 'banned', 'Ales', 'azffzf', '2025-03-26 20:13:43');
(18, 'bbb', 'bbb@gmail.com', '$2y$10$L8QopaNKH3lR3Nva/MB6Lu0v51434gVXSwneNzGJk.DuFsUXcfXhW', 'admin', 'bb', 'bbbb', '2025-03-22 16:18:33');
-- --------------------------------------------------------

--
-- Structure de la table `user_badges`
--

CREATE TABLE `user_badges` (
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `date_obtained` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_badges`
--

INSERT INTO `user_badges` (`user_id`, `badge_id`, `date_obtained`) VALUES
(1, 1, '2025-03-22 16:21:07'),
(1, 2, '2025-03-22 16:21:07');

-- --------------------------------------------------------

--
-- Structure de la table `voyages`
--

CREATE TABLE `voyages` (
  `id_voyage` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `note_moyenne` decimal(3,1) DEFAULT NULL,
  `nb_avis` int(11) DEFAULT NULL,
  `nb_etape` int(11) DEFAULT NULL,
  `contrat` tinyint(1) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `duree` varchar(50) DEFAULT NULL,
  `prix_total` decimal(10,2) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `personnes_max` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `voyages`
--

INSERT INTO `voyages` (`id_voyage`, `titre`, `description`, `note_moyenne`, `nb_avis`, `nb_etape`, `contrat`, `date_debut`, `date_fin`, `duree`, `prix_total`, `statut`, `personnes_max`) VALUES
(1, 'Ascension de l\'Everest', 'L\'ascension ultime pour les amateurs de défis extrêmes. Atteignez le toit du monde avec des guides expérimentés.', 4.9, 10, 5, 1, '2024-04-01', '2024-04-21', '21 jours', 10000.00, 'disponible', 10),
(2, 'Traversée des Alpes en parapente', 'Parcourez les Alpes en parapente, avec des décollages depuis les plus hauts sommets.', 4.8, 10, 3, 0, '2024-07-01', '2024-07-05', '5 jours', 2500.00, 'disponible', 10),
(3, 'Expédition au Mont Blanc', 'Gravissez le plus haut sommet d\'Europe avec des guides certifiés. Une aventure inoubliable dans les Alpes.', 4.7, 10, 4, 1, '2024-08-01', '2024-08-07', '7 jours', 3000.00, 'disponible', 10),
(4, 'Ski extrême en Alaska', 'Skiez sur les pentes les plus raides et les plus sauvages de l\'Alaska. Une aventure inoubliable pour les amateurs de ski extrême.', 4.6, 10, 4, 1, '2025-03-10', '2025-03-17', '7 jours', 5000.00, 'disponible', 10),
(5, 'Expédition au Denali', 'Gravissez le plus haut sommet d\'Amérique du Nord dans des conditions extrêmes. Une aventure inoubliable pour les alpinistes expérimentés.', 4.8, 10, 6, 1, '2025-06-01', '2025-06-15', '15 jours', 8000.00, 'disponible', 10),
(6, 'Via Ferrata dans les Dolomites', 'Explorez les via ferrata spectaculaires des Dolomites italiennes. Une aventure inoubliable pour les amateurs d\'escalade et de randonnée.', 4.7, 10, 4, 1, '2024-09-01', '2024-09-07', '7 jours', 3500.00, 'disponible', 10),
(7, 'Canyoning dans les Pyrénées', 'Explorez les gorges et cascades des Pyrénées en canyoning. Une aventure aquatique et sportive dans des paysages époustouflants.', 4.6, 10, 3, 0, '2024-07-10', '2024-07-14', '5 jours', 2000.00, 'disponible', 10),
(8, 'Traversée des Andes à VTT', 'Parcourez les Andes en VTT, des hauts plateaux aux vallées profondes. Une aventure sportive et culturelle dans des paysages à couper le souffle.', 4.5, 10, 5, 0, '2024-10-01', '2024-10-10', '10 jours', 3000.00, 'disponible', 10),
(9, 'Rafting extrême dans les gorges du Verdon', 'Affrontez les rapides des gorges du Verdon en rafting. Une aventure aquatique et sportive dans l\'un des plus beaux canyons d\'Europe.', 4.7, 10, 3, 0, '2024-08-01', '2024-08-05', '5 jours', 1800.00, 'disponible', 10),
(10, 'Expédition au Kilimandjaro', 'Gravissez le plus haut sommet d\'Afrique à travers la voie Machame. Une aventure inoubliable pour les amateurs de trekking et de défis en haute altitude.', 4.8, 10, 6, 0, '2024-11-01', '2024-11-10', '10 jours', 4000.00, 'disponible', 10),
(11, 'Expédition au Aconcagua', 'Gravissez le plus haut sommet d\'Amérique du Sud, l\'Aconcagua, dans les Andes argentines. Une aventure extrême pour les alpinistes chevronnés.', 4.7, 10, 5, 1, '2025-01-10', '2025-01-25', '16 jours', 5000.00, 'disponible', 10),
(12, 'Ski de randonnée en Norvège', 'Explorez les fjords norvégiens en ski de randonnée. Une aventure inoubliable dans des paysages enneigés et sauvages.', 4.6, 10, 4, 0, '2025-02-15', '2025-02-22', '8 jours', 3500.00, 'disponible', 10),
(13, 'Escalade des Drus (Mont Blanc)', 'Défi technique sur l\'une des faces les plus célèbres des Alpes. Une aventure extrême pour les alpinistes chevronnés.', 4.8, 10, 3, 1, '2024-07-15', '2024-07-20', '6 jours', 2500.00, 'disponible', 6),
(14, 'Traversée du désert de sel en Bolivie', 'Parcourez le désert de sel d\'Uyuni et les montagnes environnantes en VTT. Une aventure inoubliable dans un paysage lunaire.', 4.6, 10, 4, 0, '2024-09-10', '2024-09-17', '8 jours', 2200.00, 'disponible', 10),
(15, 'Expédition au Denali (Alaska)', 'Gravissez le plus haut sommet d\'Amérique du Nord dans des conditions extrêmes. Une aventure inoubliable pour les alpinistes chevronnés.', 4.9, 10, 6, 1, '2025-05-01', '2025-05-20', '20 jours', 8000.00, 'disponible', 10);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD PRIMARY KEY (`id_etape`),
  ADD KEY `id_voyage` (`id_voyage`);

--
-- Index pour la table `options_etape`
--
ALTER TABLE `options_etape`
  ADD PRIMARY KEY (`id_option`),
  ADD KEY `id_etape` (`id_etape`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_voyage` (`id_voyage`);

--
-- Index pour la table `reservations_json`
--
ALTER TABLE `reservations_json`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_voyage` (`id_voyage`);

--
-- Index pour la table `specificites`
--
ALTER TABLE `specificites`
  ADD PRIMARY KEY (`id_specificite`),
  ADD KEY `id_voyage` (`id_voyage`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `idx_role` (`role`);

--
-- Index pour la table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`user_id`,`badge_id`),
  ADD KEY `badge_id` (`badge_id`);

--
-- Index pour la table `voyages`
--
ALTER TABLE `voyages`
  ADD PRIMARY KEY (`id_voyage`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `etapes`
--
ALTER TABLE `etapes`
  MODIFY `id_etape` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT pour la table `options_etape`
--
ALTER TABLE `options_etape`
  MODIFY `id_option` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=310;

--
-- AUTO_INCREMENT pour la table `specificites`
--
ALTER TABLE `specificites`
  MODIFY `id_specificite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etapes`
--
ALTER TABLE `etapes`
  ADD CONSTRAINT `etapes_ibfk_1` FOREIGN KEY (`id_voyage`) REFERENCES `voyages` (`id_voyage`);

--
-- Contraintes pour la table `options_etape`
--
ALTER TABLE `options_etape`
  ADD CONSTRAINT `options_etape_ibfk_1` FOREIGN KEY (`id_etape`) REFERENCES `etapes` (`id_etape`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_voyage`) REFERENCES `voyages` (`id_voyage`);

--
-- Contraintes pour la table `reservations_json`
--
ALTER TABLE `reservations_json`
  ADD CONSTRAINT `reservations_json_ibfk_1` FOREIGN KEY (`id_voyage`) REFERENCES `voyages` (`id_voyage`);

--
-- Contraintes pour la table `specificites`
--
ALTER TABLE `specificites`
  ADD CONSTRAINT `specificites_ibfk_1` FOREIGN KEY (`id_voyage`) REFERENCES `voyages` (`id_voyage`);

--
-- Contraintes pour la table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`);
COMMIT;


ALTER TABLE reservations_json 
MODIFY id_reservation int(11) NOT NULL AUTO_INCREMENT
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
