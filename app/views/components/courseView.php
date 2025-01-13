<?php
    include_once __DIR__ . '/../layouts/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <!-- Course Info -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-4"><?php echo $course['title']; ?></h1>
        <p class="text-gray-600 mb-4"><?php echo $course['description']; ?></p>
        
        <!-- Course Stats -->
        <div class="flex items-center gap-4 text-sm text-gray-500">
            <span><i class="fas fa-book-open mr-2"></i><?php echo count($chapters); ?> chapitres</span>
            <span><i class="fas fa-users mr-2"></i><?php echo $course['student_count']; ?> étudiants</span>
            <span><i class="fas fa-clock mr-2"></i>26 min au total</span>
        </div>
    </div>

    <!-- Chapters List -->
    <div class="bg-white rounded-lg shadow">
        <!-- Headers -->
        <div class="grid grid-cols-12 gap-4 p-4 bg-gray-50 rounded-t-lg border-b font-medium">
            <div class="col-span-7">Chapitre</div>
            <div class="col-span-3">Durée</div>
            <div class="col-span-2">Statut</div>
        </div>

        <!-- Chapters -->
        <?php foreach ($chapters as $index => $chapter): ?>
            <div class="grid grid-cols-12 gap-4 p-4 border-b hover:bg-gray-50 transition-colors items-center">
                <!-- Chapter Info -->
                <div class="col-span-7">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                            <?php echo $index + 1; ?>
                        </div>
                        <div>
                            <h3 class="font-medium"><?php echo $chapter['title']; ?></h3>
                            <?php if ($chapter['video']): ?>
                                <p class="text-sm text-gray-500"><?php echo $chapter['video']['title']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Duration -->
                <div class="col-span-3 text-gray-500">
                    <i class="fas fa-play-circle mr-2"></i>
                    03:53
                </div>

                <!-- Status/Action -->
                <div class="col-span-2">
                    <?php if ($isEnrolled): ?>
                        <?php if ($chapter['video']): ?>
                            <a href="/course/<?php echo $course['id']; ?>/chapter/<?php echo $chapter['id']; ?>" 
                               class="text-blue-600 hover:text-blue-700">
                                <i class="fas fa-play mr-2"></i>Regarder
                            </a>
                        <?php else: ?>
                            <span class="text-gray-400">
                                <i class="fas fa-lock-open mr-2"></i>Bientôt
                            </span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="text-gray-400">
                            <i class="fas fa-lock mr-2"></i>Verrouillé
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (!$isEnrolled): ?>
        <!-- CTA for non-enrolled users -->
        <div class="mt-8 text-center">
            <a href="/course/enroll/<?php echo $course['id']; ?>" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg inline-block hover:bg-blue-700 transition-colors">
                S'inscrire au cours
            </a>
        </div>
    <?php endif; ?>
</div>