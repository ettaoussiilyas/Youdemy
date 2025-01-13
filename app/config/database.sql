-- Création de la base de données
CREATE DATABASE IF NOT EXISTS youdemy;
USE youdemy;

-- Table users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher', 'admin') NOT NULL,
    status ENUM('active', 'blocked') NOT NULL,
    profile_image VARCHAR(255) DEFAULT 'https://ui-avatars.com/api/?name=User',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table categories
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table courses
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    teacher_id INT,
    category_id INT,
    thumbnail VARCHAR(255) DEFAULT 'https://placehold.co/600x400?text=Course',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Table course_content
-- CREATE TABLE course_content (
--     id INT PRIMARY KEY AUTO_INCREMENT,
--     course_id INT,
--     title VARCHAR(255) NOT NULL,
--     type ENUM('video', 'document') NOT NULL,
--     content_url VARCHAR(255) NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     FOREIGN KEY (course_id) REFERENCES courses(id)
-- );


-- Table chapters
CREATE TABLE chapters (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Table chapter_content
CREATE TABLE chapter_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    chapter_id INT,
    title VARCHAR(255) NOT NULL,
    type ENUM('video', 'document') NOT NULL,
    file_path VARCHAR(255) NOT NULL,  -- hna ghadi n7to path dyal file
    original_name VARCHAR(255),       -- smiya li dkhl biha l'user
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chapter_id) REFERENCES chapters(id)
);

-- Autres tables restent les mêmes
CREATE TABLE tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE course_tags (
    course_id INT,
    tag_id INT,
    PRIMARY KEY (course_id, tag_id),
    FOREIGN KEY (course_id) REFERENCES courses(id),
    FOREIGN KEY (tag_id) REFERENCES tags(id)
);

CREATE TABLE enrollments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    course_id INT,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Données de test
INSERT INTO users (name, email, password, role, status, profile_image) VALUES
('Admin User', 'admin@youdemy.com', 'admin', 'admin', 'active', 'https://ui-avatars.com/api/?name=Admin+User'),
('Ilyas Teacher', 'ilyas@youdemy.com', 'ilyas', 'teacher', 'active', 'https://placehold.co/600x400?text=Course'),
('Sarah Teacher', 'sara@youdemy.com', 'sara', 'teacher', 'active', 'https://ui-avatars.com/api/?name=Sarah+Teacher'),
('Ali Student', 'ali@youdemy.com', 'ali', 'student', 'active', 'https://ui-avatars.com/api/?name=Mike+Student'),
('Sami Wilson', 'sami@youdemy.com', 'sami', 'student', 'blocked', 'https://ui-avatars.com/api/?name=Emma+Wilson'),
('Sam Brown', 'sam@youdemy.com', 'sam', 'teacher', 'active', 'https://ui-avatars.com/api/?name=David+Brown'),
('Abir Student', 'abir@youdemy.com', 'abir', 'student', 'active', 'https://placehold.co/600x400?text=Course');

INSERT INTO categories (name, description) VALUES
('Web Development', 'Learn web technologies and frameworks'),
('Mobile Development', 'Build mobile applications for iOS and Android'),
('Data Science', 'Master data analysis and machine learning'),
('Design', 'UI/UX and graphic design principles'),
('Business', 'Business strategy and management'),
('Marketing', 'Digital marketing and SEO'),
('Languages', 'Learn programming languages');

INSERT INTO courses (title, description, teacher_id, category_id, thumbnail) VALUES
('PHP Basics', 'Learn PHP fundamentals', 2, 1, 'https://placehold.co/600x400?text=PHP+Basics'),
('JavaScript Mastery', 'Master JavaScript basics', 2, 1, 'https://placehold.co/600x400?text=JavaScript'),
('Python Programming', 'Introduction to Python', 3, 1, 'https://placehold.co/600x400?text=Python'),
('Mobile App Design', 'Design beautiful apps', 3, 2, 'https://placehold.co/600x400?text=Mobile+Design'),
('Data Analysis', 'Learn data analysis with Python', 2, 3, 'https://placehold.co/600x400?text=Data+Analysis'),
('Web Design', 'Master web design principles', 6, 4, 'https://placehold.co/600x400?text=Web+Design'),
('React Native', 'Build mobile apps with React', 3, 2, 'https://placehold.co/600x400?text=React+Native');

INSERT INTO course_content (course_id, title, type, content_url) VALUES
(1, 'Introduction to PHP', 'video', 'https://www.youtube.com/embed/sample1'),
(1, 'PHP Documentation', 'document', 'https://drive.google.com/sample1'),
(2, 'JavaScript Basics', 'video', 'https://www.youtube.com/embed/sample2'),
(2, 'JS Exercises', 'document', 'https://drive.google.com/sample2'),
(3, 'Python Setup', 'video', 'https://www.youtube.com/embed/sample3'),
(4, 'Design Guidelines', 'document', 'https://drive.google.com/sample3'),
(5, 'Data Analysis Intro', 'video', 'https://www.youtube.com/embed/sample4');

-- Insertion des tags
INSERT INTO tags (name) VALUES
('PHP'),
('JavaScript'),
('React'),
('Python'),
('Mobile'),
('UI/UX'),
('Data Analysis'),
('Web Design'),
('Frontend'),
('Backend');

