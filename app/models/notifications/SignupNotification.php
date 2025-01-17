<?php

class SignupNotification extends AbstractNotification {
    protected $type = 'signup';
    private $userName;

    public function __construct($recipientId, $userName) {
        parent::__construct($recipientId);
        $this->userName = $userName;
    }

    protected function buildMessage(): string {
        return "Welcome to Youdemy, {$this->userName}! Your account is pending activation.";
    }
} 