-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Lug 10, 2018 alle 09:34
-- Versione del server: 5.5.52-0+deb8u1
-- PHP Version: 5.6.26-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kNet`
--

--
-- Dump dei dati per la tabella `roles`
--

INSERT INTO `roles` (`name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
('agent', 'Agente Commerciale', 'Agente Commerciale', '2016-08-30 06:53:24', '2016-08-30 06:53:24'),
('client', 'Cliente K-Group', 'Cliente di Krona Koblenz', '2016-08-30 06:53:24', '2016-08-30 06:53:24'),
('superAgent', 'Capoarea / Export Manager', 'Agente Commerciale con visualizzazioni speciali', '2016-08-30 06:53:24', '2016-08-30 06:53:24'),
('direz', 'Direzione', 'Utente Direzionale', '2016-08-30 06:53:24', '2016-08-30 06:53:24'),
('commerc', 'Commerciale', 'Utente generico Commerciale', '2016-08-30 06:53:24', '2016-08-30 06:53:24'),
('user', 'User', 'Generic User', '2016-10-21 12:26:03', '2016-10-21 12:26:03');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--(32, 'admin', 'User Administrator', 'One Role to Rule them All', '2016-08-30 06:53:24', '2016-08-30 06:53:24'),