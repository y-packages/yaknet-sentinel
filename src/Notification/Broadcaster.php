<?php

namespace YakNet\Sentinel\Notification;

class Broadcaster
{
    /** @var array<int, NotificationInterface|TelegramAdapter> */
    private array $adapters = [];

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config)
    {
        $telegram = $config['telegram'] ?? null;
        if (is_array($telegram)) {
            $this->adapters[] = new TelegramAdapter($telegram);
        }
        // Other adapters like Mail, Slack can be added here
    }

    /**
     * @param array<string, mixed>|null $analysis
     */
    public function alert(\Throwable $e, ?array $analysis): void
    {
        foreach ($this->adapters as $adapter) {
            $adapter->send($e, $analysis);
        }
    }
}

class TelegramAdapter
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(private array $config) {}

    /**
     * @param array<string, mixed>|null $analysis
     */
    public function send(\Throwable $e, ?array $analysis): void
    {
        $token = $this->config['token'] ?? null;
        $chatId = $this->config['chat_id'] ?? null;

        if (!is_string($token) || !is_string($chatId)) return;

        $message = "🚨 *YakNet Sentinel Alert*\n\n";
        $message .= "*Error:* " . $e->getMessage() . "\n";
        $message .= "*File:* " . basename($e->getFile()) . " (" . $e->getLine() . ")\n";
        
        if ($analysis) {
            $message .= "\n💡 *AI Fix Idea:*\n" . $analysis['fix'] . "\n";
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        @file_get_contents($url . "?chat_id=" . urlencode($chatId) . "&text=" . urlencode($message) . "&parse_mode=Markdown");
    }
}
