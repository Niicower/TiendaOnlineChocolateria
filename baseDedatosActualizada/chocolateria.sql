-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:33066
-- Tiempo de generación: 14-07-2022 a las 03:31:46
-- Versión del servidor: 5.6.26
-- Versión de PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `chocolateria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_compras`
--

CREATE TABLE IF NOT EXISTS `carrito_compras` (
  `id` int(30) NOT NULL,
  `cliente_id` int(30) NOT NULL,
  `inventario_id` int(30) NOT NULL,
  `precio` double NOT NULL,
  `cantidad` int(30) NOT NULL,
  `fecha_de_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `carrito_compras`
--

INSERT INTO `carrito_compras` (`id`, `cliente_id`, `inventario_id`, `precio`, `cantidad`, `fecha_de_creacion`) VALUES
(4, 2, 3, 350, 20, '2022-06-13 21:19:38'),
(8, 4, 4, 6500, 1, '2022-06-15 15:07:09'),
(19, 14, 5, 2500, 1, '2022-06-27 01:47:17'),
(20, 14, 6, 343, 1, '2022-06-27 01:48:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int(30) NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_de_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `name`, `description`, `status`, `fecha_de_creacion`) VALUES
(19, 'Donas', 'Donas de chocolate', 1, '2022-06-29 00:13:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(30) NOT NULL,
  `categoria` varchar(250) NOT NULL,
  `description` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_de_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`, `description`, `status`, `fecha_de_creacion`) VALUES
(13, 'Donas blancas', 'DONAS DE CHOCOLATE BLANCO', 1, '2022-06-29 00:14:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int(30) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `apellido` varchar(250) NOT NULL,
  `genero` varchar(20) NOT NULL,
  `contacto` varchar(15) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` text NOT NULL,
  `Direccion_de_entrega` text NOT NULL,
  `fecha_de_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `firstname`, `apellido`, `genero`, `contacto`, `email`, `password`, `Direccion_de_entrega`, `fecha_de_creacion`) VALUES
