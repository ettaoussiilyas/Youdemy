<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Course Banner/Image Section -->
    <div class="relative h-80 rounded-xl overflow-hidden mb-6 mt-10">
        <?php if($course['thumbnail']): ?>
            <img src="<?php echo htmlspecialchars($course['thumbnail']); ?>" 
                 alt="<?php echo htmlspecialchars($course['title']); ?>"
                 class="w-full h-full object-cover">
        <?php else: ?>
            <!-- Fallback gradient background if no image -->
            <div class="w-full h-full bg-gradient-to-r from-violet-500 to-purple-800 flex items-center justify-center">
                <i class="fas fa-graduation-cap text-white text-6xl"></i>
            </div>
        <?php endif; ?>
        
        <!-- Overlay with course category -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent">
            <div class="absolute bottom-6 left-6">
                <span class="bg-violet-600 text-white px-4 py-2 rounded-full text-sm font-medium">
                    <?php echo htmlspecialchars($course['category_name']); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Course Header -->
    <div class="bg-white rounded-xl shadow-md p-8 mb-6">
        <div class="flex justify-between items-start">
            <div class="max-w-3xl">
                <h1 class="text-4xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($course['title']); ?></h1>
                <p class="text-gray-600 mb-8 text-lg leading-relaxed"><?php echo htmlspecialchars($course['description']); ?></p>
                
                <!-- Course Stats in Cards -->
                <div class="grid grid-cols-3 gap-6 mb-8">
                    <!-- Students Enrolled -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-users text-violet-500 mr-2"></i>
                            <span class="text-gray-600">Students</span>
                        </div>
                        <p class="text-2xl font-bold text-gray-800"><?php echo $course['student_count']; ?></p>
                    </div>
                    
                    <!-- Chapters Count -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-book-open text-violet-500 mr-2"></i>
                            <span class="text-gray-600">Chapters</span>
                        </div>
                        <p class="text-2xl font-bold text-gray-800"><?php echo count($chapters); ?></p>
                    </div>
                    
                    <!-- Category -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-folder text-violet-500 mr-2"></i>
                            <span class="text-gray-600">Category</span>
                        </div>
                        <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($course['category_name']); ?></p>
                    </div>
                </div>

                <!-- Teacher Info -->
                <div class="flex items-center p-6 bg-gray-50 rounded-xl">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mr-6">
                        <?php if($course['teacher_image']): ?>
                            <img src="<?php echo htmlspecialchars($course['teacher_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($course['teacher_name']); ?>"
                                 class="w-full h-full object-cover rounded-full">
                        <?php else: ?>
                            <i class="fas fa-user text-gray-500 text-2xl"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm text-violet-600 mb-1">Instructor</p>
                        <p class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($course['teacher_name']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Enroll/Login Button Card -->
            <div class="bg-gray-50 p-6 rounded-xl w-80">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if(!$isEnrolled): ?>
                        <a href="/student/course/enroll/<?php echo $course['id']; ?>" 
                           class="block w-full py-4 px-6 bg-violet-600 text-white text-center font-medium rounded-xl hover:bg-violet-700 transition-colors mb-4">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Enroll Now
                        </a>
                    <?php else: ?>
                        <a href="/student/course/<?php echo $course['id']; ?>" 
                           class="block w-full py-4 px-6 bg-green-600 text-white text-center font-medium rounded-xl hover:bg-green-700 transition-colors mb-4">
                            <i class="fas fa-play-circle mr-2"></i>
                            Continue Learning
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/login" 
                       class="block w-full py-4 px-6 bg-violet-600 text-white text-center font-medium rounded-xl hover:bg-violet-700 transition-colors mb-4">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login to Enroll
                    </a>
                <?php endif; ?>
                
                <!-- Course Features -->
                <div class="space-y-4 mt-6">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-infinity text-violet-500 w-6"></i>
                        <span>Full lifetime access</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-mobile-alt text-violet-500 w-6"></i>
                        <span>Access on mobile and TV</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-certificate text-violet-500 w-6"></i>
                        <span>Certificate of completion</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chapters Preview -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Course Content</h2>
        
        <div class="space-y-4">
            <?php foreach($chapters as $index => $chapter): ?>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center mr-4">
                            <span class="text-violet-600 font-medium"><?php echo $index + 1; ?></span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800"><?php echo htmlspecialchars($chapter['title']); ?></h3>
                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($chapter['description']); ?></p>
                        </div>
                    </div>
                    <?php if($isEnrolled): ?>
                        <a href="/student/course/<?php echo $course['id']; ?>" class="text-violet-600 hover:text-violet-700">
                            <i class="fas fa-play-circle"></i>
                        </a>
                    <?php else: ?>
                        <i class="fas fa-lock text-gray-400"></i>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?> 