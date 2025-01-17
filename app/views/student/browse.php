<?php require_once dirname(__DIR__) . '/layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen">
    <?php require_once dirname(__DIR__) . '/layouts/sidebareStudent.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20 bg-gray-50">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Browse Courses</h1>
            <p class="text-gray-600">Discover and enroll in new courses</p>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4 justify-between items-center">
                <!-- Search Bar -->
                <div class="w-full md:w-1/3">
                    <div class="relative">
                        <input type="text" 
                               id="searchCourse" 
                               placeholder="Search courses..." 
                               class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-violet-500">
                        <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex gap-4 items-center">
                    <select id="categoryFilter" class="px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-violet-500">
                        <option value="">All Categories</option>
                        <?php foreach($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select id="tagFilter" class="px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-violet-500">
                        <option value="">All Tags</option>
                        <?php foreach($tags as $tag): ?>
                            <option value="<?php echo $tag['id']; ?>">
                                <?php echo htmlspecialchars($tag['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="coursesGrid">
            <?php foreach($courses as $course): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Course Header -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="h-12 w-12 rounded-lg bg-violet-100 flex items-center justify-center">
                                <i class="fas fa-book text-violet-600 text-xl"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-violet-100 text-violet-600">
                                <?php echo htmlspecialchars($course['category_name']); ?>
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            <?php echo htmlspecialchars($course['title']); ?>
                        </h3>
                        
                        <!-- Teacher Info -->
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <span class="text-gray-600 text-sm">
                                <?php echo htmlspecialchars($course['teacher_name']); ?>
                            </span>
                        </div>

                        <!-- Course Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span class="flex items-center">
                                <i class="fas fa-users mr-2"></i>
                                <?php echo $course['student_count']; ?> students
                            </span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="/student/course/details/<?php echo $course['id']; ?>" 
                               class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200 transition">
                                <i class="fas fa-info-circle mr-2"></i>
                                Details
                            </a>
                            <?php if(!$course['is_enrolled']): ?>
                                <a href="/student/course/enroll/<?php echo $course['id']; ?>" 
                                   class="flex-1 px-4 py-2 bg-violet-600 text-white text-center rounded-lg hover:bg-violet-700 transition">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Enroll
                                </a>
                            <?php else: ?>
                                <a href="/student/course/<?php echo $course['id']; ?>" 
                                   class="flex-1 px-4 py-2 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-play-circle mr-2"></i>
                                    Continue
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchCourse');
    const categoryFilter = document.getElementById('categoryFilter');
    const tagFilter = document.getElementById('tagFilter');
    const coursesGrid = document.getElementById('coursesGrid');

    function filterCourses() {
        const searchTerm = searchInput.value;
        const selectedCategory = categoryFilter.value;
        const selectedTag = tagFilter.value;

        fetch(`/api/courses/filter?search=${encodeURIComponent(searchTerm)}&category=${encodeURIComponent(selectedCategory)}&tag=${encodeURIComponent(selectedTag)}`)
            .then(response => response.json())
            .then(courses => {
                let html = '';
                courses.forEach(course => {
                    html += `
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="h-12 w-12 rounded-lg bg-violet-100 flex items-center justify-center">
                                        <i class="fas fa-book text-violet-600 text-xl"></i>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium bg-violet-100 text-violet-600">
                                        ${course.category_name}
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                    ${course.title}
                                </h3>
                                
                                <div class="flex items-center mb-4">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                    <span class="text-gray-600 text-sm">
                                        ${course.teacher_name}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <span class="flex items-center">
                                        <i class="fas fa-users mr-2"></i>
                                        ${course.student_count} students
                                    </span>
                                </div>

                                <div class="flex gap-2">
                                    <a href="/student/course/details/${course.id}" 
                                       class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-center rounded-lg hover:bg-gray-200 transition">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Details
                                    </a>
                                    ${course.is_enrolled ? `
                                        <a href="/student/course/${course.id}" 
                                           class="flex-1 px-4 py-2 bg-green-600 text-white text-center rounded-lg hover:bg-green-700 transition">
                                            <i class="fas fa-play-circle mr-2"></i>
                                            Continue
                                        </a>
                                    ` : `
                                        <a href="/student/course/enroll/${course.id}" 
                                           class="flex-1 px-4 py-2 bg-violet-600 text-white text-center rounded-lg hover:bg-violet-700 transition">
                                            <i class="fas fa-plus-circle mr-2"></i>
                                            Enroll
                                        </a>
                                    `}
                                </div>
                            </div>
                        </div>
                    `;
                });
                coursesGrid.innerHTML = html;
            })
            .catch(error => console.error('Error:', error));
    }

    searchInput.addEventListener('input', filterCourses);
    categoryFilter.addEventListener('change', filterCourses);
    tagFilter.addEventListener('change', filterCourses);
});
</script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
 