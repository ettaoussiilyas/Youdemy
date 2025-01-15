<?php require_once __DIR__.'/../../layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen">
    <?php require_once __DIR__.'/../../layouts/sidebareStudent.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20 bg-gray-50">
        <!-- Course Header -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2"><?php echo $course['title']; ?></h1>
            <p class="text-gray-600 mb-4"><?php echo $course['description']; ?></p>
            
            <!-- Progress Bar -->
            <div class="w-full bg-gray-100 rounded-full h-4 mb-2">
                <div class="bg-violet-600 h-4 rounded-full transition-all duration-300" 
                     style="width: <?php echo $progress; ?>%">
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4"><?php echo $progress; ?>% complété</p>
        </div>

        <!-- Course Content -->
        <div class="bg-white rounded-xl shadow-md">
            <!-- Chapters List -->
            <div class="divide-y divide-gray-100">
                <?php foreach($course['chapters'] as $index => $chapter): ?>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-violet-100 flex items-center justify-center mr-4">
                                    <span class="text-violet-600 font-medium"><?php echo $index + 1; ?></span>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800"><?php echo $chapter['title']; ?></h3>
                                    <p class="text-sm text-gray-600"><?php echo $chapter['description']; ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Chapter Content -->
                        <?php if(isset($chapter['content'])): ?>
                            <div class="ml-14">
                                <?php if($chapter['content']['type'] === 'video'): ?>
                                    <div class="max-w-3xl mx-auto">
                                        <div class="relative rounded-lg overflow-hidden bg-gray-100" style="padding-top: 56.25%">
                                            <video class="absolute top-0 left-0 w-full h-full" controls>
                                                <source src="/<?php echo $chapter['content']['file_path']; ?>" type="video/mp4">
                                                Votre navigateur ne supporte pas la lecture de vidéos.
                                            </video>
                                        </div>
                                    </div>
                                <?php elseif($chapter['content']['type'] === 'document'): ?>
                                    <a href="/<?php echo $chapter['content']['file_path']; ?>" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                        <i class="fas fa-file-pdf mr-2"></i>
                                        <?php echo $chapter['content']['original_name']; ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div> 