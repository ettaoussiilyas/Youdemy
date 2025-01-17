<?php require_once __DIR__.'/../layouts/headerDashboard.php'; ?>

<div class="flex min-h-screen">
    <?php 
    // Include the correct sidebar based on user role
    if(isset($_SESSION['user_role'])) {
        switch($_SESSION['user_role']) {
            case 'student':
                require_once __DIR__.'/../layouts/sidebareStudent.php';
                break;
            case 'teacher':
                require_once __DIR__.'/../layouts/sidebareTeacher.php';
                break;
            case 'admin':
                require_once __DIR__.'/../layouts/sidebareAdmin.php';
                break;
        }
    }
    ?>

    <div class="flex-1 ml-64 p-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                    <?php echo $unreadCount; ?> unread
                </span>
            </div>

            <div class="space-y-4">
                <?php if (empty($notifications)): ?>
                    <div class="text-center py-8 text-gray-500">
                        No notifications yet
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $notification): ?>
                        <div class="bg-white rounded-lg shadow p-4 <?php echo is_null($notification['read_at']) ? 'border-l-4 border-blue-500' : ''; ?>">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-800"><?php echo htmlspecialchars($notification['message']); ?></p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        <?php if ($notification['sender_name']): ?>
                                            From: <?php echo htmlspecialchars($notification['sender_name']); ?> •
                                        <?php endif; ?>
                                        <?php echo date('M j, Y g:i A', strtotime($notification['created_at'])); ?>
                                    </p>
                                </div>
                                <?php if (is_null($notification['read_at'])): ?>
                                    <button 
                                        onclick="markAsRead(<?php echo $notification['id']; ?>)"
                                        class="text-sm text-blue-600 hover:text-blue-800">
                                        Mark as read
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch('/notifications/mark-as-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `notification_id=${notificationId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>

