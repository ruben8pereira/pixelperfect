-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Abr-2025 às 11:18
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pixelperfectdatabase`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(191) NOT NULL,
  `description` text NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_e35cce0055b08308af59ecd41dbf65b0', 'i:1;', 1743419697),
('laravel_cache_e35cce0055b08308af59ecd41dbf65b0:timer', 'i:1743419697;', 1743419697);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `defect_types`
--

CREATE TABLE `defect_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `translations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`translations`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `defect_types`
--

INSERT INTO `defect_types` (`id`, `name`, `description`, `translations`, `created_at`, `updated_at`) VALUES
(1, 'Fissura crítica', NULL, '{\"name\":{\"en\":null,\"fr\":null,\"de\":null},\"description\":{\"en\":null,\"fr\":null,\"de\":null}}', '2025-03-28 10:10:46', '2025-04-04 08:16:35'),
(2, 'Fissura grave', NULL, '{\"name\":{\"en\":null,\"fr\":null,\"de\":null},\"description\":{\"en\":null,\"fr\":null,\"de\":null}}', '2025-03-28 10:10:55', '2025-04-04 08:16:52'),
(3, 'Fissura média-grave', NULL, '{\"name\":{\"en\":null,\"fr\":null,\"de\":null},\"description\":{\"en\":null,\"fr\":null,\"de\":null}}', '2025-03-28 10:11:05', '2025-04-04 08:17:02'),
(4, 'Fissura média', NULL, '{\"name\":{\"en\":null,\"fr\":null,\"de\":null},\"description\":{\"en\":null,\"fr\":null,\"de\":null}}', '2025-03-28 10:11:12', '2025-04-04 08:17:12'),
(5, 'Fissura leve', NULL, '{\"name\":{\"en\":null,\"fr\":null,\"de\":null},\"description\":{\"en\":null,\"fr\":null,\"de\":null}}', '2025-03-28 10:11:18', '2025-04-04 08:17:23');

-- --------------------------------------------------------

--
-- Estrutura da tabela `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"6d207f52-cf66-4eb6-bdbd-7e091c133bf9\",\"displayName\":\"App\\\\Notifications\\\\ReportInvitationNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:44:\\\"Illuminate\\\\Notifications\\\\AnonymousNotifiable\\\":1:{s:6:\\\"routes\\\";a:1:{s:4:\\\"mail\\\";s:15:\\\"ruben@email.com\\\";}}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:12:\\\"notification\\\";O:46:\\\"App\\\\Notifications\\\\ReportInvitationNotification\\\":2:{s:13:\\\"\\u0000*\\u0000invitation\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:27:\\\"App\\\\Models\\\\ReportInvitation\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"98f57228-bf1c-410b-99c7-abce57cc8a77\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1743174156, 1743174156),
(2, 'default', '{\"uuid\":\"6cdbe661-9ef5-4715-8e90-79263fd0c245\",\"displayName\":\"App\\\\Notifications\\\\ReportInvitationNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:29:\\\"Illuminate\\\\Support\\\\Collection\\\":2:{s:8:\\\"\\u0000*\\u0000items\\\";a:1:{i:0;O:44:\\\"Illuminate\\\\Notifications\\\\AnonymousNotifiable\\\":1:{s:6:\\\"routes\\\";a:1:{s:4:\\\"mail\\\";s:15:\\\"ruben@email.com\\\";}}}s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;}s:12:\\\"notification\\\";O:46:\\\"App\\\\Notifications\\\\ReportInvitationNotification\\\":2:{s:13:\\\"\\u0000*\\u0000invitation\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:27:\\\"App\\\\Models\\\\ReportInvitation\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"339f059f-5b56-40dc-9f88-aa2f14dfeec2\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"}}', 0, NULL, 1743174267, 1743174267);

-- --------------------------------------------------------

