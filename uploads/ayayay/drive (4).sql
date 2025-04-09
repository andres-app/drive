-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-04-2025 a las 15:03:50
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
-- Base de datos: `drive`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `folder` varchar(255) DEFAULT '',
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `files`
--

INSERT INTO `files` (`id`, `name`, `path`, `size`, `uploaded_at`, `folder`, `user_id`, `created_at`, `activo`) VALUES
(2, 'SIP', '', 0, '2025-03-25 18:53:37', '', 1, '2025-04-04 16:42:12', 1),
(10, 'odontograma_ocx.rar', 'uploads/SIP/odontograma_ocx.rar', 158353, '2025-03-25 19:07:25', 'SIP', 1, '2025-04-04 16:40:12', 1),
(11, 'nuevo', '', 0, '2025-03-25 23:43:50', '', 1, '2025-04-04 16:40:12', 1),
(13, 'Servidores_PC_Visitas_EPs.xlsx', 'uploads/Servidores_PC_Visitas_EPs.xlsx', 334780, '2025-03-28 20:45:41', '', 2, '2025-04-04 16:40:12', 1),
(14, 'MESA DE AYUDA Y SOPORTE_ABRIL 2025.pdf', 'uploads/nuevo/MESA DE AYUDA Y SOPORTE_ABRIL 2025.pdf', 131700, '2025-03-31 20:20:05', 'nuevo', 2, '2025-04-04 16:40:12', 1),
(15, 'MESA DE AYUDA Y SOPORTE_ABRIL 2025.pdf', 'uploads/MESA DE AYUDA Y SOPORTE_ABRIL 2025.pdf', 131700, '2025-03-31 20:22:14', 'nuevo', 2, '2025-04-04 16:40:12', 1),
(16, 'camara.zip', 'uploads/camara.zip', 74597494, '2025-03-31 20:22:36', 'nuevo', 2, '2025-04-04 16:40:12', 1),
(17, 'CRRuntime_64bit_13_0_4.rar', 'uploads/CRRuntime_64bit_13_0_4.rar', 78222790, '2025-03-31 20:22:53', '', 2, '2025-04-04 16:40:12', 1),
(18, 'Nueca', '', 0, '2025-03-31 21:18:50', '', 2, '2025-04-04 16:40:12', 1),
(19, 'jaja', '', 0, '2025-03-31 21:18:54', '', 2, '2025-04-04 16:40:12', 1),
(20, 'caperta1', '', 0, '2025-03-31 21:18:59', '', 2, '2025-04-04 16:40:12', 1),
(21, 'pope_install_v2.rar', 'uploads/pope_install_v2.rar', 86389914, '2025-03-31 21:19:07', '', 2, '2025-04-04 16:40:12', 1),
(22, 'jota', '', 0, '2025-03-31 21:38:37', '', 1, '2025-04-04 16:40:12', 1),
(23, 'futronic_fs60_Registro.rar', 'uploads/jota/futronic_fs60_Registro.rar', 663380, '2025-03-31 21:38:58', 'jota', 1, '2025-04-04 16:40:12', 1),
(24, 'jose', '', 0, '2025-04-02 19:51:30', '', 1, '2025-04-04 16:40:12', 1),
(25, 'pedro', '', 0, '2025-04-02 20:55:34', '', 1, '2025-04-04 16:40:12', 1),
(26, 'Directorio_inpe.pdf', 'uploads/Directorio_inpe.pdf', 876505, '2025-04-02 21:07:47', '', 1, '2025-04-04 16:40:12', 1),
(27, 'Directorio_inpe.pdf', 'uploads/Directorio_inpe.pdf', 876505, '2025-04-04 15:43:44', '', 1, '2025-04-04 16:40:12', 1),
(28, 'INFORME-R63-2025-OSIN.TI-AMSB (1).docx', 'uploads/INFORME-R63-2025-OSIN.TI-AMSB (1).docx', 344994, '2025-04-04 15:43:44', '', 1, '2025-04-04 16:40:12', 1),
(29, 'INFORME-R63-2025-OSIN.TI-AMSB.docx', 'uploads/INFORME-R63-2025-OSIN.TI-AMSB.docx', 344994, '2025-04-04 15:43:44', '', 1, '2025-04-04 16:40:12', 1),
(30, 'ticket.pdf', 'uploads/ticket.pdf', 57366, '2025-04-04 15:55:07', '', 1, '2025-04-04 16:40:12', 1),
(31, 'Directorio_inpe.pdf', 'uploads/Directorio_inpe.pdf', 876505, '2025-04-04 15:55:07', '', 1, '2025-04-04 16:40:12', 1),
(32, 'base.apk', 'uploads/base.apk', 2880527, '2025-04-04 15:55:48', '', 1, '2025-04-04 16:40:12', 1),
(33, 'base.apk', 'uploads/base.apk', 2880527, '2025-04-04 15:57:26', '', 1, '2025-04-04 16:40:12', 1),
(34, 'INVENTARIO_GENERAL_2023.xlsx', 'uploads/INVENTARIO_GENERAL_2023.xlsx', 1248862, '2025-04-04 15:57:40', '', 1, '2025-04-04 16:40:12', 1),
(36, 'miradori2.png', 'uploads/miradori2.png', 63304, '2025-04-04 16:31:14', '', 1, '2025-04-04 16:40:12', 1),
(37, 'miradori3.png', 'uploads/miradori3.png', 57742, '2025-04-04 16:31:14', '', 1, '2025-04-04 16:40:12', 1),
(38, 'CREACION USUARIO DE RED.pdf', 'uploads/CREACION USUARIO DE RED.pdf', 2630917, '2025-04-04 19:21:24', '', 1, '2025-04-04 16:40:12', 1),
(39, 'CUENTAS DE CORREO_PROCEDIMIENTO.pdf', 'uploads/CUENTAS DE CORREO_PROCEDIMIENTO.pdf', 3363247, '2025-04-04 19:21:24', '', 1, '2025-04-04 16:40:12', 1),
(40, '01 Creacion de Usuario correo electronico.pdf', 'uploads/01 Creacion de Usuario correo electronico.pdf', 502619, '2025-04-04 19:21:24', '', 1, '2025-04-04 16:40:12', 1),
(41, '01 Creacion de Usuario de red.pdf', 'uploads/01 Creacion de Usuario de red.pdf', 642824, '2025-04-04 19:21:24', '', 1, '2025-04-04 16:40:12', 1),
(42, '01 Creacion de Usuario correo electronicoooooooooo.pdf', 'uploads/01 Creacion de Usuario correo electronicoooooooooo.pdf', 502619, '2025-04-04 19:23:34', '', 1, '2025-04-04 16:40:12', 1),
(43, 'olaa.pdf', 'uploads/olaa.pdf', 642824, '2025-04-04 19:23:34', '', 1, '2025-04-04 16:40:12', 1),
(44, 'Manual Hosperador de Sala zoom.pdf', 'uploads/Manual Hosperador de Sala zoom.pdf', 236870, '2025-04-04 19:25:25', '', 1, '2025-04-04 16:40:12', 1),
(45, 'PARA CREAR SALAS ZOOM.txt', 'uploads/PARA CREAR SALAS ZOOM.txt', 715, '2025-04-04 19:25:25', '', 1, '2025-04-04 16:40:12', 1),
(46, 'Manual Hosperador de Sala zoom.pdf', 'uploads/Manual Hosperador de Sala zoom.pdf', 236870, '2025-04-04 19:26:46', '', 1, '2025-04-04 16:40:12', 1),
(47, 'PARA CREAR SALAS ZOOM.txt', 'uploads/PARA CREAR SALAS ZOOM.txt', 715, '2025-04-04 19:26:46', '', 1, '2025-04-04 16:40:12', 1),
(48, 'Manual Hosperador de Sala zoom.pdf', 'uploads/Manual Hosperador de Sala zoom.pdf', 236870, '2025-04-04 19:28:30', '', 1, '2025-04-04 16:40:12', 1),
(49, 'PARA CREAR SALAS ZOOM.txt', 'uploads/PARA CREAR SALAS ZOOM.txt', 715, '2025-04-04 19:28:30', '', 1, '2025-04-04 16:40:12', 1),
(50, 'Manual Hosperador de Sala zoom.pdf', 'uploads/Manual Hosperador de Sala zoom.pdf', 236870, '2025-04-04 19:36:52', '', 1, '2025-04-04 16:40:12', 1),
(51, 'PARA CREAR SALAS ZOOM.txt', 'uploads/PARA CREAR SALAS ZOOM.txt', 715, '2025-04-04 19:36:52', '', 1, '2025-04-04 16:40:12', 1),
(52, 'Manual Hosperador de Sala zoom.pdf', 'uploads/Manual Hosperador de Sala zoom.pdf', 236870, '2025-04-04 19:48:20', '', 1, '2025-04-04 16:40:12', 1),
(53, 'jose.pdf', 'uploads/jose.pdf', 236870, '2025-04-04 19:48:20', '', 1, '2025-04-04 16:40:12', 1),
(54, 'PARA CREAR SALAS ZOOM.txt', 'uploads/PARA CREAR SALAS ZOOM.txt', 715, '2025-04-04 19:48:20', '', 1, '2025-04-04 16:40:12', 1),
(55, 'PARA CREAR SALAS ZOOM.txt', 'uploads/PARA CREAR SALAS ZOOM.txt', 715, '2025-04-04 19:48:20', '', 1, '2025-04-04 16:40:12', 1),
(56, 'Manual Hosperador de Sala zoom.pdf', 'uploads/Manual Hosperador de Sala zoom.pdf', 236870, '2025-04-04 19:50:54', '', 1, '2025-04-04 16:40:12', 1),
(57, 'PARA CREAR SALAS ZOOM.txt', 'uploads/PARA CREAR SALAS ZOOM.txt', 715, '2025-04-04 19:50:54', '', 1, '2025-04-04 16:40:12', 1),
(58, 'Manual Hosperador de Sala zoom.pdf', 'uploads/Manual Hosperador de Sala zoom.pdf', 236870, '2025-04-04 20:06:39', '', 1, '2025-04-04 16:40:12', 1),
(59, 'PARA CREAR SALAS ZOOM.txt', 'uploads/PARA CREAR SALAS ZOOM.txt', 715, '2025-04-04 20:06:39', '', 1, '2025-04-04 16:40:12', 1),
(62, 'hola2', '', 0, '2025-04-04 20:50:03', '', 1, '2025-04-04 16:40:12', 1),
(63, 'Manual Hosperador de Sala zoom.pdf', 'uploads/Manual Hosperador de Sala zoom.pdf', 236870, '2025-04-04 20:50:03', '', 1, '2025-04-04 16:40:12', 1),
(64, 'Manual Hosperador de Sala zoom.pdf', 'uploads/Manual Hosperador de Sala zoom.pdf', 236870, '2025-04-04 20:50:28', '', 1, '2025-04-04 16:40:12', 1),
(65, 'Manual Hosperador de Sala zoom.pdf', 'uploads/Manual Hosperador de Sala zoom.pdf', 236870, '2025-04-04 20:50:41', '', 1, '2025-04-04 16:40:12', 1),
(66, 'PARA CREAR SALAS ZOOM.txt', 'uploads/PARA CREAR SALAS ZOOM.txt', 715, '2025-04-04 20:50:41', '', 1, '2025-04-04 16:40:12', 1),
(67, 'holaaaaaaa', '', 0, '2025-04-04 20:50:51', '', 1, '2025-04-04 16:40:12', 1),
(68, 'jeejejjejee', '', 0, '2025-04-07 17:05:44', '', 1, '2025-04-07 12:05:44', 1),
(69, 'abril-julio-2025.xlsx', 'uploads/abril-julio-2025.xlsx', 84322, '2025-04-07 17:05:44', '', 1, '2025-04-07 12:05:44', 1),
(70, 'ayayay', '', 0, '2025-04-07 22:37:49', '', 1, '2025-04-07 17:37:49', 1),
(71, 'PLAN DE MANTENIMIENTO DEL INPE 2025[F].pdf', 'uploads/PLAN DE MANTENIMIENTO DEL INPE 2025[F].pdf', 1447667, '2025-04-07 22:37:49', '', 1, '2025-04-07 17:37:49', 1),
(72, 'ja2ja2', '', 0, '2025-04-07 22:43:22', '', 1, '2025-04-07 17:43:22', 1),
(73, 'pepe', '', 0, '2025-04-08 21:47:40', '', 1, '2025-04-08 16:47:40', 1),
(74, '.', '', 0, '2025-04-08 21:47:55', '', 1, '2025-04-08 16:47:55', 1),
(75, 'PRUEBA1.pdf', 'uploads/PRUEBA1.pdf', 14965, '2025-04-08 21:47:55', '', 1, '2025-04-08 16:47:55', 1),
(76, 'PRUEBA2.pdf', 'uploads/PRUEBA2.pdf', 14965, '2025-04-08 21:47:55', '', 1, '2025-04-08 16:47:55', 1),
(77, 'PRUEBA3.pdf', 'uploads/PRUEBA3.pdf', 14965, '2025-04-08 21:47:55', '', 1, '2025-04-08 16:47:55', 1),
(78, 'PRUEBA1.pdf', 'uploads/PRUEBA1.pdf', 14965, '2025-04-08 21:48:14', '', 1, '2025-04-08 16:48:14', 1),
(79, 'PRUEBA2.pdf', 'uploads/PRUEBA2.pdf', 14965, '2025-04-08 21:48:14', '', 1, '2025-04-08 16:48:14', 1),
(80, 'PRUEBA3.pdf', 'uploads/PRUEBA3.pdf', 14965, '2025-04-08 21:48:14', '', 1, '2025-04-08 16:48:14', 1),
(81, '.', '', 0, '2025-04-08 21:53:44', 'hola2', 1, '2025-04-08 16:53:44', 1),
(82, 'PRUEBA3.pdf', 'uploads/hola2/PRUEBA3.pdf', 14965, '2025-04-08 21:53:44', 'hola2', 1, '2025-04-08 16:53:44', 1),
(83, 'PRUEBA2.pdf', 'uploads/hola2/PRUEBA2.pdf', 14965, '2025-04-08 21:53:53', 'hola2', 1, '2025-04-08 16:53:53', 1),
(84, '.', '', 0, '2025-04-08 21:54:08', 'holaaaaaaa', 1, '2025-04-08 16:54:08', 0),
(85, 'PRUEBA1.pdf', 'uploads/holaaaaaaa/PRUEBA1.pdf', 14965, '2025-04-08 21:54:08', 'holaaaaaaa', 1, '2025-04-08 16:54:08', 0),
(86, 'PRUEBA1.pdf', 'uploads/holaaaaaaa/PRUEBA1.pdf', 14965, '2025-04-08 22:09:19', 'holaaaaaaa', 1, '2025-04-08 17:09:19', 1),
(87, 'PRUEBA1.pdf', 'uploads/PRUEBA1.pdf', 14965, '2025-04-08 22:09:31', '', 1, '2025-04-08 17:09:31', 1),
(88, 'PRUEBA2.pdf', 'uploads/PRUEBA2.pdf', 14965, '2025-04-08 22:09:31', '', 1, '2025-04-08 17:09:31', 1),
(89, 'PRUEBA3.pdf', 'uploads/PRUEBA3.pdf', 14965, '2025-04-08 22:09:31', '', 1, '2025-04-08 17:09:31', 1),
(90, 'PRUEBA1.pdf', 'uploads/PRUEBA1.pdf', 14965, '2025-04-08 22:12:05', '', 1, '2025-04-08 17:12:05', 1),
(91, 'PRUEBA2.pdf', 'uploads/PRUEBA2.pdf', 14965, '2025-04-08 22:12:05', '', 1, '2025-04-08 17:12:05', 1),
(92, 'PRUEBA3.pdf', 'uploads/PRUEBA3.pdf', 14965, '2025-04-08 22:12:05', '', 1, '2025-04-08 17:12:05', 1),
(93, 'PRUEBA1.pdf', 'uploads/PRUEBA1.pdf', 14965, '2025-04-08 22:15:39', '', 1, '2025-04-08 17:15:39', 1),
(94, 'PRUEBA2.pdf', 'uploads/PRUEBA2.pdf', 14965, '2025-04-08 22:15:39', '', 1, '2025-04-08 17:15:39', 1),
(95, 'PRUEBA3.pdf', 'uploads/PRUEBA3.pdf', 14965, '2025-04-08 22:15:39', '', 1, '2025-04-08 17:15:39', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'andres@gmail.com', '123123'),
(2, 'usuario2', '1234');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
