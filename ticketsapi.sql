-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-08-2021 a las 01:21:54
-- Versión del servidor: 8.0.23-0ubuntu0.20.04.1
-- Versión de PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ticketsapi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `state`
--

CREATE TABLE `state` (
  `id` int NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `state`
--

INSERT INTO `state` (`id`, `name`) VALUES
(1, 'inicial');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `state_id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `active` varchar(1) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `state_id`, `title`, `text`, `active`, `date`) VALUES
(1, 1, 1, 'titulo!', 'texto del titulo!', '1', NULL),
(2, 1, 1, 'ticket prueba', 'texto!', '1', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `id_user_type` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `password` longtext,
  `active` varchar(1) DEFAULT NULL,
  `date` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `id_user_type`, `name`, `lastname`, `email`, `password`, `active`, `date`) VALUES
(1, 1, 'alfredotest', 'gonzaleztest', 'alfredogon82@yahoo.com', '9726caea40f8377550d14e46c805f35f78f14863', '1', NULL),
(2, 1, 'alfredo', 'gonzalez', 'alfredogon82@yahoo.com', 'wefafa', NULL, NULL),
(3, 1, 'alfredo', 'gonzalez', 'alfredogon82@yahoo.com', '1q2w3e4r5T', NULL, NULL),
(4, 1, 'alfredo', 'gonzalez', 'alfredogon82@yahoo.com', '1q2w3e4r5T', NULL, NULL),
(5, 1, 'alfredo', 'gonzalez', 'alfredogon82@yahoo.com', '1q2w3e4r5T', NULL, NULL),
(6, 1, 'alfredo', 'gonzalez', 'alfredogon82@yahoo.com', '9726caea40f8377550d14e46c805f35f78f14863', NULL, NULL),
(7, 1, 'alfredo', 'gonzalez', 'alfredogon82@yahoo.com', '9726caea40f8377550d14e46c805f35f78f14863', NULL, NULL),
(8, 1, 'alfredo', 'gonzalez', 'alfredogon82@yahoo.com', '1', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_type`
--

CREATE TABLE `user_type` (
  `id` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `active` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `user_type`
--

INSERT INTO `user_type` (`id`, `name`, `active`) VALUES
(1, 'regular', '0'),
(2, 'admin', '0');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`,`user_id`,`state_id`),
  ADD KEY `fk_tickets_users1_idx` (`user_id`),
  ADD KEY `fk_tickets_state1_idx` (`state_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_user_type_idx` (`id_user_type`);

--
-- Indices de la tabla `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `state`
--
ALTER TABLE `state`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `fk_tickets_state1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`),
  ADD CONSTRAINT `fk_tickets_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_user_type` FOREIGN KEY (`id_user_type`) REFERENCES `user_type` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
