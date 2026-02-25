-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2026 at 01:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistema_ventas`
--

-- --------------------------------------------------------

--
-- Table structure for table `caja_turnos`
--

CREATE TABLE `caja_turnos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_apertura` datetime DEFAULT current_timestamp(),
  `monto_inicial` decimal(10,2) NOT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `monto_final_esperado` decimal(10,2) DEFAULT NULL,
  `monto_final_real` decimal(10,2) DEFAULT NULL,
  `estado` enum('Abierta','Cerrada') DEFAULT 'Abierta',
  `monto_esperado` decimal(10,2) DEFAULT NULL,
  `monto_real` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `caja_turnos`
--

INSERT INTO `caja_turnos` (`id`, `id_usuario`, `fecha_apertura`, `monto_inicial`, `fecha_cierre`, `monto_final_esperado`, `monto_final_real`, `estado`, `monto_esperado`, `monto_real`) VALUES
(19, 3, '2026-02-21 12:31:42', 0.00, '2026-02-21 13:12:11', NULL, NULL, '', 20.00, 20.00),
(20, 3, '2026-02-21 13:49:25', 0.00, NULL, NULL, NULL, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(9, 'Farmacéutico'),
(10, 'HELADOS');

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `puntos` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `dni`, `telefono`, `direccion`, `puntos`) VALUES
(2, 'oscar manuel reategui arevalo ', '72529107', '948792314', '-------', 110),
(3, 'andy marlon', '', '940140774', '', 10);

-- --------------------------------------------------------

--
-- Table structure for table `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `unidades_reales` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`id`, `id_venta`, `id_producto`, `cantidad`, `precio`, `subtotal`, `unidades_reales`) VALUES
(7, 5, 2, 1, 10.00, 10.00, 1),
(8, 6, 2, 1, 10.00, 10.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `kardex`
--

CREATE TABLE `kardex` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `tipo_movimiento` enum('Entrada','Salida','Ajuste') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `stock_resultante` int(11) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kardex`
--

INSERT INTO `kardex` (`id`, `id_producto`, `tipo_movimiento`, `cantidad`, `stock_resultante`, `descripcion`, `fecha`) VALUES
(8, 2, 'Salida', 1, 99, 'Venta Ticket #00005', '2026-02-21 12:44:42'),
(9, 2, 'Salida', 1, 98, 'Venta Ticket #00006', '2026-02-21 12:56:04');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `compra` decimal(10,2) DEFAULT 0.00,
  `venta` decimal(10,2) DEFAULT 0.00,
  `stock` int(11) DEFAULT 0,
  `principio_activo` varchar(255) DEFAULT NULL,
  `laboratorio` varchar(255) DEFAULT NULL,
  `presentacion` varchar(255) DEFAULT NULL,
  `lote` varchar(100) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `requiere_receta` tinyint(1) DEFAULT 0,
  `unidades_por_caja` int(11) DEFAULT 1,
  `unidades_por_blister` int(11) DEFAULT 1,
  `precio_caja` decimal(10,2) DEFAULT 0.00,
  `precio_blister` decimal(10,2) DEFAULT 0.00,
  `precio_unidad` decimal(10,2) DEFAULT 0.00,
  `stock_minimo` int(11) DEFAULT 20,
  `categoria` varchar(100) DEFAULT 'Medicamentos',
  `detalles` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `descripcion`, `compra`, `venta`, `stock`, `principio_activo`, `laboratorio`, `presentacion`, `lote`, `fecha_vencimiento`, `requiere_receta`, `unidades_por_caja`, `unidades_por_blister`, `precio_caja`, `precio_blister`, `precio_unidad`, `stock_minimo`, `categoria`, `detalles`) VALUES
(2, 'asdasdsad', 'asdasdasd', 10.00, 0.00, 98, 'asdasd', '', '', 'asdasdsad', '2027-02-22', 0, 1, 1, 0.00, 0.00, 10.00, 20, 'asd', '');

-- --------------------------------------------------------

--
-- Table structure for table `productos_viejo`
--

CREATE TABLE `productos_viejo` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(4) DEFAULT 1,
  `principio_activo` varchar(255) DEFAULT '',
  `laboratorio` varchar(100) DEFAULT '',
  `presentacion` varchar(100) DEFAULT '',
  `lote` varchar(50) DEFAULT '',
  `fecha_vencimiento` date DEFAULT NULL,
  `requiere_receta` tinyint(4) DEFAULT 0,
  `unidades_por_caja` int(11) DEFAULT 1,
  `unidades_por_blister` int(11) DEFAULT 1,
  `precio_caja` decimal(10,2) DEFAULT 0.00,
  `precio_blister` decimal(10,2) DEFAULT 0.00,
  `precio_unidad` decimal(10,2) DEFAULT 0.00,
  `stock_minimo` int(11) DEFAULT 20,
  `categoria` varchar(100) DEFAULT 'Medicamentos',
  `detalles` text DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `ruc` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `rol`) VALUES
(1, 'Administrador', 'admin', '123456', 'Administrador'),
(2, 'Administrador Principal', 'admin', 'admin123', 'Administrador'),
(3, 'LORENA', 'LORENA', '1234', 'Administrador');

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `cliente_nombre` varchar(150) DEFAULT 'Público General',
  `cliente_dni` varchar(20) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id`, `cliente_nombre`, `cliente_dni`, `total`, `fecha`) VALUES
(5, 'oscar manuel reategui arevalo ', '72529107', 10.00, '2026-02-21 12:44:42'),
(6, 'andy marlon', '', 10.00, '2026-02-21 12:56:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `caja_turnos`
--
ALTER TABLE `caja_turnos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`);

--
-- Indexes for table `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indexes for table `kardex`
--
ALTER TABLE `kardex`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productos_viejo`
--
ALTER TABLE `productos_viejo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indexes for table `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `caja_turnos`
--
ALTER TABLE `caja_turnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kardex`
--
ALTER TABLE `kardex`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `productos_viejo`
--
ALTER TABLE `productos_viejo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
