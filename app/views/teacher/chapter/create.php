<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Créer un nouveau chapitre</h1>
        
        <form action="/chapter/create" method="POST" class="space-y-6">
            <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Titre du chapitre
                </label>
                <input type="text" 
                       name="title" 
                       required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Description (optionnel)
                </label>
                <textarea name="description" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md"
                          rows="3"></textarea>
            </div>
            
            <div class="flex justify-end gap-4">
                <a href="/course/<?php echo $courseId; ?>" 
                   class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Annuler
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Créer le chapitre
                </button>
            </div>
        </form>
    </div>
</div> 