<?php

class AccountActivationNotification extends AbstractNotification {
    protected $type = 'activation';

    protected function buildMessage(): string {
        return "Your account has been activated! You can now access all features.";
    }
} 