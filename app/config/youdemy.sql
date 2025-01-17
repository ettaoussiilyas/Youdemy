-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 17 jan. 2025 à 19:28
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `youdemy`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Web Development', 'Learn web technologies and frameworks', '2025-01-12 17:01:18'),
(2, 'Mobile Development', 'Build mobile applications for iOS and Android', '2025-01-12 17:01:18'),
(3, 'Data Science', 'Master data analysis and machine learning', '2025-01-12 17:01:18'),
(4, 'Design', 'UI/UX and graphic design principles', '2025-01-12 17:01:18'),
(5, 'Business', 'Business strategy and management', '2025-01-12 17:01:18'),
(6, 'Marketing', 'Digital marketing and SEO', '2025-01-12 17:01:18'),
(7, 'Languages', 'Learn programming languages', '2025-01-12 17:01:18'),
(8, 'Healty Coocking', NULL, '2025-01-16 14:19:58'),
(9, 'Fitness', 'Healty Movement And Exercices\r\n', '2025-01-16 14:23:15');

-- --------------------------------------------------------

--
-- Structure de la table `chapters`
--

CREATE TABLE `chapters` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chapters`
--

INSERT INTO `chapters` (`id`, `course_id`, `title`, `description`, `created_at`) VALUES
(1, 1, 'Introduction', 'Premier chapitre du cours', '2025-01-13 10:46:38'),
(2, 1, 'Bases', 'Les concepts de base', '2025-01-13 10:46:38'),
(3, 2, 'Démarrage', 'Comment commencer', '2025-01-13 10:46:38'),
(4, 1, 'Chapter 3 From /chapter/create?course=1', 'ghir hara wkan', '2025-01-13 16:03:52'),
(7, 10, 'Introduction', 'Vidéo à la demande de 3,5 heures\r\n2 exercices pratiques\r\nAccès sur mobiles et TV\r\nAccès illimité\r\nCertificat de fin de formation', '2025-01-14 15:08:15'),
(8, 10, 'Installation Set-Up', '​\r\n\r\nUn cours peut contenir plusieurs tags (relation many-to-many).\r\nApplication du concept de polymorphisme dans les méthodes suivantes : Ajouter cours et afficher cours.\r\nSystème d’authentification et d’autorisation pour protéger les routes sensibles.', '2025-01-14 15:08:15'),
(18, 19, 'Cupiditate quae ipsu', 'Dolore amet tenetur', '2025-01-14 19:12:56'),
(20, 20, 'Ea error reiciendis ', 'Alias adipisci dolor', '2025-01-15 14:56:55'),
(22, 19, 'Cupiditate quae ipsu', 'Dolore amet tenetur', '2025-01-15 15:18:43'),
(23, 21, '1111111', '11111111111111', '2025-01-15 15:27:24'),
(24, 21, '22222222', '2222222222', '2025-01-15 15:27:24'),
(25, 21, '3333333333', '3333333333', '2025-01-15 18:38:25'),
(27, 22, 'Cillum cupidatat ita', 'Perspiciatis quod c', '2025-01-15 18:42:35'),
(32, 26, 'Veniam dolore itaqu', 'Libero et ipsa solu', '2025-01-17 09:05:22'),
(34, 29, 'Ad facere quis excep', 'Exercitationem id no', '2025-01-17 09:10:03'),
(35, 30, 'Odio irure commodi o', 'Aut occaecat volupta', '2025-01-17 09:17:08'),
(42, 25, '123', '123', '2025-01-17 15:43:03'),
(43, 27, '11111111111111111111111111', 'sdfghbfd', '2025-01-17 15:51:08');

-- --------------------------------------------------------

--
-- Structure de la table `chapter_content`
--

CREATE TABLE `chapter_content` (
  `id` int(11) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `type` enum('video','document') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chapter_content`
--

