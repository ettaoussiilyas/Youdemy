<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Course Header -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6 mt-10">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-4"><?php echo htmlspecialchars($course['title']); ?></h1>
                <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($course['description']); ?></p>
                
                <!-- Teacher Info -->
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center mr-4">
                        <?php if($course['teacher_image']): ?>
                            <img src="<?php echo htmlspecialchars($course['teacher_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($course['teacher_name']); ?>"
                                 class="w-full h-full object-cover rounded-lg">
                        <?php else: ?>
                            <i class="fas fa-user text-gray-500 text-xl"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Instructor</p>
                        <p class="text-lg font-medium text-gray-800"><?php echo htmlspecialchars($course['teacher_name']); ?></p>
                    </div>
                </div>

                <!-- Course Stats -->
                <div class="flex gap-6">
                    <div class="flex items-center">
                        <i class="fas fa-book-open text-violet-500 mr-2"></i>
                        <span class="text-gray-600"><?php echo count($chapters); ?> chapters</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-users text-violet-500 mr-2"></i>
                        <span class="text-gray-600"><?php echo $course['student_count']; ?> students enrolled</span>
                    </div>
                </div>
            </div>

            <!-- Enroll/Login Button -->
            <div class="ml-6">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if(!$isEnrolled): ?>
                        <a href="/student/course/enroll/<?php echo $course['id']; ?>" 
                           class="inline-block px-6 py-3 bg-violet-600 text-white font-medium rounded-xl hover:bg-violet-700 transition-colors">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Enroll Now
                        </a>
                    <?php else: ?>
                        <a href="/student/course/<?php echo $course['id']; ?>" 
                           class="inline-block px-6 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                            <i class="fas fa-play-circle mr-2"></i>
                            Continue Learning
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/login" 
                       class="inline-block px-6 py-3 bg-violet-600 text-white font-medium rounded-xl hover:bg-violet-700 transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login to Enroll
                    </a>
                <?php endif; ?>
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