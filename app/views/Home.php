<?php
    require_once __DIR__.'/layouts/header.php';
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-violet-500 to-gray-900 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Bienvenue sur Youdemy</h1>
            <p class="text-xl mb-8">Découvrez des milliers de cours en ligne pour développer vos compétences</p>
            <a href="/courses" class="bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors">
                Explorer les cours
            </a>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="py-8 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-4 justify-between items-center mb-8">
            <!-- Search Bar -->
            <div class="w-full md:w-1/3">
                <div class="relative">
                    <input type="text" 
                           id="searchCourse" 
                           placeholder="Rechercher un cours..." 
                           class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex gap-4 items-center">
                <!-- Category Filter -->
                <select id="categoryFilter" class="px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les catégories</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Tags Filter -->
                <select id="tagFilter" class="px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tous les tags</option>
                    <?php foreach($tags as $tag): ?>
                        <option value="<?php echo $tag['id']; ?>">
                            <?php echo htmlspecialchars($tag['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- Popular Courses Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Cours populaires</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach($courses as $course): ?>
                <!-- Course Card -->
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col">
                    <!-- Course Image -->
                    <div class="h-48 bg-gray-200 rounded-t-lg relative overflow-hidden">
                        <?php if($course['thumbnail']): ?>
                            <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" 
                                 alt="<?php echo htmlspecialchars($course['title']); ?>"
                                 class="w-full h-full object-cover">
                        <?php endif; ?>
                        <span class="absolute top-4 left-4 bg-blue-600 text-white text-sm px-4 py-1 rounded-full">
                            <?php echo htmlspecialchars($course['category_name']); ?>
                        </span>
                    </div>

                    <!-- Card Content -->
                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">
                            <?php echo htmlspecialchars($course['title']); ?>
                        </h3>

                        <!-- Teacher Info -->
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 mr-3">
                                <?php echo strtoupper(substr($course['teacher_name'], 0, 1)); ?>
                            </div>
                            <span class="text-gray-600">
                                <?php echo htmlspecialchars($course['teacher_name']); ?>
                            </span>
                        </div>

                        <!-- Description -->
                        <p class="text-gray-600 mb-4 flex-1">
                            <?php echo substr(htmlspecialchars($course['description']), 0, 100) . '...'; ?>
                        </p>

                        <!-- Tags -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php if(isset($course['tags']) && is_array($course['tags'])): ?>
                                <?php foreach($course['tags'] as $tag): ?>
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-md text-sm">
                                        <?php echo htmlspecialchars($tag['name']); ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-between pt-4 border-t mt-auto">
                            <div class="text-gray-600 text-sm">
                                <span><?php echo $course['student_count']; ?> Students</span>
                            </div>
                            <a href="/course/<?php echo $course['id']; ?>" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                Voir le cours
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Popular Categories</h2>
        <div class="flex justify-center space-x-8">
            <a href="/courses?category=development" class="text-gray-700 hover:text-blue-600 transition-colors">
                Development
            </a>
            <a href="/courses?category=business" class="text-gray-700 hover:text-blue-600 transition-colors">
                Business
            </a>
            <a href="/courses?category=design" class="text-gray-700 hover:text-blue-600 transition-colors">
                Design
            </a>
            <a href="/courses?category=marketing" class="text-gray-700 hover:text-blue-600 transition-colors">
                Marketing
            </a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchCourse');
    const categoryFilter = document.getElementById('categoryFilter');
    const tagFilter = document.getElementById('tagFilter');

    function filterCourses() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const selectedTag = tagFilter.value;

        fetch(`/api/courses/filter?search=${searchTerm}&category=${selectedCategory}&tag=${selectedTag}`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour l'affichage des cours
                updateCoursesDisplay(data);
            })
            .catch(error => console.error('Error:', error));
    }

    searchInput.addEventListener('input', filterCourses);
    categoryFilter.addEventListener('change', filterCourses);
    tagFilter.addEventListener('change', filterCourses);
});

function updateCoursesDisplay(courses) {
    // Logique pour mettre à jour l'affichage des cours
    const coursesContainer = document.querySelector('.grid');
    // ... code pour mettre à jour l'affichage ...
}
</script>
<?php
    require_once __DIR__.'/layouts/footer.php';
?>