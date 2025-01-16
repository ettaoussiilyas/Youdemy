<?php require_once __DIR__.'/../layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen pb-64">
    <?php require_once __DIR__.'/../layouts/sidebareAdmin.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Categories Section -->
            <div class="bg-white rounded-xl shadow-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Categories</h2>
                    <button onclick="showCategoryModal()" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Category
                    </button>
                </div>

                <!-- Categories List -->
                <div class="space-y-3">
                    <?php foreach($categories as $category): ?>
                    <div class="p-3 bg-gray-50 rounded-lg group">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-gray-700 font-medium"><?php echo $category['name']; ?></span>
                                <p class="text-gray-500 text-sm mt-1"><?php echo $category['description']; ?></p>
                            </div>
                            <button onclick="deleteCategory(<?php echo $category['id']; ?>)"
                                    class="opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-600 transition-opacity">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tags Section -->
            <div class="bg-white rounded-xl shadow-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Tags</h2>
                    <button onclick="showTagModal()" 
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Tag
                    </button>
                </div>

                <!-- Tags List -->
                <div class="flex flex-wrap gap-2">
                    <?php foreach($tags as $tag): ?>
                    <div class="group relative inline-flex items-center bg-blue-100 text-blue-800 rounded-full px-4 py-1">
                        <?php echo $tag['name']; ?>
                        <button onclick="deleteTag(<?php echo $tag['id']; ?>)"
                                class="ml-2 opacity-0 group-hover:opacity-100 text-blue-600 hover:text-red-500 transition-opacity">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-96">
        <h3 class="text-lg font-bold mb-4">Add New Category</h3>
        <form action="/admin/categories/add" method="POST">
            <input type="text" name="name" placeholder="Category Name" 
                   class="w-full px-4 py-2 border rounded-lg mb-4">
            <textarea name="description" placeholder="Category Description" 
                      class="w-full px-4 py-2 border rounded-lg mb-4 h-32 resize-none"></textarea>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="hideCategoryModal()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- Tag Modal -->
<div id="tagModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-96">
        <h3 class="text-lg font-bold mb-4">Add New Tag</h3>
        <form action="/admin/tags/add" method="POST">
            <input type="text" name="name" placeholder="Tag Name" 
                   class="w-full px-4 py-2 border rounded-lg mb-4">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="hideTagModal()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                <button type="submit" 
                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Add</button>
            </div>
        </form>
    </div>
</div>

<script>
function showCategoryModal() {
    document.getElementById('categoryModal').style.display = 'flex';
}

function hideCategoryModal() {
    document.getElementById('categoryModal').style.display = 'none';
}

function showTagModal() {
    document.getElementById('tagModal').style.display = 'flex';
}

function hideTagModal() {
    document.getElementById('tagModal').style.display = 'none';
}

function deleteCategory(categoryId) {
    if(confirm('Are you sure you want to delete this category?')) {
        window.location.href = `/admin/categories/delete/${categoryId}`;
    }
}

function deleteTag(tagId) {
    if(confirm('Are you sure you want to delete this tag?')) {
        window.location.href = `/admin/tags/delete/${tagId}`;
    }
}
</script>

