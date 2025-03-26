-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 22 mars 2025 à 16:46
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
-- Structure de la table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `image` varchar(255) DEFAULT 'img/default-activity.jpg',
  `rating` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `activities`
--

INSERT INTO `activities` (`id`, `user_id`, `title`, `location`, `date`, `image`, `rating`) VALUES
(1, 1, 'Randonnée Mont Blanc', 'Chamonix', '2024-03-15', 'img/default-activity.jpg', 5),
(2, 1, 'Escalade en salle', 'Paris', '2024-03-10', 'img/default-activity.jpg', 4),
(3, 1, 'VTT en forêt', 'Fontainebleau', '2024-03-05', 'img/default-activity.jpg', 4);

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
-- Structure de la table `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `step_id` int(11) DEFAULT NULL,
  `type` enum('activity','accommodation','food','transport','childcare','laundry') DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `price_per_person` decimal(10,2) DEFAULT NULL,
  `child_price` decimal(10,2) DEFAULT NULL,
  `min_persons` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `options`
--

INSERT INTO `options` (`id`, `step_id`, `type`, `title`, `description`, `default_value`, `price_per_person`, `child_price`, `min_persons`) VALUES
(1, 1, 'activity', 'Vidéo du saut', NULL, 'Non', 50.00, NULL, 1),
(2, 1, 'activity', 'Photos du saut', NULL, 'Non', 30.00, NULL, 1),
(3, 2, '', 'Location combinaison', NULL, 'Oui', 20.00, 15.00, 1),
(4, 2, 'activity', 'Guide privé', NULL, 'Non', 100.00, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `trip_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `card_number` varchar(255) DEFAULT NULL,
  `card_expiry` date DEFAULT NULL,
  `transaction_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `status` enum('upcoming','completed','cancelled') DEFAULT 'upcoming'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `title`, `date`, `time`, `location`, `status`) VALUES
(1, 1, 'Cours d\'escalade', '2024-04-01', '14:00:00', 'Paris', 'upcoming'),
(2, 1, 'Randonnée guidée', '2024-03-15', '09:00:00', 'Chamonix', 'completed'),
(3, 1, 'Location VTT', '2024-03-05', '10:00:00', 'Fontainebleau', 'cancelled');

-- --------------------------------------------------------

--
-- Structure de la table `steps`
--

CREATE TABLE `steps` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `arrival_date` datetime DEFAULT NULL,
  `departure_date` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `steps`
--

INSERT INTO `steps` (`id`, `trip_id`, `title`, `description`, `arrival_date`, `departure_date`, `duration`, `latitude`, `longitude`, `location_name`) VALUES
(1, 1, 'Briefing sécurité', NULL, '2024-07-01 09:00:00', '2024-07-01 10:00:00', 1, NULL, NULL, 'Centre de parachutisme'),
(2, 1, 'Saut en parachute', NULL, '2024-07-01 10:00:00', '2024-07-01 12:00:00', 2, NULL, NULL, 'Zone de saut'),
(3, 2, 'Formation plongée', NULL, '2024-07-15 09:00:00', '2024-07-15 12:00:00', 3, NULL, NULL, 'Centre de plongée'),
(4, 2, 'Plongée guidée', NULL, '2024-07-15 14:00:00', '2024-07-15 16:00:00', 2, NULL, NULL, 'Site de plongée');

-- --------------------------------------------------------

--
-- Structure de la table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','paid','modified') DEFAULT 'pending',
  `max_participants` int(11) DEFAULT 1,
  `difficulty_level` enum('easy','medium','hard','expert') DEFAULT 'medium',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trips`
--

