-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-04-2026 a las 23:38:34
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
-- Base de datos: `nosecaen`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cif` varchar(9) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `cuenta_corriente` varchar(34) DEFAULT NULL,
  `pais` varchar(50) NOT NULL,
  `moneda` varchar(3) NOT NULL DEFAULT 'EUR',
  `cuota_mensual` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `cif`, `nombre`, `telefono`, `email`, `cuenta_corriente`, `pais`, `moneda`, `cuota_mensual`, `created_at`, `updated_at`) VALUES
(3, '54792759F', 'alvaro', '722101955', 'alvaro@gmail.com', NULL, 'alemania', 'EUR', 66.00, '2026-04-26 15:03:05', '2026-04-26 15:46:10'),
(4, '54345423F', 'PEPE', '822105195', 'a@gmail.com', NULL, 'España', 'EUR', 12.00, '2026-04-26 15:44:13', '2026-04-26 15:46:02'),
(5, '23455432e', 'toreto', '722101955', 'aa@gmail.com', 'ES12 3445 2323 2343', 'España', 'EUR', 54.00, '2026-04-26 15:45:22', '2026-04-26 15:45:22'),
(6, '54792759c', 'apaa', '722101966', 'alvaro@gmail.com', NULL, 'asdad', 'EUR', 12.00, '2026-04-26 16:42:30', '2026-04-26 18:04:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuotas`
--

CREATE TABLE `cuotas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED NOT NULL,
  `concepto` varchar(150) NOT NULL,
  `fecha_emision` date NOT NULL,
  `importe` decimal(10,2) NOT NULL,
  `pagada` enum('S','N') NOT NULL DEFAULT 'N',
  `fecha_pago` date DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `archivo_pdf` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuotas`
--

