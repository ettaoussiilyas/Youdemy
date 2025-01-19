<?php require_once __DIR__.'/../layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen pb-64">
    <?php require_once __DIR__.'/../layouts/sidebareTeacher.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20">
        <!-- Welcome Message -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back, <?php echo $_SESSION['user_name']; ?>! 👋</h1>
            <p class="text-gray-600">Here's what's happening with your courses today.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Courses Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-sm font-medium mb-1 opacity-90">Total Courses</h3>
                        <p class="text-4xl font-bold text-white mb-2"><?php echo $totalCourses; ?></p>
                        <p class="text-blue-100 text-sm">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <span>From last month</span>
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <i class="fas fa-book-open text-3xl text-white"></i>
                    </div>
                </div>
            </div>
            
            <!-- Total Students Card -->
            <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-sm font-medium mb-1 opacity-90">Total Students</h3>
                        <p class="text-4xl font-bold text-white mb-2"><?php echo $totalStudents; ?></p>
                        <p class="text-purple-100 text-sm">
                            <i class="fas fa-arrow-up mr-1"></i>
                            <span>Active learners</span>
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <i class="fas fa-users text-3xl text-white"></i>
                    </div>
                </div>
            </div>
            
            <!-- Active Courses Card -->
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-xl p-6 transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-sm font-medium mb-1 opacity-90">Active Courses</h3>
                        <p class="text-4xl font-bold text-white mb-2"><?php echo $activeCourses; ?></p>
                        <p class="text-green-100 text-sm">
                            <i class="fas fa-check-circle mr-1"></i>
                            <span>Currently running</span>
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <i class="fas fa-chart-line text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Courses -->
        <div class="bg-white rounded-xl shadow-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Recent Courses</h2>
                    <p class="text-gray-500 text-sm mt-1">Manage your latest courses</p>
                </div>
                <a href="/teacher/mycourses" 
                   class="bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg font-medium flex items-center transition-colors duration-200">
                    <span>View All</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left border-b-2 border-gray-200">
                            <th class="pb-3 text-gray-600 font-semibold">Title</th>
                            <th class="pb-3 text-gray-600 font-semibold">Category</th>
                            <th class="pb-3 text-gray-600 font-semibold">Students</th>
                            <th class="pb-3 text-gray-600 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($recentCourses as $course): ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-book text-blue-600"></i>
                                    </div>
                                    <span class="font-medium"><?php echo $course['title']; ?></span>
                                </div>
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <?php echo $course['category_name']; ?>
                                </span>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-user-graduate text-gray-400 mr-2"></i>
                                    <?php echo $course['student_count']; ?> students
                                </div>
                            </td>
                            <td class="py-4">
                                <a href="/teacher/course/edit?id=<?php echo $course['id']; ?>" 
                                   class="inline-flex items-center px-3 py-1 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors duration-200">
                                    <i class="fas fa-edit mr-2"></i>
                                    Manage
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

