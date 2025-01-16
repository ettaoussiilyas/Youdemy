<?php require_once __DIR__.'/../layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen pb-64">
    <?php require_once __DIR__.'/../layouts/sidebareAdmin.php'; ?>

    <!-- Main Content -->
    <div class="ml-64 flex-1 p-8 pt-20">
        <!-- Users Management -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Users Management</h1>
            <p class="text-gray-600">Manage all platform users here.</p>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-xl shadow-xl p-6">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap"><?php echo $user['name']; ?></p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap"><?php echo $user['email']; ?></p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <p class="text-gray-900 whitespace-no-wrap"><?php echo ucfirst($user['role']); ?></p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <?php 
                                $currentStatus = isset($user['status']) ? $user['status'] : 'review';
                                
                                // Définir le prochain statut et la couleur
                                switch($currentStatus) {
                                    case 'review':
                                        $newStatus = 'active';
                                        $buttonColor = 'yellow';
                                        break;
                                    case 'blocked':
                                        $newStatus = 'active';
                                        $buttonColor = 'red';
                                        break;
                                    case 'active':
                                        $newStatus = 'blocked';
                                        $buttonColor = 'green';
                                        break;
                                    default:
                                        $newStatus = 'review';
                                        $buttonColor = 'gray';
                                }
                            ?>
                            <button onclick="changeStatus(<?php echo $user['id']; ?>, '<?php echo $newStatus; ?>')"
                                    class="px-3 py-1 bg-<?php echo $buttonColor; ?>-100 text-<?php echo $buttonColor; ?>-600 rounded-lg hover:bg-<?php echo $buttonColor; ?>-200 transition-colors">
                                Status: <?php echo ucfirst($currentStatus); ?>
                            </button>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <button class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                                Edit
                            </button>
                            <button onclick="deleteUser(<?php echo $user['id']; ?>)"
                                    class="px-3 py-1 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
                                Delete
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function changeStatus(userId, newStatus) {
    if (confirm('Are you sure you want to change the status?')) {
        console.log(`Changing status for user ${userId} to ${newStatus}`);
        window.location.href = `/admin/users/updateStatus/${userId}/${newStatus}`;
    }
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        console.log(`Deleting user ${userId}`);
        window.location.href = `/admin/users/delete/${userId}`;
    }
}
</script>
