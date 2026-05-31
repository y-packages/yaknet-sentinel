<?php

namespace YakNet\Sentinel\Notification;

interface NotificationInterface
{
    /**
     * @param array<string, mixed>|null $analysis
     */
    public function send(\Throwable $e, ?array $analysis): void;
}

class SlackAdapter implements NotificationInterface
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(private array $config) {}

    /**
     * @param array<string, mixed>|null $analysis
     */
    public function send(\Throwable $e, ?array $analysis): void {
        // Slack Webhook implementation
        if (empty($this->config)) {
            return;
        }
    }
}

class MailAdapter implements NotificationInterface
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(private array $config) {}

    /**
     * @param array<string, mixed>|null $analysis
     */
    public function send(\Throwable $e, ?array $analysis): void {
        // Symfony Mailer implementation
        if (empty($this->config)) {
            return;
        }
    }
}