(18, 'nicolas', 'miranda', 'Masculino', '954961452', 'nico@gmail.com', '410ec15153a6dff0bed851467309bcbd', 'las animas', '2022-06-29 00:13:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos`
--

CREATE TABLE IF NOT EXISTS `datos` (
  `nombre` varchar(20) NOT NULL,
  `correo` varchar(30) NOT NULL,
  `mensaje` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `datos`
--

INSERT INTO `datos` (`nombre`, `correo`, `mensaje`) VALUES
('dcsdds', 'dsds@gmail.com', 'dsds'),
('ds', 'ds@gmail.com', 'ds'),
('ds', 'ds@gmail.com', 'ds'),
('ds', 'dsdsds@gmail.com', 'dsdsds'),
('dñlkss', 'ldjfkd@gmail.com', 'lslsl'),
('dñlkss', 'ldjfkd@gmail.com', 'lslsl'),
('kaja', 'ola@gmail.com', 'lkakk'),
('nico', 'nico.miranda@gmail.com', 'hola'),
('nico', 'nico.miranda@gmail.com', 'hola'),
('DS', 'nico@gmail.com', 'kk'),
('ds', 'ds@gmail.com', 'ola'),
('ds', 'ds@gmail.com', 'ola'),
('ds', 'ds@gmail.com', 'ola'),
('ds', 'ds@gmail.com', 'ola'),
('ds', 'ds@gmail.com', 'ola'),
('nico', 'nico@gmail.com', 'hola');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `info_del_sistema`
--

CREATE TABLE IF NOT EXISTS `info_del_sistema` (
  `id` int(30) NOT NULL,
  `campo` text NOT NULL,
  `valor` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `info_del_sistema`
--

INSERT INTO `info_del_sistema` (`id`, `campo`, `valor`) VALUES
(1, 'name', 'Chocolateria de mil amores'),
(6, 'short_name', 'Chocolateria'),
(11, 'logo', 'uploads/1655254860_1655254740_LOGOCHOCO.png'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/1655254920_1655254860_1655254740_LOGOCHOCO.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE IF NOT EXISTS `inventario` (
  `id` int(30) NOT NULL,
  `producto_id` int(30) NOT NULL,
  `cantidad` double NOT NULL,
  `precio` int(11) NOT NULL,
  `fecha_de_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizada` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `producto_id`, `cantidad`, `precio`, `fecha_de_creacion`, `fecha_actualizada`) VALUES
(17, 19, 100, 2990, '2022-06-29 00:15:31', '2022-07-13 20:52:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lista_de_orden`
--

CREATE TABLE IF NOT EXISTS `lista_de_orden` (
  `id` int(30) NOT NULL,
  `pedidos_id` int(30) NOT NULL,
  `producto_id` int(30) NOT NULL,
  `cantidad` int(30) NOT NULL,
  `precio` double NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `lista_de_orden`
--

INSERT INTO `lista_de_orden` (`id`, `pedidos_id`, `producto_id`, `cantidad`, `precio`, `total`) VALUES
(66, 67, 19, 1, 2990, 2990);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(30) NOT NULL,
  `cliente_id` int(30) NOT NULL,
  `delivery_address` text NOT NULL,
  `metodo_de_pago` varchar(100) NOT NULL,
  `tipo_de_orden` tinyint(1) NOT NULL COMMENT '1= pickup,2= deliver',
  `Monto` double NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `pagado` tinyint(1) NOT NULL DEFAULT '0',
  `fecha_de_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizada` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `cliente_id`, `delivery_address`, `metodo_de_pago`, `tipo_de_orden`, `Monto`, `status`, `pagado`, `fecha_de_creacion`, `fecha_actualizada`) VALUES
(67, 18, '', 'efectivo', 2, 2990, 3, 1, '2022-07-13 21:20:52', '2022-07-13 21:24:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(30) NOT NULL,
  `categoria_id` int(30) NOT NULL,
  `categorias_id` int(30) NOT NULL,
  `sub_categoria_id` int(30) NOT NULL,
  `name` varchar(250) NOT NULL,
  `especificaciones` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_de_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `categoria_id`, `categorias_id`, `sub_categoria_id`, `name`, `especificaciones`, `status`, `fecha_de_creacion`) VALUES
(19, 19, 13, 0, 'Donas de chocolate', '<p>DONAS DE CHOCOLATE RICAS</p>', 1, '2022-06-29 00:15:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sub_categorias`
--

CREATE TABLE IF NOT EXISTS `sub_categorias` (
  `id` int(30) NOT NULL,
  `identificador_id` int(30) NOT NULL,
  `sub_categoria` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_de_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `sub_categorias`
--

INSERT INTO `sub_categorias` (`id`, `identificador_id`, `sub_categoria`, `description`, `status`, `fecha_de_creacion`) VALUES
(18, 13, 'Donas de todo tipo', '', 1, '2022-06-29 00:14:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `apellido` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text,
  `ultimo_acceso` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `Fecha_Agregada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizada` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `firstname`, `apellido`, `username`, `password`, `avatar`, `ultimo_acceso`, `type`, `Fecha_Agregada`, `fecha_actualizada`) VALUES
(1, 'Adminstrator', 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/1624240500_avatar.png', NULL, 1, '2021-01-20 14:02:37', '2021-06-21 09:55:07'),
(2, 'jose', 'Jose', 'Joseo', '662eaa47199461d01a623884080934ab', '', '2022-06-22 00:00:00', 0, '2022-06-17 00:00:00', '2022-06-15 00:24:29'),
(3, 'Empleado', '1', 'Empleado', '202cb962ac59075b964b07152d234b70', NULL, '2022-06-10 00:00:00', 0, '2022-06-07 00:00:00', '2022-06-09 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int(30) NOT NULL,
  `pedidos_id` int(30) NOT NULL,
  `cantidad_total` double NOT NULL,
  `fecha_de_creacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `pedidos_id`, `cantidad_total`, `fecha_de_creacion`) VALUES
(51, 56, 1990, '2022-06-28 21:56:39'),
(52, 57, 1990, '2022-06-28 22:10:39'),
(53, 58, 4343, '2022-06-28 22:17:22'),
(54, 59, 1990, '2022-06-28 22:25:09'),
(55, 60, 1990, '2022-06-28 22:34:14'),
(56, 61, 4343, '2022-06-28 23:01:05'),
(57, 62, 4545, '2022-06-28 23:25:37'),
(58, 63, 4343, '2022-06-28 23:51:08'),
(59, 64, 5980, '2022-06-29 00:16:09'),
(60, 65, 2990, '2022-06-29 00:53:41'),
(61, 66, 2990, '2022-06-30 11:01:47'),
(62, 67, 2990, '2022-07-13 21:20:52');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `info_del_sistema`
--
ALTER TABLE `info_del_sistema`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lista_de_orden`
--
ALTER TABLE `lista_de_orden`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sub_categorias`
--
ALTER TABLE `sub_categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `info_del_sistema`
--
ALTER TABLE `info_del_sistema`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `lista_de_orden`
--
ALTER TABLE `lista_de_orden`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de la tabla `sub_categorias`
--
ALTER TABLE `sub_categorias`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=63;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
