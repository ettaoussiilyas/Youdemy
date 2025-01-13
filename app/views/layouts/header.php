<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Youdemy</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="/js/alerts.js"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-violet-600 text-white shadow-lg fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold">Youdemy</h1>
                <h3 class="text-xl font-bold">Welcome</h3>
                <div class="space-x-4">
                    <a href="/logout" class="hover:text-violet-200">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content container -->
    <div class="pt-16"> <!-- Added padding-top to account for fixed navbar -->
