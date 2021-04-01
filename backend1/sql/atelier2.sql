-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 31 mars 2021 à 13:20
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
  `title` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
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
(10, 'dezfezzfe', 'fezfezfezfze', '2021-02-04 00:00:00', '2021-03-26 13:06:35', '2021-03-31 11:03:46', NULL, 1, 'abe4aeaced36856dcad259378eb7ad4dce9cb96309eb1c37b51eb79632ecdcef', '12 rue de Prény Pulnoy', 0, 0, NULL),
(11, 'jsjdjf', 'pd d\'alex', '2021-03-26 15:07:00', '2021-03-26 13:08:26', '2021-03-26 13:08:26', NULL, 1, '0a02435a3014fe7b849cf31d8351fe7b7913438a07c5a0b9b09ea9abd37261f5', 'jsjfn', 0, 0, NULL),
(12, 'soirée à fronville', 'jajekfkfkfkdkekdkffkdllalleflflslzlzlflclckkfkskskdkckkfkfkzkalspoxcnfnenenrkglglelalakzkkfkgkgkkfkfkckfkzllzlfkfkfkfnnnckfldzl', '2021-04-01 09:42:00', '2021-03-26 18:50:38', '2021-03-31 05:42:52', NULL, 1, 'ad3767507413651b06c9db8fde4b63c38397a1dad8120417d19715d1548135f1', '5 rue cronstadt nancy', 0, 0, NULL),
(13, 'bjr', 'bjr', '2021-03-26 20:57:00', '2021-03-26 18:57:46', '2021-03-26 18:57:46', NULL, 3, '2ca1d3b79f10c91732d9448a4b8861aef45cd6f6d78351e46278d41b4ceefc6d', '5 rue cronstadt Nancy', 1, 0, NULL),
(15, 'bonjour', 'bonjour', '2021-04-01 17:47:00', '2021-03-26 19:39:06', '2021-03-30 13:48:11', NULL, 1, 'f5173f75a7da9064bd2c88e2277061f6a052efd24224c60c59f656a35cd81b2d', '5 rue cronstadt nancy', 0, 0, NULL),
(18, 'lallef', 'nalzllf', '2021-03-20 00:00:00', '2021-03-26 19:50:13', '2021-03-26 19:50:13', NULL, 1, '89c164928413e399578904d293aa88f8090f65faa296e155864a74c693560597', 'lalaldlf', 0, 0, NULL),
(19, 'isifkf', 'akdkkf', '2021-03-26 21:55:00', '2021-03-26 19:55:08', '2021-03-26 19:55:08', NULL, 1, '6d04bc7f2b36ce6e33ed6dc6518588af00c5fe6ccac9b7031d6bb5bc78b0c3bb', 'test', 0, 0, NULL),
(20, 'kskf', 'nansnf', '2021-03-26 21:56:00', '2021-03-26 19:56:12', '2021-03-26 19:56:12', NULL, 1, '6632f8d4d025546ce53d5c5f65c9cedc165346116fe79c0fc185da523e0f8253', 'test', 0, 0, NULL),
(21, 'lolilok', 'Test creation event privé', '2021-03-24 00:00:00', '2021-03-29 11:20:53', '2021-03-29 11:20:53', NULL, 2, '801de93849272e07e36dae5b38cf4c55bd525fe44ba822bfaf5afaa38afe4c35', '12 rue de Prény Pulnoy', 0, 0, NULL),
(22, 'Evenement de Test', 'fezfezfezfzeefzezf', '2021-04-04 00:00:00', '2021-03-29 11:28:12', '2021-03-29 11:28:12', NULL, 3, '7f884152cf3df6649617e1ca5cbb8de4635235ea3f9191874231fe440fe051b7', '12 rue de Prény Pulnoy', 0, 0, NULL),
(23, 'fuck alex', 'alex il est trop fort', '2021-03-31 10:22:00', '2021-03-30 06:22:31', '2021-03-30 06:22:31', NULL, 1, '5ad9ec7b8625cbadefcc16c76d8efc0266099f9f21643a090c10141bc5a56adf', '5 rue cronstadt Nancy', 1, 0, NULL),
(24, 'lolilok', 'jajsjfn', '2021-03-31 00:00:00', '2021-03-30 07:56:42', '2021-03-30 07:56:42', NULL, 2, '68924bf77c63c217086c643288f015872a1174befc0cdd8084ed1943418ac052', '12 rue de Prény Pulnoy', 1, 0, NULL),
(25, 'encule', 'encule', '2021-04-01 17:45:00', '2021-03-30 13:54:55', '2021-03-30 13:54:55', NULL, 2, 'b724637b92b30e373520bf660a2c434e7c79aa13a3368ee01a5b747f3d9fae49', '5 rue cronstadt nancy', 0, 0, NULL),
(26, 'salam', 'salam', '2021-04-03 17:55:00', '2021-03-30 13:55:34', '2021-03-30 13:55:34', NULL, 1, '8081b7f025c6546998a400245d83f3608d0ff3c3592ab601c96cbdb5d1f4e78a', '4 lot Louise Marcilly fronville ', 0, 0, NULL),
(27, 'Soirée chez alex', 'yaoyo', '2021-04-01 09:22:00', '2021-03-30 17:28:27', '2021-03-31 05:22:24', NULL, 1, 'a822e475e780265d40d753346a067cb547581ebd32cb2b6fcb99ef8ef2fd4638', '5 rue cronstadt nancy', 1, 0, NULL),
(28, 'soirée chez moi', 'Vennez tous nombreux, on dort chez moi préparez vous à boire, et surtout à vous amuser', '2021-06-25 22:45:00', '2021-03-30 18:45:08', '2021-03-30 18:45:08', NULL, 8, '2cdfaa08b7aeb71875c4e3938b9a25117cf9fcf418c5366a1bb55822f9171526', '4 lot Louise Marcilly fronville ', 0, 0, NULL);

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
(1, 'tamere', 2, 7, '2021-03-29 07:03:14', '2021-03-29 07:03:14', NULL),
(2, 'jsjdfx', 1, 12, '2021-03-29 17:34:15', '2021-03-29 17:34:15', NULL),
(3, 'yo les gens ', 3, 12, '2021-03-29 18:04:39', '2021-03-29 18:04:39', NULL),
(4, 'ça va ?', 3, 12, '2021-03-29 18:31:16', '2021-03-29 18:31:16', NULL),
(5, 'ça va.. ya pas Alex qui vient j\'espère ?', 1, 12, '2021-03-29 18:33:11', '2021-03-29 18:33:11', NULL),
(6, 'sinon je ne viens pas', 1, 12, '2021-03-29 18:33:38', '2021-03-29 18:33:38', NULL),
(7, 'nan normalement il n\'est pas là', 3, 12, '2021-03-29 18:34:51', '2021-03-29 18:34:51', NULL),
(10, 'alex le bg', 1, 12, '2021-03-29 18:42:47', '2021-03-30 14:03:30', NULL),
(11, 'irkgcnnnnatg', 1, 12, '2021-03-29 18:42:51', '2021-03-29 18:42:51', NULL),
(12, 'ozkflal', 1, 12, '2021-03-29 18:42:59', '2021-03-29 18:42:59', NULL),
(14, 'vive le sch', 1, 12, '2021-03-30 06:52:04', '2021-03-30 07:38:39', NULL),
(15, 'jwjxnxndkzlzazliffkfnfnggbzaaooconbtbzkalzlfkkfngrbnzlz', 1, 12, '2021-03-30 06:52:18', '2021-03-30 06:52:18', NULL),
(16, 'fuck alex ', 1, 12, '2021-03-30 11:21:17', '2021-03-30 11:21:34', NULL),
(17, 'jzkfkfk', 1, 12, '2021-03-30 14:03:38', '2021-03-30 14:03:38', NULL),
(18, '0', 1, 12, '2021-03-30 18:34:29', '2021-03-30 18:34:29', NULL),
(19, 'bonjour les gens', 3, 27, '2021-03-31 05:28:35', '2021-03-31 05:28:35', NULL),
(20, 'ça va ?', 3, 27, '2021-03-31 05:28:56', '2021-03-31 05:28:56', NULL),
(22, 'yo', 3, 27, '2021-03-31 05:29:30', '2021-03-31 05:29:38', NULL),
(23, 'bien et toi ?', 1, 27, '2021-03-31 05:30:10', '2021-03-31 05:30:10', NULL),
(24, 'yoyoyo', 1, 27, '2021-03-31 05:30:17', '2021-03-31 05:30:17', NULL),
(25, 'tranquille ', 8, 27, '2021-03-31 05:31:25', '2021-03-31 05:31:25', NULL),
(26, 'je viens perso', 8, 27, '2021-03-31 05:31:33', '2021-03-31 05:31:33', NULL);

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
(2, 12, 1),
(2, 9, NULL),
(2, 23, 1),
(2, 10, 1),
(2, 23, 1),
(2, 7, 0),
(3, 12, 0),
(8, 27, 0),
(3, 27, 1);

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
(3, 'test@test.fr', '$2y$10$IATxPIszqK6Bap6vSLZ0/uF9TBes../kbdLaEXTLtfmXB6UIsjn/K', 'test', 'test', '2021-03-26 14:07:23', '2021-03-26 14:07:23'),
(4, 'bonjour@test.fr', '$2y$10$h5s1KMmC8ZoLRZgGiw09xeom1LFMcb7IXHYXcgw/aXm3LWu8ncxfG', 'bonjour', 'bonjour', '2021-03-30 18:09:50', '2021-03-30 18:09:50'),
(5, 'azerty@azerty.fr', '$2y$10$QtN9Yg.AidGY38cPF0oFUOIAs2s6Z4vRxG7jGFElcPG.hphE2Wh6q', 'azerty', 'azerty', '2021-03-30 18:11:48', '2021-03-30 18:11:48'),
(6, 'jajdjf@jzjdkf.fr', '$2y$10$Ox7jy2jdIRwetLu5pco5kOk6O62xK3tm4vH.ZAN9BjUGhQB35S.GC', 'ajjdjf', 'nandfn', '2021-03-30 18:13:30', '2021-03-30 18:13:30'),
(7, '', '$2y$10$1LKMEwgb5O87lsGJS.RdyOe1vdmoIc7IhiM/SdNnjJH75SrTDq4g2', '', '', '2021-03-30 18:15:05', '2021-03-30 18:15:05'),
(8, 'ben@ben.fr', '$2y$10$vFK68ZEG1MEyTPLr3tKLNefe9VoI79STL7/fcSW2YcTakMnYmVwwS', 'Mathieu', 'Benjamin', '2021-03-30 18:43:10', '2021-03-30 18:43:10'),
(10, 'Gerard54@hotmail.fr', '$2y$10$p./R.javFIENAam/W8ZBZuqF83jZdk1/teTnzEhh2b/45Nt/0zlP2', 'Depardieu', 'Gerard', '2021-03-31 11:11:54', '2021-03-31 11:11:54');

-- --------------------------------------------------------

--
-- Structure de la table `useradmin`
--

CREATE TABLE `useradmin` (
  `id` int NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `useradmin`
--

INSERT INTO `useradmin` (`id`, `username`, `password`) VALUES
(2, 'admin', '$2y$10$/8TppB3ZLGBLTLVTwKCt8.Ar4YHxENiSU.mghScZ5QKaJvMRPqhH.');

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
-- Index pour la table `useradmin`
--
ALTER TABLE `useradmin`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `event`
--
ALTER TABLE `event`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `useradmin`
--
ALTER TABLE `useradmin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
