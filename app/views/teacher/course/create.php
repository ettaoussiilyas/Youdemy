<?php
    $categories = $data['categories'];
    require_once __DIR__ . '/../../layouts/headerDashboard.php';
    require_once __DIR__ . '/../../layouts/sidebareTeacher.php';
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6 mt-20">
        <h1 class="text-2xl font-bold mb-6">Créer un nouveau cours</h1>

        <form action="/teacher/course/store" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Course Details -->
            <div class="border-b pb-6">
                <h2 class="text-lg font-semibold mb-4">Informations du cours</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Titre du cours
                        </label>
                        <input type="text" name="title" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Catégorie
                        </label>
                        <select name="category_id" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <?php foreach($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Chapters Section -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold">Chapitres du cours</h2>
                <div id="chapters-container" class="space-y-6">
                    <!-- Template for a chapter -->
                    <div class="chapter-item border rounded-md p-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Titre du chapitre
                                </label>
                                <input type="text" name="chapters[0][title]" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Description
                                </label>
                                <textarea name="chapters[0][description]" rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Type de contenu
                                    </label>
                                    <select name="chapters[0][type]" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-type">
                                        <option value="video">Vidéo</option>
                                        <option value="document">Document</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Fichier
                                    </label>
                                    <input type="file" name="chapters[0][content]" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-file"
                                           accept=".mp4,.webm,.pdf,.doc,.docx">
                                    <p class="mt-1 text-xs text-gray-500">
                                        Vidéos: MP4, WEBM (max 100MB)<br>
                                        Documents: PDF, DOC, DOCX
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-chapter" 
                        class="text-blue-600 hover:text-blue-700 flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Ajouter un chapitre
                </button>
            </div>

            <div class="flex justify-end gap-4 pt-6">
                <a href="/teacher/dashboard" 
                   class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Créer le cours
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let chapterCount = 1;

document.getElementById('add-chapter').addEventListener('click', function() {
    const container = document.getElementById('chapters-container');
    const template = `
        <div class="chapter-item border rounded-md p-4">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Titre du chapitre
                    </label>
                    <input type="text" name="chapters[${chapterCount}][title]" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea name="chapters[${chapterCount}][description]" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Type de contenu
                        </label>
                        <select name="chapters[${chapterCount}][type]" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-type">
                            <option value="video">Vidéo</option>
                            <option value="document">Document</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Fichier
                        </label>
                        <input type="file" name="chapters[${chapterCount}][content]" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-file"
                               accept=".mp4,.webm,.pdf,.doc,.docx">
                        <p class="mt-1 text-xs text-gray-500">
                            Vidéos: MP4, WEBM (max 100MB)<br>
                            Documents: PDF, DOC, DOCX
                        </p>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', template);
    chapterCount++;
});

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