-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-09-2025 a las 00:43:07
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `green_valley_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contacto`
--

CREATE TABLE `contacto` (
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

CREATE TABLE `cotizacion` (
  `id_cotizacion` int(11) NOT NULL,
  `rut_usuario` varchar(10) NOT NULL,
  `id_modelo` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL,
  `id_kit` int(11) NOT NULL,
  `Id_servicio` int(11) NOT NULL,
  `estado` enum('pendiente','aceptada') NOT NULL DEFAULT 'pendiente',
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `precio_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kit`
--

CREATE TABLE `kit` (
  `id_kit` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_kit` decimal(10,2) NOT NULL,
  `tipo_kit` enum('iniciar','estructural','completo') DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo_casa`
--

CREATE TABLE `modelo_casa` (
  `id_modelo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `superficie` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_casa` decimal(12,2) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `id_kit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_cotizacion` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo` enum('debito','credito','transferencia') NOT NULL,
  `validado` tinyint(1) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_entrega_estimada` datetime NOT NULL,
  `estado` enum('iniciado','en construccion','entregado') DEFAULT NULL,
  `comprobante_url` varchar(255) NOT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_cotizacion`
--

CREATE TABLE `pedido_cotizacion` (
  `id_cotizacion` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto`
--

CREATE TABLE `proyecto` (
  `id_proyecto` int(11) NOT NULL,
  `superficie` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `precio_proyecto` decimal(10,2) NOT NULL,
  `imagen_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_adicional`
--

CREATE TABLE `servicio_adicional` (
  `id_servicio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_servicio` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_casa`
--

CREATE TABLE `stock_casa` (
  `id_stock` int(11) NOT NULL,
  `id_modelo` int(11) NOT NULL,
  `cantidad_disponible` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `rut` varchar(10) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `rol` enum('administrador','vendedor','cliente') NOT NULL,
  `estado` enum('activo','bloqueado') NOT NULL DEFAULT 'activo',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`rut`, `nombre`, `apellido`, `correo`, `contrasena`, `telefono`, `direccion`, `region`, `rol`, `estado`, `fecha_creacion`) VALUES
('0', 'Javier', 'Espinoza', 'Javier@gmail.com', 'Root', '+569', '', '', 'administrador', 'activo', '2025-08-10 20:21:45'),
('1', 'Juana', 'espinozaa', 'pruebas@gmail.com', 'root', '+5699', '', '', 'vendedor', 'activo', '2025-08-10 21:46:17'),
('2', 'Felipe', 'espinoza', 'prueba1@gmail.com', 'root', '+569976', '', '', '', 'activo', '2025-08-10 21:46:39');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `contacto`
--
ALTER TABLE `contacto`
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD PRIMARY KEY (`id_cotizacion`),
  ADD KEY `id_usuario` (`rut_usuario`),
  ADD KEY `id_modelo` (`id_modelo`),
  ADD KEY `id_proyecto` (`id_proyecto`),
  ADD KEY `fk_cotizacion_kit` (`id_kit`),
  ADD KEY `fk_cotizacion_servicio` (`Id_servicio`);

--
-- Indices de la tabla `kit`
--
ALTER TABLE `kit`
  ADD PRIMARY KEY (`id_kit`);

--
-- Indices de la tabla `modelo_casa`
--
ALTER TABLE `modelo_casa`
  ADD PRIMARY KEY (`id_modelo`),
  ADD KEY `fk_modelocasa_kit` (`id_kit`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cotizacion` (`id_cotizacion`);

--
-- Indices de la tabla `pedido_cotizacion`
--
ALTER TABLE `pedido_cotizacion`
  ADD PRIMARY KEY (`id_cotizacion`,`id_pedido`),
  ADD KEY `fk_pc_pedido` (`id_pedido`);

--
-- Indices de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  ADD PRIMARY KEY (`id_proyecto`);

--
-- Indices de la tabla `servicio_adicional`
--
ALTER TABLE `servicio_adicional`
  ADD PRIMARY KEY (`id_servicio`);

--
-- Indices de la tabla `stock_casa`
--
ALTER TABLE `stock_casa`
  ADD PRIMARY KEY (`id_stock`),
  ADD KEY `fk_stock_modelo` (`id_modelo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`rut`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  MODIFY `id_cotizacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `kit`
--
ALTER TABLE `kit`
  MODIFY `id_kit` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modelo_casa`
--
ALTER TABLE `modelo_casa`
  MODIFY `id_modelo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  MODIFY `id_proyecto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `servicio_adicional`
--
ALTER TABLE `servicio_adicional`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD CONSTRAINT `cotizacion_ibfk_2` FOREIGN KEY (`id_modelo`) REFERENCES `modelo_casa` (`id_modelo`),
  ADD CONSTRAINT `fk_cotizacion_kit` FOREIGN KEY (`id_kit`) REFERENCES `kit` (`id_kit`),
  ADD CONSTRAINT `fk_cotizacion_servicio` FOREIGN KEY (`Id_servicio`) REFERENCES `servicio_adicional` (`id_servicio`),
  ADD CONSTRAINT `fk_cotizacion_usuario` FOREIGN KEY (`rut_usuario`) REFERENCES `usuario` (`rut`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `modelo_casa`
--
ALTER TABLE `modelo_casa`
  ADD CONSTRAINT `fk_modelocasa_kit` FOREIGN KEY (`id_kit`) REFERENCES `kit` (`id_kit`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_cotizacion` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_cotizacion`
--
ALTER TABLE `pedido_cotizacion`
  ADD CONSTRAINT `fk_pc_cotizacion` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`),
  ADD CONSTRAINT `fk_pc_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);

--
-- Filtros para la tabla `stock_casa`
--
ALTER TABLE `stock_casa`
  ADD CONSTRAINT `fk_stock_modelo` FOREIGN KEY (`id_modelo`) REFERENCES `modelo_casa` (`id_modelo`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
