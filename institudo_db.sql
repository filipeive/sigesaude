-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 23, 2025 at 08:00 PM
-- Server version: 8.0.41-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `institudo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `anos_lectivos`
--

CREATE TABLE `anos_lectivos` (
  `id` bigint UNSIGNED NOT NULL,
  `ano` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Ativo','Inativo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ativo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `anos_lectivos`
--

INSERT INTO `anos_lectivos` (`id`, `ano`, `status`, `created_at`, `updated_at`) VALUES
(1, '2025', 'Ativo', '2025-02-23 13:49:22', '2025-02-23 13:49:22');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cursos`
--

CREATE TABLE `cursos` (
  `id` bigint UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cursos`
--

INSERT INTO `cursos` (`id`, `nome`, `descricao`, `created_at`, `updated_at`) VALUES
(1, 'Medicina Geral', 'Duracao 2 anos e meio', '2025-02-23 13:25:20', '2025-02-23 13:25:20'),
(2, 'SMI', 'Saude Materno Infantil', '2025-02-23 13:25:20', '2025-02-23 13:25:20');

-- --------------------------------------------------------

--
-- Table structure for table `curso_docente`
--

CREATE TABLE `curso_docente` (
  `id` bigint UNSIGNED NOT NULL,
  `curso_id` bigint UNSIGNED NOT NULL,
  `docente_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disciplinas`
--

CREATE TABLE `disciplinas` (
  `id` bigint UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `docente_id` bigint UNSIGNED NOT NULL,
  `curso_id` bigint UNSIGNED NOT NULL,
  `nivel_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `docentes`
--

CREATE TABLE `docentes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `departamento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `formacao` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `anos_experiencia` int DEFAULT NULL,
  `status` enum('Ativo','Inativo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ativo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `departamento_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estudantes`
--

CREATE TABLE `estudantes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `matricula` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `curso_id` bigint UNSIGNED NOT NULL,
  `ano_lectivo_id` bigint UNSIGNED NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `genero` enum('Masculino','Feminino','Outro') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ano_ingresso` year NOT NULL,
  `turno` enum('Diurno','Noturno') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Ativo','Inativo','Concluído','Desistente') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ativo',
  `contato_emergencia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estudantes`
--

INSERT INTO `estudantes` (`id`, `user_id`, `matricula`, `curso_id`, `ano_lectivo_id`, `data_nascimento`, `genero`, `ano_ingresso`, `turno`, `status`, `contato_emergencia`, `created_at`, `updated_at`) VALUES
(2, 9, 'Autem explicabo Dol', 1, 1, '1972-07-18', 'Feminino', '2025', 'Diurno', 'Concluído', '8475215687', '2025-02-23 12:25:54', '2025-02-23 12:30:01'),
(3, 10, 'Sequi modi laborum', 1, 1, '1982-11-22', 'Outro', '2025', 'Noturno', 'Ativo', 'Sed consequatur volu', '2025-02-23 12:31:32', '2025-02-23 12:31:32'),
(4, 11, 'Perspiciatis odio p', 2, 1, '1999-10-27', 'Masculino', '2025', 'Diurno', 'Ativo', 'Aute molestiae ipsum', '2025-02-23 12:32:34', '2025-02-23 12:32:34'),
(5, 12, 'Doloremque et eiusmo', 1, 1, '1999-07-21', 'Feminino', '2025', 'Noturno', 'Ativo', 'Blanditiis nihil eos', '2025-02-23 12:33:55', '2025-02-23 12:33:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matriculas`
--

CREATE TABLE `matriculas` (
  `id` bigint UNSIGNED NOT NULL,
  `estudante_id` bigint UNSIGNED NOT NULL,
  `disciplina_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 2),
(3, '0001_01_01_000002_create_jobs_table', 3),
(4, '2025_02_21_141755_create_anos_lectivos_table', 4),
(5, '2025_02_21_141823_create_niveis_table', 5),
(6, '2025_02_21_135812_create_cursos_table', 6),
(7, '2025_02_21_135415_create_estudantes_table', 7),
(8, '2025_02_21_135455_create_docentes_table', 8),
(9, '2025_02_21_135530_create_disciplinas_table', 9),
(10, '2025_02_21_135555_create_matriculas_table', 10),
(11, '2025_02_21_135622_create_pagamentos_table', 11),
(12, '2025_02_21_140257_create_curso_docente_table', 12),
(13, '2025_02_21_145615_create_permission_tables', 13);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 6),
(5, 'App\\Models\\User', 7);

-- --------------------------------------------------------

--
-- Table structure for table `niveis`
--

CREATE TABLE `niveis` (
  `id` bigint UNSIGNED NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pagamentos`
--

CREATE TABLE `pagamentos` (
  `id` bigint UNSIGNED NOT NULL,
  `estudante_id` bigint UNSIGNED NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `data_pagamento` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view estudantes', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(2, 'create estudantes', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(3, 'edit estudantes', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(4, 'delete estudantes', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(5, 'view docentes', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(6, 'create docentes', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(7, 'edit docentes', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(8, 'delete docentes', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(9, 'view disciplinas', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(10, 'create disciplinas', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(11, 'edit disciplinas', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(12, 'delete disciplinas', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(13, 'view pagamentos', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(14, 'create pagamentos', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(15, 'edit pagamentos', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(16, 'delete pagamentos', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(2, 'secretaria', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(3, 'docente', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(4, 'financeiro', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12'),
(5, 'estudante', 'web', '2025-02-21 16:34:12', '2025-02-21 16:34:12');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(1, 2),
(2, 2),
(3, 2),
(5, 2),
(9, 2),
(1, 3),
(9, 3),
(11, 3),
(1, 4),
(13, 4),
(14, 4),
(15, 4),
(9, 5);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('9x4AvAiX76QE419s6HKILh1PS2TQpQCPeOr1RTcQ', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:135.0) Gecko/20100101 Firefox/135.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMG44c05zZERFeTVYc3RNVHZqSmlrdjduMDR1cGtackpENVFRUkpLQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1740148410),
('LQEMDmlDTIrKsXVUKHJxlYvLFrR1mmwlsZrkPC2t', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64; rv:135.0) Gecko/20100101 Firefox/135.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVVKUU9nZnBHQmlERDBLenJMUkF1Q20xNG8xa2xlNVlMNmVVbHVtQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1740148410);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero` enum('Masculino','Feminino','Outro') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('admin','docente','estudante','financeiro','secretaria') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'estudante',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `telefone`, `foto_perfil`, `genero`, `tipo`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Filipe Domingos dos Santos', 'filipedomingos198@gmail.com', NULL, '$2y$10$M1o3oI15xN1BwdCkSFB6j.KYLxrmSQQ4Fy9f32N6sTItRixV/k3qC', '+258847240296', NULL, 'Masculino', 'admin', NULL, '2025-02-21 15:36:53', '2025-02-23 10:27:49'),
(3, 'Administrador', 'admin@example.com', NULL, '$2y$10$T1EDUKeGqWOx0MwRrHkjVedRLwa0ahpOt/ZYXLgzSW0asUIlwADI.', '+258847240296', NULL, 'Masculino', 'admin', 'KYmdjrTkGjmuMxThCPoFm0ookodMs7yhkAWLMysxYhP096Jwvx8Hhp6IB4HL', '2025-02-21 16:36:52', '2025-02-23 10:47:10'),
(4, 'Secretaria', 'secretaria@example.com', NULL, '$2y$10$VAScI34eIJQuYQMEaoQkQeR0R4/fRk5FQJf.7JQKIzD6IL1luAbVW', '999999998', NULL, 'Feminino', 'secretaria', NULL, '2025-02-21 16:36:52', '2025-02-21 16:36:52'),
(5, 'Docente', 'docente@example.com', NULL, '$2y$10$T/tuZzL3KNISHIkOsFyQmeBCJAMDo5bQiFDom3S3zN4wkXjXVPZju', '999999997', NULL, 'Masculino', 'docente', NULL, '2025-02-21 16:36:52', '2025-02-21 16:36:52'),
(6, 'Financeiro', 'financeiro@example.com', NULL, '$2y$10$OgKrUcCoKuqu0NTSH24z1edOKgC4/6CTodqYLiC9wSUbfEL3TrtSS', '999999996', NULL, 'Feminino', 'financeiro', NULL, '2025-02-21 16:36:52', '2025-02-21 16:36:52'),
(7, 'Estudante', 'estudante@example.com', NULL, '$2y$10$gXzyz5K04hJxiIwnDtlhgOySAgProjVXk7KEmZv6KttS58vR604cC', '999999995', NULL, 'Masculino', 'estudante', NULL, '2025-02-21 16:36:52', '2025-02-21 16:36:52'),
(9, 'Lynn Gibson', 'luvuqotov@mailinator.com', NULL, '$2y$10$16B./0.FDtuS8I6IgHMPM.Jb5IaboHEkfzhVbnkda0wBy/YDxdcAi', '+1 (423) 371-1346', NULL, 'Feminino', 'estudante', NULL, '2025-02-23 12:25:54', '2025-02-23 12:30:01'),
(10, 'Ariana Ryan', 'zefo@mailinator.com', NULL, '$2y$10$lmE7JanPeEnyvhZ.FRN4TOdTsghA9Saqr7QLdPU5Hb0h895KQYafC', 'Laboris beatae illum', NULL, 'Outro', 'estudante', NULL, '2025-02-23 12:31:32', '2025-02-23 12:31:32'),
(11, 'Coby Gonzalez', 'xufy@mailinator.com', NULL, '$2y$10$ni.nFtIJ6rlhsJMvEN8BLeYCpTSCNnsKlxyIMRG63RR3JRE1HlNGC', 'Minima molestias mag', NULL, 'Masculino', 'estudante', NULL, '2025-02-23 12:32:34', '2025-02-23 12:32:34'),
(12, 'Nell Watts', 'hoqikuny@mailinator.com', NULL, '$2y$10$8MprQVWOTPQcgVA1rr8eM.Y3l67NwulyQ5TydpoH.otOG9sXjCvFK', 'Reprehenderit blandi', NULL, 'Feminino', 'estudante', NULL, '2025-02-23 12:33:55', '2025-02-23 12:33:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anos_lectivos`
--
ALTER TABLE `anos_lectivos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `anos_lectivos_ano_unique` (`ano`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cursos_nome_unique` (`nome`);

--
-- Indexes for table `curso_docente`
--
ALTER TABLE `curso_docente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curso_docente_curso_id_foreign` (`curso_id`),
  ADD KEY `curso_docente_docente_id_foreign` (`docente_id`);

--
-- Indexes for table `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indexes for table `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `disciplinas_nome_unique` (`nome`),
  ADD KEY `disciplinas_docente_id_foreign` (`docente_id`),
  ADD KEY `disciplinas_curso_id_foreign` (`curso_id`),
  ADD KEY `disciplinas_nivel_id_foreign` (`nivel_id`);

--
-- Indexes for table `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `docentes_user_id_foreign` (`user_id`),
  ADD KEY `fk_departamento` (`departamento_id`);

--
-- Indexes for table `estudantes`
--
ALTER TABLE `estudantes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `estudantes_matricula_unique` (`matricula`),
  ADD KEY `estudantes_user_id_foreign` (`user_id`),
  ADD KEY `estudantes_curso_id_foreign` (`curso_id`),
  ADD KEY `estudantes_ano_lectivo_id_foreign` (`ano_lectivo_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `matriculas`
--
ALTER TABLE `matriculas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matriculas_estudante_id_foreign` (`estudante_id`),
  ADD KEY `matriculas_disciplina_id_foreign` (`disciplina_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `niveis`
--
ALTER TABLE `niveis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `niveis_nome_unique` (`nome`);

--
-- Indexes for table `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pagamentos_estudante_id_foreign` (`estudante_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anos_lectivos`
--
ALTER TABLE `anos_lectivos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `curso_docente`
--
ALTER TABLE `curso_docente`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estudantes`
--
ALTER TABLE `estudantes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `niveis`
--
ALTER TABLE `niveis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `curso_docente`
--
ALTER TABLE `curso_docente`
  ADD CONSTRAINT `curso_docente_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `curso_docente_docente_id_foreign` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `disciplinas`
--
ALTER TABLE `disciplinas`
  ADD CONSTRAINT `disciplinas_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disciplinas_docente_id_foreign` FOREIGN KEY (`docente_id`) REFERENCES `docentes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disciplinas_nivel_id_foreign` FOREIGN KEY (`nivel_id`) REFERENCES `niveis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `docentes`
--
ALTER TABLE `docentes`
  ADD CONSTRAINT `docentes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `estudantes`
--
ALTER TABLE `estudantes`
  ADD CONSTRAINT `estudantes_ano_lectivo_id_foreign` FOREIGN KEY (`ano_lectivo_id`) REFERENCES `anos_lectivos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `estudantes_curso_id_foreign` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `estudantes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matriculas_estudante_id_foreign` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD CONSTRAINT `pagamentos_estudante_id_foreign` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
