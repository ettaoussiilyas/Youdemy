<?php
    $categories = $data['categories'];
    $tags = $data['tags'];
    require_once __DIR__ . '/../../layouts/headerDashboard.php';
    require_once __DIR__ . '/../../layouts/sidebareTeacher.php';
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6 mt-20">
        <h1 class="text-2xl font-bold mb-6">Create a new course</h1>

        <!-- Ajoutez ce bloc pour afficher les erreurs -->
        <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Several errors occurred:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <?php foreach ($_SESSION['errors'] as $field => $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Ajoutez ce bloc pour afficher le message de succès -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            <?php echo htmlspecialchars($_SESSION['success']); ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="/teacher/course/store" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Course Details -->
            <div class="border-b pb-6">
                <h2 class="text-lg font-semibold mb-4">Course informations</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Course title
                        </label>
                        <input type="text" name="title" c 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Thumbnail URL (CDN)
                        </label>
                        <input type="url" 
                               name="thumbnail" 
                               placeholder="https://example.com/image.jpg"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <p class="mt-1 text-sm text-gray-500">
                            Enter a valid image URL for your course thumbnail
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" rows="3" c
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Category
                        </label>
                        <select name="category_id" c 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <?php foreach($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="tags-select">
                            Tags
                        </label>
                        <select id="tags-select" name="tags[]" multiple="multiple" class="tags-select w-full">
                            <?php foreach($tags as $tag): ?>
                                <option value="<?php echo htmlspecialchars($tag['id']); ?>">
                                    <?php echo htmlspecialchars($tag['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Chapters Section -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold">Course chapters</h2>
                <div id="chapters-container" class="space-y-6">
                    <!-- Template for a chapter -->
                    <div class="chapter-item border rounded-md p-4">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-md font-medium">Chapter</h3>
                            <button type="button" class="delete-chapter text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Chapter title
                                </label>
                                <input type="text" name="chapters[0][title]" c
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
                                        Content type
                                    </label>
                                    <select name="chapters[0][type]" c
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-type">
                                        <option value="video">Video</option>
                                        <option value="document">Document</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        File
                                    </label>
                                    <input type="file" name="chapters[0][content]" c
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-file"
                                           accept=".mp4,.webm,.pdf,.doc,.docx">
                                    <p class="mt-1 text-xs text-gray-500">
                                        Videos: MP4, WEBM (max 100MB)<br>
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
                    Add a chapter
                </button>
            </div>

            <div class="flex justify-end gap-4 pt-6">
                <a href="/teacher/dashboard" 
                   class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create
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
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-md font-medium">Chapter</h3>
                <button type="button" class="delete-chapter text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Titre du chapitre
                    </label>
                    <input type="text" name="chapters[${chapterCount}][title]" c
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
                        <select name="chapters[${chapterCount}][type]" c
                                class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-type">
                            <option value="video">Vidéo</option>
                            <option value="document">Document</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Fichier
                        </label>
                        <input type="file" name="chapters[${chapterCount}][content]" c
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

// Initialisation de Select2 pour les tags
$(document).ready(function() {
    $('.tags-select').select2({
        placeholder: 'Sélectionnez les tags',
        allowClear: true,
        width: '100%',
        theme: 'classic',
        language: {
            noResults: function() {
                return "Aucun tag trouvé";
            }
        }
    });

    // Style personnalisé pour correspondre à votre design
    $('.select2-container--classic .select2-selection--multiple').css({
        'border-color': '#E5E7EB',
        'border-radius': '0.5rem',
        'min-height': '42px'
    });

    $('.select2-container--classic .select2-selection--multiple .select2-selection__choice').css({
        'background-color': '#8B5CF6',
        'color': 'white',
        'border': 'none',
        'border-radius': '0.375rem',
        'padding': '2px 8px'
    });

    $('.select2-container--classic .select2-selection--multiple .select2-selection__choice__remove').css({
        'color': 'white',
        'margin-right': '5px'
    });
});

// Add delete functionality for chapters
document.addEventListener('click', function(e) {
    if (e.target.closest('.delete-chapter')) {
        if (document.querySelectorAll('.chapter-item').length > 1) {
            if (confirm('Are you sure you want to delete this chapter?')) {
                e.target.closest('.chapter-item').remove();
            }
        } else {
            alert('You cannot delete the last chapter. At least one chapter is c.');
        }
    }
});
</script> 