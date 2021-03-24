-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : mer. 24 mars 2021 à 09:04
-- Version du serveur :  10.5.8-MariaDB-1:10.5.8+maria~focal
-- Version de PHP : 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `events`
--

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `title` varchar(128) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL,
  `token` varchar(128) CHARACTER SET utf8 NOT NULL,
  `adress` varchar(150) CHARACTER SET utf8 NOT NULL,
  `public` tinyint(1) NOT NULL,
  `main_event` tinyint(1) NOT NULL,
  `event_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `event`
--

INSERT INTO `event` (`id`, `title`, `description`, `date`, `created_at`, `updated_at`, `deleted_at`, `user_id`, `token`, `adress`, `public`, `main_event`, `event_id`) VALUES
(1, 'soir&eacute;e com&eacute;die caf&eacute;', 'sortie entre potes au com&eacute;die caf&eacute; ramenez du monde !!', '2021-03-30 18:00:00', '2021-03-23 16:29:09', '2021-03-23 16:29:09', '0000-00-00 00:00:00', 1, 'beee40eedf9c7c05ba4a35c7e3084419c8973dfc5cfac50aa7a0fb7aa49ddb2c', '2 rue du pont des roches 57000 metz', 1, 1, NULL),
(2, 'restaurant beef&amp;co', 'restaurant apr&egrave;s le boulot entre coll&egrave;gues venez bien habill&eacute;', '2021-03-27 20:30:00', '2021-03-23 16:33:16', '2021-03-23 16:32:29', '0000-00-00 00:00:00', 3, '971eb587c1755d556831d6feec6a3484988526d99fea6f97597ff261c5309fc9', '317 avenue de strasbourg 57070 metz', 0, 1, NULL),
(3, 'balade nocturne haloween', 'balade nocturne pour f&ecirc;ter haloween venez avec vos amis', '2021-10-31 20:00:00', '2021-03-23 16:37:28', '2021-03-23 16:36:04', '0000-00-00 00:00:00', 5, 'b0efeed85a4450c2ed73364ad3c1930c74919264fcbbb0e668c23b516ad9a1ce', '9 rue de l&#039;&eacute;glise 57140 woippy', 0, 1, NULL),
(4, 'after chez moi !!', 'after apr&egrave;s la com chez moi avec de la piav !!', '2021-03-30 01:15:00', '2021-03-23 16:40:33', '2021-03-23 16:39:58', '0000-00-00 00:00:00', 1, 'b22895981104e5dbb89879566b7be45a4a8aa5311c9a1767703c364c25055d71', '22 en nexirue 57000 metz', 0, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `participants`
--

CREATE TABLE `participants` (
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `present` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `participants`
--

INSERT INTO `participants` (`user_id`, `event_id`, `name`, `present`) VALUES
(1, 1, NULL, 1),
(2, 1, NULL, 1),
(5, 1, NULL, 0),
(6, 1, NULL, 1),
(NULL, 1, 'enzo', 1),
(NULL, 1, 'titouan', 0),
(NULL, 1, 'jhonny', 0),
(3, 2, NULL, 1),
(9, 2, NULL, 0),
(10, 2, NULL, 1),
(11, 2, NULL, 1),
(12, 2, NULL, 1),
(NULL, 2, 'prescillia', 1),
(5, 3, NULL, 1),
(12, 3, NULL, 1),
(NULL, 3, 'simon', 0),
(NULL, 3, 'eve', 0),
(NULL, 3, 'virginie', 1),
(11, 4, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `mail` varchar(50) CHARACTER SET utf8 NOT NULL,
  `password` varchar(128) CHARACTER SET utf8 NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 NOT NULL,
  `firstname` varchar(30) CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `mail`, `password`, `name`, `firstname`, `created_at`, `updated_at`) VALUES
(1, 'jean.dupont@gmail.com', '$2y$10$Hdph/Qtz6oITdkC5Ql7VhOrO4KBZY/N0eQyuJ0wrAtJrx/9u/bRzi', 'dupont', 'jean', '2021-03-23 15:27:52', '2021-03-23 15:27:52'),
(2, 'valerie221@outlook.fr', '$2y$10$kVzX.OT6uQZ.O1vKkJILS.OQTks0AUvCbRarWZ03PaiXuMcFyhaFm', 'leloi', 'valerie', '2021-03-23 16:02:58', '2021-03-23 16:02:58'),
(3, 'c-bedron@hotmail.fr', '$2y$10$omV7XDj1gIkvxL9YJVkZD.q73mmycr5g6H0PF.bxVBZYEgpx3BBfG', 'bedron', 'christine', '2021-03-23 16:03:51', '2021-03-23 16:03:51'),
(4, 'fredcelion@yahoo.fr', '$2y$10$KFiREJTfzCEHYOYRayqZCea5WeFwd61owovb/c4aQr9.eErvheF3a', 'celion', 'frederique', '2021-03-23 16:04:47', '2021-03-23 16:04:47'),
(5, 'maxoulo24520@hotmail.fr', '$2y$10$Tf5/7K.uEweD3M2FTzOnXeDI.kkljbYuGaeqk5CcUWK1hnIwNNX0m', 'markevich', 'maxime', '2021-03-23 16:05:45', '2021-03-23 16:05:45'),
(6, 'jujuz57@gmail.com', '$2y$10$qBNpZl2E03GskOoYegCameZOhex.N7NxeZeLaGxfDym6TrIlprhVK', 'zengerle', 'julie', '2021-03-23 16:06:32', '2021-03-23 16:06:32'),
(7, 'av.senegal@gmail.com', '$2y$10$BLvldSNUicTfDxbtgcFppe2wm97uopaOygSOShRt4PKaFqUm5oQOq', 'nonenmacher', 'alexis', '2021-03-23 16:07:08', '2021-03-23 16:07:08'),
(8, 'camelia.bakouala@gmail.com', '$2y$10$0ZsZt3RQL8hiMHI/Lo/EVuq1H/cJxZSw7OzKa.J11XhJIwoACPyv.', 'bakouala', 'camelia', '2021-03-23 16:07:42', '2021-03-23 16:07:42'),
(9, 'kohlanta.tf1@gmail.com', '$2y$10$lfD75gTRsx4FwpIZO3x8mezxpUCREou73ED0a7s32io8wOFwaPwuS', 'brognard', 'denis', '2021-03-23 16:10:11', '2021-03-23 16:10:11'),
(10, 'cdupuis22@yahoo.fr', '$2y$10$j2TmpZKgkwXj25gmHEcOkucMleR5Ts/gGKNBzWGt8ARg1kOoSQt6G', 'depuis', 'cecelia', '2021-03-23 16:10:38', '2021-03-23 16:10:38'),
(11, 'guillaumeharte9@outlook.fr', '$2y$10$LU3scfP9/xXB2pxN9eTM8ejOXXwVN9PeTjNGr6XEDuKFypbK6H0hm', 'harte', 'guillaume', '2021-03-23 16:11:25', '2021-03-23 16:11:25'),
(12, 'swalinfamille@wanadoo.fr', '$2y$10$BvZ8GEUDmY09.LObpAqLIeREZbgck9yWDyJTGFfa32ilb2LMLW0Ym', 'swalin', 'john', '2021-03-23 16:12:12', '2021-03-23 16:12:12');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