INSERT INTO `chapter_content` (`id`, `chapter_id`, `title`, `type`, `file_path`, `original_name`, `created_at`) VALUES
(2, 1, 'INTRO INTO PHP LANGUAGE', 'video', 'uploads/videos/course_1/chapter_1/678508cd88119_php intro.mp4', 'php intro.mp4', '2025-01-13 12:36:29'),
(3, 2, 'SYNTAX and Varibles', 'video', 'uploads/videos/course_1/chapter_2/67852bad8e3c5_php intro.mp4', 'php intro.mp4', '2025-01-13 15:05:17'),
(5, 7, 'Introduction', 'video', 'uploads/videos/course_10/chapter_7/67867ddf62c9f_yt1z.net - Apprendre le PHP  Chapitre 1, Présentation de PHP.mp4', 'yt1z.net - Apprendre le PHP  Chapitre 1, Présentation de PHP.mp4', '2025-01-14 15:08:15'),
(6, 8, 'Installation Set-Up', 'video', 'uploads/videos/course_10/chapter_8/67867ddf65afd_yt1z.net - Apprendre le PHP  Chapitre 1, Présentation de PHP.mp4', 'yt1z.net - Apprendre le PHP  Chapitre 1, Présentation de PHP.mp4', '2025-01-14 15:08:15'),
(15, 18, 'Ad corrupti necessi', 'document', 'uploads/documents/course_19/chapter_18/6786b73822b2b_Lutilisation-des-Traits-en-PHP.pdf', 'Lutilisation-des-Traits-en-PHP.pdf', '2025-01-14 19:12:56'),
(17, 20, 'Ea error reiciendis ', 'document', 'uploads/documents/course_20/chapter_20/6787ccb79cc4a_ETTAOUSSI ILYAS.pdf', 'ETTAOUSSI ILYAS.pdf', '2025-01-15 14:56:55'),
(19, 23, 'ETTAOUSSI ILYAS.pdf', 'document', 'uploads/courses/21/chapters/6787d3dc8050d_ETTAOUSSI ILYAS.pdf', 'ETTAOUSSI ILYAS.pdf', '2025-01-15 15:27:24'),
(20, 24, 'motivation.pdf', 'document', 'uploads/courses/21/chapters/6787d3dc817e0_motivation.pdf', 'motivation.pdf', '2025-01-15 15:27:24'),
(21, 25, 'yt1z.net - Apprendre le PHP  Chapitre 1, Présentation de PHP.mp4', 'video', 'uploads/courses/21/chapters/678800a146474_yt1z.net - Apprendre le PHP  Chapitre 1, Présentation de PHP.mp4', 'yt1z.net - Apprendre le PHP  Chapitre 1, Présentation de PHP.mp4', '2025-01-15 18:38:25'),
(23, 27, 'Cillum cupidatat ita', 'document', 'uploads/documents/course_22/chapter_27/6788019be7b6d_Cahier des Charges #Example.pdf', 'Cahier des Charges #Example.pdf', '2025-01-15 18:42:35'),
(28, 32, 'Veniam dolore itaqu', 'document', 'uploads/documents/course_26/chapter_32/678a1d5215ad8_L’utilisation des Traits en PHP.pdf', 'L’utilisation des Traits en PHP.pdf', '2025-01-17 09:05:22'),
(30, 34, 'Ad facere quis excep', 'document', 'uploads/documents/course_29/chapter_34/678a1e6b68e38_L’utilisation des Traits en PHP.pdf', 'L’utilisation des Traits en PHP.pdf', '2025-01-17 09:10:03'),
(31, 35, 'Odio irure commodi o', 'document', 'uploads/documents/course_30/chapter_35/678a2014281aa_SoftSkillsForDev.pdf', 'SoftSkillsForDev.pdf', '2025-01-17 09:17:08'),
(37, 42, 'L’utilisation des Traits en PHP.pdf', 'document', 'uploads/courses/25/chapters/678a7a874af8d_L’utilisation des Traits en PHP.pdf', 'L’utilisation des Traits en PHP.pdf', '2025-01-17 15:43:03'),
(38, 43, 'L’utilisation des Traits en PHP.pdf', 'document', 'uploads/courses/27/chapters/678a7c6c20fff_L’utilisation des Traits en PHP.pdf', 'L’utilisation des Traits en PHP.pdf', '2025-01-17 15:51:08');

-- --------------------------------------------------------

