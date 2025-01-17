<div class="w-64 bg-gradient-to-b from-gray-900 to-gray-800 min-h-screen fixed top-0 left-0 pt-16 shadow-xl">
    <!-- Profile Section -->
    <div class="px-6 py-4 border-b border-gray-700/50">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center shadow-lg">
                <span class="text-white text-lg font-bold">
                    <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                </span>
            </div>
            <div>
                <h3 class="text-white font-medium"><?php echo $_SESSION['user_name']; ?></h3>
                <span class="px-2 py-1 bg-violet-500/20 text-violet-300 text-xs rounded-full">
                    <i class="fas fa-user-graduate mr-1"></i>
                    Student
                </span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-3">
        <!-- Dashboard Link -->
        <a href="/student/dashboard" 
           class="group flex items-center px-4 py-3 mb-3 text-gray-300 hover:text-white rounded-xl transition-all duration-200 hover:bg-white/10">
            <div class="mr-3 w-9 h-9 rounded-xl bg-white/10 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-200">
                <i class="fas fa-home text-violet-400 group-hover:text-violet-300"></i>
            </div>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- My Courses Link -->
        <a href="/student/courses" 
           class="group flex items-center px-4 py-3 mb-3 text-gray-300 hover:text-white rounded-xl transition-all duration-200 hover:bg-white/10">
            <div class="mr-3 w-9 h-9 rounded-xl bg-white/10 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-200">
                <i class="fas fa-book text-blue-400 group-hover:text-blue-300"></i>
            </div>
            <span class="font-medium">My courses</span>
        </a>

        <!-- Browse Courses Link -->
        <a href="/student/browse" 
           class="group flex items-center px-4 py-3 mb-3 text-gray-300 hover:text-white rounded-xl transition-all duration-200 hover:bg-white/10">
            <div class="mr-3 w-9 h-9 rounded-xl bg-white/10 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-200">
                <i class="fas fa-search text-green-400 group-hover:text-green-300"></i>
            </div>
            <span class="font-medium">Browse</span>
        </a>

        <!-- Profile Link -->
        <a href="/student/profile" 
           class="group flex items-center px-4 py-3 mb-3 text-gray-300 hover:text-white rounded-xl transition-all duration-200 hover:bg-white/10">
            <div class="mr-3 w-9 h-9 rounded-xl bg-white/10 group-hover:bg-white/20 flex items-center justify-center transition-colors duration-200">
                <i class="fas fa-user text-amber-400 group-hover:text-amber-300"></i>
            </div>
            <span class="font-medium">Profile</span>
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