<?php 
    require_once dirname(__DIR__).'/layouts/headerDashboard.php';

    $courses = $data['courses'];
    $error = $data['error'];
    $success = $data['success'];
?>

<div class="flex min-h-screen pb-64">
    <?php require_once dirname(__DIR__).'/layouts/sidebareTeacher.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20">
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $error; ?></span>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $success; ?></span>
            </div>
        <?php endif; ?>

        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">My Courses</h1>
                <p class="text-gray-600">Manage your cour ses and their content</p>
            </div>
            <a href="/teacher/course/create" 
               class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>
                New Course
            </a>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($courses as $course): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <?php echo $course['category_name']; ?>
                        </span>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-user-graduate mr-2"></i>
                            <?php echo $course['student_count']; ?> Students
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo $course['title']; ?></h3>
                    <p class="text-gray-600 mb-4 line-clamp-2"><?php echo $course['description']; ?></p>

                    <?php if (!empty($course['tags'])): ?>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php foreach($course['tags'] as $tag): ?>
                            <span class="px-2 py-1 bg-violet-100 text-violet-800 text-xs rounded-full">
                                <?php echo htmlspecialchars($tag['name']); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="flex items-center justify-between pt-4 border-t">
                        <div class="flex space-x-2">
                            <a href="/teacher/course/edit?id=<?php echo $course['id']; ?>" 
                               class="text-blue-600 hover:text-blue-700">
                                <i class="fas fa-edit"></i>
                            </a>
                          
                            <button onclick="deleteCourse(<?php echo $course['id']; ?>)" 
                                    class="text-red-600 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                         
                        </div>
                        <a href="/teacher/course/edit?id=<?php echo $course['id']; ?>" class="btn">
                            Manage
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
function deleteCourse(courseId) {
    if (confirm('Are you sure you want to delete this course ?')) {
        window.location.href = `/teacher/course/delete?id=${courseId}`;
    }
}
</script>
