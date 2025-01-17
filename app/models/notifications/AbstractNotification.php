<?php

abstract class AbstractNotification extends Db {
    protected $recipientId;
    protected $senderId;
    protected $message;
    protected $type;

    public function __construct($recipientId, $senderId = null) {
        parent::__construct(); // Call parent constructor to initialize database connection
        $this->recipientId = $recipientId;
        $this->senderId = $senderId;
    }

    abstract protected function buildMessage(): string;

    public function send(): bool {
        try {
            $sql = "INSERT INTO notifications (type, recipient_id, sender_id, message) 
                    VALUES (:type, :recipient_id, :sender_id, :message)";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':type' => $this->type,
                ':recipient_id' => $this->recipientId,
                ':sender_id' => $this->senderId,
                ':message' => $this->buildMessage()
            ]);
        } catch (PDOException $e) {
            error_log("Error creating notification: " . $e->getMessage());
            return false;
        }
    }
} 