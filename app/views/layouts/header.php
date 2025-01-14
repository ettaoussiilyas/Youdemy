<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Youdemy</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/js/alerts.js"></script>
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-violet-600 to-violet-700 text-white shadow-lg fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo et nom -->
                <div class="flex items-center space-x-3">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                    <p class="text-2xl font-bold tracking-tight">Youdemy</p>
                </div>

                <!-- Menu central -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="/home" class="hover:text-violet-200 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="/courses" class="hover:text-violet-200 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-book mr-2"></i>Courses
                    </a>
                    <a href="#" class="hover:text-violet-200 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-envelope mr-2"></i>Contact
                    </a>
                    <a href="#" class="hover:text-violet-200 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>About
                    </a>
                </div>
                
                <!-- Bouton Get Started -->
                <div>
                    <a href="/login" class="hover:bg-violet-100 bg-white text-violet-600 px-6 py-2 rounded-full text-sm font-semibold transition-all duration-300 hover:shadow-md flex items-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content container -->
    <!-- <div class="pt-16">  -->