--
-- Structure de la table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT 'https://placehold.co/600x400?text=Course',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `teacher_id`, `category_id`, `thumbnail`, `created_at`) VALUES
(1, 'PHP Basics', 'Learn PHP fundamentals', 2, 5, 'https://img.freepik.com/free-vector/programming-concept-illustration_114360-1351.jpg', '2025-01-12 17:01:18'),
(2, 'JavaScript Mastery', 'Master JavaScript basics', 2, 1, 'https://img.freepik.com/free-photo/programming-background-with-person-working-with-codes-computer_23-2150010125.jpg', '2025-01-12 17:01:18'),
(3, 'Python Programming', 'Introduction to Python', 3, 1, 'https://img.freepik.com/free-vector/web-development-programmer-engineering-coding-website-augmented-reality-interface-screens-developer-project-engineer-programming-software-application-design-cartoon-illustration_107791-3863.jpg', '2025-01-12 17:01:18'),
(4, 'Mobile App Design', 'Design beautiful apps', 3, 2, 'https://img.freepik.com/free-photo/html-system-website-concept_23-2150376770.jpg', '2025-01-12 17:01:18'),
(5, 'Data Analysis', 'Learn data analysis with Python', 2, 3, 'https://img.freepik.com/free-vector/gradient-ui-ux-background_23-2149052117.jpg', '2025-01-12 17:01:18'),
(6, 'Web Design', 'Master web design principles', 6, 4, 'https://img.freepik.com/free-vector/graphic-design-geometric-wallpaper_52683-34399.jpg', '2025-01-12 17:01:18'),
(7, 'React Native', 'Build mobile apps with React', 3, 2, 'https://img.freepik.com/free-vector/gradient-ui-ux-elements-collection_79603-1923.jpg', '2025-01-12 17:01:18'),
(10, 'Docker Pour Les Débutants', 'Apprenez Docker avec des exercices de codage pratiques. Pour les débutants en DevOps', 6, 1, 'https://img.freepik.com/free-photo/business-concept-with-graphic-holography_23-2149160032.jpg\n', '2025-01-14 15:08:15'),
(19, 'Autem aut explicabo', 'Omnis lorem impedit', 2, 5, 'https://img.freepik.com/free-photo/marketing-ideas-share-research-planning-concept_53876-127431.jpg', '2025-01-14 19:12:56'),
(20, 'Thered Language', 'Veritatis sit nobis ', 2, 7, 'https://img.freepik.com/free-vector/hand-drawn-flat-design-english-school-illustration_23-2149483286.jpg', '2025-01-15 14:56:55'),
(21, 'DropDrTbdel', 'Quos sit sintTbdel', 2, 2, 'https://img.freepik.com/free-vector/big-data-analytics-abstract-concept-illustration_335657-2136.jpg', '2025-01-15 14:57:47'),
(22, 'Esse culpa quia con', 'Pariatur Aliqua Vo', 2, 2, 'https://img.freepik.com/free-vector/digital-marketing-team-with-laptops-light-bulb-marketing-team-metrics-marketing-team-lead-responsibilities-concept_335657-258.jpg', '2025-01-15 18:42:35'),
(25, 'Dolor vitae tempora 12', 'Libero consequat Ci', 2, 9, 'https://img.freepik.com/free-vector/programming-concept-illustration_114360-1351.jpg', '2025-01-16 20:12:18'),
(26, 'Courses For Testing', 'Ut excepturi quos co', 2, 3, 'https://placehold.co/600x400?text=Course', '2025-01-17 09:05:22'),
(27, 'Tester Couurses :', 'Sint sunt delenioti o', 2, 8, 'https://img.freepik.com/free-vector/gradient-ui-ux-background_23-2149052117.jpg', '2025-01-17 09:06:38'),
(28, 'Duis animi illo cul', 'Aperiam molestias ad', 2, 8, 'https://img.freepik.com/free-vector/gradient-ui-ux-background_23-2149052117.jpg', '2025-01-17 09:09:27'),
(29, 'Duis animi illo cul', 'Aperiam molestias ad', 2, 8, 'https://img.freepik.com/free-vector/gradient-ui-ux-background_23-2149052117.jpg', '2025-01-17 09:10:03'),
(30, 'Quia Quia Quia Quia', 'Qui molestias molest', 2, 7, 'https://img.freepik.com/free-vector/gradient-ui-ux-background_23-2149052117.jpg', '2025-01-17 09:17:08'),
(31, 'Test From Thambnail', 'Praesentium cupidata', 2, 3, 'https://img.freepik.com/free-vector/programming-concept-illustration_114360-1351.jpg', '2025-01-17 09:54:53');

-- --------------------------------------------------------

--
-- Structure de la table `course_tag`
--

