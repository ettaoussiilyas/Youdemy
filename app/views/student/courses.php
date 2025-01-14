<?php
    require_once __DIR__.'/../../views/layouts/header.php';
?>
<div class="container mx-auto px-4 py-8 pt-20">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    
        <?php foreach($courses as $course): ?>
            <!-- Single Course Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                <!-- Course Image -->
                <div class="relative">
                    <img src="<?php echo $course['thumbnail']; ?>" alt="Course thumbnail" class="w-full h-48 object-cover">
                    <!-- Category Badge -->
                    <span class="absolute top-4 left-4 bg-blue-500 text-white text-sm px-3 py-1 rounded-full">
                        <?php echo $course['category_name']; ?>
                    </span>
                </div>

                <!-- Card Content -->
                <div class="p-6">
                    <!-- Title -->
                    <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo $course['title']; ?></h3>

                    <!-- Teacher Info -->
                    <div class="flex items-center mb-4">
                        <img src="<?php echo $course['teacher_image']; ?>" alt="Teacher" class="w-8 h-8 rounded-full mr-2">
                        <span class="text-sm text-gray-600"><?php echo $course['teacher_name']; ?></span>
                    </div>

                    <!-- Description -->
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                        <?php echo $course['description']; ?>
                    </p>

                    <!-- Tags -->
                    <div class="flex flex-wrap gap-2 mb-4">
                    <?php 
                        $tags = !empty($course['tags']) ? explode(',', $course['tags']) : [];
                        foreach($tags as $tag): 
                            if(trim($tag) !== ''): ?>
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded"><?php echo trim($tag); ?></span>
                            <?php endif; 
                        endforeach; 
                    ?>
                    </div>  

                    <!-- Enrollment Info & Action Button -->
                    <div class="flex items-center justify-between mt-4">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-users mr-2"></i><?php echo $course['student_count']; ?> students
                        </span>
                        <a href="/course/<?php echo $course['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-600 transition-colors">
                            View Course
                        </a>
                    </div>
                </div>
            </div>

            <!-- Repeat for each course -->
    <?php endforeach; ?>

    </div>
</div>
<?php require_once __DIR__.'/../layouts/footer.php'; ?>