-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 06-11-2024 a las 06:42:13
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
  `descripcion` varchar(300) CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish_ci NOT NULL,
  `ubicacion_actual` varchar(60) COLLATE utf8mb3_spanish_ci NOT NULL,
  `fecha_adopcion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen` varchar(250) COLLATE utf8mb3_spanish_ci NOT NULL,
  `usuario_id` int NOT NULL,
  PRIMARY KEY (`id_mascota`),
  KEY `tipo_mascota` (`tipo_mascota`,`usuario_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id_mascota`, `nombre_mascota`, `tipo_mascota`, `raza`, `edad`, `sexo`, `descripcion`, `ubicacion_actual`, `fecha_adopcion`, `imagen`, `usuario_id`) VALUES
(1, 'Coxis bebe', 2, 'desconocido', 1, 'H', 'Es una gata muy educada, le encantan los premios', 'Vicente Guerrero 48 #7 Zapotlán del Rey', '2024-11-05 18:32:31', 'fotos/672a64bf7f74f-gato.jpg', 1),
(3, 'Frida Sofia', 1, 'Chihuahua', 5, 'H', 'Es muy fiel a su dueño, le encanta la atención de su dueño y aparte le encantan los premios', 'González Gallo 48 Poncitlán', '2024-11-06 05:51:46', 'fotos/672b03f2bde27-R.jpg', 1),
(4, 'Luiso Fernando', 1, 'Pug', 4, 'M', 'Le encanta divertirse mucho en los parques y áreas verdes', 'Hidalgo 165 Ocotlán', '2024-11-06 06:41:41', 'fotos/672b0fa52329a-R (1).jpg', 3);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `nombre_completo`, `contraseña`, `correo`, `telefono`, `fec_registro`, `tipo_usuario`) VALUES
(1, 'luisalvareztv', 'Luis Antonio Alvarez Mayoral', '9f451b3fe936a85a0b853c308f99cc72', 'luis.alvarez6917@alumnos.udg.mx', 3319895604, '2024-11-05 18:27:51', 1),
(2, 'Diego', 'Diego Ibarra', 'dcff38ba9e3ee099edafd2606e35a6b6', 'ibarraramirez01@gmail.com', 3931171442, '2024-11-05 18:30:16', 1),
(3, 'luuiisaalvaareez', 'Luis Antonio Mayoral', '8b511b330c8ea4aeb9448b551b7d2c5f', 'gollo235@gmail.com', 3919215257, '2024-11-06 06:38:51', 1);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `adopciones`
--
ALTER TABLE `adopciones`
  ADD CONSTRAINT `adopciones_ibfk_1` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id_mascota`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`tipo_mascota`) REFERENCES `tipos_mascotas` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mascotas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`tipo_usuario`) REFERENCES `tipos_usuarios` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
