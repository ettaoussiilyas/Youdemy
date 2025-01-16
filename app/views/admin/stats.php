<?php require_once __DIR__.'/../layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen pb-64">
    <?php require_once __DIR__.'/../layouts/sidebareAdmin.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Platform Statistics</h1>
            <p class="text-gray-600">Detailed overview of your platform's performance</p>
        </div>

        <!-- Main Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-sm font-medium mb-1 opacity-90">Total Users</h3>
                        <p class="text-4xl font-bold text-white mb-2"><?php echo $totalUsers; ?></p>
                        <p class="text-blue-100 text-sm">
                            <span class="<?php echo $userGrowth >= 0 ? 'text-green-300' : 'text-red-300'; ?>">
                                <i class="fas fa-<?php echo $userGrowth >= 0 ? 'arrow-up' : 'arrow-down'; ?> mr-1"></i>
                                <?php echo abs($userGrowth); ?>%
                            </span>
                            vs last month
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <i class="fas fa-users text-3xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Active Courses -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-sm font-medium mb-1 opacity-90">Active Courses</h3>
                        <p class="text-4xl font-bold text-white mb-2"><?php echo $activeCourses; ?></p>
                        <p class="text-green-100 text-sm">
                            <span class="<?php echo $courseGrowth >= 0 ? 'text-green-300' : 'text-red-300'; ?>">
                                <i class="fas fa-<?php echo $courseGrowth >= 0 ? 'arrow-up' : 'arrow-down'; ?> mr-1"></i>
                                <?php echo abs($courseGrowth); ?>%
                            </span>
                            vs last month
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <i class="fas fa-book-open text-3xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Total Categories -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-sm font-medium mb-1 opacity-90">Categories</h3>
                        <p class="text-4xl font-bold text-white mb-2"><?php echo $totalCategories; ?></p>
                        <p class="text-purple-100 text-sm">
                            <i class="fas fa-layer-group mr-1"></i>
                            Active categories
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <i class="fas fa-folder text-3xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Total Tags -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-sm font-medium mb-1 opacity-90">Tags</h3>
                        <p class="text-4xl font-bold text-white mb-2"><?php echo $totalTags; ?></p>
                        <p class="text-yellow-100 text-sm">
                            <i class="fas fa-tags mr-1"></i>
                            Active tags
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <i class="fas fa-tag text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Most Popular Categories -->
            <div class="bg-white rounded-xl shadow-xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Popular Categories</h3>
                <div class="space-y-4">
                    <?php foreach($popularCategories as $category): ?>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700"><?php echo $category['name']; ?></span>
                        <div class="flex items-center">
                            <span class="text-gray-500 mr-2"><?php echo $category['course_count']; ?> courses</span>
                            <div class="w-24 h-2 bg-gray-200 rounded-full">
                                <div class="h-full bg-blue-500 rounded-full" 
                                     style="width: <?php echo $category['percentage']; ?>%"></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Most Used Tags -->
            <div class="bg-white rounded-xl shadow-xl p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Popular Tags</h3>
                <div class="flex flex-wrap gap-2">
                    <?php foreach($popularTags as $tag): ?>
                    <div class="px-3 py-1 bg-gray-100 rounded-full text-sm">
                        <span class="text-gray-700"><?php echo $tag['name']; ?></span>
                        <span class="text-gray-500 ml-1">(<?php echo $tag['count']; ?>)</span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
