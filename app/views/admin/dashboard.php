<?php require_once __DIR__.'/../layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen pb-64">
    <?php require_once __DIR__.'/../layouts/sidebareAdmin.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20">
        <!-- Welcome Message -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Welcome Back, <?php echo $_SESSION['user_name']; ?>! 👋</h1>
            <p class="text-gray-600">Here's an overview of your platform statistics.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-sm font-medium mb-1 opacity-90">Total Users</h3>
                        <!-- Debug: Afficher le calcul -->
                        <?php $totalUsers = $totalStudents + $totalTeachers; ?>
                        <p class="text-4xl font-bold text-white mb-2"><?php echo $totalUsers; ?></p>
                        <p class="text-red-100 text-sm">
                            <i class="fas fa-users mr-1"></i>
                            <span>All platform users</span>
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <i class="fas fa-users-cog text-3xl text-white"></i>
                    </div>
                </div>
            </div>
            
            <!-- Teachers Card -->
            <div class="bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-sm font-medium mb-1 opacity-90">Teachers</h3>
                        <p class="text-4xl font-bold text-white mb-2"><?php echo $totalTeachers; ?></p>
                        <p class="text-purple-100 text-sm">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            <span>Active teachers</span>
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <i class="fas fa-graduation-cap text-3xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Students Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-sm font-medium mb-1 opacity-90">Students</h3>
                        <p class="text-4xl font-bold text-white mb-2"><?php echo $totalStudents; ?></p>
                        <p class="text-blue-100 text-sm">
                            <i class="fas fa-user-graduate mr-1"></i>
                            <span>Enrolled students</span>
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <i class="fas fa-users text-3xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Courses Card -->
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-white text-sm font-medium mb-1 opacity-90">Total Courses</h3>
                        <p class="text-4xl font-bold text-white mb-2"><?php echo $totalCourses; ?></p>
                        <p class="text-green-100 text-sm">
                            <i class="fas fa-book mr-1"></i>
                            <span>Available courses</span>
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                        <i class="fas fa-book-open text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities & Last Courses -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Pending Teacher Approvals -->
            <div class="bg-white rounded-xl shadow-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Pending Approvals</h2>
                        <p class="text-gray-500 text-sm mt-1">Teachers waiting for approval</p>
                    </div>
                    <a href="/admin/users/teachers/pending" 
                       class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg font-medium flex items-center transition-colors duration-200">
                        <span>View All</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="space-y-4">
                    <?php if(empty($pendingTeachers)): ?>
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-check-circle text-2xl mb-2"></i>
                            <p>No pending approvals</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($pendingTeachers as $teacher): ?>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center mr-3">
                                    <span class="text-red-600 font-bold">
                                        <?php echo strtoupper(substr($teacher['name'], 0, 1)); ?>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800"><?php echo $teacher['name']; ?></h3>
                                    <p class="text-sm text-gray-500"><?php echo $teacher['email']; ?></p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="approveTeacher(<?php echo $teacher['id']; ?>)"
                                        class="px-3 py-1 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-colors">
                                    <i class="fas fa-check mr-1"></i> Approve
                                </button>
                                <button onclick="deleteUser(<?php echo $user['id']; ?>)"
                                        class="px-3 py-1 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
                                    <i class="fas fa-times mr-1"></i> Reject
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Last Courses -->
            <div class="bg-white rounded-xl shadow-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Last Courses</h2>
                        <p class="text-gray-500 text-sm mt-1">Recently added courses</p>
                    </div>
                    <a href="/admin/courses" 
                       class="bg-violet-50 text-violet-600 hover:bg-violet-100 px-4 py-2 rounded-lg font-medium flex items-center transition-colors duration-200">
                        <span>View All</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="space-y-4">
                    <?php if(empty($lastCourses)): ?>
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-book text-2xl mb-2"></i>
                            <p>No courses available</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($lastCourses as $course): ?>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-violet-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-book-open text-violet-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800"><?php echo $course['title']; ?></h3>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500"><?php echo date('d M Y', strtotime($course['created_at'])); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function approveTeacher(teacherId) {
        window.location.href = '/admin/users';
   
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        console.log(`Deleting user ${userId}`);
        window.location.href = `/admin/users/delete/${userId}`;
    }
}
</script>

