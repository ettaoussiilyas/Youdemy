<?php
    include_once __DIR__ . '/../layouts/header.php';
?>

<div class="container mx-auto px-4 py-8 mt-10">
    <!-- En-tête du cours -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h1 class="text-3xl font-bold mb-4"><?php echo $course['title']; ?></h1>
        
        <!-- Tags du cours -->
        <div class="flex flex-wrap gap-2 mb-4">
            <?php foreach(explode(',', $course['tags']) as $tag): ?>
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                    <?php echo $tag; ?>
                </span>
            <?php endforeach; ?>
        </div>

        <!-- Informations du cours -->
        <div class="mb-6">
            <p class="text-gray-600 mb-4"><?php echo $course['description']; ?></p>
            <p class="text-sm text-gray-500">
                Instructeur: <?php echo $course['teacher_name']; ?>
            </p>
        </div>

        <!-- Bouton d'inscription -->
        <?php if (!$isEnrolled): ?>
            <form action="/enrollment/enroll" method="POST">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    S'inscrire au cours
                </button>
            </form>
        <?php else: ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded">
                Vous êtes inscrit à ce cours
            </div>
        <?php endif; ?>
    </div>

    <!-- Liste des chapitres -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Contenu du cours</h2>
        
        <?php if ($isEnrolled): ?>
            <div class="space-y-4">
                <?php foreach($chapters as $chapter): ?>
                    <div class="border-b pb-4">
                        <h3 class="text-lg font-semibold">
                            <?php echo $chapter['title']; ?>
                        </h3>
                        <p class="text-gray-600">
                            <?php echo $chapter['description']; ?>
                        </p>
                        <a href="/chapter/view/<?php echo $chapter['id']; ?>" 
                           class="text-blue-500 hover:underline">
                            Voir le chapitre
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600">
                Inscrivez-vous au cours pour accéder au contenu
            </p>
        <?php endif; ?>
    </div>
</div>