INSERT INTO `trips` (`id`, `title`, `description`, `start_date`, `end_date`, `duration`, `location`, `total_price`, `status`, `max_participants`, `difficulty_level`, `created_at`) VALUES
(1, 'Parachutisme Alpes', 'Saut en parachute avec vue sur le Mont Blanc', NULL, NULL, NULL, NULL, 299.99, 'pending', 1, 'hard', '2025-03-22 15:45:03'),
(2, 'Plongée Méditerranée', 'Exploration des fonds marins', NULL, NULL, NULL, NULL, 149.99, 'pending', 1, 'medium', '2025-03-22 15:45:03'),
(3, 'Escalade Verdon', 'Grimpe dans les Gorges du Verdon', NULL, NULL, NULL, NULL, 199.99, 'pending', 1, 'expert', '2025-03-22 15:45:03'),
(4, 'Rafting Ardèche', 'Descente des rapides en groupe', NULL, NULL, NULL, NULL, 89.99, 'pending', 1, 'medium', '2025-03-22 15:45:03'),
(5, 'Canyoning Pyrénées', 'Aventure aquatique en montagne', NULL, NULL, NULL, NULL, 129.99, 'pending', 1, 'hard', '2025-03-22 15:45:03'),
(6, 'Wingsuit Chamonix', 'Vol en combinaison ailée', NULL, NULL, NULL, NULL, 399.99, 'pending', 1, 'expert', '2025-03-22 15:45:03'),
(7, 'Spéléologie Vercors', 'Exploration souterraine', NULL, NULL, NULL, NULL, 149.99, 'pending', 1, 'medium', '2025-03-22 15:45:03'),
(8, 'Ski Hors-Piste', 'Descente en poudreuse', NULL, NULL, NULL, NULL, 249.99, 'pending', 1, 'expert', '2025-03-22 15:45:03'),
(9, 'Via Ferrata Alpes', 'Parcours sécurisé en hauteur', NULL, NULL, NULL, NULL, 79.99, 'pending', 1, 'medium', '2025-03-22 15:45:03'),
(10, 'Surf Biarritz', 'Cours de surf océan', NULL, NULL, NULL, NULL, 69.99, 'pending', 1, 'easy', '2025-03-22 15:45:03'),
(11, 'Kitesurf Méditerranée', 'Initiation au kitesurf', NULL, NULL, NULL, NULL, 159.99, 'pending', 1, 'hard', '2025-03-22 15:45:03'),
(12, 'Alpinisme Mont Blanc', 'Ascension du plus haut sommet', NULL, NULL, NULL, NULL, 499.99, 'pending', 1, 'expert', '2025-03-22 15:45:03'),
(13, 'Kayak Mer Bretagne', 'Exploration côtière', NULL, NULL, NULL, NULL, 89.99, 'pending', 1, 'medium', '2025-03-22 15:45:03'),
(14, 'VTT Descente Alps', 'Parcours techniques en montagne', NULL, NULL, NULL, NULL, 129.99, 'pending', 1, 'hard', '2025-03-22 15:45:03'),
(15, 'Highline Verdon', 'Traversée sur sangle', NULL, NULL, NULL, NULL, 199.99, 'pending', 1, 'expert', '2025-03-22 15:45:03'),
(16, 'Parachutisme Alpes', 'Saut en parachute avec vue sur le Mont Blanc', '2024-07-01', '2024-07-01', 1, NULL, 299.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(17, 'Plongée Méditerranée', 'Exploration des fonds marins', '2024-07-15', '2024-07-16', 2, NULL, 149.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(18, 'Escalade Verdon', 'Grimpe dans les Gorges du Verdon', '2024-08-01', '2024-08-02', 2, NULL, 199.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(19, 'Rafting Ardèche', 'Descente des rapides en groupe', '2024-08-15', '2024-08-15', 1, NULL, 89.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(20, 'Canyoning Pyrénées', 'Aventure aquatique en montagne', '2024-09-01', '2024-09-01', 1, NULL, 129.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(21, 'Wingsuit Chamonix', 'Vol en combinaison ailée', '2024-09-15', '2024-09-15', 1, NULL, 399.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(22, 'Spéléologie Vercors', 'Exploration souterraine', '2024-10-01', '2024-10-02', 2, NULL, 149.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(23, 'Ski Hors-Piste', 'Descente en poudreuse', '2025-01-15', '2025-01-16', 2, NULL, 249.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(24, 'Via Ferrata Alpes', 'Parcours sécurisé en hauteur', '2024-07-20', '2024-07-20', 1, NULL, 79.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(25, 'Surf Biarritz', 'Cours de surf océan', '2024-08-10', '2024-08-11', 2, NULL, 69.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(26, 'Kitesurf Méditerranée', 'Initiation au kitesurf', '2024-08-20', '2024-08-21', 2, NULL, 159.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(27, 'Alpinisme Mont Blanc', 'Ascension du plus haut sommet', '2024-09-10', '2024-09-12', 3, NULL, 499.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(28, 'Kayak Mer Bretagne', 'Exploration côtière', '2024-07-25', '2024-07-25', 1, NULL, 89.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(29, 'VTT Descente Alps', 'Parcours techniques en montagne', '2024-08-05', '2024-08-05', 1, NULL, 129.99, 'pending', 1, 'medium', '2025-03-22 15:53:35'),
(30, 'Highline Verdon', 'Traversée sur sangle', '2024-08-25', '2024-08-25', 1, NULL, 199.99, 'pending', 1, 'medium', '2025-03-22 15:53:35');

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
(17, 'kahil78', 'kahil.mokhtari@gmail.com', '$2y$10$9UR6LUL3uevK7MlUGkJASeUvG49YeW8lfiLvidVs8U/lTHldgLlyO', 'user', 'kahil', 'mokhtari', '2025-03-22 16:18:33');

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

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `step_id` (`step_id`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `trip_id` (`trip_id`),
  ADD KEY `idx_transaction_date` (`transaction_date`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `steps`
--
ALTER TABLE `steps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Index pour la table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`);

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
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `steps`
--
ALTER TABLE `steps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`step_id`) REFERENCES `steps` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `steps`
--
ALTER TABLE `steps`
  ADD CONSTRAINT `steps_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

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

