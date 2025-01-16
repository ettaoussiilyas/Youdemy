<?php require_once __DIR__.'/../layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen pb-64">
    <?php require_once __DIR__.'/../layouts/sidebareAdmin.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20">
        <!-- Content Management Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Content Management</h1>
            <p class="text-gray-600">Manage all courses content</p>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if(empty($courses)): ?>
                <div class="col-span-3 text-center py-8 text-gray-500">
                    <i class="fas fa-book-open text-4xl mb-3"></i>
                    <p>No courses available</p>
                </div>
            <?php else: ?>
                <?php foreach($courses as $course): ?>
                    <!-- Course Card -->
                    <div class="bg-white rounded-xl shadow-xl p-6 relative group">
                        <!-- Delete Button -->
                        <button onclick="deleteCourse(<?php echo $course['id']; ?>)"
                                class="absolute top-4 right-4 p-2 bg-red-100 text-red-600 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-200 hover:bg-red-200">
                            <i class="fas fa-trash"></i>
                        </button>

                        <!-- Course Info -->
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo $course['title']; ?></h3>
                            <p class="text-gray-600 text-sm">
                                <?php echo substr($course['description'], 0, 100) . '...'; ?>
                            </p>
                        </div>

                        <!-- Course Stats -->
                        <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100">
                            <!-- Teacher -->
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-chalkboard-teacher text-violet-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Teacher</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        <?php echo isset($course['teacher_name']) ? $course['teacher_name'] : 'N/A'; ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Students -->
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Students</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        <?php echo isset($course['enrolled_students']) ? $course['enrolled_students'] : '0'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function deleteCourse(courseId) {
    if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
        window.location.href = `/admin/content/delete/${courseId}`;
    }
}
</script>
