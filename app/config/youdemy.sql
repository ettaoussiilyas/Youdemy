-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 15 jan. 2025 à 10:27
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
(7, 'Languages', 'Learn programming languages', '2025-01-12 17:01:18');

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
(18, 19, 'Cupiditate quae ipsu', 'Dolore amet tenetur', '2025-01-14 19:12:56');

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
(15, 18, 'Ad corrupti necessi', 'document', 'uploads/documents/course_19/chapter_18/6786b73822b2b_Lutilisation-des-Traits-en-PHP.pdf', 'Lutilisation-des-Traits-en-PHP.pdf', '2025-01-14 19:12:56');

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
(1, 'PHP Basicssssss', 'Learn PHP fundamentals', 2, 5, 'https://placehold.co/600x400?text=PHP+Basics', '2025-01-12 17:01:18'),
(2, 'JavaScript Mastery', 'Master JavaScript basics', 2, 1, 'https://placehold.co/600x400?text=JavaScript', '2025-01-12 17:01:18'),
(3, 'Python Programming', 'Introduction to Python', 3, 1, 'https://placehold.co/600x400?text=Python', '2025-01-12 17:01:18'),
(4, 'Mobile App Design', 'Design beautiful apps', 3, 2, 'https://placehold.co/600x400?text=Mobile+Design', '2025-01-12 17:01:18'),
(5, 'Data Analysis', 'Learn data analysis with Python', 2, 3, 'https://placehold.co/600x400?text=Data+Analysis', '2025-01-12 17:01:18'),
(6, 'Web Design', 'Master web design principles', 6, 4, 'https://placehold.co/600x400?text=Web+Design', '2025-01-12 17:01:18'),
(7, 'React Native', 'Build mobile apps with React', 3, 2, 'https://placehold.co/600x400?text=React+Native', '2025-01-12 17:01:18'),
(10, 'Docker Pour Les Débutants', 'Apprenez Docker avec des exercices de codage pratiques. Pour les débutants en DevOps', 6, 1, 'https://placehold.co/600x400?text=Course', '2025-01-14 15:08:15'),
(19, 'Autem aut explicabo', 'Omnis lorem impedit', 2, 5, 'https://placehold.co/600x400?text=Course', '2025-01-14 19:12:56');

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
(7, 5);

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
(8, 4, 5, '2025-01-12 19:16:07', 0, '2025-01-15 09:26:50');

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
(10, 'Backend'),
(7, 'Data Analysis'),
(9, 'Frontend'),
(2, 'JavaScript'),
(5, 'Mobile'),
(1, 'PHP'),
(4, 'Python'),
(3, 'React'),
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
(9, 'Jennifer Clarke', 'sycowisa@mailinator.com', '$2y$10$hjrLAKgV4vt5i1XUutJoPee/Qgok1TW32WA9KlJx3dS/S5G/vIiam', 'teacher', 'review', 'https://ui-avatars.com/api/?name=User', '2025-01-14 11:35:58'),
(10, 'rana mhalabia', 'rana@youdemy.com', '$2y$10$mOYarUMswqUNXMPF3YJq5ecc6st/47UhyRwsvpAip.Va2YyxVNpby', 'student', 'active', 'https://ui-avatars.com/api/?name=User', '2025-01-14 11:42:07'),
(11, 'Miranda Justice', 'pisahibe@mailinator.com', '$2y$10$qq879.2dTuL8FVTHJB0Xgurd8mLgAcvNQErC669Stft.3Chj.A12O', 'teacher', 'review', 'https://ui-avatars.com/api/?name=User', '2025-01-14 17:06:30'),
(12, 'tester123', 'tester@youdemy.com', '$2y$10$NwCyH.T7SQYVgqkppBRE4.gZhi.wyNT8zHY.AnH9NjxcTCW7USFxi', 'teacher', 'review', 'https://ui-avatars.com/api/?name=User', '2025-01-14 17:07:30');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `chapter_content`
--
ALTER TABLE `chapter_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
