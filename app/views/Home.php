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

<!-- Popular Courses Section -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Cours populaires</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach($courses as $course): ?>
                <!-- Course Card -->
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col">
                    <!-- Course Image -->
                    <div class="h-48 bg-gray-200 rounded-t-lg relative">
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
                            <?php echo htmlspecialchars($course['description']); ?>
                        </p>

                        <!-- Tags -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php 
                                $tags = ['Backend', 'PHP'];
                                foreach($tags as $tag): ?>
                                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-md text-sm">
                                        <?php echo htmlspecialchars($tag); ?>
                                    </span>
                            <?php endforeach; ?>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-between pt-4 border-t mt-auto">
                            <div class="text-gray-600 text-sm">
                                <span><?php echo $course['student_count']; ?> étudiants</span>
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
        <h2 class="text-3xl font-bold text-center mb-12">Catégories populaires</h2>
        <div class="flex justify-center space-x-8">
            <a href="/courses?category=development" class="text-gray-700 hover:text-blue-600 transition-colors">
                Développement
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
<?php
    require_once __DIR__.'/layouts/footer.php';
?>