<?php

namespace YakNet\Sentinel\Notification;

interface NotificationInterface
{
    public function send(\Throwable $e, ?array $analysis): void;
}

class SlackAdapter implements NotificationInterface
{
    public function __construct(private array $config) {}
    public function send(\Throwable $e, ?array $analysis): void {
        // Slack Webhook implementation
    }
}

class MailAdapter implements NotificationInterface
{
    public function __construct(private array $config) {}
    public function send(\Throwable $e, ?array $analysis): void {
        // Symfony Mailer implementation
    }
}
