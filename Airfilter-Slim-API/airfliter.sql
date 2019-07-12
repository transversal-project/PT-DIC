-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Ven 12 Juillet 2019 à 16:57
-- Version du serveur :  5.7.26-0ubuntu0.18.04.1
-- Version de PHP :  7.2.19-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `airfliter`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `validation` varchar(2) NOT NULL DEFAULT 'ok'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `validation`) VALUES
(1, 'derguene.mbaye@ucad.edu.sn', 'd07bc4e2211c9b8f247cf41b00ddf36b', 'ok');

-- --------------------------------------------------------

--
-- Structure de la table `alerte`
--

CREATE TABLE `alerte` (
  `idAlerte` int(11) NOT NULL,
  `zone` varchar(255) NOT NULL,
  `idClient` int(11) NOT NULL,
  `idCapteur` int(11) NOT NULL,
  `date` date NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `capteur`
--

CREATE TABLE `capteur` (
  `idCapteur` int(11) NOT NULL,
  `nomCapteur` varchar(255) NOT NULL,
  `typeDonnee` enum('pm1','pm10','pm25') NOT NULL,
  `idPosition` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `capteur`
--

INSERT INTO `capteur` (`idCapteur`, `nomCapteur`, `typeDonnee`, `idPosition`) VALUES
(1, 'capt65', 'pm10', 1),
(2, 'capt66', 'pm25', 2),
(3, 'capt68', 'pm1', 3),
(5, 'capt90', 'pm10', 8);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `idClient` int(11) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `validation` enum('ok','en attente','refus') NOT NULL DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `client`
--

INSERT INTO `client` (`idClient`, `prenom`, `nom`, `email`, `password`, `telephone`, `validation`) VALUES
(1, 'Abdoulaye', 'Thiam', 'absthiam@hotmail.fr', 'd07bc4e2211c9b8f247cf41b00ddf36b', '777336779', 'ok'),
(4, 'Jeumbs', 'bloqueur', 'JeumbsDakhaar@gmail.com', 'd5d3788c35d858f92f653b20f5172901', '7678687687', 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `mesure`
--

CREATE TABLE `mesure` (
  `idCapteur` int(11) NOT NULL,
  `valeur` float NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `mesure`
--

INSERT INTO `mesure` (`idCapteur`, `valeur`, `date`) VALUES
(1, 76.7869, '2019-07-17'),
(2, 23.7868, '2019-07-16'),
(2, 67.9879, '2019-07-08'),
(1, 767.98, '2019-07-07'),
(3, 66.898, '2019-07-09'),
(1, 767.98, '2019-07-12'),
(5, 75.9799, '2019-07-12');

-- --------------------------------------------------------

--
-- Structure de la table `position`
--

CREATE TABLE `position` (
  `idPosition` int(11) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `designation` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `position`
--

INSERT INTO `position` (`idPosition`, `latitude`, `longitude`, `designation`) VALUES
(1, 14.7378, -17.453300000000013, 'Arafat'),
(2, 14.7594, -17.438499999999976, 'Parcelles Assainies, Dakar, Sénégal'),
(3, 14.7401, -17.45010000000002, 'Cité Millionnaire'),
(8, 12.7687678, -65.98798789, 'Diamalaye');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `alerte`
--
ALTER TABLE `alerte`
  ADD PRIMARY KEY (`idAlerte`),
  ADD KEY `fk_idClient` (`idClient`),
  ADD KEY `fk_idCapteur` (`idCapteur`);

--
-- Index pour la table `capteur`
--
ALTER TABLE `capteur`
  ADD PRIMARY KEY (`idCapteur`),
  ADD KEY `fk_idPosition` (`idPosition`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`idClient`);

--
-- Index pour la table `mesure`
--
ALTER TABLE `mesure`
  ADD KEY `fk_idCapteur2` (`idCapteur`);

--
-- Index pour la table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`idPosition`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `alerte`
--
ALTER TABLE `alerte`
  MODIFY `idAlerte` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `capteur`
--
ALTER TABLE `capteur`
  MODIFY `idCapteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `idClient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `position`
--
ALTER TABLE `position`
  MODIFY `idPosition` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `alerte`
--
ALTER TABLE `alerte`
  ADD CONSTRAINT `fk_idCapteur` FOREIGN KEY (`idCapteur`) REFERENCES `capteur` (`idCapteur`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_idClient` FOREIGN KEY (`idClient`) REFERENCES `client` (`idClient`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `capteur`
--
ALTER TABLE `capteur`
  ADD CONSTRAINT `fk_idPosition` FOREIGN KEY (`idPosition`) REFERENCES `position` (`idPosition`);

--
-- Contraintes pour la table `mesure`
--
ALTER TABLE `mesure`
  ADD CONSTRAINT `fk_idCapteur2` FOREIGN KEY (`idCapteur`) REFERENCES `capteur` (`idCapteur`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
