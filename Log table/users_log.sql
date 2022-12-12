-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 07, 2019 at 03:10 AM
-- Server version: 5.6.44-cll-lve
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart-adviser`
--

-- --------------------------------------------------------

--
-- Table structure for table `users_log`
--

CREATE TABLE `users_log` (
  `id` varchar(22) COLLATE utf8_persian_ci NOT NULL,
  `id_user` varchar(22) COLLATE utf8_persian_ci NOT NULL,
  `action` varchar(10) COLLATE utf8_persian_ci NOT NULL,
  `description` varchar(70) COLLATE utf8_persian_ci NOT NULL,
  `ip` varchar(70) COLLATE utf8_persian_ci DEFAULT NULL,
  `date` varchar(12) COLLATE utf8_persian_ci NOT NULL,
  `time` varchar(12) COLLATE utf8_persian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `users_log`
--

INSERT INTO `users_log` (`id`, `id_user`, `action`, `description`, `ip`, `date`, `time`) VALUES
('20190801054120657743', '20190801011120623738', 'insert', 'User Created An Account', NULL, '2019-08-01', '05:41:20'),
('20190801055001910505', '20190801012001985852', 'insert', 'User Created An Account', NULL, '2019-08-01', '05:50:01'),
('20190801061958426475', '20190801014958450612', 'insert', 'User Created An Account', NULL, '2019-08-01', '06:19:58'),
('20190801062050128859', '20190801015050168220', 'insert', 'User Created An Account', NULL, '2019-08-01', '06:20:50'),
('20190801062131655784', '20190801015131697503', 'insert', 'User Created An Account', NULL, '2019-08-01', '06:21:31'),
('20190801102818393377', '20190801055818310521', 'insert', 'User Created An Account', NULL, '2019-08-01', '10:28:18'),
('20190801221854936398', '20190801174854936870', 'insert', 'User Created An Account', NULL, '2019-08-01', '22:18:54'),
('20190802170251467372', '20190802123251420476', 'insert', 'User Created An Account', NULL, '2019-08-02', '17:02:51'),
('20190805184106477873', '20190805141106487007', 'insert', 'User Created An Account', NULL, '2019-08-05', '18:41:06'),
('20190805184240617602', '20190805141240696547', 'insert', 'User Created An Account', NULL, '2019-08-05', '18:42:40'),
('20190805184327950928', '20190805141327926664', 'insert', 'User Created An Account', NULL, '2019-08-05', '18:43:27'),
('20190805184525566618', '20190805141525549368', 'insert', 'User Created An Account', NULL, '2019-08-05', '18:45:25'),
('20190805184752561908', '20190805141752526889', 'insert', 'User Created An Account', NULL, '2019-08-05', '18:47:52'),
('20190807005412643947', '20190806202412692281', 'insert', 'User Created An Account', '185.22.173.86', '2019-08-07', '00:54:12'),
('20190807113324316837', '20190807070324374080', 'insert', 'User Created An Account', '185.22.173.191', '2019-08-07', '11:33:24'),
('20190807113540456155', '20190807070540473885', 'insert', 'User Created An Account', '185.22.173.191', '2019-08-07', '11:35:40'),
('20190807114255281754', '20190807071255296136', 'insert', 'User Created An Account', '212.8.249.177', '2019-08-07', '11:42:55');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
