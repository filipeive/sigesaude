-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 03, 2025 at 05:50 AM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `DescribeAllTables` ()   BEGIN
    DECLARE done INT DEFAULT FALSE$$

DELIMITER ;

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
(1, '2025', 'Ativo', '2025-02-23 13:49:22', '2025-02-23 13:49:22'),
(2, '2024', 'Ativo', NULL, NULL),
(3, '2023', 'Ativo', NULL, NULL);

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
-- Table structure for table `configuracao_pagamentos`
--

CREATE TABLE `configuracao_pagamentos` (
  `id` bigint UNSIGNED NOT NULL,
  `metodo_pagamento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalhes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(2, 'SMI', 'Saude Materno Infantil', '2025-02-23 13:25:20', '2025-02-23 13:25:20'),
(3, 'Curso de Enfermagem', 'Curso de enfermagem com especialização em cuidados intensivos', NULL, NULL),
(4, 'Curso de Medicina', 'Curso de medicina com especialização em cardiologia', NULL, NULL),
(5, 'Curso de Fisioterapia', 'Curso de fisioterapia com especialização em reabilitação', NULL, NULL),
(6, 'Curso de Nutrição', 'Curso de nutrição com especialização em nutrição clínica', NULL, NULL),
(7, 'Curso de Psicologia', 'Curso de psicologia com especialização em psicologia clínica', NULL, NULL);

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

--
-- Dumping data for table `curso_docente`
--

INSERT INTO `curso_docente` (`id`, `curso_id`, `docente_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL),
(2, 5, 2, NULL, NULL),
(3, 4, 2, NULL, NULL),
(4, 7, 2, NULL, NULL),
(5, 1, 2, NULL, NULL),
(6, 5, 3, NULL, NULL),
(7, 4, 3, NULL, NULL),
(8, 7, 3, NULL, NULL),
(9, 1, 3, NULL, NULL),
(10, 2, 3, NULL, NULL),
(11, 5, 4, NULL, NULL),
(12, 6, 4, NULL, NULL),
(13, 1, 4, NULL, NULL),
(14, 2, 4, NULL, NULL),
(15, 4, 5, NULL, NULL),
(16, 6, 5, NULL, NULL),
(17, 7, 5, NULL, NULL),
(18, 5, 6, NULL, NULL),
(19, 4, 6, NULL, NULL),
(20, 6, 6, NULL, NULL),
(21, 7, 6, NULL, NULL),
(22, 1, 6, NULL, NULL);

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

--
-- Dumping data for table `departamentos`
--

INSERT INTO `departamentos` (`id`, `nome`, `descricao`, `created_at`, `updated_at`) VALUES
(1, 'Saúde Publica', 'Saúde de Publica', '2025-02-25 09:26:10', '2025-02-25 09:26:10');

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

--
-- Dumping data for table `disciplinas`
--

INSERT INTO `disciplinas` (`id`, `nome`, `docente_id`, `curso_id`, `nivel_id`, `created_at`, `updated_at`) VALUES
(25, 'Anatomia Humana', 1, 1, 2, NULL, '2025-03-06 11:28:21'),
(26, 'Fisiologia Humana', 2, 2, 1, NULL, NULL),
(27, 'Farmacologia', 3, 3, 2, NULL, NULL),
(28, 'Cuidados Intensivos', 1, 1, 2, NULL, NULL),
(29, 'Reabilitação Física', 3, 5, 1, NULL, '2025-04-02 08:19:55'),
(30, 'Psicologia Clínica', 5, 5, 2, NULL, NULL),
(31, 'Nutrição Clínica', 6, 4, 3, NULL, NULL),
(32, 'Cardiologia', 6, 2, 3, NULL, NULL),
(34, 'Qui tenetur id anim', 3, 7, 2, '2025-03-06 11:50:34', '2025-03-06 11:50:34'),
(35, 'Aut aspernatur odio', 2, 4, 3, '2025-03-06 11:56:16', '2025-03-06 11:56:16'),
(36, 'Vitae vitae sunt sae', 5, 2, 1, '2025-03-06 11:56:25', '2025-03-06 11:56:25');

-- --------------------------------------------------------

--
-- Table structure for table `docentes`
--

CREATE TABLE `docentes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `formacao` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `anos_experiencia` int DEFAULT NULL,
  `status` enum('Ativo','Inativo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ativo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `departamento_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `docentes`
--

INSERT INTO `docentes` (`id`, `user_id`, `formacao`, `anos_experiencia`, `status`, `created_at`, `updated_at`, `departamento_id`) VALUES
(1, 5, 'Nihil ullam magnam e', 5, 'Ativo', '2025-02-25 08:19:45', '2025-02-25 08:26:46', 1),
(2, 20, 'Cupiditate adipisici', 64, 'Inativo', '2025-02-26 13:12:28', '2025-02-26 13:12:28', 1),
(3, 21, 'Veniam laborum dolo', 20, 'Ativo', '2025-02-26 13:13:01', '2025-02-26 13:13:01', 1),
(4, 22, 'Delectus at fuga E', 83, 'Ativo', '2025-02-26 13:13:26', '2025-02-26 13:13:26', 1),
(5, 23, 'Adipisci nostrum ut', 75, 'Ativo', '2025-02-26 13:13:42', '2025-02-26 13:13:42', 1),
(6, 24, 'Non expedita ipsum e', 79, 'Ativo', '2025-02-26 13:14:03', '2025-02-26 13:14:03', 1);

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
(5, 12, 'Esse vel aliquam ve', 2, 1, '1999-10-15', 'Outro', '2024', 'Diurno', 'Desistente', 'Reprehenderit ad sit', '2025-02-23 12:33:55', '2025-02-25 08:28:39'),
(6, 7, 'FIlipe Domingos dos Santos', 1, 1, '1992-03-09', 'Masculino', '2025', 'Diurno', 'Ativo', '8472121589', '2025-02-25 15:24:03', '2025-02-25 15:24:03'),
(7, 25, '20254', 1, 1, '2025-03-26', 'Masculino', '2025', 'Diurno', 'Ativo', '8472122', '2025-03-26 12:33:54', '2025-03-26 12:33:54'),
(8, 26, '001.01.2025', 5, 1, '2025-03-27', 'Masculino', '2025', 'Diurno', 'Ativo', '847240296', '2025-03-27 04:23:49', '2025-03-27 04:23:49');

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
-- Table structure for table `historico_academico`
--

