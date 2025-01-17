<div class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 min-h-screen fixed top-0 left-0 pt-16 shadow-xl">
    <!-- Profile Section -->
    <div class="px-6 py-4 border-b border-gray-700/50">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg">
                <span class="text-white text-lg font-bold">
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </span>
            </div>
            <div>
                <h3 class="text-white font-medium"><?php echo $_SESSION['user_name']; ?></h3>
                <span class="px-2 py-1 bg-red-500/20 text-red-300 text-xs rounded-full">
                    <i class="fas fa-user-shield mr-1"></i>
                    Administrator
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-3">
        <!-- Dashboard Link -->
        <a href="/admin/dashboard" 
           class="group flex items-center px-4 py-3 mb-3 text-gray-300 hover:text-white rounded-xl transition-all duration-200
                  <?php echo ($_SERVER['REQUEST_URI'] === '/admin/dashboard') ? 'bg-gradient-to-r from-red-600 to-red-500 shadow-lg shadow-red-500/30' : 'hover:bg-white/10'; ?>">
            <div class="mr-3 w-9 h-9 rounded-xl bg-white/10 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-200">
                <i class="fas fa-home text-red-400 group-hover:text-red-300"></i>
            </div>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Users Management -->
        <a href="/admin/users" 
           class="group flex items-center px-4 py-3 mb-3 text-gray-300 hover:text-white rounded-xl transition-all duration-200
                  <?php echo (str_contains($_SERVER['REQUEST_URI'], '/admin/users')) ? 'bg-gradient-to-r from-red-600 to-red-500 shadow-lg shadow-red-500/30' : 'hover:bg-white/10'; ?>">
            <div class="mr-3 w-9 h-9 rounded-xl bg-white/10 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-200">
                <i class="fas fa-users text-blue-400 group-hover:text-blue-300"></i>
            </div>
            <span class="font-medium">Users Management</span>
        </a>

        <!-- Content Management -->
        <a href="/admin/content" 
           class="group flex items-center px-4 py-3 mb-3 text-gray-300 hover:text-white rounded-xl transition-all duration-200
                  <?php echo (str_contains($_SERVER['REQUEST_URI'], '/admin/content')) ? 'bg-gradient-to-r from-red-600 to-red-500 shadow-lg shadow-red-500/30' : 'hover:bg-white/10'; ?>">
            <div class="mr-3 w-9 h-9 rounded-xl bg-white/10 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-200">
                <i class="fas fa-book-open text-green-400 group-hover:text-green-300"></i>
            </div>
            <span class="font-medium">Content Management</span>
        </a>

        <!-- Categories & Tags -->
        <a href="/admin/categories" 
           class="group flex items-center px-4 py-3 mb-3 text-gray-300 hover:text-white rounded-xl transition-all duration-200
                  <?php echo (str_contains($_SERVER['REQUEST_URI'], '/admin/categories')) ? 'bg-gradient-to-r from-red-600 to-red-500 shadow-lg shadow-red-500/30' : 'hover:bg-white/10'; ?>">
            <div class="mr-3 w-9 h-9 rounded-xl bg-white/10 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-200">
                <i class="fas fa-tags text-yellow-400 group-hover:text-yellow-300"></i>
            </div>
            <span class="font-medium">Categories & Tags</span>
        </a>

        <!-- Statistics -->
        <a href="/admin/statistics" 
           class="group flex items-center px-4 py-3 mb-3 text-gray-300 hover:text-white rounded-xl transition-all duration-200
                  <?php echo ($_SERVER['REQUEST_URI'] === '/admin/statistics') ? 'bg-gradient-to-r from-red-600 to-red-500 shadow-lg shadow-red-500/30' : 'hover:bg-white/10'; ?>">
            <div class="mr-3 w-9 h-9 rounded-xl bg-white/10 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-200">
                <i class="fas fa-chart-line text-purple-400 group-hover:text-purple-300"></i>
            </div>
            <span class="font-medium">Global Statistics</span>
        </a>

        <a href="/notifications" class="group flex items-center px-4 py-3 text-gray-300 hover:text-white rounded-xl transition-all duration-200">
            <div class="mr-3 w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center">
                <i class="fas fa-bell text-blue-400"></i>
            </div>
            <span class="font-medium">Notifications</span>
        </a>
    </nav>

    <!-- Bottom Section -->
    <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-gray-700/50 bg-gray-900/50 backdrop-blur-sm">
        <a href="/logout" class="flex items-center text-gray-400 hover:text-white transition-colors duration-200 group">
            <div class="mr-3 w-9 h-9 rounded-xl bg-white/10 group-hover:bg-red-500/20 flex items-center justify-center transition-colors duration-200">
                <i class="fas fa-sign-out-alt text-red-400 group-hover:text-red-300"></i>
            </div>
            <span class="font-medium">Logout</span>
        </a>
    </div>
</div> 