INSERT INTO `cuotas` (`id`, `cliente_id`, `concepto`, `fecha_emision`, `importe`, `pagada`, `fecha_pago`, `notas`, `archivo_pdf`, `created_at`, `updated_at`) VALUES
(1, 5, 'Mensualidad April 2026', '2026-04-26', 54.00, 'N', NULL, 'Generado automáticamente por Remesa', NULL, '2026-04-26 15:45:43', '2026-04-26 15:45:43'),
(2, 3, 'Mensualidad April 2026', '2026-04-26', 66.00, 'N', NULL, 'Generado automáticamente por Remesa', NULL, '2026-04-26 15:46:21', '2026-04-26 15:46:21'),
(3, 4, 'Mensualidad April 2026', '2026-04-26', 12.00, 'N', NULL, 'Generado automáticamente por Remesa', NULL, '2026-04-26 15:46:21', '2026-04-26 15:46:21'),
(4, 5, 'Mensualidad April 2026', '2026-04-26', 54.00, 'N', NULL, 'Generado automáticamente por Remesa', NULL, '2026-04-26 15:46:21', '2026-04-26 15:46:21'),
(5, 3, 'Mensualidad April 2026', '2026-04-26', 66.00, 'N', NULL, 'Generado automáticamente por Remesa', NULL, '2026-04-26 18:00:00', '2026-04-26 18:00:00'),
(6, 4, 'Mensualidad April 2026', '2026-04-26', 12.00, 'N', NULL, 'Generado automáticamente por Remesa', NULL, '2026-04-26 18:00:00', '2026-04-26 18:00:00'),
(7, 5, 'Mensualidad April 2026', '2026-04-26', 54.00, 'N', NULL, 'Generado automáticamente por Remesa', NULL, '2026-04-26 18:00:00', '2026-04-26 18:00:00'),
(8, 6, 'Mensualidad April 2026', '2026-04-26', 12.00, 'N', NULL, 'Generado automáticamente por Remesa', NULL, '2026-04-26 18:00:00', '2026-04-26 18:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dni` varchar(9) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_alta` date NOT NULL,
  `tipo` enum('administrador','operario') NOT NULL DEFAULT 'operario',
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `dni`, `nombre`, `email`, `telefono`, `direccion`, `fecha_alta`, `tipo`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, '12345678A', 'Admin', 'admin@test.com', '600000001', NULL, '2026-04-26', 'administrador', '$2y$12$v/eV.FazLAIVnkm.OVhPgOpRMj57LL9XHvAL3cIvy3R06.YxTF91e', NULL, '2026-04-26 10:42:19', '2026-04-26 14:29:41'),
(2, '54792759C', 'op1', 'op1@test.com', '722101955', NULL, '2026-04-26', 'operario', '$2y$12$gPm3QS5Xu4J7UJy8UzFCJOCczAK5/LNdWC57R2WIDqDRR310diAzC', NULL, '2026-04-26 10:46:49', '2026-04-26 10:46:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidencias`
--

CREATE TABLE `incidencias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cliente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `persona_contacto` varchar(100) NOT NULL,
  `telefono_contacto` varchar(20) NOT NULL,
  `descripcion` text NOT NULL,
  `email_contacto` varchar(150) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `poblacion` varchar(100) DEFAULT NULL,
  `codigo_postal` varchar(5) DEFAULT NULL,
  `provincia_codigo` varchar(2) DEFAULT NULL,
  `estado` enum('P','R','C') NOT NULL DEFAULT 'P',
  `operario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_realizacion` date DEFAULT NULL,
  `anotaciones_antes` text DEFAULT NULL,
  `anotaciones_despues` text DEFAULT NULL,
  `fichero_resumen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `incidencias`
--

INSERT INTO `incidencias` (`id`, `cliente_id`, `persona_contacto`, `telefono_contacto`, `descripcion`, `email_contacto`, `direccion`, `poblacion`, `codigo_postal`, `provincia_codigo`, `estado`, `operario_id`, `fecha_realizacion`, `anotaciones_antes`, `anotaciones_despues`, `fichero_resumen`, `created_at`, `updated_at`) VALUES
(1, NULL, 'qweqweqwe', '722101955', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'a@test.com', NULL, NULL, NULL, '40', 'P', 2, NULL, NULL, NULL, NULL, '2026-04-26 12:00:57', '2026-04-26 12:00:57'),
(2, 3, 'aaa', '722101955', 'asdasdadasdasdadasdas', 'alvaro@gmail.com', 'aaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'aaa', '21002', '09', 'P', 2, NULL, NULL, NULL, NULL, '2026-04-26 17:47:55', '2026-04-26 15:47:55');

--
-- Disparadores `incidencias`
--
DELIMITER $$
CREATE TRIGGER `trg_fecha_creacion_auto` BEFORE INSERT ON `incidencias` FOR EACH ROW BEGIN
    SET NEW.created_at = NOW();
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_21_074037_create_provincias_table', 1),
(5, '2026_04_21_074042_create_empleados_table', 1),
(6, '2026_04_21_074043_create_clientes_table', 1),
(7, '2026_04_21_074043_create_cuotas_table', 1),
(8, '2026_04_21_074043_create_incidencias_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

CREATE TABLE `provincias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo_ine` varchar(2) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `provincias`
--

INSERT INTO `provincias` (`id`, `codigo_ine`, `nombre`) VALUES
(1, '01', 'Álava'),
(2, '02', 'Albacete'),
(3, '03', 'Alicante'),
(4, '04', 'Almería'),
(5, '05', 'Ávila'),
(6, '06', 'Badajoz'),
(7, '07', 'Baleares'),
(8, '08', 'Barcelona'),
(9, '09', 'Burgos'),
(10, '10', 'Cáceres'),
(11, '11', 'Cádiz'),
(12, '12', 'Castellón'),
(13, '13', 'Ciudad Real'),
(14, '14', 'Córdoba'),
(15, '15', 'A Coruña'),
(16, '16', 'Cuenca'),
(17, '17', 'Girona'),
(18, '18', 'Granada'),
(19, '19', 'Guadalajara'),
(20, '20', 'Gipuzkoa'),
(21, '21', 'Huelva'),
(22, '22', 'Huesca'),
(23, '23', 'Jaén'),
(24, '24', 'León'),
(25, '25', 'Lleida'),
(26, '26', 'La Rioja'),
(27, '27', 'Lugo'),
(28, '28', 'Madrid'),
(29, '29', 'Málaga'),
(30, '30', 'Murcia'),
(31, '31', 'Navarra'),
(32, '32', 'Ourense'),
(33, '33', 'Asturias'),
(34, '34', 'Palencia'),
(35, '35', 'Las Palmas'),
(36, '36', 'Pontevedra'),
(37, '37', 'Salamanca'),
(38, '38', 'Santa Cruz de Tenerife'),
(39, '39', 'Cantabria'),
(40, '40', 'Segovia'),
(41, '41', 'Sevilla'),
(42, '42', 'Soria'),
(43, '43', 'Tarragona'),
(44, '44', 'Teruel'),
(45, '45', 'Toledo'),
(46, '46', 'Valencia'),
(47, '47', 'Valladolid'),
(48, '48', 'Bizkaia'),
(49, '49', 'Zamora'),
(50, '50', 'Zaragoza'),
(51, '51', 'Ceuta'),
(52, '52', 'Melilla');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('pwVS8ylM56Xw1XlnNqQvvUio9TJzbHLvEOe9oM4i', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibGZ6S2dVSVdCbDJnNHI3Mm1zR2tKQ2FjbjJnMFFxMlNjalpjRXdCeCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvY2xpZW50ZXMtdnVlLXZpdGUiO3M6NToicm91dGUiO3M6MTk6ImNsaWVudGVzLnZpdGUuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1777233930);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clientes_cif_unique` (`cif`);

--
-- Indices de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cuotas_cliente_id_foreign` (`cliente_id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `empleados_dni_unique` (`dni`),
  ADD UNIQUE KEY `empleados_email_unique` (`email`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incidencias_cliente_id_foreign` (`cliente_id`),
  ADD KEY `incidencias_operario_id_foreign` (`operario_id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `provincias_codigo_ine_unique` (`codigo_ine`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cuotas`
--
ALTER TABLE `cuotas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `incidencias`
--
ALTER TABLE `incidencias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `provincias`
--
ALTER TABLE `provincias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuotas`
--
ALTER TABLE `cuotas`
  ADD CONSTRAINT `cuotas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `incidencias`
--
ALTER TABLE `incidencias`
  ADD CONSTRAINT `incidencias_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `incidencias_operario_id_foreign` FOREIGN KEY (`operario_id`) REFERENCES `empleados` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
