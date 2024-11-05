-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 05-11-2024 a las 05:42:10
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `adoptapetcienega`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adopciones`
--

DROP TABLE IF EXISTS `adopciones`;
CREATE TABLE IF NOT EXISTS `adopciones` (
  `id_adopcion` int NOT NULL AUTO_INCREMENT,
  `mascota_id` int NOT NULL,
  `nombre_adoptante` varchar(60) COLLATE utf8mb3_spanish_ci NOT NULL,
  `correo_adoptante` varchar(65) COLLATE utf8mb3_spanish_ci NOT NULL,
  `fecha_adopcion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_adopcion`),
  KEY `mascota_id` (`mascota_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatus_mascota`
--

DROP TABLE IF EXISTS `estatus_mascota`;
CREATE TABLE IF NOT EXISTS `estatus_mascota` (
  `mascota_id` int NOT NULL,
  `estatus` varchar(11) COLLATE utf8mb3_spanish_ci NOT NULL,
  KEY `mascota_id` (`mascota_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

DROP TABLE IF EXISTS `mascotas`;
CREATE TABLE IF NOT EXISTS `mascotas` (
  `id_mascota` int NOT NULL AUTO_INCREMENT,
  `nombre_mascota` varchar(30) COLLATE utf8mb3_spanish_ci NOT NULL,
  `tipo_mascota` int NOT NULL,
  `raza` varchar(30) COLLATE utf8mb3_spanish_ci NOT NULL,
  `edad` tinyint NOT NULL,
  `sexo` char(1) COLLATE utf8mb3_spanish_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb3_spanish_ci NOT NULL,
  `ubicacion_actual` varchar(60) COLLATE utf8mb3_spanish_ci NOT NULL,
  `fecha_adopcion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen` blob NOT NULL,
  `usuario_id` int NOT NULL,
  PRIMARY KEY (`id_mascota`),
  KEY `usuario_id` (`usuario_id`),
  KEY `tipo_mascota` (`tipo_mascota`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_mascotas`
--

DROP TABLE IF EXISTS `tipos_mascotas`;
CREATE TABLE IF NOT EXISTS `tipos_mascotas` (
  `id_tipo` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) COLLATE utf8mb3_spanish_ci NOT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `tipos_mascotas`
--

INSERT INTO `tipos_mascotas` (`id_tipo`, `descripcion`) VALUES
(1, 'Perro'),
(2, 'Gato');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_usuarios`
--

DROP TABLE IF EXISTS `tipos_usuarios`;
CREATE TABLE IF NOT EXISTS `tipos_usuarios` (
  `id_tipo` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(10) COLLATE utf8mb3_spanish_ci NOT NULL,
  PRIMARY KEY (`id_tipo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `tipos_usuarios`
--

INSERT INTO `tipos_usuarios` (`id_tipo`, `descripcion`) VALUES
(1, 'visitante'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) COLLATE utf8mb3_spanish_ci NOT NULL,
  `nombre_completo` varchar(65) COLLATE utf8mb3_spanish_ci NOT NULL,
  `contraseña` varchar(32) COLLATE utf8mb3_spanish_ci NOT NULL,
  `correo` varchar(50) COLLATE utf8mb3_spanish_ci NOT NULL,
  `telefono` bigint NOT NULL,
  `fec_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo_usuario` int NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `tipo_usuario` (`tipo_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `nombre_completo`, `contraseña`, `correo`, `telefono`, `fec_registro`, `tipo_usuario`) VALUES
(1, 'luisalvareztv', 'Luis Antonio Alvarez Mayoral', '9f451b3fe936a85a0b853c308f99cc72', 'luis.alvarez6917@alumnos.udg.mx', 3319895604, '2024-11-05 05:40:54', 2),
(2, 'luuiisaalvaareez', 'Luis Alvarez', '8b511b330c8ea4aeb9448b551b7d2c5f', 'gollo235@gmail.com', 3919215257, '2024-11-05 05:41:49', 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `adopciones`
--
ALTER TABLE `adopciones`
  ADD CONSTRAINT `adopciones_ibfk_1` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id_mascota`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estatus_mascota`
--
ALTER TABLE `estatus_mascota`
  ADD CONSTRAINT `estatus_mascota_ibfk_1` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id_mascota`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`tipo_mascota`) REFERENCES `tipos_mascotas` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`tipo_usuario`) REFERENCES `tipos_usuarios` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
