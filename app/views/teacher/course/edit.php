<?php
    require_once __DIR__ . '/../../layouts/headerDashboard.php';
    require_once __DIR__ . '/../../layouts/sidebareTeacher.php';
?>

<!-- Get course ID from URL -->
<?php $courseId = isset($_GET['id']) ? $_GET['id'] : null; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6 mt-20">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Modifier le cours</h1>
            <a href="/teacher/mycourses" class="text-gray-600 hover:text-gray-800 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour aux cours
            </a>
        </div>

        <form action="/teacher/course/update" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Add hidden input for course ID -->
            <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">

            <!-- Course Details -->
            <div class="border-b pb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations du cours</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Titre du cours
                        </label>
                        <input type="text" name="title" required 
                               value="<?php echo htmlspecialchars($course['title']); ?>"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" rows="3" required
                                  class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition"><?php echo htmlspecialchars($course['description']); ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catégorie
                        </label>
                        <select name="category_id" required 
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition">
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                        <?php echo ($category['id'] == $course['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo $category['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Existing Chapters -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-800">Chapitres existants</h2>
                <div id="existing-chapters" class="space-y-6">
                    <?php foreach($chapters as $index => $chapter): ?>
                    <div class="chapter-item bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Titre du chapitre
                                </label>
                                <input type="text" name="existing_chapters[<?php echo $chapter['id']; ?>][title]" 
                                       value="<?php echo htmlspecialchars($chapter['title']); ?>" required
                                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition">
                                <input type="hidden" name="existing_chapters[<?php echo $chapter['id']; ?>][id]" 
                                       value="<?php echo $chapter['id']; ?>">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="existing_chapters[<?php echo $chapter['id']; ?>][description]" rows="2"
                                          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition"><?php echo htmlspecialchars($chapter['description']); ?></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Type de contenu actuel
                                    </label>
                                    <p class="text-gray-600 bg-white px-4 py-2 rounded-lg border border-gray-200">
                                        <?php echo $chapter['content_type'] ?? 'Aucun contenu'; ?>
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Nouveau fichier (optionnel)
                                    </label>
                                    <input type="file" name="existing_chapters[<?php echo $chapter['id']; ?>][content]"
                                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 transition chapter-file"
                                           accept=".mp4,.webm,.pdf,.doc,.docx">
                                </div>
                            </div>

                            <button type="button" onclick="deleteChapter(<?php echo $chapter['id']; ?>)"
                                    class="flex items-center text-red-600 hover:text-red-700 transition">
                                <i class="fas fa-trash mr-2"></i>
                                Supprimer ce chapitre
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- New Chapters -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-800">Nouveaux chapitres</h2>
                <div id="new-chapters-container" class="space-y-6">
                    <!-- New chapters will be added here -->
                </div>
                
                <button type="button" id="add-chapter" 
                        class="flex items-center text-violet-600 hover:text-violet-700 transition">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Ajouter un chapitre
                </button>
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="/teacher/courses" 
                   class="px-6 py-2 text-gray-600 hover:text-gray-800 transition">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-violet-600 to-violet-500 text-white rounded-lg hover:from-violet-700 hover:to-violet-600 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 transform hover:scale-105 transition">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let newChapterCount = 0;

document.getElementById('add-chapter').addEventListener('click', function() {
    const container = document.getElementById('new-chapters-container');
    const template = `
        <div class="chapter-item border rounded-md p-4">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Titre du chapitre
                    </label>
                    <input type="text" name="new_chapters[${newChapterCount}][title]" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea name="new_chapters[${newChapterCount}][description]" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Type de contenu
                        </label>
                        <select name="new_chapters[${newChapterCount}][type]" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-type">
                            <option value="video">Vidéo</option>
                            <option value="document">Document</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Fichier
                        </label>
                        <input type="file" name="new_chapters[${newChapterCount}][content]" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-file"
                               accept=".mp4,.webm,.pdf,.doc,.docx">
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    newChapterCount++;
});

function deleteChapter(chapterId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce chapitre ?')) {
        // Send AJAX request to delete chapter
        fetch(`/teacher/chapter/delete/${chapterId}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove chapter from DOM
                const chapterElement = document.querySelector(`[name="existing_chapters[${chapterId}][id]"]`).closest('.chapter-item');
                chapterElement.remove();
            } else {
                alert('Erreur lors de la suppression du chapitre');
            }
        });
    }
}

// Update accept attribute based on type selection
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('chapter-type')) {
        const fileInput = e.target.closest('.chapter-item').querySelector('.chapter-file');
        if (e.target.value === 'video') {
            fileInput.accept = '.mp4,.webm';
        } else {
            fileInput.accept = '.pdf,.doc,.docx';
        }
    }
});
</script>