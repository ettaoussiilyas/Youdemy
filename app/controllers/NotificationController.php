<?php

class NotificationController extends BaseController {
    private $notificationManager;

    public function __construct() {
        parent::__construct();
        $this->notificationManager = new NotificationManager();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $notifications = $this->notificationManager->getUserNotifications($_SESSION['user_id']);
        $unreadCount = $this->notificationManager->getUnreadCount($_SESSION['user_id']);

        $this->render('notifications/index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function markAsRead() {
        if (!isset($_SESSION['user_id']) || !isset($_POST['notification_id'])) {
            header('HTTP/1.1 400 Bad Request');
            exit;
        }

        $success = $this->notificationManager->markAsRead(
            $_POST['notification_id'], 
            $_SESSION['user_id']
        );

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }
} 