CREATE TABLE `course_tag` (
  `course_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `course_tags`
--

CREATE TABLE `course_tags` (
  `course_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `course_tags`
--

INSERT INTO `course_tags` (`course_id`, `tag_id`) VALUES
(1, 1),
(1, 10),
(2, 1),
(2, 2),
(2, 9),
(3, 4),
(3, 10),
(4, 5),
(4, 6),
(5, 4),
(5, 7),
(6, 6),
(6, 8),
(6, 9),
(7, 3),
(7, 5),
(10, 2),
(21, 5),
(21, 6),
(25, 8),
(25, 9),
(25, 14),
(25, 17),
(27, 1),
(27, 5),
(27, 6),
(27, 8),
(27, 10),
(28, 6),
(28, 10),
(29, 1),
(29, 3),
(29, 4),
(29, 5),
(29, 6),
(29, 7),
(29, 8),
(29, 9),
(30, 7),
(31, 2),
(31, 3),
(31, 6);

-- --------------------------------------------------------

--
-- Structure de la table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `enrollment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `progress` int(11) DEFAULT 0,
  `last_accessed` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `enrollment_date`, `progress`, `last_accessed`) VALUES
(1, 4, 1, '2025-01-12 19:16:07', 0, '2025-01-15 09:26:50'),
(2, 4, 2, '2025-01-12 19:16:07', 0, '2025-01-15 09:26:50'),
(3, 4, 10, '2025-01-12 19:16:07', 0, '2025-01-15 09:26:50'),
(4, 7, 2, '2025-01-12 19:16:07', 0, '2025-01-15 09:26:50'),
(5, 7, 10, '2025-01-12 19:16:07', 0, '2025-01-15 09:26:50'),
(6, 7, 6, '2025-01-12 19:16:07', 0, '2025-01-15 09:26:50'),
(7, 4, 10, '2025-01-12 19:16:07', 0, '2025-01-15 09:26:50'),
(8, 4, 5, '2025-01-12 19:16:07', 0, '2025-01-15 09:26:50'),
(9, 4, 19, '2025-01-15 13:22:57', 0, '2025-01-15 13:22:57'),
(10, 4, 4, '2025-01-15 13:27:09', 0, '2025-01-15 13:27:09'),
(11, 4, 7, '2025-01-15 13:27:20', 0, '2025-01-15 13:27:20'),
(12, 4, 6, '2025-01-15 13:55:04', 0, '2025-01-15 13:55:04'),
(13, 4, 21, '2025-01-15 19:07:38', 0, '2025-01-15 19:07:38'),
(14, 4, 22, '2025-01-15 19:13:13', 0, '2025-01-15 19:13:13'),
(15, 4, 3, '2025-01-15 19:13:25', 0, '2025-01-15 19:13:25'),
(16, 4, 20, '2025-01-16 16:21:30', 0, '2025-01-16 16:21:30'),
(18, 4, 25, '2025-01-17 08:48:08', 0, '2025-01-17 08:48:08'),
(19, 16, 6, '2025-01-17 16:47:03', 0, '2025-01-17 16:47:03');

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(14, '34'),
(17, '35'),
(10, 'Backend'),
(7, 'Data Analysis'),
(9, 'Frontend'),
(2, 'JavaScript'),
(5, 'Mobile'),
(1, 'PHP'),
(4, 'Python'),
(3, 'React'),
(11, 'Tambnails'),
(6, 'UI/UX'),
(8, 'Web Design');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','teacher','admin') NOT NULL,
  `status` enum('active','blocked','review') NOT NULL,
  `profile_image` varchar(255) DEFAULT 'https://ui-avatars.com/api/?name=User',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `profile_image`, `created_at`) VALUES
(1, 'Admin User', 'admin@youdemy.com', '$2y$10$ZxZkmasB0wn.4ouFcUmgq.PSMS3TUz3LGFmCdi4DuWQZvD8HiX3OK', 'admin', 'active', 'https://ui-avatars.com/api/?name=Admin+User', '2025-01-12 17:01:17'),
(2, 'Ilyas Teacher', 'ilyas@youdemy.com', '$2y$10$zcI1TPT4R3D4FHbavJsmXO2Clzwr1w6dpKIVo3wv.ZhqB18pvAIbK', 'teacher', 'active', 'https://placehold.co/600x400?text=Course', '2025-01-12 17:01:17'),
(3, 'Sarah Teacher', 'sara@youdemy.com', '$2y$10$PGZMJ..zWenKRB9X9wnC9.knC4YWFZMxfP7DXtoY9Lf8rKBqtwE3O', 'teacher', 'active', 'https://ui-avatars.com/api/?name=Sarah+Teacher', '2025-01-12 17:01:17'),
(4, 'Ali Student', 'ali@youdemy.com', '$2y$10$w9D8k.mPL5ZWZ9mWhFlR7unR/t1r5/FvBUVgRDpjpcVboXwbI2uhK', 'student', 'active', 'https://ui-avatars.com/api/?name=Mike+Student', '2025-01-12 17:01:17'),
(5, 'Sami Wilson', 'sami@youdemy.com', '$2y$10$Jtk0NRIaPB7gVbshXbCYFe263tQ0zWU82R5YaCFPL5umKN.h5jATa', 'student', 'blocked', 'https://ui-avatars.com/api/?name=Emma+Wilson', '2025-01-12 17:01:17'),
(6, 'Sam Brown', 'sam@youdemy.com', '$2y$10$69ZWdrNVDtekeWSJPmf34eocz45KwbjOfzoLfQNPPgvpKcWkPPony', 'teacher', 'active', 'https://ui-avatars.com/api/?name=David+Brown', '2025-01-12 17:01:17'),
(7, 'Abir Student', 'abir@youdemy.com', '$2y$10$195mGcb4QAop6aDIpQ.GZOOzYbOdsnMTuhYLqRQCHUPKC2kxN4FaS', 'student', 'active', 'https://placehold.co/600x400?text=Course', '2025-01-12 17:01:17'),
(8, 'Test User', 'test@youdemy.com', '$2y$10$D4qySulHMDVIE0JxeF8NT.LbJDhX2xSJ9N2EcMpUpendcMDhWocmq', 'teacher', 'review', 'https://ui-avatars.com/api/?name=User', '2025-01-14 10:24:12'),
(9, 'Jennifer Clarke', 'sycowisa@mailinator.com', '$2y$10$hjrLAKgV4vt5i1XUutJoPee/Qgok1TW32WA9KlJx3dS/S5G/vIiam', 'teacher', 'active', 'https://ui-avatars.com/api/?name=User', '2025-01-14 11:35:58'),
(10, 'rana mhalabia', 'rana@youdemy.com', '$2y$10$mOYarUMswqUNXMPF3YJq5ecc6st/47UhyRwsvpAip.Va2YyxVNpby', 'student', 'active', 'https://ui-avatars.com/api/?name=User', '2025-01-14 11:42:07'),
(11, 'Miranda Justice', 'pisahibe@mailinator.com', '$2y$10$qq879.2dTuL8FVTHJB0Xgurd8mLgAcvNQErC669Stft.3Chj.A12O', 'teacher', 'active', 'https://ui-avatars.com/api/?name=User', '2025-01-14 17:06:30'),
(13, 'Belle Meyer', 'qupuxisyne@mailinator.com', '$2y$10$128cg2xsPUP3vLJ25qJp9ehd5az3cHqrdfdhxBOHD4z7xx4eouS/G', 'teacher', 'review', 'https://ui-avatars.com/api/?name=User', '2025-01-15 10:45:58'),
(14, 'ahmed adil', 'ahmed@youdemy.com', '$2y$10$C25ThEd8oDNYpLTS/SzqfOOfeiMwErbFYKtYWwxoSXXS.fvWOiJ52', 'student', 'active', 'https://ui-avatars.com/api/?name=User', '2025-01-17 16:29:45'),
(15, 'ostad ostad', 'ostad@youdemy.com', '$2y$10$yOkj2k/DDOpZsJ1MgZywg.VX9G2NSb2nG.1fjRTcQT1fvw7ZLKwwi', 'teacher', 'review', 'https://ui-avatars.com/api/?name=User', '2025-01-17 16:31:14'),
(16, 'amelah othmane', 'othmane@youdemy.com', '$2y$10$hLvEcJSZz0G5t9X7q3ZhbeFtlZNsoBZYOpDanAy4f7RoLFOxk0UPS', 'student', 'active', 'https://ui-avatars.com/api/?name=User', '2025-01-17 16:46:47');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Index pour la table `chapter_content`
--
ALTER TABLE `chapter_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_chapter_video` (`chapter_id`);

--
-- Index pour la table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Index pour la table `course_tag`
--
ALTER TABLE `course_tag`
  ADD PRIMARY KEY (`course_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Index pour la table `course_tags`
--
ALTER TABLE `course_tags`
  ADD PRIMARY KEY (`course_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Index pour la table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipient_id` (`recipient_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `chapter_content`
--
ALTER TABLE `chapter_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chapters`
--
ALTER TABLE `chapters`
  ADD CONSTRAINT `chapters_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Contraintes pour la table `chapter_content`
--
ALTER TABLE `chapter_content`
  ADD CONSTRAINT `chapter_content_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`);

--
-- Contraintes pour la table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `course_tag`
--
ALTER TABLE `course_tag`
  ADD CONSTRAINT `course_tag_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `course_tags`
--
ALTER TABLE `course_tags`
  ADD CONSTRAINT `course_tags_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `course_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`);

--
-- Contraintes pour la table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_recipient_fk` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `notifications_sender_fk` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
