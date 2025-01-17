<?php
    require_once __DIR__ . '/../../layouts/headerDashboard.php';
    require_once __DIR__ . '/../../layouts/sidebareTeacher.php';
?>

<!-- Get course ID from URL -->
<?php $courseId = isset($_GET['id']) ? $_GET['id'] : null; ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php if (isset($error)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $error; ?></span>
    </div>
<?php endif; ?>

<?php if (isset($success)): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline"><?php echo $success; ?></span>
    </div>
<?php endif; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-6 mt-20">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Edit course</h1>
            <a href="/teacher/mycourses" class="text-gray-600 hover:text-gray-800 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to courses
            </a>
        </div>

        <form action="/teacher/course/update" method="POST" enctype="multipart/form-data" class="space-y-6">
            <!-- Add hidden input for course ID -->
            <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">

            <!-- Course Details -->
            <div class="border-b pb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Course informations</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Course title
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
                            Category
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
                     <!-- Tag Selection Section -->
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tags</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <?php 
                            $courseTags = isset($course) ? $tagModel->getCourseTags($course['id']) : [];
                            $courseTagIds = array_column($courseTags, 'id');
                            $allTags = $tagModel->getAllTags();

                            foreach($allTags as $tag): 
                                $isSelected = in_array($tag['id'], $courseTagIds);
                            ?>
                                <label class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 cursor-pointer hover:bg-gray-200">
                                    <input type="checkbox" 
                                           name="tags[]" 
                                           value="<?php echo $tag['id']; ?>" 
                                           <?php echo $isSelected ? 'checked' : ''; ?>
                                           class="form-checkbox h-4 w-4 text-blue-600 mr-2">
                                    <span class="text-sm text-gray-700"><?php echo $tag['name']; ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Thumbnail URL (CDN)
                        </label>
                        <input type="url" 
                               name="thumbnail" 
                               value="<?php echo htmlspecialchars($course['thumbnail']); ?>"
                               placeholder="https://placehold.co/600x400?text=Course"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <p class="mt-1 text-sm text-gray-500">
                            Enter a valid image URL for your course thumbnail
                        </p>
                    </div>
                    
                </div>
            </div>

            <!-- Existing Chapters -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-800">Chapitres existants</h2>
                <div id="existing-chapters" class="space-y-6">
                    <?php foreach($chapters as $index => $chapter): ?>
                    <div id="chapter-<?php echo $chapter['id']; ?>" class="chapter-item border rounded-md p-4 mb-4">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Titre du chapitre
                                </label>
                                <input type="text" 
                                       name="chapters[<?php echo $chapter['id']; ?>][title]" 
                                       value="<?php echo htmlspecialchars($chapter['title']); ?>"
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Description
                                </label>
                                <textarea name="chapters[<?php echo $chapter['id']; ?>][description]" 
                                          rows="2"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md"><?php echo htmlspecialchars($chapter['description']); ?></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Type de contenu actuel
                                    </label>
                                    <div class="text-gray-600">
                                        <?php if (isset($chapter['content']) && $chapter['content']): ?>
                                            <?php if ($chapter['content']['type'] === 'video'): ?>
                                                <span class="flex items-center">
                                                    <i class="fas fa-video mr-2"></i>
                                                    Vidéo: <?php echo htmlspecialchars($chapter['content']['original_name']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="flex items-center">
                                                    <i class="fas fa-file-pdf mr-2"></i>
                                                    Document: <?php echo htmlspecialchars($chapter['content']['original_name']); ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-gray-500">Aucun contenu</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nouveau fichier (optionnel)
                                    </label>
                                    <input type="file" 
                                           name="chapters[<?php echo $chapter['id']; ?>][content]"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-file"
                                           accept=".mp4,.webm,.pdf,.doc,.docx">
                                    <p class="mt-1 text-xs text-gray-500">
                                        Vidéos: MP4, WEBM (max 100MB)<br>
                                        Documents: PDF, DOC, DOCX
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" 
                                        class="delete-chapter text-red-600 hover:text-red-800 flex items-center"
                                        data-chapter-id="<?php echo $chapter['id']; ?>"
                                        data-course-id="<?php echo $course['id']; ?>">
                                    <i class="fas fa-trash mr-2"></i> 
                                    Delete this chapter
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- New Chapters -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-800">New chapters</h2>
                <div id="new-chapters-container" class="space-y-6">
                    <!-- New chapters will be added here -->
                </div>
                
                <button type="button" id="add-chapter" 
                        class="flex items-center text-violet-600 hover:text-violet-700 transition">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Add a chapter
                </button>
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t">
                <a href="/teacher/mycourses" 
                   class="px-6 py-2 text-gray-600 hover:text-gray-800 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-gradient-to-r from-violet-600 to-violet-500 text-white rounded-lg hover:from-violet-700 hover:to-violet-600 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 transform hover:scale-105 transition">
                    <i class="fas fa-save mr-2"></i>
                    Save changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let chapterCount = <?php echo count($chapters); ?>; // Initialiser avec le nombre de chapitres existants

document.getElementById('add-chapter').addEventListener('click', function() {
    const container = document.getElementById('new-chapters-container');
    const chapterCount = document.querySelectorAll('.chapter-item').length;
    
    const template = `
        <div class="chapter-item border rounded-md p-4 mb-4">
            <input type="hidden" name="new_chapters[${chapterCount}][is_new]" value="1">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Titre du chapitre
                    </label>
                    <input type="text" 
                           name="new_chapters[${chapterCount}][title]" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea name="new_chapters[${chapterCount}][description]" 
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Type de contenu
                        </label>
                        <select name="new_chapters[${chapterCount}][type]" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-type">
                            <option value="video">Vidéo</option>
                            <option value="document">Document</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Fichier
                        </label>
                        <input type="file" 
                               name="new_chapters[${chapterCount}][content]"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md chapter-file"
                               accept=".mp4,.webm,.pdf,.doc,.docx">
                        <p class="mt-1 text-xs text-gray-500">
                            Vidéos: MP4, WEBM (max 100MB)<br>
                            Documents: PDF, DOC, DOCX
                        </p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" 
                            class="text-red-600 hover:text-red-800"
                            onclick="this.closest('.chapter-item').remove()">
                        <i class="fas fa-trash"></i> Supprimer ce chapitre
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', template);
});

function deleteChapter(chapterId, courseId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce chapitre ?')) {
        return;
    }

    console.log('Deleting chapter:', chapterId); // Debug log

    fetch('/teacher/chapter/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `chapter_id=${chapterId}&course_id=${courseId}`
    })
    .then(response => {
        console.log('Response status:', response.status); // Debug log
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data); // Debug log
        if (data.success) {
            const chapterElement = document.querySelector(`#chapter-${chapterId}`);
            if (chapterElement) {
                chapterElement.remove();
                showMessage('success', 'Chapitre supprimé avec succès');
            }
        } else {
            showMessage('error', data.message || 'Erreur lors de la suppression');
        }
    })
    .catch(error => {
        console.error('Error:', error); // Debug log
        showMessage('error', 'Une erreur est survenue');
    });
}

function showMessage(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `${type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'} px-4 py-3 rounded relative mb-4`;
    alertDiv.innerHTML = `<span class="block sm:inline">${message}</span>`;
    
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => alertDiv.remove(), 3000);
}

// Event listeners
document.querySelectorAll('.delete-chapter').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const chapterId = this.dataset.chapterId;
        const courseId = this.dataset.courseId;
        console.log('Button clicked:', chapterId, courseId); // Debug log
        deleteChapter(chapterId, courseId);
    });
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
</script>