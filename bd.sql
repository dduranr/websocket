-- --------------------------------------------------------
-- Host:                         localhost
-- Versión del servidor:         5.7.24 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para websocket
CREATE DATABASE IF NOT EXISTS `websocket` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `websocket`;

-- Volcando estructura para tabla websocket.chatrooms
CREATE TABLE IF NOT EXISTS `chatrooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `msg` varchar(200) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla websocket.chatrooms: ~10 rows (aproximadamente)
/*!40000 ALTER TABLE `chatrooms` DISABLE KEYS */;
INSERT INTO `chatrooms` (`id`, `userid`, `msg`, `created_on`) VALUES
	(1, 2, 'Hello', '2018-04-28 02:33:45'),
	(2, 1, 'Hi', '2018-04-28 02:33:55'),
	(3, 2, 'How are you?', '2018-04-28 02:34:07'),
	(4, 1, 'I am good..\nwhat about you?', '2018-04-28 02:34:39'),
	(5, 2, 'Me too...', '2018-04-28 02:34:51'),
	(6, 5, 'Hola', '2020-12-31 06:41:44'),
	(7, 7, 'Holas', '2020-12-31 07:04:04'),
	(8, 8, 'Hola\n', '2020-12-31 07:09:57'),
	(9, 7, 'CÃ³mo te va, Firefox?', '2020-12-31 07:10:14'),
	(10, 8, 'Ah, pues bien, estoy aquÃ­ en casa en Noche Vieja!', '2020-12-31 07:10:32');
/*!40000 ALTER TABLE `chatrooms` ENABLE KEYS */;

-- Volcando estructura para tabla websocket.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `login_status` tinyint(4) NOT NULL DEFAULT '0',
  `last_login` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla websocket.users: ~8 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `login_status`, `last_login`) VALUES
	(1, 'Durgesh', 'durgesh@gmail.com', 0, '2020-12-30 06:29:39'),
	(2, 'Sachin', 'sachin@gmail.com', 0, '2018-04-29 02:15:52'),
	(3, 'Dinesh', 'dinesh@gmail.com', 1, '2018-04-29 02:15:18'),
	(4, 'David', 'david@duran.company', 1, '2020-12-31 12:25:36'),
	(5, 'JuÃ¡n', 'juan@gmail.com', 0, '2020-12-31 01:00:03'),
	(6, 'Juanita', 'juana@gmail.com', 1, '2020-12-31 08:45:16'),
	(7, 'Soy Firefox', 'firefox@gmail.com', 1, '2020-12-31 12:59:52'),
	(8, 'Soy Chrome', 'chrome@gmail.com', 1, '2020-12-31 01:00:16');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