--
-- Estrutura da tabela `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
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
-- Estrutura da tabela `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(21, '0001_01_01_000001_create_cache_table', 1),
(22, '0001_01_01_000002_create_jobs_table', 1),
(23, '2025_02_25_110840_create_roles_table', 1),
(24, '2025_02_25_111143_create_organizations_table', 1),
(25, '2025_02_25_111144_create_users_table', 1),
(26, '2025_02_25_111418_create_reports_table', 1),
(27, '2025_02_25_111425_create_defect_types_table', 1),
(28, '2025_02_25_111430_create_report_defects_table', 1),
(29, '2025_02_25_111435_create_report_comments_table', 1),
(30, '2025_02_25_111441_create_report_images_table', 1),
(31, '2025_02_25_112112_create_user_invitations_table', 1),
(32, '2025_02_25_113820_add_two_factor_columns_to_users_table', 1),
(33, '2025_02_27_171354_create_audit_logs_table', 1),
(34, '2025_03_21_115759_create_report_invitations_table', 1),
(35, '2025_03_21_121236_rename_image_column_to_file_path_in_report_images', 1),
(36, '2025_03_21_122045_add_defect_id_to_report_images_table', 1),
(37, '2025_03_24_152320_add_missing_fields_to_reports_table', 1),
(38, '2025_03_27_095132_create_report_sections_table', 1),
(39, '2025_03_27_211640_add_column_section_id_to_table_report_images', 1),
(40, '2025_03_27_222412_add_column_section_id_to_table_report_defects', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `vat` varchar(191) NOT NULL,
  `phone` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `logo_path` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `organizations`
--

INSERT INTO `organizations` (`id`, `name`, `description`, `address`, `vat`, `phone`, `email`, `logo_path`, `created_at`, `updated_at`) VALUES
(1, 'RúbenCorp', NULL, NULL, '1111111111', '911111111', 'rubencorp@email.com', NULL, '2025-03-28 10:10:24', '2025-03-28 10:10:24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `report_number` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `organization_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `pdf_export_count` int(11) NOT NULL DEFAULT 0,
  `language` varchar(2) NOT NULL DEFAULT 'en',
  `inspection_date` date DEFAULT NULL,
  `client` varchar(191) DEFAULT NULL,
  `operator` varchar(191) DEFAULT NULL,
  `intervention_reason` varchar(191) DEFAULT NULL,
  `weather` varchar(191) DEFAULT NULL,
  `location` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `reports`
--

INSERT INTO `reports` (`id`, `title`, `report_number`, `description`, `organization_id`, `created_by`, `pdf_export_count`, `language`, `inspection_date`, `client`, `operator`, `intervention_reason`, `weather`, `location`, `created_at`, `updated_at`) VALUES
(2, 'CESAE', '1811', 'Problemas na rede de água na Rua Ciríaco Cardoso.', 1, 1, 17, 'fr', '2024-11-18', 'Márcia Santos', 'Bruno Santos', 'Network state control', 'Sunny', 'Porto, Portugal', '2025-03-28 10:22:42', '2025-04-04 08:13:52');

-- --------------------------------------------------------

--
-- Estrutura da tabela `report_comments`
--

CREATE TABLE `report_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `include_in_pdf` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `report_comments`
--

INSERT INTO `report_comments` (`id`, `report_id`, `user_id`, `content`, `include_in_pdf`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'A intervenção rápida da equipa impediu a aparição de problemas mais graves.', 1, '2025-03-28 10:23:32', '2025-03-28 10:23:32'),
(2, 2, 1, 'Bom trabalho, equipa.', 0, '2025-03-28 10:23:43', '2025-03-28 10:23:43');

-- --------------------------------------------------------

--
-- Estrutura da tabela `report_defects`
--

CREATE TABLE `report_defects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `defect_type_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `severity` varchar(191) DEFAULT NULL,
  `coordinates` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`coordinates`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `report_defects`
--

INSERT INTO `report_defects` (`id`, `report_id`, `defect_type_id`, `section_id`, `description`, `severity`, `coordinates`, `created_at`, `updated_at`) VALUES
(3, 2, 1, 3, 'Fissura crítica.', 'critical', '{\"distance\":\"12.55\",\"counter\":\"00:01:30\",\"water_level\":\"5%\",\"reference\":\"PT1\",\"comment\":\"Fissura cr\\u00edtica.\",\"latitude\":\"45.123456\",\"longitude\":\"65.123456\"}', '2025-03-28 10:22:42', '2025-03-28 10:22:42'),
(4, 2, 2, 4, 'Fissura grave.', 'high', '{\"distance\":\"5\",\"counter\":\"00:00:00\",\"water_level\":\"50%\",\"reference\":\"PT2\",\"comment\":\"Fissura grave.\"}', '2025-03-28 10:22:42', '2025-03-28 10:22:42'),
(5, 2, 3, 3, 'Fissura média-grave.', 'medium', '{\"distance\":\"3\",\"counter\":\"00:02:00\",\"water_level\":\"100%\",\"reference\":\"PT3\",\"comment\":\"Fissura m\\u00e9dia-grave.\",\"latitude\":\"47.123456\",\"longitude\":\"69.123456\"}', '2025-03-28 10:22:42', '2025-03-28 10:22:42'),
(6, 2, 4, 4, 'Fissura média.', 'low', '{\"distance\":\"0.25\",\"counter\":\"00:00:30\",\"water_level\":\"dry\",\"reference\":\"PT4\",\"comment\":\"Fissura m\\u00e9dia.\",\"latitude\":\"79.123456\",\"longitude\":\"97.123456\"}', '2025-03-28 10:22:42', '2025-03-28 10:22:42');

-- --------------------------------------------------------

--
-- Estrutura da tabela `report_images`
--

CREATE TABLE `report_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `defect_id` bigint(20) UNSIGNED DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_path` varchar(191) NOT NULL,
  `caption` text DEFAULT NULL,
  `drawing_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`drawing_data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `report_images`
--

INSERT INTO `report_images` (`id`, `report_id`, `defect_id`, `section_id`, `file_path`, `caption`, `drawing_data`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, 'report-images/gxebJ8csE9n0QaJ6zsL6cEIi73lnJyXjUuDqZD8l.png', 'Map', NULL, '2025-03-28 10:22:42', '2025-03-28 10:22:42'),
(2, 2, NULL, 3, 'report-images/JCG12z14fynyBWHoUvdAbfHwpszT9dZ6dL7Qhdlv.png', 'Section Início da Rua Image', NULL, '2025-03-28 10:22:42', '2025-03-28 10:22:42'),
(3, 2, NULL, 4, 'report-images/rFFjwWAHH4gNf78hyPV4wMRKpd9hej5XlNRQ20Te.png', 'Section Fim da Rua Image', NULL, '2025-03-28 10:22:42', '2025-03-28 10:22:42'),
(4, 2, 3, NULL, 'report-images/9AncSx0Yp6PvRlhbYlS71OKksGVwoXaUuoe5RX59.png', 'Fissura crítica.', NULL, '2025-03-28 10:22:42', '2025-03-28 10:22:42'),
(5, 2, 4, NULL, 'report-images/nArcMGPnyGkjCtBKDivBDKcCCD0ncV7c5WwJjlqs.png', 'Fissura grave.', NULL, '2025-03-28 10:22:42', '2025-03-28 10:22:42'),
(6, 2, 5, NULL, 'report-images/FOj6Rr15oBUvWx4cPMiNHnykmCIyRzPbtB2ZVB94.png', 'Fissura média-grave.', NULL, '2025-03-28 10:22:42', '2025-03-28 10:22:42'),
(7, 2, 6, NULL, 'report-images/zCKJsx3CvygYNvzNOlV6krFjVidC4xpkN5IsLZZ7.png', 'Fissura média.', NULL, '2025-03-28 10:22:42', '2025-03-28 10:22:42');

-- --------------------------------------------------------

--
-- Estrutura da tabela `report_invitations`
--

CREATE TABLE `report_invitations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `invited_by` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `last_accessed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `report_sections`
--

CREATE TABLE `report_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `diameter` int(11) DEFAULT NULL,
  `material` varchar(191) DEFAULT NULL,
  `length` decimal(8,2) DEFAULT NULL,
  `start_manhole` varchar(191) DEFAULT NULL,
  `end_manhole` varchar(191) DEFAULT NULL,
  `location` varchar(191) DEFAULT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `report_sections`
--

INSERT INTO `report_sections` (`id`, `report_id`, `name`, `diameter`, `material`, `length`, `start_manhole`, `end_manhole`, `location`, `comments`, `created_at`, `updated_at`) VALUES
(3, 2, 'Início da Rua', 500, 'pvc', '45.80', 'Mauro', 'Rui', 'Porto', 'Intervenção urgente.', '2025-03-28 10:22:42', '2025-03-28 10:22:42'),
(4, 2, 'Fim da Rua', 125, 'steel', '25.00', 'Marcos', 'Bruno', 'Porto', 'Intervenção necessário mas não urgente.', '2025-03-28 10:22:42', '2025-03-28 10:22:42');

-- --------------------------------------------------------

--
-- Estrutura da tabela `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'System administrator', NULL, NULL),
(2, 'Organization', 'Organization manager', NULL, NULL),
(3, 'User', 'Paying member of an organization', NULL, NULL),
(4, 'Guest', 'Unregistered user', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('GFiQIM3O5lmbyCv4My9IUYxGS5PGDIGlROE8lbm3', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWDJiRDBIcDh3Y2cxWWlobUhhQU5DVm9lczVGa3pMdXQxdDg0c0pUVCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3JlcG9ydHMvMiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkazIwcXBXZUZBRWxTZjlJMXFUS0p3LjZQelRiRXFNaUdCUG42bDFJYjY4RHNCb3pmMWJILmkiO30=', 1743674813),
('jouAtKV5A8Uv5r2pTYjOW0EyzhHCds5BQ1Ky3PSY', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiWHhtQjBHdHBZUDg2d0JTdUp6bnJiNmV3bFNONnVWM0dZUFZZbFFqdyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2RlZmVjdC10eXBlcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkazIwcXBXZUZBRWxTZjlJMXFUS0p3LjZQelRiRXFNaUdCUG42bDFJYjY4RHNCb3pmMWJILmkiO3M6ODoiZmlsYW1lbnQiO2E6MDp7fX0=', 1743758248),
('NcdjuiDvFSb1JinknEHqxlB0wZLSN33o53XMhETj', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSllBWGtKNWVYZk80OWZydGJhT1VMT010ZjAzRnM3Yzg0MG9NOGdweCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1743690123),
('S72NmlGzRQiDU6lROQYWyRfntx42GrtZ9uAKHOne', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVWVIS0FTenFOemZvdzVVM2pxNGUxdUt3aVBkUXFhMGxaU2owcTl5VyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1743673689);

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `organization_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT 0,
  `access_expires_at` timestamp NULL DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `role_id`, `organization_id`, `phone`, `is_validated`, `access_expires_at`, `is_archived`) VALUES
(1, 'Rúben Pereira', 'ruben@email.com', NULL, '$2y$12$k20qpWeFAElSf9I1qTKJw.6PzTbEqMiGBPn6l1Ib68DsBozf1bH.i', NULL, NULL, NULL, 'ogF2cWla0lpePFacgMieVOuDnN0kGkgj0RstvJmYOlGsCG4mA5XXx5SqVv1k', '2025-03-28 10:10:24', '2025-03-28 10:10:24', 2, 1, NULL, 0, NULL, 0),
(2, 'Rui', 'admin@admin.com', NULL, '$2y$12$rDhDiNh/5V6r3PpTBBKvru4c6bNO2darljyT4oLm48nSSs1Xs0bES', NULL, NULL, NULL, NULL, '2025-03-28 15:06:39', '2025-03-31 11:51:07', 3, 1, '123456789', 0, NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `user_invitations`
--

CREATE TABLE `user_invitations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(191) NOT NULL,
  `organization_id` bigint(20) UNSIGNED NOT NULL,
  `invited_by` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`);

--
-- Índices para tabela `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Índices para tabela `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Índices para tabela `defect_types`
--
ALTER TABLE `defect_types`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Índices para tabela `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Índices para tabela `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `organizations_vat_unique` (`vat`);

--
-- Índices para tabela `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Índices para tabela `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_organization_id_foreign` (`organization_id`),
  ADD KEY `reports_created_by_foreign` (`created_by`);

--
-- Índices para tabela `report_comments`
--
ALTER TABLE `report_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_comments_report_id_foreign` (`report_id`),
  ADD KEY `report_comments_user_id_foreign` (`user_id`);

--
-- Índices para tabela `report_defects`
--
ALTER TABLE `report_defects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_defects_report_id_foreign` (`report_id`),
  ADD KEY `report_defects_defect_type_id_foreign` (`defect_type_id`),
  ADD KEY `report_defects_section_id_foreign` (`section_id`);

--
-- Índices para tabela `report_images`
--
ALTER TABLE `report_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_images_report_id_foreign` (`report_id`),
  ADD KEY `report_images_defect_id_foreign` (`defect_id`),
  ADD KEY `report_images_section_id_foreign` (`section_id`);

--
-- Índices para tabela `report_invitations`
--
ALTER TABLE `report_invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `report_invitations_token_unique` (`token`),
  ADD KEY `report_invitations_report_id_foreign` (`report_id`),
  ADD KEY `report_invitations_invited_by_foreign` (`invited_by`);

--
-- Índices para tabela `report_sections`
--
ALTER TABLE `report_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_sections_report_id_foreign` (`report_id`);

--
-- Índices para tabela `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_organization_id_foreign` (`organization_id`);

--
-- Índices para tabela `user_invitations`
--
ALTER TABLE `user_invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_invitations_token_unique` (`token`),
  ADD KEY `user_invitations_organization_id_foreign` (`organization_id`),
  ADD KEY `user_invitations_invited_by_foreign` (`invited_by`),
  ADD KEY `user_invitations_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `defect_types`
--
ALTER TABLE `defect_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `report_comments`
--
ALTER TABLE `report_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `report_defects`
--
ALTER TABLE `report_defects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `report_images`
--
ALTER TABLE `report_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `report_invitations`
--
ALTER TABLE `report_invitations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `report_sections`
--
ALTER TABLE `report_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `user_invitations`
--
ALTER TABLE `user_invitations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reports_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`);

--
-- Limitadores para a tabela `report_comments`
--
ALTER TABLE `report_comments`
  ADD CONSTRAINT `report_comments_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `report_defects`
--
ALTER TABLE `report_defects`
  ADD CONSTRAINT `report_defects_defect_type_id_foreign` FOREIGN KEY (`defect_type_id`) REFERENCES `defect_types` (`id`),
  ADD CONSTRAINT `report_defects_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_defects_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `report_sections` (`id`) ON DELETE SET NULL;

--
-- Limitadores para a tabela `report_images`
--
ALTER TABLE `report_images`
  ADD CONSTRAINT `report_images_defect_id_foreign` FOREIGN KEY (`defect_id`) REFERENCES `report_defects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_images_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_images_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `report_sections` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `report_invitations`
--
ALTER TABLE `report_invitations`
  ADD CONSTRAINT `report_invitations_invited_by_foreign` FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `report_invitations_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `report_sections`
--
ALTER TABLE `report_sections`
  ADD CONSTRAINT `report_sections_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Limitadores para a tabela `user_invitations`
--
ALTER TABLE `user_invitations`
  ADD CONSTRAINT `user_invitations_invited_by_foreign` FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_invitations_organization_id_foreign` FOREIGN KEY (`organization_id`) REFERENCES `organizations` (`id`),
  ADD CONSTRAINT `user_invitations_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
