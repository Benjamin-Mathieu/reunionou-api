-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 29 mars 2021 à 09:22
-- Version du serveur :  8.0.22
-- Version de PHP : 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `atelier2`
--

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

CREATE TABLE `event` (
  `id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` int NOT NULL,
  `token` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `adress` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `public` tinyint(1) NOT NULL,
  `main_event` tinyint(1) NOT NULL,
  `event_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `event`
--

INSERT INTO `event` (`id`, `title`, `description`, `date`, `created_at`, `updated_at`, `deleted_at`, `user_id`, `token`, `adress`, `public`, `main_event`, `event_id`) VALUES
(7, 'lanzkf', 'kakdkx', '2021-03-26 10:48:00', '2021-03-26 08:48:47', '2021-03-26 08:48:47', NULL, 1, '67b85c2955e37d4e5845acd43c870e633277b3159a8a9faba871c699e3727f78', 'kadkfk', 1, 0, NULL),
(8, 'kzkf', 'jakf', '2021-03-26 12:00:00', '2021-03-26 10:00:21', '2021-03-26 10:00:21', NULL, 1, '465842698db7459b940428b7f0c025198c00b7fbab139e5b6b0d8b8211773a3c', 'sjjf', 0, 0, NULL),
(9, 'jsjf', 'zjkdkf', '2021-03-31 14:55:00', '2021-03-26 12:55:56', '2021-03-26 12:55:56', NULL, 1, '015e9481af433984bd5a49fb70ad821c7368e00efe181712515d1c64d3f7298a', 'kzkkd', 0, 0, NULL),
(10, 'jzfn', 'àâáãæéèêëēěîïôœòóőñ\"\" \'', '2021-03-26 15:05:00', '2021-03-26 13:06:35', '2021-03-26 13:06:35', NULL, 1, 'abe4aeaced36856dcad259378eb7ad4dce9cb96309eb1c37b51eb79632ecdcef', 'jzjfjf', 1, 0, NULL),
(11, 'jsjdjf', 'pd d\'alex', '2021-03-26 15:07:00', '2021-03-26 13:08:26', '2021-03-26 13:08:26', NULL, 1, '0a02435a3014fe7b849cf31d8351fe7b7913438a07c5a0b9b09ea9abd37261f5', 'jsjfn', 0, 0, NULL),
(12, 'soirée à Nancy', 'jzjff', '2021-03-26 20:50:00', '2021-03-26 18:50:38', '2021-03-26 18:50:38', NULL, 1, 'ad3767507413651b06c9db8fde4b63c38397a1dad8120417d19715d1548135f1', '5 rue cronstadt  Nancy', 0, 0, NULL),
(13, 'bjr', 'bjr', '2021-03-26 20:57:00', '2021-03-26 18:57:46', '2021-03-26 18:57:46', NULL, 3, '2ca1d3b79f10c91732d9448a4b8861aef45cd6f6d78351e46278d41b4ceefc6d', '5 rue cronstadt Nancy', 1, 0, NULL),
(15, 'stp marche', 'stp', '2021-03-26 21:38:00', '2021-03-26 19:39:06', '2021-03-26 19:39:06', NULL, 1, 'f5173f75a7da9064bd2c88e2277061f6a052efd24224c60c59f656a35cd81b2d', 'test', 0, 0, NULL),
(18, 'lallef', 'nalzllf', '2021-03-20 00:00:00', '2021-03-26 19:50:13', '2021-03-26 19:50:13', NULL, 1, '89c164928413e399578904d293aa88f8090f65faa296e155864a74c693560597', 'lalaldlf', 0, 0, NULL),
(19, 'isifkf', 'akdkkf', '2021-03-26 21:55:00', '2021-03-26 19:55:08', '2021-03-26 19:55:08', NULL, 1, '6d04bc7f2b36ce6e33ed6dc6518588af00c5fe6ccac9b7031d6bb5bc78b0c3bb', 'test', 0, 0, NULL),
(20, 'kskf', 'nansnf', '2021-03-26 21:56:00', '2021-03-26 19:56:12', '2021-03-26 19:56:12', NULL, 1, '6632f8d4d025546ce53d5c5f65c9cedc165346116fe79c0fc185da523e0f8253', 'test', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `text`, `user_id`, `event_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'tamere', 2, 7, '2021-03-29 07:03:14', '2021-03-29 07:03:14', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `participants`
--

CREATE TABLE `participants` (
  `user_id` int NOT NULL,
  `event_id` int NOT NULL,
  `present` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `participants`
--

INSERT INTO `participants` (`user_id`, `event_id`, `present`) VALUES
(2, 7, 1),
(3, 7, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `mail` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `firstname` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `mail`, `password`, `name`, `firstname`, `created_at`, `updated_at`) VALUES
(1, 'bastien52300@hotmail.fr', '$2y$10$djpGnvFFdnTR425Fj36hQOcj73jbSxCQSPpZfVI1My1f0tPiopH3K', 'Girardin-Vincent', 'Bastien', '2021-03-26 07:43:18', '2021-03-26 07:43:18'),
(2, 'giratina54@hotmail.fr', '$2y$10$oDsUNU9HwOYFi/MIwfoSOuI2Bm/7moDGE/gieEr3ek1dva6sUJW9a', 'Kremer', 'Thomas', '2021-03-26 08:29:45', '2021-03-26 08:29:45'),
(3, 'test@test.fr', '$2y$10$IATxPIszqK6Bap6vSLZ0/uF9TBes../kbdLaEXTLtfmXB6UIsjn/K', 'test', 'test', '2021-03-26 14:07:23', '2021-03-26 14:07:23');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk1_user_id` (`user_id`),
  ADD KEY `fk2_event_id` (`event_id`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk1_event_event_id` (`event_id`),
  ADD KEY `fk2_user_user_id` (`user_id`);

--
-- Index pour la table `participants`
--
ALTER TABLE `participants`
  ADD KEY `fk1_user_user_id` (`user_id`),
  ADD KEY `fk2_event_event_id` (`event_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `event`
--
ALTER TABLE `event`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `fk1_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk2_event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk1_event_event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk2_user_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `fk1_user_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk2_event_event_id` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
