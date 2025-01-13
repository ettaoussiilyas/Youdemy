<?php

    include_once __DIR__ . '/../../layouts/header.php';

?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">
        Ajouter du contenu au chapitre: <?php echo $chapter['title']; ?>
    </h2>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <form action="/chapter/add-content" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
        <input type="hidden" name="chapter_id" value="<?php echo $chapter['id']; ?>">

        <?php
        echo "Chapter ID: " . $chapter['id'] . "<br>";
        echo "Course ID: " . $chapter['course_id'] . "<br>";
        ?>

        <div>
            <label class="block text-sm font-medium text-gray-700">Titre</label>
            <input type="text" name="title" required 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Type</label>
            <select name="type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="video">Vidéo</option>
                <option value="document">Document</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Fichier</label>
            <input type="file" name="content" required 
                   class="mt-1 block w-full">
            <p class="mt-1 text-sm text-gray-500">
                Vidéos: MP4, WEBM (max 100MB)<br>
                Documents: PDF, DOC, DOCX
            </p>
        </div>

        <button type="submit" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Ajouter le contenu
        </button>
    </form>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const fileInput = document.querySelector('input[type="file"]');
    const fileType = document.querySelector('select[name="type"]').value;
    const file = fileInput.files[0];

    if (!file) {
        e.preventDefault();
        alert('Veuillez sélectionner un fichier');
        return;
    }

    // Vérifier la taille (100MB max)
    if (file.size > 100000000) {
        e.preventDefault();
        alert('Le fichier est trop volumineux (max 100MB)');
        return;
    }

    // Vérifier le type
    if (fileType === 'video' && !['video/mp4', 'video/webm'].includes(file.type)) {
        e.preventDefault();
        alert('Format de vidéo non supporté (MP4 ou WEBM uniquement)');
        return;
    }

    if (fileType === 'document' && !['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'].includes(file.type)) {
        e.preventDefault();
        alert('Format de document non supporté (PDF, DOC ou DOCX uniquement)');
        return;
    }
});
</script>