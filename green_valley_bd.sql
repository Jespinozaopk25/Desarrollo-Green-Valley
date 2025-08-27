-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-08-2025 a las 07:25:26
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

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_proyectos_aceptados` ()   BEGIN
    INSERT INTO proyecto (
        id_usuario,
        id_modelo,
        estado,
        fecha_inicio,
        fecha_entrega_estimada,
        id_pedido
    )
    SELECT
        id_usuario,
        id_modelo,
        estado,
        fecha,
        DATE_ADD(fecha, INTERVAL 30 DAY),
        id_cotizacion
    FROM cotizacion
    WHERE estado = 'aceptada'
    AND id_cotizacion NOT IN (
        SELECT id_pedido FROM proyecto
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertar_proyecto_15` ()   BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM proyecto WHERE id_pedido = 15
    ) THEN
        INSERT INTO proyecto (
            id_usuario,
            id_modelo,
            estado,
            fecha_inicio,
            fecha_entrega_estimada,
            id_pedido
        )
        SELECT
            id_usuario,
            id_modelo,
            estado,
            fecha,
            DATE_ADD(fecha, INTERVAL 30 DAY),
            id_cotizacion
        FROM cotizacion
        WHERE id_cotizacion = 15;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

CREATE TABLE `cotizacion` (
  `id_cotizacion` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_modelo` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(12,2) DEFAULT NULL,
  `estado` enum('pendiente','aceptada') DEFAULT NULL,
  `id_vendedor` int(11) NOT NULL,
  `modelo_casa` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cotizacion`
--

INSERT INTO `cotizacion` (`id_cotizacion`, `id_usuario`, `id_modelo`, `fecha`, `total`, `estado`, `id_vendedor`, `modelo_casa`, `region`, `observaciones`) VALUES
(13, 4, NULL, '2025-08-24 21:31:59', 7000.00, 'pendiente', 2, 'Casa Familiar 65m²', 'ynosee', ''),
(14, 6, NULL, '2025-08-24 21:32:28', 999999.00, 'pendiente', 2, 'Casa de lujo 120m²', 'noseeee', ''),
(15, 5, 7, '2025-08-24 21:45:05', 130.00, 'aceptada', 2, 'Casa Ejecutiva 120m²', 'puede ser', ''),
(16, 16, NULL, '2025-08-25 00:30:34', 6000.00, 'pendiente', 2, 'Casa de lujo 120m²', 'pudu', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_kit`
--

CREATE TABLE `cotizacion_kit` (
  `id_cotizacion` int(11) NOT NULL,
  `id_kit` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_servicio`
--

CREATE TABLE `cotizacion_servicio` (
  `id_cotizacion` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cronograma`
--

CREATE TABLE `cronograma` (
  `id_cronograma` int(11) NOT NULL,
  `id_proyecto` int(11) DEFAULT NULL,
  `descripcion_actividad` text DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `porcentaje_avance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `kit`
--

CREATE TABLE `kit` (
  `id_kit` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `tipo_kit` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `kit`
--

INSERT INTO `kit` (`id_kit`, `nombre`, `descripcion`, `precio`, `tipo_kit`, `activo`, `fecha_creacion`) VALUES
(1, 'Kit Cocina Premium', 'Incluye refrigerador, cocina encimera y campana extractora', 3500000.00, 'Electrodomésticos', 1, '2025-08-24 19:07:03'),
(2, 'Kit Baño Deluxe', 'Incluye tina, WC suspendido y lavamanos de diseño', 2200000.00, 'Sanitarios', 1, '2025-08-24 19:07:03'),
(3, 'Kit Seguridad', 'Incluye cámaras, alarmas y sensores de movimiento', 1500000.00, 'Seguridad', 1, '2025-08-24 19:07:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelo_casa`
--

CREATE TABLE `modelo_casa` (
  `id_modelo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `superficie` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_base` decimal(12,2) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modelo_casa`
--

INSERT INTO `modelo_casa` (`id_modelo`, `nombre`, `superficie`, `descripcion`, `precio_base`, `imagen_url`) VALUES
(1, 'Casa de lujo 120m²', 120, 'Casa prefabricada de lujo de 120m²', 9999999999.99, 'casa1.jpg'),
(4, 'Casa Moderna 45m²', 45, 'Casa prefabricada moderna de 1 dormitorio, ideal para parejas jóvenes. Incluye living-comedor, cocina americana, baño completo y dormitorio principal.', 25000000.00, '/images/casa_moderna_45.jpg'),
(5, 'Casa Familiar 65m²', 65, 'Casa prefabricada familiar de 2 dormitorios. Perfecta para familias pequeñas con living, comedor, cocina independiente, 2 dormitorios y baño.', 35000000.00, '/images/casa_familiar_65.jpg'),
(6, 'Casa Premium 85m²', 85, 'Casa prefabricada premium de 3 dormitorios. Incluye living, comedor, cocina equipada, 3 dormitorios, 2 baños y terraza cubierta.', 48000000.00, '/images/casa_premium_85.jpg'),
(7, 'Casa Ejecutiva 120m²', 120, 'Casa prefabricada ejecutiva de lujo. 3 dormitorios, 2 baños, living, comedor, cocina equipada, oficina y amplia terraza.', 65000000.00, '/images/casa_ejecutiva_120.jpg'),
(8, 'Casa Compacta 35m²', 35, 'Casa prefabricada compacta tipo estudio. Ideal para solteros o como casa de campo. Incluye área integrada y baño.', 18000000.00, '/images/casa_compacta_35.jpg'),
(10, 'Casa Moderna 45m²', 45, 'Casa prefabricada moderna de 1 dormitorio, ideal para parejas jóvenes. Incluye living-comedor, cocina americana, baño completo y dormitorio principal.', 25000000.00, '/images/casa_moderna_45.jpg'),
(11, 'Casa Familiar 65m²', 65, 'Casa prefabricada familiar de 2 dormitorios. Perfecta para familias pequeñas con living, comedor, cocina independiente, 2 dormitorios y baño.', 35000000.00, '/images/casa_familiar_65.jpg'),
(12, 'Casa Premium 85m²', 85, 'Casa prefabricada premium de 3 dormitorios. Incluye living, comedor, cocina equipada, 3 dormitorios, 2 baños y terraza cubierta.', 48000000.00, '/images/casa_premium_85.jpg'),
(13, 'Casa Ejecutiva 120m²', 120, 'Casa prefabricada ejecutiva de lujo. 3 dormitorios, 2 baños, living, comedor, cocina equipada, oficina y amplia terraza.', 65000000.00, '/images/casa_ejecutiva_120.jpg'),
(14, 'Casa Compacta 35m²', 35, 'Casa prefabricada compacta tipo estudio. Ideal para solteros o como casa de campo. Incluye área integrada y baño.', 18000000.00, '/images/casa_compacta_35.jpg'),
(15, 'Casa Rústica 90m²', 90, 'Casa prefabricada estilo rústico, con terraza techada y chimenea.', 52000000.00, '/images/casa_rustica_90.jpg'),
(16, 'Casa Minimalista 70m²', 70, 'Casa moderna y minimalista, ideal para parejas jóvenes. Incluye patio interior.', 40000000.00, '/images/casa_minimalista_70.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `monto` decimal(12,2) DEFAULT NULL,
  `metodo` enum('debito','credito','transferencia') DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `validado` tinyint(1) DEFAULT 0,
  `comprobante_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_cotizacion` int(11) DEFAULT NULL,
  `fecha_pedido` datetime DEFAULT NULL,
  `estado` enum('pendiente','aceptado','rechazado','cancelado') DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `id_pago` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `id_cotizacion`, `fecha_pedido`, `estado`, `observaciones`, `id_pago`, `id_usuario`) VALUES
(15, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto`
--

CREATE TABLE `proyecto` (
  `id_proyecto` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_modelo` int(11) DEFAULT NULL,
  `estado` enum('en diseño','en construcción','entregado','suspendido') DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_entrega_estimada` date DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyecto`
--

INSERT INTO `proyecto` (`id_proyecto`, `id_usuario`, `id_modelo`, `estado`, `fecha_inicio`, `fecha_entrega_estimada`, `id_pedido`) VALUES
(4, 5, 7, '', '2025-08-24', '2025-09-23', 15);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_adicional`
--

CREATE TABLE `servicio_adicional` (
  `id_servicio` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio_adicional`
--

INSERT INTO `servicio_adicional` (`id_servicio`, `nombre`, `descripcion`, `precio_unitario`, `fecha_creacion`) VALUES
(1, 'Instalación de Paneles Solares', 'Sistema solar fotovoltaico completo', 2500000.00, '2025-08-24 19:06:48'),
(2, 'Sistema de Calefacción Central', 'Instalación de calefacción central a gas', 1800000.00, '2025-08-24 19:06:48'),
(3, 'Paisajismo', 'Diseño e implementación de jardín y áreas verdes', 1200000.00, '2025-08-24 19:06:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_casa`
--

CREATE TABLE `stock_casa` (
  `id_stock` int(11) NOT NULL,
  `id_modelo` int(11) NOT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `estado` enum('disponible','reservado','vendido') DEFAULT 'disponible',
  `cantidad_disponible` int(11) DEFAULT 0,
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_casa`
--

INSERT INTO `stock_casa` (`id_stock`, `id_modelo`, `ubicacion`, `estado`, `cantidad_disponible`, `fecha_actualizacion`) VALUES
(1, 15, 'Bodega Central - Santiago', 'disponible', 6, '2025-08-24 22:56:44'),
(2, 16, 'Sucursal', 'reservado', 2, '2025-08-24 19:08:20'),
(3, 7, 'Sucursal', 'vendido', 0, '2025-08-24 19:08:20'),
(4, 1, 'Bodega Central - Santiago', 'disponible', 5, '2025-08-25 00:28:24'),
(5, 4, 'Bodega Central - Santiago', 'disponible', 20, '2025-08-24 23:12:46'),
(6, 7, 'Bodega Central - Santiago', 'disponible', 7, '2025-08-24 23:15:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol` enum('administrador','vendedor','usuario') NOT NULL,
  `estado` enum('activo','bloqueado') DEFAULT 'activo',
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `apellido`, `correo`, `contrasena`, `telefono`, `rol`, `estado`, `fecha_creacion`) VALUES
(1, 'Javier', 'Espinoza', 'Javier@gmail.com', 'Root', '+569', 'administrador', 'activo', '2025-08-10 20:21:45'),
(2, 'Juana', 'espinozaa', 'pruebas@gmail.com', 'root', '+5699', 'vendedor', 'activo', '2025-08-10 21:46:17'),
(3, 'Felipe', 'espinoza', 'prueba1@gmail.com', 'root', '+569976', 'usuario', 'activo', '2025-08-10 21:46:39'),
(4, 'Mariana', 'González', 'mariana.gonzalez@email.com', 'cliente123', '+56987654321', 'usuario', 'activo', '2025-08-19 00:43:23'),
(5, 'Car', 'Rodríguez', 'car.rodriguez@email.com', 'ciente123', '+56912345678', 'usuario', 'activo', '2025-08-19 00:43:23'),
(6, 'Ana', 'Martínez', 'ana.martinez@email.com', 'clente123', '+56998765432', 'usuario', 'activo', '2025-08-19 00:43:23'),
(7, 'Pedro', 'Silva', 'pedro.silva@email.com', 'vendedor12', '+56987123456', 'vendedor', 'activo', '2025-08-19 00:43:23'),
(8, 'Fernanda', 'Fernández', 'fernanda.fernandez@email.com', 'vendedor13', '+56976543210', 'vendedor', 'activo', '2025-08-19 00:43:23'),
(9, 'Roberto', 'Morales', 'roberto.morales@email.com', 'client123', '+56965432109', 'usuario', 'activo', '2025-08-19 00:43:23'),
(10, 'Carmen', 'Torres', 'carmen.torres@email.com', 'cliete123', '+56954321098', 'usuario', 'activo', '2025-08-19 00:43:23'),
(11, 'Laura', 'Mendoza', 'laura.mendoza@email.com', 'user456', '+56922233445', 'usuario', 'activo', '2025-08-24 19:06:03'),
(12, 'Diego', 'Alvarez', 'diego.alvarez@email.com', 'user789', '+56999887766', 'usuario', 'activo', '2025-08-24 19:06:03'),
(13, 'Valentina', 'Ríos', 'valentina.rios@email.com', 'valen123', '+56933445566', 'vendedor', 'activo', '2025-08-24 19:06:03'),
(14, 'Andrés', 'Pérez', 'andres.perez@email.com', 'andres456', '+56912344321', 'usuario', 'activo', '2025-08-24 19:06:03'),
(15, 'felipe', 'nuñez', 'felxnun@gmail.com', 'natrepatan', '+569', 'usuario', 'activo', '2025-08-24 21:17:28'),
(16, 'rodrigo', 'quiroga', 'r.quiroga@gmail.com', '$2y$10$5Wt8zKY8I8oTDBzp5L5UTuld/iIlMfMGXISKnPPcfy/r.sqPOAbGW', '+569987', 'usuario', 'activo', '2025-08-24 22:46:12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD PRIMARY KEY (`id_cotizacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_modelo` (`id_modelo`);

--
-- Indices de la tabla `cotizacion_kit`
--
ALTER TABLE `cotizacion_kit`
  ADD PRIMARY KEY (`id_cotizacion`,`id_kit`),
  ADD KEY `id_kit` (`id_kit`);

--
-- Indices de la tabla `cotizacion_servicio`
--
ALTER TABLE `cotizacion_servicio`
  ADD PRIMARY KEY (`id_cotizacion`,`id_servicio`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `cronograma`
--
ALTER TABLE `cronograma`
  ADD PRIMARY KEY (`id_cronograma`),
  ADD UNIQUE KEY `id_proyecto` (`id_proyecto`);

--
-- Indices de la tabla `kit`
--
ALTER TABLE `kit`
  ADD PRIMARY KEY (`id_kit`);

--
-- Indices de la tabla `modelo_casa`
--
ALTER TABLE `modelo_casa`
  ADD PRIMARY KEY (`id_modelo`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_cotizacion` (`id_pedido`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cotizacion` (`id_cotizacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_pago` (`id_pago`);

--
-- Indices de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  ADD PRIMARY KEY (`id_proyecto`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_modelo` (`id_modelo`),
  ADD KEY `id_pedido` (`id_pedido`);

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
  ADD KEY `id_modelo` (`id_modelo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  MODIFY `id_cotizacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `cronograma`
--
ALTER TABLE `cronograma`
  MODIFY `id_cronograma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `kit`
--
ALTER TABLE `kit`
  MODIFY `id_kit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `modelo_casa`
--
ALTER TABLE `modelo_casa`
  MODIFY `id_modelo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `proyecto`
--
ALTER TABLE `proyecto`
  MODIFY `id_proyecto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `servicio_adicional`
--
ALTER TABLE `servicio_adicional`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `stock_casa`
--
ALTER TABLE `stock_casa`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD CONSTRAINT `cotizacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `cotizacion_ibfk_2` FOREIGN KEY (`id_modelo`) REFERENCES `modelo_casa` (`id_modelo`),
  ADD CONSTRAINT `cotizacion_ibfk_4` FOREIGN KEY (`id_modelo`) REFERENCES `modelo_casa` (`id_modelo`);

--
-- Filtros para la tabla `cotizacion_kit`
--
ALTER TABLE `cotizacion_kit`
  ADD CONSTRAINT `cotizacion_kit_ibfk_1` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`),
  ADD CONSTRAINT `cotizacion_kit_ibfk_2` FOREIGN KEY (`id_kit`) REFERENCES `kit` (`id_kit`),
  ADD CONSTRAINT `cotizacion_kit_ibfk_3` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`),
  ADD CONSTRAINT `cotizacion_kit_ibfk_4` FOREIGN KEY (`id_kit`) REFERENCES `kit` (`id_kit`);

--
-- Filtros para la tabla `cotizacion_servicio`
--
ALTER TABLE `cotizacion_servicio`
  ADD CONSTRAINT `cotizacion_servicio_ibfk_1` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`),
  ADD CONSTRAINT `cotizacion_servicio_ibfk_2` FOREIGN KEY (`id_servicio`) REFERENCES `servicio_adicional` (`id_servicio`),
  ADD CONSTRAINT `cotizacion_servicio_ibfk_3` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`),
  ADD CONSTRAINT `cotizacion_servicio_ibfk_4` FOREIGN KEY (`id_servicio`) REFERENCES `servicio_adicional` (`id_servicio`);

--
-- Filtros para la tabla `cronograma`
--
ALTER TABLE `cronograma`
  ADD CONSTRAINT `cronograma_ibfk_1` FOREIGN KEY (`id_proyecto`) REFERENCES `proyecto` (`id_proyecto`),
  ADD CONSTRAINT `cronograma_ibfk_2` FOREIGN KEY (`id_proyecto`) REFERENCES `proyecto` (`id_proyecto`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `pago_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `cotizacion` (`id_cotizacion`),
  ADD CONSTRAINT `pago_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `pago_ibfk_4` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`id_cotizacion`) REFERENCES `cotizacion` (`id_cotizacion`),
  ADD CONSTRAINT `pedido_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_ibfk_4` FOREIGN KEY (`id_pago`) REFERENCES `pago` (`id_pago`);

--
-- Filtros para la tabla `proyecto`
--
ALTER TABLE `proyecto`
  ADD CONSTRAINT `proyecto_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `proyecto_ibfk_2` FOREIGN KEY (`id_modelo`) REFERENCES `modelo_casa` (`id_modelo`),
  ADD CONSTRAINT `proyecto_ibfk_4` FOREIGN KEY (`id_modelo`) REFERENCES `modelo_casa` (`id_modelo`),
  ADD CONSTRAINT `proyecto_ibfk_5` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);

--
-- Filtros para la tabla `stock_casa`
--
ALTER TABLE `stock_casa`
  ADD CONSTRAINT `stock_casa_ibfk_1` FOREIGN KEY (`id_modelo`) REFERENCES `modelo_casa` (`id_modelo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
