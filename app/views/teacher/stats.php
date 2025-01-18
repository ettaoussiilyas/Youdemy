<?php
    require_once dirname(__DIR__).'/layouts/headerDashboard.php';
?>

<div class="flex min-h-screen">
    <?php require_once dirname(__DIR__).'/layouts/sidebareTeacher.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20 bg-gray-50">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Statistiques</h1>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Courses -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Courses</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $totalCourses; ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-book text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total students</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $totalStudents; ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-violet-100 flex items-center justify-center">
                        <i class="fas fa-users text-violet-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Chapters -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Chapters</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1"><?php echo $totalChapters; ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fas fa-list text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Courses Table with Visual Elements -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Details by Courses</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étudiants</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chapitres</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php foreach($courseStats as $course): ?>
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-book text-white"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo $course['title']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex items-center rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-tag mr-1.5 text-blue-600"></i>
                                    <?php echo $course['category_name']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex -space-x-2 mr-2">
                                        <?php for($i = 0; $i < min(3, $course['student_count']); $i++): ?>
                                        <div class="h-6 w-6 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 border-2 border-white flex items-center justify-center">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="text-sm text-gray-600">
                                        <?php echo $course['student_count']; ?> Students
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mr-2">
                                        <i class="fas fa-list"></i>
                                    </div>
                                    <span class="text-sm text-gray-600"><?php echo $course['chapter_count']; ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                                    <?php echo date('d/m/Y', strtotime($course['created_at'])); ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Enhanced Category Stats with Visual Cards -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach($coursesByCategory as $category): ?>
            <div class="bg-white rounded-xl shadow-md p-6 transform hover:scale-105 transition duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center">
                        <i class="fas fa-folder text-white text-xl"></i>
                    </div>
                    <span class="text-3xl font-bold text-gray-800"><?php echo $category['count']; ?></span>
                </div>
                <h4 class="text-sm font-medium text-gray-600"><?php echo $category['name']; ?></h4>
                <div class="mt-2 h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600" 
                         style="width: <?php echo ($category['count'] / $totalCourses * 100); ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div> 