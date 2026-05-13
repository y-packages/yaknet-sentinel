<?php

namespace YakNet\Sentinel\Notification;

class Broadcaster
{
    private array $adapters = [];

    public function __construct(array $config)
    {
        if (isset($config['telegram'])) {
            $this->adapters[] = new TelegramAdapter($config['telegram']);
        }
        // Other adapters like Mail, Slack can be added here
    }

    public function alert(\Throwable $e, ?array $analysis): void
    {
        foreach ($this->adapters as $adapter) {
            $adapter->send($e, $analysis);
        }
    }
}

class TelegramAdapter
{
    public function __construct(private array $config) {}

    public function send(\Throwable $e, ?array $analysis): void
    {
        $token = $this->config['token'] ?? null;
        $chatId = $this->config['chat_id'] ?? null;

        if (!$token || !$chatId) return;

        $message = "🚨 *YakNet Sentinel Alert*\n\n";
        $message .= "*Error:* " . $e->getMessage() . "\n";
        $message .= "*File:* " . basename($e->getFile()) . " (" . $e->getLine() . ")\n";
        
        if ($analysis) {
            $message .= "\n💡 *AI Fix Idea:*\n" . $analysis['fix'] . "\n";
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        @file_get_contents($url . "?chat_id={$chatId}&text=" . urlencode($message) . "&parse_mode=Markdown");
    }
}
