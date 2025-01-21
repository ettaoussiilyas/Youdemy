<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-violet-600 to-purple-800 rounded-2xl p-8 mb-8 mt-16 text-white">
        <h1 class="text-3xl font-bold mb-4">Explore Our Courses</h1>
        <p class="text-lg opacity-90">Discover a world of knowledge with our diverse range of courses.</p>
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php foreach($courses as $course): ?>
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                <!-- Course Image -->
                <div class="h-48 rounded-t-xl relative overflow-hidden">
                    <?php if($course['thumbnail']): ?>
                        <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" 
                             alt="<?php echo htmlspecialchars($course['title']); ?>"
                             class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-r from-violet-500 to-purple-800 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-4xl"></i>
                        </div>
                    <?php endif; ?>
                    <span class="absolute top-4 left-4 bg-violet-600 text-white px-3 py-1 rounded-full text-sm">
                        <?php echo htmlspecialchars($course['category_name']); ?>
                    </span>
                </div>

                <!-- Course Content -->
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">
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

                    <!-- View Course Button -->
                    <a href="/course/<?php echo $course['id']; ?>" 
                       class="block w-full text-center bg-violet-600 text-white py-2 rounded-lg hover:bg-violet-700 transition-colors">
                        View Course
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- After the Courses Grid -->
    <div class="mt-8 flex justify-center">
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            <?php if($currentPage > 1): ?>
                <a href="?page=<?php echo $currentPage - 1; ?>" 
                   class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Previous</span>
                    <i class="fas fa-chevron-left"></i>
                </a>
            <?php endif; ?>
            
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" 
                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium 
                          <?php echo $i === $currentPage ? 'text-violet-600 bg-violet-50' : 'text-gray-700 hover:bg-gray-50'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <?php if($currentPage < $totalPages): ?>
                <a href="?page=<?php echo $currentPage + 1; ?>" 
                   class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Next</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </nav>
    </div>
</div>

<!-- Add the same search script as Home page -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchCourse');
    const categoryFilter = document.getElementById('categoryFilter');
    const tagFilter = document.getElementById('tagFilter');

    function filterCourses() {
        const searchTerm = searchInput.value;
        const selectedCategory = categoryFilter.value;
        const selectedTag = tagFilter.value;
        const currentPage = new URLSearchParams(window.location.search).get('page') || 1;

        fetch(`/api/courses/filter?search=${encodeURIComponent(searchTerm)}&category=${encodeURIComponent(selectedCategory)}&tag=${encodeURIComponent(selectedTag)}&page=${currentPage}`)
            .then(response => response.json())
            .then(data => {
                updateCoursesDisplay(data.courses);
                updatePagination(data.currentPage, data.totalPages);
            })
            .catch(error => console.error('Error:', error));
    }

    searchInput.addEventListener('input', filterCourses);
    categoryFilter.addEventListener('change', filterCourses);
    tagFilter.addEventListener('change', filterCourses);
});

function updateCoursesDisplay(courses) {
    const coursesContainer = document.querySelector('.grid');
    let html = '';
    
    courses.forEach(course => {
        html += `
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="h-48 rounded-t-xl relative overflow-hidden">
                    ${course.thumbnail ? `
                        <img src="${course.thumbnail}" 
                             alt="${course.title}"
                             class="w-full h-full object-cover">
                    ` : `
                        <div class="w-full h-full bg-gradient-to-r from-violet-500 to-purple-800 flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-white text-4xl"></i>
                        </div>
                    `}
                    <span class="absolute top-4 left-4 bg-violet-600 text-white px-3 py-1 rounded-full text-sm">
                        ${course.category_name}
                    </span>
                </div>

                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">
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

                    <a href="/course/${course.id}" 
                       class="block w-full text-center bg-violet-600 text-white py-2 rounded-lg hover:bg-violet-700 transition-colors">
                        View Course
                    </a>
                </div>
            </div>
        `;
    });
    
    coursesContainer.innerHTML = html;
}

function updatePagination(currentPage, totalPages) {
    const paginationContainer = document.querySelector('nav[aria-label="Pagination"]');
    let html = '';
    
    if (currentPage > 1) {
        html += `<a href="?page=${currentPage - 1}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
            <span class="sr-only">Previous</span>
            <i class="fas fa-chevron-left"></i>
        </a>`;
    }
    
    for (let i = 1; i <= totalPages; i++) {
        html += `<a href="?page=${i}" 
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium 
            ${i === currentPage ? 'text-violet-600 bg-violet-50' : 'text-gray-700 hover:bg-gray-50'}">
            ${i}
        </a>`;
    }
    
    if (currentPage < totalPages) {
        html += `<a href="?page=${currentPage + 1}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
            <span class="sr-only">Next</span>
            <i class="fas fa-chevron-right"></i>
        </a>`;
    }
    
    paginationContainer.innerHTML = html;
}
</script>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?> 
