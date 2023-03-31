-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Ven 31 Mars 2023 à 11:25
-- Version du serveur :  5.6.20-log
-- Version de PHP :  7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `sauceblog`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
`id_article` int(11) NOT NULL,
  `titre` varchar(50) NOT NULL,
  `date_publication` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `contenu` text,
  `id_utilisateur` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `articles`
--

INSERT INTO `articles` (`id_article`, `titre`, `date_publication`, `contenu`, `id_utilisateur`) VALUES
(2, 'Les API REST', '2023-03-31 13:23:39', 'Les API REST (Representational State Transfer) sont devenues un élément clé des applications Web modernes. Elles permettent à différents systèmes de communiquer et de partager des données via Internet de manière efficace et cohérente. Dans cet article, nous allons discuter de ce qu''est une API REST, comment elle fonctionne et comment elle peut être utilisée dans le développement d''applications Web.\r\n\r\nQu''est-ce qu''une API REST?\r\nUne API REST est une interface de programmation d''application qui utilise les méthodes HTTP standard telles que GET, POST, PUT, DELETE, etc. pour permettre aux applications de communiquer entre elles. Une API REST est considérée comme "stateless", ce qui signifie qu''elle ne garde pas de trace de l''état d''une session entre les requêtes. Au lieu de cela, chaque requête est considérée comme une requête indépendante.\r\n\r\nUne API REST utilise également des formats de données standard tels que JSON (JavaScript Object Notation) ou XML (eXtensible Markup Language) pour échanger des données avec les clients. Cela permet aux clients de comprendre facilement les données échangées et de les utiliser dans leur propre application.\r\n\r\nComment fonctionne une API REST?\r\nUne API REST utilise les méthodes HTTP standard pour permettre aux clients de communiquer avec le serveur. Les méthodes les plus courantes sont:\r\n\r\nGET: Utilisé pour récupérer des données à partir du serveur.\r\nPOST: Utilisé pour envoyer des données au serveur.\r\nPUT: Utilisé pour mettre à jour des données existantes sur le serveur.\r\nDELETE: Utilisé pour supprimer des données du serveur.\r\nLorsqu''un client envoie une requête à une API REST, il envoie une demande spécifique pour récupérer ou modifier des données. Le serveur répond alors avec une réponse HTTP contenant les données demandées. Cette réponse peut être au format JSON ou XML, selon ce qui a été spécifié dans la demande du client.\r\n\r\nUtilisation des API REST dans le développement d''applications Web\r\nLes API REST sont souvent utilisées dans le développement d''applications Web pour permettre aux différents systèmes de communiquer entre eux. Les développeurs peuvent utiliser des API REST pour récupérer des données à partir de différents systèmes, comme des bases de données ou des services Web tiers, et les intégrer dans leur propre application.\r\n\r\nLes API REST sont également utilisées dans le développement d''applications mobiles pour permettre aux applications de communiquer avec les serveurs. Les applications mobiles peuvent envoyer des requêtes à une API REST pour récupérer des données à afficher à l''utilisateur.\r\n\r\nConclusion\r\nLes API REST sont devenues un élément clé du développement d''applications Web modernes. Elles permettent aux différentes applications de communiquer entre elles de manière efficace et cohérente. Les développeurs peuvent utiliser des API REST pour récupérer des données à partir de différents systèmes et les intégrer dans leur propre application. Avec l''augmentation du nombre d''applications Web et mobiles, l''utilisation des API REST devrait continuer à croître à l''avenir.', 1),
(1, 'Sauce', '2023-03-16 09:13:33', 'sauce', 0);

-- --------------------------------------------------------

--
-- Structure de la table `disliker`
--

CREATE TABLE IF NOT EXISTS `disliker` (
  `id_article` int(11) NOT NULL DEFAULT '0',
  `id_utilisateur` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `disliker`
--

INSERT INTO `disliker` (`id_article`, `id_utilisateur`) VALUES
(1, 1),
(2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `liker`
--

CREATE TABLE IF NOT EXISTS `liker` (
  `id_article` int(11) NOT NULL DEFAULT '0',
  `id_utilisateur` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `liker`
--

INSERT INTO `liker` (`id_article`, `id_utilisateur`) VALUES
(1, 0),
(2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int(11) NOT NULL DEFAULT '0',
  `denomination` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `role`
--

INSERT INTO `role` (`id_role`, `denomination`) VALUES
(0, 'Moderateur'),
(1, 'Publisher'),
(-1, 'Random');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int(11) NOT NULL DEFAULT '0',
  `identifiant` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `identifiant`, `password`, `id_role`) VALUES
(0, 'Sauce', '$2y$12$n3Y/v7Sj.z5Euiun9DWH5.JSycZQ3wmngnsYeCMZMS0YGGNZRrdAq', 0),
(1, 'Jean-patrick', '$2y$12$UP5646ZLu5fgthqT/gHmhOMKNy3P09wYi/XUXBiaWyKWdMKUWSx7W', 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
 ADD PRIMARY KEY (`id_article`), ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `disliker`
--
ALTER TABLE `disliker`
 ADD PRIMARY KEY (`id_article`,`id_utilisateur`), ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `liker`
--
ALTER TABLE `liker`
 ADD PRIMARY KEY (`id_article`,`id_utilisateur`), ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
 ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
 ADD PRIMARY KEY (`id_utilisateur`), ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
MODIFY `id_article` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