CREATE TABLE `historico_academico` (
  `id` bigint UNSIGNED NOT NULL,
  `estudante_id` bigint UNSIGNED DEFAULT NULL,
  `nivel_id` bigint UNSIGNED DEFAULT NULL,
  `data_inicial` date DEFAULT NULL,
  `data_final` date DEFAULT NULL,
  `status` enum('Aprovado','Reprovado','Em Andamento') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inscricao_disciplinas`
--

CREATE TABLE `inscricao_disciplinas` (
  `id` bigint UNSIGNED NOT NULL,
  `inscricao_id` bigint UNSIGNED NOT NULL,
  `disciplina_id` bigint UNSIGNED NOT NULL,
  `tipo` enum('Normal','Em Atraso') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Normal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inscricao_disciplinas`
--

INSERT INTO `inscricao_disciplinas` (`id`, `inscricao_id`, `disciplina_id`, `tipo`, `created_at`, `updated_at`) VALUES
(1, 2, 25, 'Normal', '2025-02-27 07:14:28', '2025-02-27 07:14:28'),
(2, 2, 29, 'Normal', '2025-02-27 07:14:28', '2025-02-27 07:14:28'),
(3, 3, 25, 'Normal', '2025-02-27 07:15:16', '2025-02-27 07:15:16'),
(4, 3, 29, 'Normal', '2025-02-27 07:15:16', '2025-02-27 07:15:16'),
(7, 5, 25, 'Normal', '2025-02-27 17:48:11', '2025-02-27 17:48:11'),
(8, 5, 29, 'Normal', '2025-02-27 17:48:11', '2025-02-27 17:48:11'),
(9, 6, 25, 'Normal', '2025-03-08 16:39:41', '2025-03-08 16:39:41'),
(10, 6, 26, 'Normal', '2025-03-08 16:39:41', '2025-03-08 16:39:41'),
(11, 6, 27, 'Normal', '2025-03-08 16:39:41', '2025-03-08 16:39:41'),
(12, 6, 28, 'Normal', '2025-03-08 16:39:41', '2025-03-08 16:39:41'),
(13, 7, 29, 'Normal', '2025-03-12 07:58:43', '2025-03-12 07:58:43'),
(14, 8, 29, 'Normal', '2025-03-12 10:08:55', '2025-03-12 10:08:55'),
(15, 9, 26, 'Normal', '2025-03-12 12:09:43', '2025-03-12 12:09:43'),
(16, 9, 36, 'Normal', '2025-03-12 12:09:43', '2025-03-12 12:09:43'),
(17, 10, 29, 'Normal', '2025-03-16 11:50:41', '2025-03-16 11:50:41'),
(18, 11, 29, 'Normal', '2025-03-26 12:34:39', '2025-03-26 12:34:39'),
(19, 12, 29, 'Normal', '2025-03-28 05:16:42', '2025-03-28 05:16:42'),
(20, 9, 25, 'Normal', '2025-04-01 15:00:51', '2025-04-01 15:00:51'),
(21, 13, 29, 'Normal', '2025-04-02 08:18:02', '2025-04-02 08:18:02');

-- --------------------------------------------------------

--
-- Table structure for table `inscricoes`
--

CREATE TABLE `inscricoes` (
  `id` bigint UNSIGNED NOT NULL,
  `estudante_id` bigint UNSIGNED NOT NULL,
  `ano_lectivo_id` bigint UNSIGNED NOT NULL,
  `semestre` int NOT NULL,
  `status` enum('Pendente','Confirmada','Cancelada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendente',
  `valor` decimal(10,2) DEFAULT NULL,
  `referencia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_inscricao` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inscricoes`
--

INSERT INTO `inscricoes` (`id`, `estudante_id`, `ano_lectivo_id`, `semestre`, `status`, `valor`, `referencia`, `data_inscricao`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 1, 'Cancelada', NULL, 'INS-WpbYD3HK', '2025-02-27', '2025-02-27 07:13:02', '2025-02-27 08:51:51'),
(2, 6, 1, 1, 'Cancelada', NULL, 'INS-GCfRJbh3', '2025-02-27', '2025-02-27 07:14:28', '2025-02-27 08:51:31'),
(3, 6, 1, 1, 'Confirmada', NULL, 'INS-m4FKyQZn', '2025-02-27', '2025-02-27 07:15:16', '2025-02-27 10:42:07'),
(4, 6, 1, 1, 'Cancelada', NULL, 'INS-VdIqsN7b', '2025-02-27', '2025-02-27 07:16:32', '2025-02-27 08:51:10'),
(5, 3, 1, 1, 'Confirmada', NULL, 'INS-L4tbQ7w0', '2025-02-27', '2025-02-27 17:48:11', '2025-02-27 17:58:28'),
(6, 2, 1, 1, 'Confirmada', NULL, 'INS-uEIjCcEG', '2025-03-08', '2025-03-08 16:39:41', '2025-03-08 16:40:21'),
(7, 6, 1, 1, 'Confirmada', NULL, 'INS-QlPp5zgF', '2025-03-12', '2025-03-12 07:58:43', '2025-03-12 09:13:24'),
(8, 6, 1, 1, 'Confirmada', NULL, 'INS-CbOzwXLc', '2025-03-12', '2025-03-12 10:08:55', '2025-03-12 10:08:55'),
(9, 4, 1, 1, 'Confirmada', NULL, 'INS-8tMlkGjN', '2025-03-12', '2025-03-12 12:09:43', '2025-03-12 12:10:38'),
(10, 6, 1, 1, 'Confirmada', NULL, 'INS-HObHer4d', '2025-03-16', '2025-03-16 11:50:41', '2025-03-20 12:52:56'),
(11, 7, 1, 1, 'Confirmada', NULL, 'INS-dmUKbCWU', '2025-03-26', '2025-03-26 12:34:39', '2025-03-27 04:10:30'),
(12, 7, 1, 1, 'Confirmada', NULL, 'INS-nhsklKtG', '2025-03-28', '2025-03-28 05:16:42', '2025-04-02 08:11:15'),
(13, 8, 1, 1, 'Confirmada', NULL, 'INS-8eCSNQZe', '2025-04-02', '2025-04-02 08:18:02', '2025-04-02 08:18:51');

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

--
-- Dumping data for table `matriculas`
--

INSERT INTO `matriculas` (`id`, `estudante_id`, `disciplina_id`, `created_at`, `updated_at`) VALUES
(1, 6, 25, '2025-02-26 16:07:55', '2025-02-26 16:07:55'),
(2, 6, 27, '2025-02-26 16:08:06', '2025-02-26 16:08:06'),
(3, 3, 25, '2025-02-27 17:58:28', '2025-02-27 17:58:28'),
(4, 3, 29, '2025-02-27 17:58:28', '2025-02-27 17:58:28'),
(5, 2, 25, '2025-03-08 16:40:21', '2025-03-08 16:40:21'),
(6, 2, 26, '2025-03-08 16:40:21', '2025-03-08 16:40:21'),
(7, 2, 27, '2025-03-08 16:40:21', '2025-03-08 16:40:21'),
(8, 2, 28, '2025-03-08 16:40:21', '2025-03-08 16:40:21'),
(9, 6, 29, '2025-03-12 09:13:24', '2025-03-12 09:13:24'),
(10, 4, 26, '2025-03-12 12:10:38', '2025-03-12 12:10:38'),
(11, 4, 36, '2025-03-12 12:10:38', '2025-03-12 12:10:38'),
(12, 2, 25, '2025-03-18 10:59:54', '2025-03-18 10:59:54'),
(13, 7, 29, '2025-03-27 04:10:30', '2025-03-27 04:10:30'),
(14, 8, 25, '2025-04-01 15:00:18', '2025-04-01 15:00:18'),
(15, 8, 29, '2025-04-02 08:18:51', '2025-04-02 08:18:51');

-- --------------------------------------------------------

--
-- Table structure for table `media_finals`
--

CREATE TABLE `media_finals` (
  `id` bigint UNSIGNED NOT NULL,
  `estudante_id` bigint UNSIGNED NOT NULL,
  `disciplina_id` bigint UNSIGNED NOT NULL,
  `ano_lectivo_id` bigint UNSIGNED NOT NULL,
  `media` decimal(5,2) NOT NULL,
  `status` enum('Aprovado','Reprovado','Dispensado') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media_finals`
--

INSERT INTO `media_finals` (`id`, `estudante_id`, `disciplina_id`, `ano_lectivo_id`, `media`, `status`, `created_at`, `updated_at`) VALUES
(1, 8, 25, 1, 16.00, 'Dispensado', '2025-03-28 18:01:28', '2025-03-28 18:01:28'),
(2, 2, 27, 1, 17.30, 'Dispensado', '2025-04-02 07:06:49', '2025-04-02 07:37:36'),
(3, 6, 29, 1, 9.10, 'Reprovado', '2025-04-02 08:23:15', '2025-04-02 17:29:10'),
(4, 3, 29, 1, 14.90, 'Dispensado', '2025-04-02 08:23:15', '2025-04-02 17:28:44'),
(5, 7, 29, 1, 13.25, 'Aprovado', '2025-04-02 08:23:15', '2025-04-02 17:13:35'),
(6, 8, 29, 1, 15.00, 'Dispensado', '2025-04-02 08:23:16', '2025-04-02 17:54:34'),
(7, 6, 25, 1, 9.10, 'Reprovado', '2025-04-03 03:45:20', '2025-04-03 03:48:49'),
(8, 3, 25, 1, 0.00, 'Reprovado', '2025-04-03 03:45:20', '2025-04-03 03:48:56'),
(9, 2, 25, 1, 0.00, 'Reprovado', '2025-04-03 03:45:20', '2025-04-03 03:48:56'),
(10, 4, 25, 1, 0.00, 'Reprovado', '2025-04-03 03:45:20', '2025-04-03 03:48:56');

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
(13, '2025_02_21_145615_create_permission_tables', 13),
(14, '2025_02_25_105055_create_notas_frequencia_table', 14),
(15, '2025_02_25_105104_create_notas_exame_table', 15),
(16, '2025_02_25_105114_create_media_finals_table', 16),
(17, '2025_02_26_131212_create_notas_detalhadas_table', 17),
(18, '2025_02_26_131642_create_notas_detalhadas_table', 18),
(19, '2025_02_26_131736_create_notas_detalhadas_table', 19),
(20, '2025_02_26_140609_create_notas_detalhadas_table', 20),
(21, '2025_02_26_140830_create_notas_detalhadas_table', 21),
(22, '2025_02_26_164202_add_fields_to_pagamentos_table', 22),
(23, '2025_02_27_081322_create_inscricoes_table', 23),
(24, '2025_03_03_094353_create_update_pagamentos_table', 24),
(25, '2025_03_12_144901_create_transacoes_table', 25),
(26, '2025_03_12_144919_create_relatorio_financeiros_table', 26),
(27, '2025_03_12_144950_create_configuracao_pagamentos_table', 27),
(28, '2025_03_28_143851_create_notificacoes_table', 28);

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

--
-- Dumping data for table `niveis`
--

INSERT INTO `niveis` (`id`, `nome`, `created_at`, `updated_at`) VALUES
(1, '1º Ano', NULL, NULL),
(2, '2º Ano', NULL, NULL),
(3, '3º Ano', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notas_detalhadas`
--

CREATE TABLE `notas_detalhadas` (
  `id` bigint UNSIGNED NOT NULL,
  `notas_frequencia_id` bigint UNSIGNED NOT NULL,
  `tipo` enum('Teste 1','Teste 2','Teste 3','Trabalho 1','Trabalho 2','Trabalho 3') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nota` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notas_detalhadas`
--

INSERT INTO `notas_detalhadas` (`id`, `notas_frequencia_id`, `tipo`, `nota`, `created_at`, `updated_at`) VALUES
(1, 1, 'Teste 1', 12.00, '2025-02-26 18:13:13', '2025-02-26 18:13:13'),
(2, 5, 'Teste 1', 20.00, '2025-04-02 06:32:11', '2025-04-02 06:33:31'),
(3, 5, 'Teste 2', 19.00, '2025-04-02 06:32:31', '2025-04-02 06:33:40'),
(4, 5, 'Trabalho 1', 20.00, '2025-04-02 06:32:36', '2025-04-02 06:33:52'),
(5, 5, 'Trabalho 2', 10.00, '2025-04-02 06:33:03', '2025-04-02 06:33:03'),
(6, 5, 'Trabalho 3', 12.00, '2025-04-02 06:33:13', '2025-04-02 06:33:13'),
(7, 5, 'Teste 3', 17.00, '2025-04-02 06:33:21', '2025-04-02 06:33:21'),
(8, 9, 'Teste 1', 14.00, '2025-04-02 08:23:16', '2025-04-02 17:07:29'),
(9, 9, 'Teste 2', 14.00, '2025-04-02 17:07:10', '2025-04-02 17:07:10'),
(10, 9, 'Teste 3', 17.00, '2025-04-02 17:07:29', '2025-04-02 17:07:29'),
(11, 9, 'Trabalho 1', 15.00, '2025-04-02 17:07:29', '2025-04-02 17:07:29'),
(12, 8, 'Teste 1', 14.00, '2025-04-02 17:07:38', '2025-04-02 17:07:38'),
(13, 8, 'Teste 2', 13.00, '2025-04-02 17:08:00', '2025-04-02 17:08:00'),
(14, 8, 'Trabalho 1', 10.00, '2025-04-02 17:08:07', '2025-04-02 17:08:07'),
(15, 6, 'Teste 1', 7.00, '2025-04-02 17:28:08', '2025-04-02 17:29:10'),
(16, 7, 'Teste 1', 11.00, '2025-04-02 17:28:18', '2025-04-02 17:28:58'),
(17, 6, 'Trabalho 1', 14.00, '2025-04-02 17:28:33', '2025-04-02 17:28:33'),
(18, 7, 'Trabalho 1', 10.00, '2025-04-02 17:28:44', '2025-04-02 17:28:44'),
(19, 6, 'Teste 2', 17.00, '2025-04-02 17:54:26', '2025-04-02 17:54:26'),
(20, 7, 'Teste 2', 13.00, '2025-04-02 17:54:34', '2025-04-02 17:54:34'),
(21, 1, 'Teste 2', 14.00, '2025-04-03 03:45:35', '2025-04-03 03:48:49'),
(22, 1, 'Trabalho 1', 10.00, '2025-04-03 03:45:41', '2025-04-03 03:48:12'),
(23, 1, 'Teste 3', 20.00, '2025-04-03 03:46:28', '2025-04-03 03:48:56'),
(24, 1, 'Trabalho 2', 10.00, '2025-04-03 03:47:54', '2025-04-03 03:47:54'),
(25, 1, 'Trabalho 3', 10.00, '2025-04-03 03:48:01', '2025-04-03 03:48:01');

-- --------------------------------------------------------

--
-- Table structure for table `notas_exame`
--

CREATE TABLE `notas_exame` (
  `id` bigint UNSIGNED NOT NULL,
  `estudante_id` bigint UNSIGNED NOT NULL,
  `disciplina_id` bigint UNSIGNED NOT NULL,
  `ano_lectivo_id` bigint UNSIGNED NOT NULL,
  `tipo_exame` enum('Normal','Recorrência') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nota` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notas_exame`
--

INSERT INTO `notas_exame` (`id`, `estudante_id`, `disciplina_id`, `ano_lectivo_id`, `tipo_exame`, `nota`, `created_at`, `updated_at`) VALUES
(1, 6, 25, 1, 'Normal', 16.00, '2025-02-26 18:19:39', NULL),
(2, 6, 25, 1, 'Normal', 16.00, '2025-02-26 18:19:39', NULL),
(4, 2, 27, 1, 'Normal', 14.00, '2025-04-02 07:06:49', '2025-04-02 07:06:49'),
(5, 7, 29, 1, 'Normal', 8.00, '2025-04-02 17:08:39', '2025-04-02 17:13:23'),
(6, 7, 29, 1, 'Recorrência', 14.00, '2025-04-02 17:13:35', '2025-04-02 17:13:35');

-- --------------------------------------------------------

--
-- Table structure for table `notas_frequencia`
--

CREATE TABLE `notas_frequencia` (
  `id` bigint UNSIGNED NOT NULL,
  `estudante_id` bigint UNSIGNED NOT NULL,
  `disciplina_id` bigint UNSIGNED NOT NULL,
  `ano_lectivo_id` bigint UNSIGNED NOT NULL,
  `nota` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` enum('Admitido','Excluído','Dispensado') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notas_frequencia`
--

INSERT INTO `notas_frequencia` (`id`, `estudante_id`, `disciplina_id`, `ano_lectivo_id`, `nota`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 25, 1, 13.70, 'Admitido', NULL, '2025-04-03 03:48:56'),
(2, 6, 25, 1, 15.00, 'Admitido', NULL, NULL),
(3, 6, 28, 1, 14.00, 'Admitido', '2025-03-12 13:45:44', '2025-03-12 13:45:44'),
(4, 6, 28, 1, 14.00, 'Admitido', '2025-03-12 13:45:44', '2025-03-12 13:45:44'),
(5, 2, 27, 1, 17.30, 'Dispensado', '2025-04-02 06:32:11', '2025-04-02 07:37:36'),
(6, 6, 29, 1, 12.60, 'Admitido', '2025-04-02 08:23:15', '2025-04-02 17:54:34'),
(7, 3, 29, 1, 11.40, 'Admitido', '2025-04-02 08:23:15', '2025-04-02 17:54:34'),
(8, 7, 29, 1, 12.50, 'Admitido', '2025-04-02 08:23:15', '2025-04-02 17:54:34'),
(9, 8, 29, 1, 15.00, 'Dispensado', '2025-04-02 08:23:15', '2025-04-02 17:54:34'),
(10, 3, 25, 1, 0.00, 'Excluído', '2025-04-03 03:45:20', '2025-04-03 03:48:56'),
(11, 2, 25, 1, 0.00, 'Excluído', '2025-04-03 03:45:20', '2025-04-03 03:48:56'),
(12, 4, 25, 1, 0.00, 'Excluído', '2025-04-03 03:45:20', '2025-04-03 03:48:56');

-- --------------------------------------------------------

--
-- Table structure for table `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `mensagem` text NOT NULL,
  `tipo` enum('academico','financeiro','administrativo','geral') NOT NULL,
  `icone` varchar(255) DEFAULT NULL,
  `cor` varchar(50) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `lida` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notificacoes`
--

INSERT INTO `notificacoes` (`id`, `user_id`, `titulo`, `mensagem`, `tipo`, `icone`, `cor`, `link`, `lida`, `created_at`, `updated_at`) VALUES
(1, 7, 'Inscricoes Abertas', 'Ola Estudantes', 'academico', NULL, NULL, NULL, 0, '2025-03-28 13:33:00', '2025-03-28 13:33:00'),
(2, 9, 'Inscricoes Abertas', 'Ola Estudantes', 'academico', NULL, NULL, NULL, 0, '2025-03-28 13:33:00', '2025-03-28 13:33:00'),
(3, 10, 'Inscricoes Abertas', 'Ola Estudantes', 'academico', NULL, NULL, NULL, 0, '2025-03-28 13:33:00', '2025-03-28 13:33:00'),
(4, 11, 'Inscricoes Abertas', 'Ola Estudantes', 'academico', NULL, NULL, NULL, 0, '2025-03-28 13:33:00', '2025-03-28 13:33:00'),
(5, 12, 'Inscricoes Abertas', 'Ola Estudantes', 'academico', NULL, NULL, NULL, 0, '2025-03-28 13:33:00', '2025-03-28 13:33:00'),
(6, 25, 'Inscricoes Abertas', 'Ola Estudantes', 'academico', NULL, NULL, NULL, 0, '2025-03-28 13:33:00', '2025-03-28 13:33:00'),
(7, 26, 'Inscricoes Abertas', 'Ola Estudantes', 'academico', NULL, NULL, NULL, 1, '2025-03-28 13:33:00', '2025-04-02 08:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `pagamentos`
--

CREATE TABLE `pagamentos` (
  `id` bigint UNSIGNED NOT NULL,
  `referencia` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estudante_id` bigint UNSIGNED NOT NULL,
  `descricao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `status` enum('pendente','pago','em_analise','cancelado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendente',
  `comprovante` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_pagamento` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_vencimento` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pagamentos`
--

INSERT INTO `pagamentos` (`id`, `referencia`, `estudante_id`, `descricao`, `valor`, `status`, `comprovante`, `data_pagamento`, `data_vencimento`, `created_at`, `updated_at`) VALUES
(1, '06555061803', 6, 'Janeiro', 2420.00, 'pago', 'comprovantes/kOpuBXZWNWAL0iqq95Saf44IoYuXHwhyB2kdtLHm.png', '2025-03-12 08:36:12', '2025-01-10', '2025-03-11 11:38:39', '2025-03-12 11:57:43'),
(2, '01182316116', 6, 'Fevereiro', 2820.00, 'pago', NULL, '2025-03-12 08:36:19', '2025-02-10', '2025-03-11 11:38:39', '2025-03-12 06:33:10'),
(3, '06924090904', 6, 'Marco', 2420.00, 'pago', NULL, '2025-03-12 08:36:26', '2025-03-10', '2025-03-11 11:38:39', '2025-03-12 06:33:11'),
(4, '02669849648', 6, 'Abril', 2420.00, 'pago', NULL, '2025-03-12 08:36:28', '2025-04-10', '2025-03-11 11:38:39', '2025-03-12 06:33:14'),
(5, '01829960889', 6, 'Maio', 2420.00, 'pago', NULL, '2025-03-12 11:14:43', '2025-05-10', '2025-03-11 11:38:39', '2025-03-12 09:13:53'),
(6, '05507030941', 6, 'Junho', 2420.00, 'pago', NULL, '2025-03-12 11:14:47', '2025-06-10', '2025-03-11 11:38:39', '2025-03-12 09:13:52'),
(7, '05129539512', 6, 'Julho', 2420.00, 'pago', NULL, '2025-03-12 11:14:49', '2025-07-10', '2025-03-11 11:38:39', '2025-03-12 09:13:50'),
(8, '05562553228', 6, 'Agosto', 2420.00, 'pago', 'comprovantes/w1i9Rqn76gBPsstIj2xIZmde7DuBnAB2k55vFCdf.jpg', '2025-03-12 11:14:51', '2025-08-10', '2025-03-11 11:38:39', '2025-03-12 09:13:47'),
(9, '01820739667', 6, 'Setembro', 2420.00, 'pago', NULL, '2025-03-12 11:14:53', '2025-09-10', '2025-03-11 11:38:39', '2025-03-12 09:13:55'),
(10, '01278917577', 6, 'Outubro', 2420.00, 'pago', NULL, '2025-03-12 11:14:54', '2025-10-10', '2025-03-11 11:38:39', '2025-03-12 09:13:56'),
(11, '05771076517', 6, 'Novembro', 2420.00, 'pago', NULL, '2025-03-12 11:14:55', '2025-11-10', '2025-03-11 11:38:39', '2025-03-12 09:13:57'),
(12, '08072632914', 6, 'Dezembro', 2420.00, 'pago', NULL, '2025-03-12 11:14:57', '2025-12-10', '2025-03-11 11:38:39', '2025-03-12 09:13:59'),
(37, '09546617705', 4, NULL, 2420.00, 'pago', 'comprovantes/BqZYj5b761e3cQLwagvn5ddxhRgKF88gxeb0tBfT.jpg', '2025-03-12 14:09:00', '2025-01-10', '2025-03-12 12:09:00', '2025-03-12 12:12:56'),
(38, '07137125319', 4, NULL, 2820.00, 'pago', 'comprovantes/Re59WhLv6b85OH6giAm3su9mElCoFdIp0fri401f.pdf', '2025-03-12 14:09:00', '2025-02-10', '2025-03-12 12:09:00', '2025-03-12 12:12:52'),
(39, '04270114659', 4, NULL, 2420.00, 'pago', 'comprovantes/8kVFRam4FUDYn7A5I3LaR2p1V9sZj7zdBJJlHVQC.pdf', '2025-03-12 14:09:00', '2025-03-10', '2025-03-12 12:09:00', '2025-03-12 12:12:34'),
(40, '09864511254', 4, NULL, 2420.00, 'pago', NULL, '2025-03-12 14:09:00', '2025-04-10', '2025-03-12 12:09:00', '2025-03-12 12:12:47'),
(41, '08183657699', 4, NULL, 2420.00, 'pendente', NULL, '2025-03-12 14:09:00', '2025-05-10', '2025-03-12 12:09:00', '2025-03-12 12:09:00'),
(42, '06062984240', 4, NULL, 2420.00, 'pendente', NULL, '2025-03-12 14:09:00', '2025-06-10', '2025-03-12 12:09:00', '2025-03-12 12:09:00'),
(43, '03989440554', 4, NULL, 2420.00, 'pendente', NULL, '2025-03-12 14:09:00', '2025-07-10', '2025-03-12 12:09:00', '2025-03-12 12:09:00'),
(44, '08352817378', 4, NULL, 2420.00, 'pendente', NULL, '2025-03-12 14:09:00', '2025-08-10', '2025-03-12 12:09:00', '2025-03-12 12:09:00'),
(45, '04723239586', 4, NULL, 2420.00, 'pendente', NULL, '2025-03-12 14:09:00', '2025-09-10', '2025-03-12 12:09:00', '2025-03-12 12:09:00'),
(46, '09756433791', 4, NULL, 2420.00, 'pago', NULL, '2025-03-12 14:09:00', '2025-10-10', '2025-03-12 12:09:00', '2025-03-24 12:46:26'),
(47, '09296003806', 4, NULL, 2420.00, 'pago', NULL, '2025-03-12 14:09:00', '2025-11-10', '2025-03-12 12:09:00', '2025-03-27 09:19:32'),
(48, '08534734549', 4, NULL, 2420.00, 'pago', NULL, '2025-03-12 14:09:00', '2025-12-10', '2025-03-12 12:09:00', '2025-03-12 13:25:03'),
(49, '03829711432', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-01-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(50, '01536328441', 6, NULL, 2820.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-02-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(51, '05587102703', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-03-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(52, '02523284230', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-04-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(53, '02514462296', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-05-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(54, '06934789170', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-06-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(55, '07257905708', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-07-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(56, '09811975310', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-08-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(57, '05611752941', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-09-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(58, '01799896523', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-10-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(59, '05877930572', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-11-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(60, '08606667897', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:52', '2024-12-10', '2025-03-16 12:03:52', '2025-03-16 12:03:52'),
(61, '04712465147', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-01-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(62, '07978571495', 6, NULL, 2820.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-02-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(63, '04864398850', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-03-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(64, '01213571329', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-04-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(65, '08644061150', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-05-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(66, '09246114665', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-06-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(67, '08750350210', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-07-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(68, '09211277659', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-08-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(69, '09076893362', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-09-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(70, '06756701191', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-10-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(71, '03713162040', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-11-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(72, '01218530370', 6, NULL, 2420.00, 'pendente', NULL, '2025-03-16 14:03:59', '2023-12-10', '2025-03-16 12:03:59', '2025-03-16 12:03:59'),
(73, '04179451161', 8, NULL, 2420.00, 'em_analise', 'comprovantes/pscdT48YxAaLIGb03oLPW5wq9NbY4xMvKmX8j95x.jpg', '2025-03-27 06:25:01', '2025-01-10', '2025-03-27 04:25:01', '2025-03-27 08:00:52'),
(74, '01511892023', 8, NULL, 2820.00, 'pago', NULL, '2025-03-27 06:25:01', '2025-02-10', '2025-03-27 04:25:01', '2025-03-27 09:19:06'),
(75, '06906966889', 8, NULL, 2420.00, 'pendente', NULL, '2025-03-27 06:25:01', '2025-03-10', '2025-03-27 04:25:01', '2025-03-27 04:25:01'),
(76, '07901466811', 8, NULL, 2420.00, 'pendente', NULL, '2025-03-27 06:25:01', '2025-04-10', '2025-03-27 04:25:01', '2025-03-27 04:25:01'),
(77, '05521905538', 8, NULL, 2420.00, 'pendente', NULL, '2025-03-27 06:25:01', '2025-05-10', '2025-03-27 04:25:01', '2025-03-27 04:25:01'),
(78, '06416380560', 8, NULL, 2420.00, 'pendente', NULL, '2025-03-27 06:25:01', '2025-06-10', '2025-03-27 04:25:01', '2025-03-27 04:25:01'),
(79, '03797920644', 8, NULL, 2420.00, 'pendente', NULL, '2025-03-27 06:25:01', '2025-07-10', '2025-03-27 04:25:01', '2025-03-27 04:25:01'),
(80, '07806702334', 8, NULL, 2420.00, 'pendente', NULL, '2025-03-27 06:25:01', '2025-08-10', '2025-03-27 04:25:01', '2025-03-27 04:25:01'),
(81, '06108813295', 8, NULL, 2420.00, 'pendente', NULL, '2025-03-27 06:25:01', '2025-09-10', '2025-03-27 04:25:01', '2025-03-27 04:25:01'),
(82, '04542997680', 8, NULL, 2420.00, 'pendente', NULL, '2025-03-27 06:25:01', '2025-10-10', '2025-03-27 04:25:01', '2025-03-27 04:25:01'),
(83, '03510392753', 8, NULL, 2420.00, 'pago', NULL, '2025-03-27 06:25:01', '2025-11-10', '2025-03-27 04:25:01', '2025-03-27 09:19:40'),
(84, '05251201302', 8, NULL, 2420.00, 'pago', NULL, '2025-03-27 06:25:01', '2025-12-10', '2025-03-27 04:25:01', '2025-03-27 09:19:21'),
(85, '01953545447', 7, NULL, 2420.00, 'em_analise', 'comprovantes/WhWfoHr1Co0FrVH4CYNu3EEFwn5bGZLGFUVfMbAy.pdf', '2025-03-28 07:16:27', '2025-01-10', '2025-03-28 05:16:27', '2025-03-28 09:59:52'),
(86, '04394899092', 7, NULL, 2820.00, 'em_analise', 'comprovantes/kGMcVOY4PM6oijeHSfL8qYoJ8ssQN3i8ycHrRpZW.png', '2025-03-28 07:16:27', '2025-02-10', '2025-03-28 05:16:27', '2025-03-28 10:04:06'),
(87, '05304064287', 7, NULL, 2420.00, 'em_analise', 'comprovantes/mO56Vw5qZNZj9eWK8F423dKnr9XOFxcg8N1lFLP4.pdf', '2025-03-28 07:16:27', '2025-03-10', '2025-03-28 05:16:27', '2025-03-28 08:10:00'),
(88, '06201036541', 7, NULL, 2420.00, 'em_analise', 'comprovantes/bFfcaK8nRx53lfbBzSzab3mbbZ05dp9WWy94WgYo.pdf', '2025-03-28 07:16:27', '2025-04-10', '2025-03-28 05:16:27', '2025-03-28 09:17:04'),
(89, '01498022285', 7, NULL, 2420.00, 'em_analise', 'comprovantes/rnWMLgWLdj59PTOYYx80lGJVMuP37uhnM28Ni6Ah.pdf', '2025-03-28 07:16:27', '2025-05-10', '2025-03-28 05:16:27', '2025-03-28 09:57:29'),
(90, '01022100607', 7, NULL, 2420.00, 'em_analise', 'comprovantes/7Z0mucd7VjEw99GZ2vxATAGV8VKCbolHiaKjipkF.pdf', '2025-03-28 07:16:27', '2025-06-10', '2025-03-28 05:16:27', '2025-03-28 10:30:06'),
(91, '07421316822', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 07:16:27', '2025-07-10', '2025-03-28 05:16:27', '2025-03-28 05:16:27'),
(92, '01865179180', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 07:16:27', '2025-08-10', '2025-03-28 05:16:27', '2025-03-28 05:16:27'),
(93, '02323320448', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 07:16:27', '2025-09-10', '2025-03-28 05:16:27', '2025-03-28 05:16:27'),
(94, '02714099313', 7, NULL, 2420.00, 'pago', NULL, '2025-03-28 07:16:27', '2025-10-10', '2025-03-28 05:16:27', '2025-03-28 08:58:34'),
(95, '05471956226', 7, NULL, 2420.00, 'pago', NULL, '2025-03-28 07:16:27', '2025-11-10', '2025-03-28 05:16:27', '2025-03-28 08:58:21'),
(96, '02175956475', 7, NULL, 2420.00, 'pago', NULL, '2025-03-28 07:16:27', '2025-12-10', '2025-03-28 05:16:27', '2025-03-28 08:58:09'),
(97, '07437782686', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-01-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(98, '06428052894', 7, NULL, 2820.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-02-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(99, '05476724432', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-03-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(100, '06674607114', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-04-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(101, '03361218663', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-05-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(102, '04592192033', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-06-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(103, '05377641314', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-07-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(104, '08584152716', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-08-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(105, '08990994276', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-09-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(106, '03273163563', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-10-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(107, '03013219811', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-11-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(108, '03666693881', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 10:48:22', '2024-12-10', '2025-03-28 08:48:22', '2025-03-28 08:48:22'),
(109, '07459308471', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-01-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55'),
(110, '04086198952', 7, NULL, 2820.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-02-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55'),
(111, '02773427185', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-03-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55'),
(112, '09611858890', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-04-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55'),
(113, '02917277857', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-05-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55'),
(114, '03557091254', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-06-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55'),
(115, '06721541367', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-07-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55'),
(116, '03618545289', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-08-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55'),
(117, '01274067706', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-09-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55'),
(118, '01570601287', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-10-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55'),
(119, '08819887475', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-11-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55'),
(120, '05981550628', 7, NULL, 2420.00, 'pendente', NULL, '2025-03-28 11:21:55', '2023-12-10', '2025-03-28 09:21:55', '2025-03-28 09:21:55');

--
-- Triggers `pagamentos`
--
DELIMITER $$
CREATE TRIGGER `before_insert_pagamento` BEFORE INSERT ON `pagamentos` FOR EACH ROW SET NEW.data_pagamento = IFNULL(NEW.data_pagamento, CURDATE())
$$
$$
DELIMITER ;

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
-- Table structure for table `relatorio_financeiros`
--

CREATE TABLE `relatorio_financeiros` (
  `id` bigint UNSIGNED NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` date NOT NULL,
  `conteudo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `transacoes`
--

CREATE TABLE `transacoes` (
  `id` bigint UNSIGNED NOT NULL,
  `descricao` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` decimal(15,2) NOT NULL,
  `data` date NOT NULL,
  `tipo` enum('entrada','saida') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transacoes`
--

INSERT INTO `transacoes` (`id`, `descricao`, `valor`, `data`, `tipo`, `created_at`, `updated_at`) VALUES
(1, 'Pagamento de Taxas', 2500.00, '2025-03-12', 'entrada', '2025-03-12 12:56:36', '2025-03-12 12:56:36'),
(2, 'Pagamento de Propinas', 700.00, '2025-03-12', 'entrada', '2025-03-12 14:10:13', '2025-03-12 14:10:13');

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
(2, 'Filipe Domingos dos Santos', 'filipedomingos198@gmail.com', NULL, '$2y$10$J.kYNX4Y/biT370vi5f7x.FRfoDW12bBXB.iGFr.k7MjeqO70zKe.', '+258847240296', 'storage/users/profile/iW9MK8OjzpZDKRTTAkIMPd6K0oHjjS310DzdntMG.jpg', 'Masculino', 'admin', NULL, '2025-02-21 15:36:53', '2025-03-16 11:46:57'),
(3, 'Administrador do Sistema', 'admin@example.com', NULL, '$2y$10$fnKFcvHq39PrYucTeue/9ewYM8mUB2SJa9T7/gFHD.0boT.NrQMiS', '+258847240296', 'storage/users/profile/LnwlB5VcCXZ1jBwKWjET1mgRDzxpYXTTLqzR2p5Y.jpg', 'Masculino', 'admin', 'zNPY7iQfmqkNA1iAOlexoKcGAhIGWY8AftR6wj9oBjQpzucXQMCK0JYk092g', '2025-02-21 16:36:52', '2025-03-19 06:54:46'),
(4, 'Secretaria', 'secretaria@example.com', NULL, '$2y$10$VAScI34eIJQuYQMEaoQkQeR0R4/fRk5FQJf.7JQKIzD6IL1luAbVW', '999999998', NULL, 'Feminino', 'secretaria', NULL, '2025-02-21 16:36:52', '2025-02-21 16:36:52'),
(5, 'Docente', 'docente@example.com', NULL, '$2y$10$T/tuZzL3KNISHIkOsFyQmeBCJAMDo5bQiFDom3S3zN4wkXjXVPZju', '999999997', NULL, 'Masculino', 'docente', NULL, '2025-02-21 16:36:52', '2025-02-21 16:36:52'),
(6, 'Financeiro', 'financeiro@example.com', NULL, '$2y$10$OgKrUcCoKuqu0NTSH24z1edOKgC4/6CTodqYLiC9wSUbfEL3TrtSS', '999999996', NULL, 'Feminino', 'financeiro', NULL, '2025-02-21 16:36:52', '2025-02-21 16:36:52'),
(7, 'Filipe Domingos dos Santos', 'filipe.dossantos@lifechild.org.za', NULL, '$2y$10$LfHXZj5To9uddvcxulpcFOlKO1NiGp./pNCG22TL/RO5KAqFgV8si', '847204578', 'storage/users/profile/qScNlaBXw1IiPsqNm2c9Juw6mFQIanigbUjOKDbv.png', 'Masculino', 'estudante', NULL, '2025-02-21 16:36:52', '2025-03-19 06:55:15'),
(9, 'Lynn Gibson', 'luvuqotov@mailinator.com', NULL, '$2y$10$16B./0.FDtuS8I6IgHMPM.Jb5IaboHEkfzhVbnkda0wBy/YDxdcAi', '+1 (423) 371-1346', NULL, 'Feminino', 'estudante', NULL, '2025-02-23 12:25:54', '2025-02-23 12:30:01'),
(10, 'Ariana Ryan', 'zefo@mailinator.com', NULL, '$2y$10$DMOH8DZ4LlsMeazODLkPdO.TwE8rUuQ1MB24ZHXNB8y9UfoXM4q32', 'Laboris beatae illum', 'storage/users/profile/rD7umq7R6xriRM8LwQCO9QB3WgiHpTU0FmDQzsLQ.png', 'Outro', 'estudante', NULL, '2025-02-23 12:31:32', '2025-02-27 11:58:42'),
(11, 'Coby Gonzalez', 'xufy@mailinator.com', NULL, '$2y$10$ni.nFtIJ6rlhsJMvEN8BLeYCpTSCNnsKlxyIMRG63RR3JRE1HlNGC', 'Minima molestias mag', NULL, 'Masculino', 'estudante', NULL, '2025-02-23 12:32:34', '2025-02-23 12:32:34'),
(12, 'Bo Rowe', 'cacido@mailinator.com', NULL, '$2y$10$8MprQVWOTPQcgVA1rr8eM.Y3l67NwulyQ5TydpoH.otOG9sXjCvFK', '+1 (626) 163-8661', 'perfil/gQz7v5PDeRF2oSk2lh6g5GeqTGrb4RIEeql4lnvj.png', 'Outro', 'estudante', NULL, '2025-02-23 12:33:55', '2025-02-25 08:28:39'),
(19, 'Vaughan Patterson', 'suxavivi@mailinator.com', NULL, '$2y$10$UuXWYCR5xPz1WrQElljpA.m.v6mdOi0ZMi2YALt8pY2WyZuK7iDgi', 'Commodi esse sed con', 'perfil/EMNJigxpUwjvUsTG2KS1Qntxq4lwXAftGGAkbNvo.png', NULL, 'docente', NULL, '2025-02-25 08:19:45', '2025-02-25 08:23:09'),
(20, 'Anne Ferguson', 'weryci@mailinator.com', NULL, '$2y$10$2CfQkUQL.8hnjL1sOy7F0e.84mcaia0uQmG2UT8/UxdfOHpUkmy/y', 'Reprehenderit tempo', 'perfil/nGWc5zWNhAMBvaDRMThSGQ245QprXtov1haQLmIf.png', NULL, 'docente', NULL, '2025-02-26 13:12:28', '2025-03-10 05:47:19'),
(21, 'Imogene Flowers', 'dilek@mailinator.com', NULL, '$2y$10$r4TrdfqcTco8i3vHkWRk3.eVvbvoOMtwXKjX6dPdXrYSi4/gwnQou', 'Quod anim fugit ips', 'perfil/0pLYWyawubwjdiba7wzr0fqLCMNainfnvZsYYyW0.png', NULL, 'docente', NULL, '2025-02-26 13:13:01', '2025-04-02 08:22:46'),
(22, 'Indigo Waters', 'nuhuqiho@mailinator.com', NULL, '$2y$10$01uXaGexEFw.8RAyyQePaenYsffGYDIfgW9RrSTgeBBhwWaSxeMsa', 'Proident nesciunt', 'perfil/6cOXOWmZOkg6P4u5g6SCFUN2ADJWYAS61xIC1m4p.png', NULL, 'docente', NULL, '2025-02-26 13:13:26', '2025-03-10 04:09:00'),
(23, 'Lucas Petersen', 'vumaz@mailinator.com', NULL, '$2y$10$99M5Q7fiX1zFRNLxjOMoGuQlPaJ59yj5aAEXvn7g2E7LZxllC3eOC', 'Proident qui in vol', 'perfil/gc4KwVG5oN9WDNIiD4BNz6bOQwVNYs5qLqGaj1Vq.png', NULL, 'docente', NULL, '2025-02-26 13:13:42', '2025-03-10 04:08:31'),
(24, 'Serina Munoz', 'hitohym@mailinator.com', NULL, '$2y$10$6Hm1O1ZZTJK3zIqHD1muoOY73L.ycgLV0HQpjQk3WZgoEGrbMXOca', 'Voluptate voluptatem', 'perfil/Kip5JDi254zZihSYPkaSFAzjqq0u2IcL9Wkj8MuA.png', NULL, 'docente', NULL, '2025-02-26 13:14:03', '2025-03-10 04:07:51'),
(25, 'Rogerio', 'rogerio@estudante.com', NULL, '$2y$10$k4TAKgOsNRd3Ehb1nvKUCe1yOVeZw5gj09exCYNzoXzzAdMoMQPaa', '847204578', NULL, 'Masculino', 'estudante', NULL, '2025-03-26 12:32:21', '2025-03-26 12:32:21'),
(26, 'Teste Filipe', 'instituto@teste.com', NULL, '$2y$10$PHMwWL8Kiq16Ap7C3yuz9OYpeSwbgYW9teUpYLU3djoBr3M6DFkbC', '847204578', 'storage/users/profile/GLCzijOlx37XsD8fooNru52JoaFtcjxMzCqZIZ1Y.jpg', 'Masculino', 'estudante', NULL, '2025-03-27 04:12:44', '2025-04-02 08:13:16');

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
-- Indexes for table `configuracao_pagamentos`
--
ALTER TABLE `configuracao_pagamentos`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `historico_academico`
--
ALTER TABLE `historico_academico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `estudante_id` (`estudante_id`),
  ADD KEY `nivel_id` (`nivel_id`);

--
-- Indexes for table `inscricao_disciplinas`
--
ALTER TABLE `inscricao_disciplinas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inscricao_disciplinas_inscricao_id_foreign` (`inscricao_id`),
  ADD KEY `inscricao_disciplinas_disciplina_id_foreign` (`disciplina_id`);

--
-- Indexes for table `inscricoes`
--
ALTER TABLE `inscricoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inscricoes_estudante_id_foreign` (`estudante_id`),
  ADD KEY `inscricoes_ano_lectivo_id_foreign` (`ano_lectivo_id`);

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
-- Indexes for table `media_finals`
--
ALTER TABLE `media_finals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `media_finals_estudante_id_foreign` (`estudante_id`),
  ADD KEY `media_finals_disciplina_id_foreign` (`disciplina_id`),
  ADD KEY `media_finals_ano_lectivo_id_foreign` (`ano_lectivo_id`);

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
-- Indexes for table `notas_detalhadas`
--
ALTER TABLE `notas_detalhadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notas_detalhadas_notas_frequencia_id_foreign` (`notas_frequencia_id`);

--
-- Indexes for table `notas_exame`
--
ALTER TABLE `notas_exame`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notas_exame_estudante_id_foreign` (`estudante_id`),
  ADD KEY `notas_exame_disciplina_id_foreign` (`disciplina_id`),
  ADD KEY `notas_exame_ano_lectivo_id_foreign` (`ano_lectivo_id`);

--
-- Indexes for table `notas_frequencia`
--
ALTER TABLE `notas_frequencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notas_frequencia_estudante_id_foreign` (`estudante_id`),
  ADD KEY `notas_frequencia_disciplina_id_foreign` (`disciplina_id`),
  ADD KEY `notas_frequencia_ano_lectivo_id_foreign` (`ano_lectivo_id`);

--
-- Indexes for table `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notificacoes_user` (`user_id`);

--
-- Indexes for table `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pagamentos_referencia_unique` (`referencia`),
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
-- Indexes for table `relatorio_financeiros`
--
ALTER TABLE `relatorio_financeiros`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `configuracao_pagamentos`
--
ALTER TABLE `configuracao_pagamentos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `curso_docente`
--
ALTER TABLE `curso_docente`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `disciplinas`
--
ALTER TABLE `disciplinas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `estudantes`
--
ALTER TABLE `estudantes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `historico_academico`
--
ALTER TABLE `historico_academico`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inscricao_disciplinas`
--
ALTER TABLE `inscricao_disciplinas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `inscricoes`
--
ALTER TABLE `inscricoes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matriculas`
--
ALTER TABLE `matriculas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `media_finals`
--
ALTER TABLE `media_finals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `niveis`
--
ALTER TABLE `niveis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notas_detalhadas`
--
ALTER TABLE `notas_detalhadas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `notas_exame`
--
ALTER TABLE `notas_exame`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `notas_frequencia`
--
ALTER TABLE `notas_frequencia`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `relatorio_financeiros`
--
ALTER TABLE `relatorio_financeiros`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
-- Constraints for table `historico_academico`
--
ALTER TABLE `historico_academico`
  ADD CONSTRAINT `historico_academico_ibfk_1` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`),
  ADD CONSTRAINT `historico_academico_ibfk_2` FOREIGN KEY (`nivel_id`) REFERENCES `niveis` (`id`);

--
-- Constraints for table `inscricao_disciplinas`
--
ALTER TABLE `inscricao_disciplinas`
  ADD CONSTRAINT `inscricao_disciplinas_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscricao_disciplinas_inscricao_id_foreign` FOREIGN KEY (`inscricao_id`) REFERENCES `inscricoes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inscricoes`
--
ALTER TABLE `inscricoes`
  ADD CONSTRAINT `inscricoes_ano_lectivo_id_foreign` FOREIGN KEY (`ano_lectivo_id`) REFERENCES `anos_lectivos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscricoes_estudante_id_foreign` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `matriculas`
--
ALTER TABLE `matriculas`
  ADD CONSTRAINT `matriculas_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matriculas_estudante_id_foreign` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `media_finals`
--
ALTER TABLE `media_finals`
  ADD CONSTRAINT `media_finals_ano_lectivo_id_foreign` FOREIGN KEY (`ano_lectivo_id`) REFERENCES `anos_lectivos` (`id`),
  ADD CONSTRAINT `media_finals_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`),
  ADD CONSTRAINT `media_finals_estudante_id_foreign` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`);

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
-- Constraints for table `notas_detalhadas`
--
ALTER TABLE `notas_detalhadas`
  ADD CONSTRAINT `notas_detalhadas_notas_frequencia_id_foreign` FOREIGN KEY (`notas_frequencia_id`) REFERENCES `notas_frequencia` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notas_exame`
--
ALTER TABLE `notas_exame`
  ADD CONSTRAINT `notas_exame_ano_lectivo_id_foreign` FOREIGN KEY (`ano_lectivo_id`) REFERENCES `anos_lectivos` (`id`),
  ADD CONSTRAINT `notas_exame_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`),
  ADD CONSTRAINT `notas_exame_estudante_id_foreign` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`);

--
-- Constraints for table `notas_frequencia`
--
ALTER TABLE `notas_frequencia`
  ADD CONSTRAINT `notas_frequencia_ano_lectivo_id_foreign` FOREIGN KEY (`ano_lectivo_id`) REFERENCES `anos_lectivos` (`id`),
  ADD CONSTRAINT `notas_frequencia_disciplina_id_foreign` FOREIGN KEY (`disciplina_id`) REFERENCES `disciplinas` (`id`),
  ADD CONSTRAINT `notas_frequencia_estudante_id_foreign` FOREIGN KEY (`estudante_id`) REFERENCES `estudantes` (`id`);

--
-- Constraints for table `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `fk_notificacoes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
