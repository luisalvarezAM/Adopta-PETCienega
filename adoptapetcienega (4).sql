-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-05-2025 a las 06:25:43
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
-- Base de datos: `adoptapetcienega`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `adopciones`
--

CREATE TABLE `adopciones` (
  `id_adopcion` int(11) NOT NULL,
  `mascota_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre_adoptante` varchar(60) NOT NULL,
  `numero_telefonico` bigint(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `fecha_adopcion` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagen_evidencia` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `adopciones`
--

INSERT INTO `adopciones` (`id_adopcion`, `mascota_id`, `usuario_id`, `nombre_adoptante`, `numero_telefonico`, `correo`, `fecha_adopcion`, `imagen_evidencia`) VALUES
(1, 2, 2, 'Luis Alvarez ', 3319895604, 'gollo235@gmail.com', '2025-05-07 06:00:00', '../assets/img/evidencia_adopciones/681b3faa2368b_680fb99486d80-Imagen de WhatsApp 2025-04-22 a las 10.59.37_2a8cca09.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estatus_adopcion`
--

CREATE TABLE `estatus_adopcion` (
  `id_estatus` int(11) NOT NULL,
  `nombre_estatus` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estatus_adopcion`
--

INSERT INTO `estatus_adopcion` (`id_estatus`, `nombre_estatus`) VALUES
(1, 'Disponible'),
(2, 'En proceso'),
(3, 'Adoptado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `interesados`
--

CREATE TABLE `interesados` (
  `id` int(11) NOT NULL,
  `id_interesado` int(11) NOT NULL,
  `id_mascota` int(11) NOT NULL,
  `fecha_interes` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `interesados`
--

INSERT INTO `interesados` (`id`, `id_interesado`, `id_mascota`, `fecha_interes`) VALUES
(1, 2, 2, '2025-05-07 09:23:09'),
(2, 26, 3, '2025-05-07 15:40:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mascotas`
--

CREATE TABLE `mascotas` (
  `id_mascota` int(11) NOT NULL,
  `nombre_mascota` varchar(30) NOT NULL,
  `tipo_mascota` int(11) NOT NULL,
  `raza` varchar(30) NOT NULL,
  `edad` tinyint(4) NOT NULL,
  `sexo` char(1) NOT NULL,
  `descripcion` varchar(300) NOT NULL,
  `municipio` int(11) NOT NULL,
  `direccion` varchar(60) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `imagen` varchar(250) NOT NULL,
  `estatus_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mascotas`
--

INSERT INTO `mascotas` (`id_mascota`, `nombre_mascota`, `tipo_mascota`, `raza`, `edad`, `sexo`, `descripcion`, `municipio`, `direccion`, `fecha_registro`, `imagen`, `estatus_id`, `usuario_id`) VALUES
(2, 'dasdas', 1, 'Pitbull', 3, 'M', 'Le encantan los  premios y jugar con sus dueños', 7, 'Vicente Guerrero 48 #7 ', '2025-05-07 08:08:30', '../assets/img/fotos_mascotas/mascota_681b14febadc53.77375442.jpg', 3, 2),
(3, 'Luis Roberto', 2, 'Desconocido', 2, 'H', 'Le encantan los premios', 8, 'Vicente Guerrero 48', '2025-05-07 11:41:11', '../assets/img/fotos_mascotas/mascota_681b46d7325849.28282511.jpg', 1, 2),
(4, 'Ximena', 2, 'Desconocido', 2, 'H', 'Le encantan los premios', 5, 'Vicente Guerrero 48', '2025-05-07 11:42:15', '../assets/img/fotos_mascotas/mascota_681b47171c6035.28874874.jpg', 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE `municipios` (
  `id_municipio` int(11) NOT NULL,
  `nombre_municipio` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO `municipios` (`id_municipio`, `nombre_municipio`) VALUES
(1, 'Atotonilco el Alto'),
(2, 'Ayotlán'),
(3, 'Degollado'),
(4, 'Jamay'),
(5, 'La barca'),
(6, 'Ocotlán'),
(7, 'Poncitlán'),
(8, 'Tototlán'),
(9, 'Zapotlán del Rey');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_mascotas`
--

CREATE TABLE `tipos_mascotas` (
  `id_tipo` int(11) NOT NULL,
  `descripcion` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipos_mascotas`
--

INSERT INTO `tipos_mascotas` (`id_tipo`, `descripcion`) VALUES
(1, 'Perro'),
(2, 'Gato'),
(3, 'Ave'),
(4, 'Conejo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_usuarios`
--

CREATE TABLE `tipos_usuarios` (
  `id_tipo` int(11) NOT NULL,
  `descripcion` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

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

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `nombre_completo` varchar(65) NOT NULL,
  `contraseña` varchar(32) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `telefono` bigint(20) NOT NULL,
  `municipio` int(11) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `img_perfil` varchar(250) NOT NULL,
  `fec_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `nombre_completo`, `contraseña`, `correo`, `telefono`, `municipio`, `direccion`, `img_perfil`, `fec_registro`, `tipo_usuario`) VALUES
(1, 'admin1', 'Luis Alvarez', '9f451b3fe936a85a0b853c308f99cc72', 'luis.alvarez6917@alumnos.udg.mx', 3319895604, 9, 'Vicente Guerrero 48 ', '../assets/img/fotos_perfil/admin1.jpg', '2025-04-30 02:09:20', 2),
(2, 'luisalvareztv', 'Luis Alvarez ', '8b511b330c8ea4aeb9448b551b7d2c5f', 'gollo235@gmail.com', 3319895604, 5, 'Vicente Guerrero ', '../assets/img/fotos_perfil/luisalvareztv.jpg', '2025-04-30 21:23:46', 1),
(8, 'cesarcasta1978', 'Cesar Castañeda Flores', 'c8220409ccedb2b3ef3adeb596584158', 'cesarcast23@gmail.com', 3325547852, 9, 'Vicente Guerrero 48 #7', '../assets/img/fotos_perfil/cesarcasta1978.jpg', '2025-05-07 01:31:17', 1),
(25, 'paulina2011', 'Paulina Castañeda Mayoral', '861e9318f3c4a1b201f74837f0d2907f', 'Paulina@gmail.com', 3314225841, 3, 'Vicente Guerrero 48', '../assets/img/fotos_perfil/paulina2011.jpg', '2025-05-07 17:38:40', 1),
(26, 'diego2003', 'Diego Ibarra', '5ca16db59c9791b874c7c2d5f339de21', 'diego@gmail.com', 3325698745, 5, 'Vicente Guerrero #123', '../assets/img/fotos_perfil/profile_681b7df23105b.jpg', '2025-05-07 23:36:18', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `adopciones`
--
ALTER TABLE `adopciones`
  ADD PRIMARY KEY (`id_adopcion`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`),
  ADD KEY `mascota_id` (`mascota_id`);

--
-- Indices de la tabla `estatus_adopcion`
--
ALTER TABLE `estatus_adopcion`
  ADD PRIMARY KEY (`id_estatus`);

--
-- Indices de la tabla `interesados`
--
ALTER TABLE `interesados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_interesado`,`id_mascota`),
  ADD KEY `id_mascota` (`id_mascota`),
  ADD KEY `id_interesado` (`id_interesado`);

--
-- Indices de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD PRIMARY KEY (`id_mascota`),
  ADD UNIQUE KEY `municipio` (`municipio`),
  ADD KEY `tipo_mascota` (`tipo_mascota`,`usuario_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `id_estatus` (`estatus_id`);

--
-- Indices de la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id_municipio`);

--
-- Indices de la tabla `tipos_mascotas`
--
ALTER TABLE `tipos_mascotas`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  ADD PRIMARY KEY (`id_tipo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `tipo_usuario` (`tipo_usuario`),
  ADD KEY `municipio` (`municipio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `adopciones`
--
ALTER TABLE `adopciones`
  MODIFY `id_adopcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estatus_adopcion`
--
ALTER TABLE `estatus_adopcion`
  MODIFY `id_estatus` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `interesados`
--
ALTER TABLE `interesados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mascotas`
--
ALTER TABLE `mascotas`
  MODIFY `id_mascota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tipos_mascotas`
--
ALTER TABLE `tipos_mascotas`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipos_usuarios`
--
ALTER TABLE `tipos_usuarios`
  MODIFY `id_tipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `adopciones`
--
ALTER TABLE `adopciones`
  ADD CONSTRAINT `adopciones_ibfk_1` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id_mascota`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `interesados`
--
ALTER TABLE `interesados`
  ADD CONSTRAINT `interesados_ibfk_1` FOREIGN KEY (`id_mascota`) REFERENCES `mascotas` (`id_mascota`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `interesados_ibfk_2` FOREIGN KEY (`id_interesado`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mascotas`
--
ALTER TABLE `mascotas`
  ADD CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`tipo_mascota`) REFERENCES `tipos_mascotas` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mascotas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mascotas_ibfk_3` FOREIGN KEY (`estatus_id`) REFERENCES `estatus_adopcion` (`id_estatus`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mascotas_ibfk_4` FOREIGN KEY (`municipio`) REFERENCES `municipios` (`id_municipio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`tipo_usuario`) REFERENCES `tipos_usuarios` (`id_tipo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`municipio`) REFERENCES `municipios` (`id_municipio`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