-- Association des tags aux cours
INSERT INTO course_tags (course_id, tag_id) VALUES
-- PHP Basics (cours 1)
(1, 1), -- PHP
(1, 10), -- Backend

-- JavaScript Mastery (cours 2)
(2, 2), -- JavaScript
(2, 9), -- Frontend
(2, 1), -- PHP

-- Python Programming (cours 3)
(3, 4), -- Python
(3, 10), -- Backend

-- Mobile App Design (cours 4)
(4, 5), -- Mobile
(4, 6), -- UI/UX

-- Data Analysis (cours 5)
(5, 7), -- Data Analysis
(5, 4), -- Python

-- Web Design (cours 6)
(6, 8), -- Web Design
(6, 6), -- UI/UX
(6, 9), -- Frontend

-- React Native (cours 7)
(7, 3), -- React
(7, 5); -- Mobile

-- Inscriptions des étudiants aux cours
INSERT INTO enrollments (student_id, course_id) VALUES
-- Ali Student (id: 4)
(4, 1), -- PHP Basics
(4, 2), -- JavaScript Mastery
(4, 3), -- Python Programming

-- Abir Student (id: 7)
(7, 2), -- JavaScript Mastery
(7, 4), -- Mobile App Design
(7, 6), -- Web Design

-- Sami Wilson (id: 5)
(5, 1), -- PHP Basics
(5, 5); -- Data Analysis

-- Les autres insertions restent les mêmes


ALTER TABLE chapter_content
ADD UNIQUE KEY `unique_chapter_video` (chapter_id);

-- Les Cas d jointre
-- 1. Afficher tous les cours avec leurs enseignants
SELECT c.title, u.name as teacher_name
FROM courses c
INNER JOIN users u ON c.teacher_id = u.id
WHERE u.role = 'teacher';

-- 2. Afficher les cours avec leurs catégories
SELECT c.title, cat.name as category_name
FROM courses c
INNER JOIN categories cat ON c.category_id = cat.id;

-- 3. Afficher les cours avec leurs tags
SELECT c.title, GROUP_CONCAT(t.name) as tags
FROM courses c
LEFT JOIN course_tags ct ON c.id = ct.course_id
LEFT JOIN tags t ON ct.tag_id = t.id
GROUP BY c.id;

-- 4. Liste des étudiants inscrits à un cours spécifique
SELECT u.name as student_name, c.title as course_title
FROM enrollments e
INNER JOIN users u ON e.student_id = u.id
INNER JOIN courses c ON e.course_id = c.id
WHERE c.id = 1; -- Remplacer 1 par l'ID du cours souhaité

-- 5. Nombre d'étudiants par cours
SELECT c.title, COUNT(e.student_id) as student_count
FROM courses c
LEFT JOIN enrollments e ON c.id = e.course_id
GROUP BY c.id;

-- 6. Tous les cours d'un étudiant spécifique
SELECT u.name as student_name, c.title as course_title
FROM users u
INNER JOIN enrollments e ON u.id = e.student_id
INNER JOIN courses c ON e.course_id = c.id
WHERE u.id = 4; -- Remplacer 4 par l'ID de l'étudiant

-- 7. Cours avec tous leurs détails (enseignant, catégorie, tags)
SELECT 
    c.title,
    u.name as teacher_name,
    cat.name as category_name,
    GROUP_CONCAT(t.name) as tags
FROM courses c
INNER JOIN users u ON c.teacher_id = u.id
INNER JOIN categories cat ON c.category_id = cat.id
LEFT JOIN course_tags ct ON c.id = ct.course_id
LEFT JOIN tags t ON ct.tag_id = t.id
GROUP BY c.id;

-- 8. Nombre de cours par enseignant
SELECT u.name as teacher_name, COUNT(c.id) as course_count
FROM users u
LEFT JOIN courses c ON u.id = c.teacher_id
WHERE u.role = 'teacher'
GROUP BY u.id;

-- 9. Cours sans inscriptions
SELECT c.title
FROM courses c
LEFT JOIN enrollments e ON c.id = e.course_id
WHERE e.id IS NULL;

-- 10. Étudiants sans inscriptions
SELECT u.name
FROM users u
LEFT JOIN enrollments e ON u.id = e.student_id
WHERE u.role = 'student' AND e.id IS NULL;

-- 11. Top 3 des cours les plus populaires
SELECT c.title, COUNT(e.id) as enrollment_count
FROM courses c
LEFT JOIN enrollments e ON c.id = e.course_id
GROUP BY c.id
ORDER BY enrollment_count DESC
LIMIT 3;

-- 12. Cours par catégorie avec nombre d'étudiants
SELECT 
    cat.name as category_name,
    c.title,
    COUNT(e.id) as student_count
FROM categories cat
LEFT JOIN courses c ON cat.id = c.category_id
LEFT JOIN enrollments e ON c.id = e.course_id
GROUP BY cat.id, c.id;

-- Zid chi data de test (optionnel)
INSERT INTO chapters (course_id, title, description) VALUES
(1, 'Introduction', 'Premier chapitre du cours'),
(1, 'Bases', 'Les concepts de base'),
(2, 'Démarrage', 'Comment commencer');