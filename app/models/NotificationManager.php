<?php

class NotificationManager extends Db {
    public function getUserNotifications($userId) {
        try {
            $sql = "SELECT n.*, 
                    sender.name as sender_name 
                    FROM notifications n 
                    LEFT JOIN users sender ON n.sender_id = sender.id 
                    WHERE n.recipient_id = ? 
                    ORDER BY n.created_at DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching notifications: " . $e->getMessage());
            return [];
        }
    }

    public function markAsRead($notificationId, $userId) {
        try {
            $sql = "UPDATE notifications 
                    SET read_at = CURRENT_TIMESTAMP 
                    WHERE id = ? AND recipient_id = ? AND read_at IS NULL";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$notificationId, $userId]);
        } catch (PDOException $e) {
            error_log("Error marking notification as read: " . $e->getMessage());
            return false;
        }
    }

    public function getUnreadCount($userId) {
        try {
            $sql = "SELECT COUNT(*) as count 
                    FROM notifications 
                    WHERE recipient_id = ? AND read_at IS NULL";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        } catch (PDOException $e) {
            error_log("Error counting unread notifications: " . $e->getMessage());
            return 0;
        }
    }
} 