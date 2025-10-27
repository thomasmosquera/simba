-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-10-2025 a las 19:49:59
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

--
-- Volcado de datos para la tabla `emergencia`
--

INSERT INTO `emergencia` (`idemer`, `idusu`, `nomemer`, `telemer`) VALUES
(2, 8, 'Brayan Lopez', '3214079456'),
(3, 9, 'Roque', '3108627688'),
(4, 10, 'Thomas', '3168820833'),
(5, 11, 'Yilbert', '3232872968'),
(6, 12, 'Mayra Cuchumbe', '3208091583'),
(7, 13, 'Alberto', '1111111111');

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
  `fecevi` datetime DEFAULT NULL,
  `resp` varchar(100) DEFAULT NULL,
  `tipevi` varchar(50) DEFAULT NULL
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

--
-- Volcado de datos para la tabla `mascota`
--

INSERT INTO `mascota` (`idmas`, `idusu`, `nommas`, `sexmas`, `pesomas`, `razamas`, `edadmas`, `tipomas`, `tammas`, `cuidmas`, `vacmas`, `carmas`, `fotmas`) VALUES
(1, 6, 'Tobi', 'Macho', 124.00, 'Pastor', 25, 'Foto', 12.00, '123', 'Si', NULL, NULL);

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
(1, 'prueba', 'vprue.php', 1);

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
(1, 'Administrador', 1, 1, 1, 1),
(2, 'Cuidador', 0, 0, 0, 0),
(3, 'Cliente', 0, 0, 0, 0),
(4, 'Desarrollador', 1001, 1, 1, 1);

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
(2, 13, 1, '0000-00-00 00:00:00', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `serres`
--

CREATE TABLE `serres` (
  `idserres` bigint(11) NOT NULL,
  `idres` bigint(11) DEFAULT NULL,
  `idser` bigint(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `serres`
--

INSERT INTO `serres` (`idserres`, `idres`, `idser`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 2, 3);

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

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`idser`, `nomser`, `preser`, `descser`) VALUES
(1, 'Baño básico', 99.999, 'Baño con agua tibia y shampoo antipulgas, secado con toalla y cepillado general. Ideal para mantener la higiene de la mascota.'),
(2, 'Baño + Corte de uñas', 40.000, 'Incluye baño completo, limpieza de orejas, secado con aire y corte de uñas con cuidado especializado.'),
(3, 'Baño premium con peinado', 60.000, 'Servicio completo con shampoo dermatológico, secado profesional, peinado y perfume hipoalergénico.'),
(4, 'Guardería por día', 45.000, 'Cuidado diurno de la mascota en un entorno seguro, con juegos, alimentación y socialización.'),
(5, 'Paquete de guardería (5 días)', 99.999, 'Paquete semanal de 5 días de guardería con actividades recreativas, alimentación y monitoreo fotográfico diario a través de la plataforma SIMBA.'),
(6, 'Spa relajante', 70.000, 'Baño con aromaterapia, masaje antiestrés y cepillado profundo. Ideal para mascotas que necesitan relajación.');

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
(6, 1, 'Brayan', 'Lopez', 'dlopezespinosa04@gmail.com', '3214079456', 'Calle 123 #45-67', '$2y$10$beQtoBvbJLh3heXSbjEtAuLVHsrW93IwIpj.hwSFnyX1sr286kh02', '1072643902'),
(8, 1, 'juan', 'perez', 'juanprueba@prueba.com', '0000000000', 'casasd', '$2y$10$XB8VCl/SAPHvwsEorApnleT.a/0hzrOu0cLeKHeD.iEbz/PcG/4Ti', '00000000'),
(9, 1, 'Roque', 'Galeano', 'roquegaleano301@gmail.com', '3108627688', 'CRA 10A 10-34', '$2y$10$CNElFWA2hmg4AxWgE/nFou6OC3KSSUDvzb7pnHbnIVCUzWHZyI/j.', '1072643266'),
(10, 1, 'Thomas', 'Mosquera', 'thomasmosquerapedrazaa@gmail.com', '3168820833', 'Calle 13 #5-02', '$2y$10$4rqEF9h3oZ2y5jsQYi/CNejzkKJx7487fEVc.8YbEQ3TIPHypD5Wm', '1032938133'),
(11, 1, 'Yilbert', 'Jimenez', 'yilbertprog21@gmail.com', '3232872968', 'Calle 123', '$2y$10$tQf325C7j1/n.fqzYSzwNOXrDqgRoIU1.cFLslcsr72dOgwPZ/ETG', '1076737473'),
(12, 1, ' Mayra', 'Cuchumbe', 'malexacc10@gmail.com', '3208091583', 'Madrid, Cundinamarca ', '$2y$10$ZhUeFFBv2F2en3x2Y5ASmuSqq1JLyXZptBkwTxu7HgChwKRBLm6mi', '1007155164'),
(13, 2, 'Alberto', 'Rodriguez', 'alberto.cuidador@gmai.com', '1111111111', 'AAAAAAAA', '$2y$10$WGGWiXuXlMkOjX3T2EgH9uC3n8oJL1AfEmGkUXzFuesA9vTp6lzDO', '00000001');

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
  MODIFY `idemer` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `evidencia`
--
ALTER TABLE `evidencia`
  MODIFY `idevi` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mascota`
--
ALTER TABLE `mascota`
  MODIFY `idmas` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `idper` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pxp`
--
ALTER TABLE `pxp`
  MODIFY `idpxp` bigint(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `idres` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `serres`
--
ALTER TABLE `serres`
  MODIFY `idserres` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `idser` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idusu` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
