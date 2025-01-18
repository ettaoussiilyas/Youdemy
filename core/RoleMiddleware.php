<?php

class RoleMiddleware {
    public static function checkRole($allowedRoles) {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            header('Location: /login');
            exit;
        }

        // Vérifier si le rôle de l'utilisateur est autorisé
        if (!in_array($_SESSION['user_role'], $allowedRoles)) {
            // Rediriger selon le rôle actuel
            switch($_SESSION['user_role']) {
                case 'student':
                    header('Location: /student/dashboard');
                    break;
                case 'teacher':
                    header('Location: /teacher/dashboard');
                    break;
                case 'admin':
                    header('Location: /admin/dashboard');
                    break;
                default:
                    header('Location: /login');
            }
            exit;
        }

        return true;
    }
} 