<?php require_once dirname(__DIR__).'/layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen">
    <?php require_once dirname(__DIR__).'/layouts/sidebareStudent.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20 bg-gray-50">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Parcourir les cours</h1>
                <p class="text-gray-600">Découvrez nos cours disponibles</p>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($courses as $course): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Course Header -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="h-12 w-12 rounded-lg bg-violet-100 flex items-center justify-center">
                                <i class="fas fa-book text-violet-600 text-xl"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-600">
                                Available
                            </span>
                        </div>
                        
                        <!-- Course Info -->
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo $course['title']; ?></h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo $course['description']; ?></p>
                        
                        <!-- Teacher Info -->
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Teacher</p>
                                <p class="text-sm font-medium text-gray-800"><?php echo $course['teacher_name']; ?></p>
                            </div>
                        </div>

                        <!-- Course Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-book-open mr-2"></i>
                                <?php echo $course['chapter_count']; ?> chapters
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users mr-2"></i>
                                <?php echo $course['student_count']; ?> students
                            </div>
                        </div>

                        <!-- View Details & Enroll Buttons -->
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
                                    Continuer
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if(empty($courses)): ?>
                <div class="col-span-full bg-white rounded-xl shadow-md p-6 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-books text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-medium mb-2">No courses available</h3>
                    <p class="text-gray-600">Come back later to discover new courses</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
 