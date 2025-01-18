<?php require_once __DIR__.'/../../layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen">
    <?php require_once __DIR__.'/../../layouts/sidebareStudent.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20 bg-gray-50">
        <!-- Course Header -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2"><?php echo $course['title']; ?></h1>
            <p class="text-gray-600 mb-4"><?php echo $course['description']; ?></p>
        </div>

        <!-- Course Content -->
        <div class="grid grid-cols-12 gap-6">
            <!-- Chapters List -->
            <div class="col-span-4 bg-white rounded-xl shadow-md p-6">
                <h2 class="text-lg font-semibold mb-4">Chapters</h2>
                <div class="space-y-2">
                    <?php foreach($course['chapters'] as $index => $chapter): ?>
                        <button onclick="showChapterContent(<?php echo $chapter['id']; ?>)"
                                class="w-full text-left p-3 rounded-lg hover:bg-violet-50 transition-colors
                                       <?php echo isset($activeChapter) && $activeChapter == $chapter['id'] ? 'bg-violet-100' : ''; ?>">
                            <div class="flex items-center">
                                <div class="min-w-[2rem] w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center mr-3">
                                    <span class="text-violet-600 font-medium"><?php echo $index + 1; ?></span>
                                </div>
                                <div class="overflow-hidden">
                                    <h3 class="font-medium text-gray-800 truncate"><?php echo $chapter['title']; ?></h3>
                                    <p class="text-sm text-gray-600 truncate"><?php echo $chapter['description']; ?></p>
                                </div>
                            </div>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Content Display -->
            <div class="col-span-8 bg-white rounded-xl shadow-md p-6">
                <div id="content-display">
                    <?php foreach($course['chapters'] as $chapter): ?>
                        <div id="chapter-content-<?php echo $chapter['id']; ?>" class="chapter-content hidden">
                            <h2 class="text-xl font-bold mb-4 break-words"><?php echo $chapter['title']; ?></h2>
                            <p class="text-gray-600 mb-6 break-words"><?php echo $chapter['description']; ?></p>

                            <?php if(isset($chapter['content'])): ?>
                                <div class="overflow-hidden rounded-lg">
                                    <?php if($chapter['content']['type'] === 'video'): ?>
                                        <div class="aspect-w-16 aspect-h-9 bg-gray-900 rounded-lg">
                                            <video controls class="w-full h-full object-contain">
                                                <source src="/<?php echo $chapter['content']['file_path']; ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <div class="flex items-center justify-between flex-wrap gap-4">
                                                <div class="flex items-center min-w-0">
                                                    <i class="fas fa-file-pdf text-red-500 text-2xl mr-3 flex-shrink-0"></i>
                                                    <div class="overflow-hidden">
                                                        <p class="font-medium text-gray-800 truncate">
                                                            <?php echo $chapter['content']['original_name']; ?>
                                                        </p>
                                                        <p class="text-sm text-gray-500">PDF Document</p>
                                                    </div>
                                                </div>
                                                <a href="/<?php echo $chapter['content']['file_path']; ?>" 
                                                   target="_blank"
                                                   class="px-4 py-2 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors flex-shrink-0">
                                                    <i class="fas fa-download mr-2"></i>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-gray-500 py-8">
                                    <i class="fas fa-info-circle text-2xl mb-2"></i>
                                    <p>No content available for this chapter.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showChapterContent(chapterId) {
    // Cacher tous les contenus
    document.querySelectorAll('.chapter-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Afficher le contenu sélectionné
    const selectedContent = document.getElementById(`chapter-content-${chapterId}`);
    if (selectedContent) {
        selectedContent.classList.remove('hidden');
    }
}

// Afficher le premier chapitre par défaut
document.addEventListener('DOMContentLoaded', function() {
    const firstChapter = document.querySelector('.chapter-content');
    if (firstChapter) {
        firstChapter.classList.remove('hidden');
    }
});
</script>

