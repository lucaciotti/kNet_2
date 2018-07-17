-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Lug 16, 2018 alle 15:04
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
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `name`, `nickname`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `codag`, `codcli`, `codfor`, `ditta`, `avatar`, `lang`) VALUES
(3272, 'LEOPALDI MAURO', 'mauro.leopaldi@koblenz.it', 'mauro.leopaldi@koblenz.it', '$2y$10$j0/2fbcdFIVw/KTFEciblOUnpYpQzKNblC1xhmm/R04y99rluYkBa', 'nVLwnuNupmrriNTqdHW56JZ4QO26Rwqj4gXp5JhL0DumYniWiWsqJZR94VvB', '2016-10-24 10:31:44', '2017-11-20 13:10:05', '002', '', NULL, 'it', 'avatar_default.jpg', ''),
(3273, 'FURIO POMPONIO', 'furio.pomponio@koblenz.it', 'furio.pomponio@koblenz.it', '$2y$10$1/92ycEdCq.xRYB5LGrGjuB2D7kg40tK/d0NYbbiC2YfejZrWZw3e', NULL, '2016-10-24 10:31:44', '2016-10-24 10:56:32', '003', '', NULL, 'it', 'avatar_default.jpg', ''),
(3274, 'FIADONE LUCA', 'luca.fiadone@k-group.com', 'luca.fiadone@k-group.com', '$2y$10$Qj6ZOGSI87nhj.ru83jM/u.3AoUw6N/cE8YW7NkBXRDiDI/uOOPCa', 'F3L84nKkPUI5PGykF0XvVm1xORtpMmgm3QwPqKZOckIQ69tscbwk0LYVzRtw', '2016-10-24 10:31:45', '2017-01-25 14:06:31', '004', '', NULL, 'it', 'avatar_default.jpg', ''),
(3275, 'DE LUCA GIUSEPPE', 'giuseppe.deluca@k-group.com', 'giuseppe.deluca@k-group.com', '$2y$10$YVtiEUg66ZMR4J51bGU5jej7DZGNcrCvoZ.3kueD277BhqCqe8XXi', 'GQWBudzym0rCWnJBTsMbckjCE19NSOHybXlXP9cwNOYjwsXZqPQP8KIGb2lL', '2016-10-24 10:31:45', '2017-04-10 16:25:02', '005', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3276, 'DIREZIONALE COMMERCIALE', 'mauro.leopaldi@k-group.com', 'mauro.leopaldi@k-group.com', '$2y$10$gKX4JSsjQgqocKqU.NC6ZeZkehEXUxLOWIucog9em8zT7wAl/UGe.', 'AKPOgKZnyhndYPpnKmkKEmgUrjrPLS3UdLcrXGonmE35qTevKbVim1cXQacX', '2016-10-24 10:31:45', '2016-10-27 06:38:52', '01', '', NULL, 'it', 'avatar_default.jpg', ''),
(3277, 'MIGLIORINI MIRKO', 'mircati@libero.it', 'mircati@libero.it', '$2y$10$SUH/cJjGGDRuyqXkvtY3wejhlfO2pS3GbmLVSk9.oyV3dysGHtqPG', '8TGWzm7gxkR3Q2w51cKCVdjtmCN3cLmBM8Vyu4krgYbJFtVVqwnPczd1JosN', '2016-10-24 10:31:46', '2017-10-26 05:38:36', '1', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3278, 'CELONA', 'rappresentanzecelona@alice.it', 'rappresentanzecelona@alice.it', '$2y$10$x1/haZCK19wuZh7IYtl9B.nqNxAYcFaKLjDz2Ojdx1AnofPzCN0fu', NULL, '2016-10-24 10:31:46', '2016-10-24 10:31:46', '13', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3279, 'GARIN FRANCESCO', 'francesco.garin@k-group.com', 'francesco.garin@k-group.com', '$2y$10$j02NxsqCSb5.kuqgk1Lxfejp/c3Rq1mPJm6YuvT9RjsPv0s8hJkCa', 'oDlNLL0cNXVV4XQRLFb56xT9U12lfFMjh1tmHopraxuY02O0sRF3ai6hEQiY', '2016-10-24 10:31:46', '2018-04-18 11:07:01', '22', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3280, 'MIOTTO MICHELE', 'michele.miotto@k-group.com', 'michele.miotto@k-group.com', '$2y$10$2dTNgXbfuOmRxQGsy7grw.5JL7mdbSOdPef0f/I9Yl87ZO4gjh4Xu', NULL, '2016-10-24 10:31:46', '2016-10-24 10:31:47', '18', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3282, 'TREVISANI SAVERIO', 'saverio.trevisani@k-group.com', 'saverio.trevisani@k-group.com', '$2y$10$j2v/L0JvERy6Ptn2hse5lOMIgcOgjwWlcY24Uzx6ejP.I9t09K8lu', NULL, '2016-10-24 10:31:47', '2016-10-24 10:31:47', '28', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3283, 'INTICHERO PIERO RAPPRESENTANTE', 'pierintagent@tiscali.it', 'pierintagent@tiscali.it', '$2y$10$DngInUWzgQxc8BcqV2uA3.LgPFfvFLU3cVYz5hG1CEY8tEpSpZbPq', NULL, '2016-10-24 10:31:47', '2016-10-24 10:31:48', '48', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3284, 'MINARDI CARMINE RAPPRESENTANZE', 'info@minardi-agente.it', 'info@minardi-agente.it', '$2y$10$IVgnUh2ySHmCn5FqMbVjHOjKJb6RHfY6kdWYsUIDVHCQWkTAsb6ju', NULL, '2016-10-24 10:31:48', '2016-10-24 10:31:48', '51', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3285, 'BENUZZI FABIO', 'benuzzif@tiscali.it', 'benuzzif@tiscali.it', '$2y$10$ngHtHYAW6EwfyHKVZyTVAOjBtFZcQJ1vlY3..Lmn2UmAcyDMuC2Qe', NULL, '2016-10-24 10:31:48', '2016-10-24 10:31:48', '9', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3286, 'TECAO', 'olegadamcik@centrum.cz', 'olegadamcik@centrum.cz', '$2y$10$XTsCQag80wg4JTP449O0p.FXXfMEmMeOHulE1bz5b9uSm2tC0czJO', NULL, '2016-10-24 10:31:48', '2016-10-24 10:31:49', 'A11', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3287, 'CK DESIGN', 'erik@ckdesign-int.com', 'erik@ckdesign-int.com', '$2y$10$1SUy5xuG.W6wlwBkRwDE9.BZQh5qXz78qBxIlFdk1AVvvPXjbwMIy', NULL, '2016-10-24 10:31:49', '2016-10-24 10:31:49', 'A13', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3288, 'R. SHERWOOD', 'rob@rsagencies.com', 'rob@rsagencies.com', '$2y$10$Nx48Z887BPPK/mH7aD6NV.cKLytuCfUjUPNntncSojPC/Uze7nzm6', NULL, '2016-10-24 10:31:49', '2016-10-24 10:31:49', 'A15', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3289, 'FRANK PORST', 'fporst@hp-porst.de', 'fporst@hp-porst.de', '$2y$10$AUg2AK6hQK0j2UpOSsyjzeCwn3hyhi8AHXMeqYe0.gQNZAkBio17q', NULL, '2016-10-24 10:31:49', '2016-10-24 10:31:49', 'A17', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3290, 'GRADENEGGER', 'wolfgang@gradenegger.at', 'wolfgang@gradenegger.at', '$2y$10$31AgQiUuBMc3hBG9Jgttle2Hov.ntAdHN1EI/lUSiKTOaMWgpYvFS', NULL, '2016-10-24 10:31:50', '2016-10-24 10:31:50', 'A19', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3291, 'ELLELOGIKA', 'ellelogikasrl@gmail.com', 'ellelogikasrl@gmail.com', '$2y$10$i/MVhjykgYQtraH31KGEUeveiL9rXKYRadF0DOeG.k5yyMcsaVj7.', NULL, '2016-10-24 10:31:50', '2016-10-24 10:31:50', 'A2', NULL, NULL, 'it', 'avatar_default.jpg', ''),
(3294, 'FRANCESCO TRANCHINA', 'tranchina@k-group.com', 'tranchina@k-group.com', '$2y$10$GkionwFf1xxPdTjuqNNc5..WJCfMYkgtMQMh/7blXYWdVybm.NGoe', 'wakVbHlR4fzDgHc6izXbnJk1G6QxmkLCC9aDWFAqkkNGQRRxnRes1S6wZKoh', '2016-10-24 10:31:51', '2018-06-27 08:36:53', 'AM3', '', NULL, 'it', 'avatar_default.jpg', ''),
(4794, 'LUIS MARTOS', 'luis.martos@kronakoblenz.es', 'luis.martos@kronakoblenz.es', '$2y$10$J8wv83E0Dfh292wmArcVSOmXgfGRoEK7Ay.Nll0c4CBgltHFbrDVK', 'BjjH9dtCX27kTbcNLjyasWX1fVRkr6jvm37zICfZdz5h9i5hXFsK8cM89pZc', '2016-10-27 13:36:07', '2018-06-08 15:41:47', 'A3', '', NULL, 'es', 'avatar_default.jpg', 'es'),
(4803, 'Matthieu de Lachaise', 'matthieu.delachaise@kronakoblenz.fr', 'matthieu.delachaise@kronakoblenz.fr', '$2y$10$3LNUn2eg/1um0iDYGZtIEe7Ihi0fNSP9A1HWd3ha/e8nKjwRa83/.', 'HbtvjBTVFwJ6sXmXwT0W6kLGQHY3yywzyw9GccXvChVBv6DP7FDwfs8pe8q9', '2016-12-20 09:18:29', '2017-01-20 13:46:21', '001', '', NULL, 'fr', 'avatar_default.jpg', 'fr'),
(4804, 'TOMMASINI OLIVIER VRP', 'olivier.tommasini@kronakoblenz.fr', 'olivier.tommasini@kronakoblenz.fr', '$2y$10$8WyH2AVAxGVDSGoFejP31uIr6XZq8KA0mgouFss.kdqz1FlDnNyTS', '3u0haOrv7AIeN6sFzvyhsTREuRAIBKTZMG365FW075U9eJ1yRNGaMLGeoVyU', '2016-12-20 09:18:00', '2017-01-20 13:27:22', '002', NULL, NULL, 'fr', 'avatar_default.jpg', 'fr'),
(4805, 'BAUDY ERIC VRP', 'eric.baudy@kronakoblenz.fr', 'eric.baudy@kronakoblenz.fr', '$2y$10$T9oX9qFauhv0PQsINqvB9.DrSOX8awPx1lxzbD7RWxOltHO3pbvvS', 'loDJIQhbwMuLknGp37tAPwyH6rcTZNtZSuIBNYibvU8WLSy5zHeNhLFP4nHE', '2016-12-20 09:18:00', '2017-04-10 15:43:46', '003', NULL, NULL, 'fr', 'avatar_default.jpg', 'fr'),
(4806, 'DEKEUKELAERE JULIEN VRP', 'julien.dekeukelaere@kronakoblenz.fr', 'julien.dekeukelaere@kronakoblenz.fr', '$2y$10$Ekhh10pYPW.l2QzF.DgjmOCLVio5bBp7mnbKHvKYOzFC2AEbko8P6', 'rkHFyMZ1kMS5PspJvDK6sWgLtm1NNyFJYzg51eU58qEjGvhqY6DqvU0SF3nF', '2016-12-20 09:18:01', '2017-01-20 13:28:02', '004', NULL, NULL, 'fr', 'avatar_default.jpg', 'fr');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
