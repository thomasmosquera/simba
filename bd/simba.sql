-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-10-2025 a las 17:33:17
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `simba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emergencia`
--

CREATE TABLE `emergencia` (
  `idemer` bigint(11) NOT NULL,
  `idusu` bigint(11) DEFAULT NULL,
  `nomemer` varchar(255) NOT NULL,
  `telemer` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evidencia`
--

CREATE TABLE `evidencia` (
  `idevi` bigint(11) NOT NULL,
  `idres` bigint(11) DEFAULT NULL,
  `idusu` bigint(11) DEFAULT NULL,
  `arcevi` varchar(255) DEFAULT NULL,
  `desevi` varchar(500) DEFAULT NULL,
  `fecevi` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascota`
--

CREATE TABLE `mascota` (
  `idmas` bigint(11) NOT NULL,
  `idusu` bigint(11) DEFAULT NULL,
  `nommas` varchar(100) DEFAULT NULL,
  `sexmas` varchar(100) DEFAULT NULL,
  `pesomas` decimal(5,2) DEFAULT NULL,
  `razamas` varchar(100) DEFAULT NULL,
  `edadmas` int(4) DEFAULT NULL,
  `tipomas` varchar(100) DEFAULT NULL,
  `tammas` decimal(5,2) DEFAULT NULL,
  `cuidmas` varchar(300) DEFAULT NULL,
  `vacmas` varchar(300) DEFAULT NULL,
  `carmas` varchar(500) DEFAULT NULL,
  `fotmas` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `idnot` bigint(11) NOT NULL,
  `mennot` varchar(700) DEFAULT NULL,
  `fecnot` datetime DEFAULT NULL,
  `leida` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagina`
--

CREATE TABLE `pagina` (
  `idpag` bigint(11) NOT NULL,
  `nompag` varchar(255) DEFAULT NULL,
  `rutpag` varchar(255) DEFAULT NULL,
  `mospag` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagina`
--

INSERT INTO `pagina` (`idpag`, `nompag`, `rutpag`, `mospag`) VALUES
(1, 'Prueba', 'vpag.php', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE `perfil` (
  `idper` bigint(11) NOT NULL,
  `nomper` varchar(255) DEFAULT NULL,
  `pgini` int(5) DEFAULT NULL,
  `insper` tinyint(1) DEFAULT NULL,
  `updper` tinyint(1) DEFAULT NULL,
  `delper` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`idper`, `nomper`, `pgini`, `insper`, `updper`, `delper`) VALUES
(1, 'Administrador', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pxp`
--

CREATE TABLE `pxp` (
  `idpxp` bigint(11) NOT NULL,
  `idpag` bigint(11) DEFAULT NULL,
  `idper` bigint(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `idres` bigint(11) NOT NULL,
  `idusu` bigint(11) DEFAULT NULL,
  `idmas` bigint(11) DEFAULT NULL,
  `fecact` datetime DEFAULT NULL,
  `estres` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`idres`, `idusu`, `idmas`, `fecact`, `estres`) VALUES
(1, NULL, NULL, '2025-10-06 10:31:00', 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `serres`
--

CREATE TABLE `serres` (
  `idserres` bigint(11) NOT NULL,
  `idres` bigint(11) DEFAULT NULL,
  `idser` bigint(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `idser` bigint(11) NOT NULL,
  `nomser` varchar(255) DEFAULT NULL,
  `preser` decimal(5,3) DEFAULT NULL,
  `descser` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idusu` bigint(11) NOT NULL,
  `idper` bigint(11) DEFAULT NULL,
  `nomusu` varchar(255) DEFAULT NULL,
  `apeusu` varchar(255) DEFAULT NULL,
  `emausu` varchar(255) DEFAULT NULL,
  `telusus` varchar(20) DEFAULT NULL,
  `dirusu` varchar(255) DEFAULT NULL,
  `contusu` varchar(255) DEFAULT NULL,
  `cedusu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idusu`, `idper`, `nomusu`, `apeusu`, `emausu`, `telusus`, `dirusu`, `contusu`, `cedusu`) VALUES
(5, 1, 'Juan', 'Pérez', 'juanperez@example.com', '3001234567', 'Calle 123 #45-67', '$2y$10$WLoChgqGXRjMWJno/5.xe.RuDhZrBas0Acs3XrabOj9jGj8.hu8hO', '1100'),
(6, 1, 'brayan', 'lopez', 'dlopezespinosa04@gmail.com', '3214079456', 'Calle 123 #45-67', '$2y$10$dj5yQnJ4xEdSTP8p3WQvROpBHbDPqdjLtzjhJv//h/87/eGSBOh6e', '1072643902');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usunot`
--

CREATE TABLE `usunot` (
  `idusunot` bigint(11) NOT NULL,
  `idusu` bigint(11) DEFAULT NULL,
  `idnot` bigint(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `emergencia`
--
ALTER TABLE `emergencia`
  ADD PRIMARY KEY (`idemer`),
  ADD KEY `fkemus` (`idusu`);

--
-- Indices de la tabla `evidencia`
--
ALTER TABLE `evidencia`
  ADD PRIMARY KEY (`idevi`),
  ADD KEY `fkevre` (`idres`),
  ADD KEY `fkevus` (`idusu`);

--
-- Indices de la tabla `mascota`
--
ALTER TABLE `mascota`
  ADD PRIMARY KEY (`idmas`),
  ADD KEY `fkmsus` (`idusu`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`idnot`);

--
-- Indices de la tabla `pagina`
--
ALTER TABLE `pagina`
  ADD PRIMARY KEY (`idpag`);

--
-- Indices de la tabla `perfil`
--
ALTER TABLE `perfil`
  ADD PRIMARY KEY (`idper`);

--
-- Indices de la tabla `pxp`
--
ALTER TABLE `pxp`
  ADD PRIMARY KEY (`idpxp`),
  ADD KEY `fkpxpg` (`idpag`),
  ADD KEY `fkpxpr` (`idper`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`idres`),
  ADD KEY `fkrems` (`idmas`),
  ADD KEY `fkreus` (`idusu`);

--
-- Indices de la tabla `serres`
--
ALTER TABLE `serres`
  ADD PRIMARY KEY (`idserres`),
  ADD KEY `fksrre` (`idres`),
  ADD KEY `fksrse` (`idser`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`idser`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idusu`),
  ADD UNIQUE KEY `emausu` (`emausu`),
  ADD UNIQUE KEY `cedusu` (`cedusu`),
  ADD KEY `fkuspr` (`idper`);

--
-- Indices de la tabla `usunot`
--
ALTER TABLE `usunot`
  ADD PRIMARY KEY (`idusunot`),
  ADD KEY `fkunus` (`idusu`),
  ADD KEY `fkunnt` (`idnot`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `emergencia`
--
ALTER TABLE `emergencia`
  MODIFY `idemer` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `evidencia`
--
ALTER TABLE `evidencia`
  MODIFY `idevi` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mascota`
--
ALTER TABLE `mascota`
  MODIFY `idmas` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `idnot` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pagina`
--
ALTER TABLE `pagina`
  MODIFY `idpag` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `perfil`
--
ALTER TABLE `perfil`
  MODIFY `idper` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pxp`
--
ALTER TABLE `pxp`
  MODIFY `idpxp` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `idres` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `serres`
--
ALTER TABLE `serres`
  MODIFY `idserres` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `idser` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusu` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usunot`
--
ALTER TABLE `usunot`
  MODIFY `idusunot` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `emergencia`
--
ALTER TABLE `emergencia`
  ADD CONSTRAINT `fkemus` FOREIGN KEY (`idusu`) REFERENCES `usuario` (`idusu`);

--
-- Filtros para la tabla `evidencia`
--
ALTER TABLE `evidencia`
  ADD CONSTRAINT `fkevre` FOREIGN KEY (`idres`) REFERENCES `reserva` (`idres`),
  ADD CONSTRAINT `fkevus` FOREIGN KEY (`idusu`) REFERENCES `usuario` (`idusu`);

--
-- Filtros para la tabla `mascota`
--
ALTER TABLE `mascota`
  ADD CONSTRAINT `fkmsus` FOREIGN KEY (`idusu`) REFERENCES `usuario` (`idusu`);

--
-- Filtros para la tabla `pxp`
--
ALTER TABLE `pxp`
  ADD CONSTRAINT `fkpxpg` FOREIGN KEY (`idpag`) REFERENCES `pagina` (`idpag`),
  ADD CONSTRAINT `fkpxpr` FOREIGN KEY (`idper`) REFERENCES `perfil` (`idper`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fkrems` FOREIGN KEY (`idmas`) REFERENCES `mascota` (`idmas`),
  ADD CONSTRAINT `fkreus` FOREIGN KEY (`idusu`) REFERENCES `usuario` (`idusu`);

--
-- Filtros para la tabla `serres`
--
ALTER TABLE `serres`
  ADD CONSTRAINT `fksere` FOREIGN KEY (`idres`) REFERENCES `reserva` (`idres`),
  ADD CONSTRAINT `fksrse` FOREIGN KEY (`idser`) REFERENCES `servicio` (`idser`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fkuspr` FOREIGN KEY (`idper`) REFERENCES `perfil` (`idper`);

--
-- Filtros para la tabla `usunot`
--
ALTER TABLE `usunot`
  ADD CONSTRAINT `fkunnt` FOREIGN KEY (`idnot`) REFERENCES `notificacion` (`idnot`),
  ADD CONSTRAINT `fkunus` FOREIGN KEY (`idusu`) REFERENCES `usuario` (`idusu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
