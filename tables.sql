-- --------------------------------------------------------
-- Hôte :                        127.0.0.1
-- Version du serveur:           5.6.17 - MySQL Community Server (GPL)
-- SE du serveur:                Win32
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Export de la structure de la base pour oauth2
DROP DATABASE IF EXISTS `oauth2`;
CREATE DATABASE IF NOT EXISTS `oauth2` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `oauth2`;


-- Export de la structure de table oauth2. client
DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `secret` varchar(255) CHARACTER SET latin1 NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 NOT NULL,
  `redirect_url` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `user_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Export de données de la table oauth2.client : ~1 rows (environ)
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` (`id`, `secret`, `name`, `description`, `redirect_url`, `user_id`) VALUES
	('259337193970440', '7304762e5e972356bd0b5f12c747d11e4cb65d96', 'API Web', 'this app let user to allow access to their data', 'http://localhost/test/oauth2server/', 'nguereza');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;


-- Export de la structure de table oauth2. code
DROP TABLE IF EXISTS `code`;
CREATE TABLE IF NOT EXISTS `code` (
  `id` varchar(255) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `client_id` varchar(255) DEFAULT NULL,
  `user_id` varchar(70) DEFAULT NULL,
  `scope` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Export de données de la table oauth2.code : ~1 rows (environ)
/*!40000 ALTER TABLE `code` DISABLE KEYS */;
INSERT INTO `code` (`id`, `expire`, `client_id`, `user_id`, `scope`) VALUES
	('da09e2aa140127c7178149fcc7143b8d', 1454346553, '259337193970440', '829490630449542', 'email,photo');
/*!40000 ALTER TABLE `code` ENABLE KEYS */;


-- Export de la structure de table oauth2. token
DROP TABLE IF EXISTS `token`;
CREATE TABLE IF NOT EXISTS `token` (
  `id` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `expire` int(11) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `scope` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Export de données de la table oauth2.token : ~1 rows (environ)
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
INSERT INTO `token` (`id`, `client_id`, `expire`, `user_id`, `scope`) VALUES
	('f49144107349f5f2c9bf54c58f8e1ad5efed7f4d', '259337193970440', 1454346634, '829490630449542', NULL);
/*!40000 ALTER TABLE `token` ENABLE KEYS */;


-- Export de la structure de table oauth2. user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` varchar(30) NOT NULL,
  `nom` varchar(200) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `email` varchar(70) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Export de données de la table oauth2.user : ~0 rows (environ)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `nom`, `prenom`, `email`, `password`, `username`) VALUES
	('829490630449542', 'NGUEREZA', 'Tony', 'nguerezatony@gmail.com', '9cf95dacd226dcf43da376cdb6cbba7035218921', 'tony');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
