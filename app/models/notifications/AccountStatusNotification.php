<?php

class AccountStatusNotification extends AbstractNotification {
    protected $type = 'account_status';
    private $status;
    private $userName;

    public function __construct($recipientId, $status, $userName) {
        parent::__construct($recipientId);
        $this->status = $status;
        $this->userName = $userName;
    }

    protected function buildMessage(): string {
        switch($this->status) {
            case 'active':
                return "Hello {$this->userName}, your account has been activated! You can now access all features.";
            case 'blocked':
                return "Your account has been temporarily blocked. Please contact the administration for more information.";
            default:
                return "Your account status has been updated to '{$this->status}'.";
        }
    }
} 