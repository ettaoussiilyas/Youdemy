<?php require_once __DIR__.'/../layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen">
    <?php require_once __DIR__.'/../layouts/sidebareStudent.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20 bg-gray-50">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Mes cours</h1>
                <p class="text-gray-600">Gérez vos cours inscrits</p>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($courses as $course): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Course Header -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="h-12 w-12 rounded-lg bg-violet-100 flex items-center justify-center">
                                <i class="fas fa-book text-violet-600 text-xl"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-violet-100 text-violet-600">
                                <?php echo $course['progress']; ?>% Complété
                            </span>
                        </div>
                        
                        <!-- Course Info -->
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo $course['title']; ?></h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo $course['description']; ?></p>
                        
                        <!-- Progress Bar -->
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden mb-4">
                            <div class="h-full bg-violet-500 rounded-full" 
                                 style="width: <?php echo $course['progress']; ?>%"></div>
                        </div>

                        <!-- Course Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-book-open mr-2"></i>
                                <?php echo $course['chapter_count']; ?> chapitres
                            </div>
                            <div class="flex items-center">
                                <i class="far fa-clock mr-2"></i>
                                <?php echo date('d/m/Y', strtotime($course['last_accessed'])); ?>
                            </div>
                        </div>

                        <!-- Continue Button -->
                        <a href="/student/course/<?php echo $course['id']; ?>" 
                           class="block w-full px-4 py-2 bg-violet-600 text-white text-center rounded-lg hover:bg-violet-700 transition">
                            <i class="fas fa-play-circle mr-2"></i>
                            Continuer
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if(empty($courses)): ?>
                <div class="col-span-full bg-white rounded-xl shadow-md p-6 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-books text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-gray-800 font-medium mb-2">Aucun cours inscrit</h3>
                    <p class="text-gray-600 mb-4">Commencez votre apprentissage en parcourant nos cours disponibles</p>
                    <a href="/student/browse" 
                       class="inline-flex items-center text-violet-600 hover:text-violet-700 transition">
                        <i class="fas fa-search mr-2"></i>
                        Parcourir les cours
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
