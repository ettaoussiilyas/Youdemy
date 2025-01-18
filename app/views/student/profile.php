<?php require_once dirname(__DIR__).'/layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen">
    <?php require_once dirname(__DIR__).'/layouts/sidebareStudent.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20 bg-gray-50">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">My Profile</h1>
            <p class="text-gray-600">Manage your personal information</p>
        </div>

        <!-- Profile Info Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center space-x-4">
                    <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">
                            <?php echo strtoupper(substr($profile['name'], 0, 1)); ?>
                        </span>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800"><?php echo $profile['name']; ?></h2>
                        <p class="text-gray-600"><?php echo $profile['email']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Learning Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Enrolled Courses -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-book text-blue-600"></i>
                            </div>
                            <span class="text-2xl font-bold text-gray-800"><?php echo $stats['total_courses']; ?></span>
                        </div>
                        <p class="text-sm text-gray-600">Enrolled Courses</p>
                    </div>

                    <!-- Average Progress -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="h-10 w-10 rounded-lg bg-green-100 flex items-center justify-center">
                                <i class="fas fa-chart-line text-green-600"></i>
                            </div>
                            <span class="text-2xl font-bold text-gray-800"><?php echo round($stats['avg_progress']); ?>%</span>
                        </div>
                        <p class="text-sm text-gray-600">Average Progress</p>
                    </div>

                    <!-- Last Activity -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="h-10 w-10 rounded-lg bg-violet-100 flex items-center justify-center">
                                <i class="fas fa-clock text-violet-600"></i>
                            </div>
                            <span class="text-2xl font-bold text-gray-800">
                                <?php echo date('d/m/Y', strtotime($stats['last_activity'])); ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">Last Activity</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

