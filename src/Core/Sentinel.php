<?php

namespace YakNet\Sentinel\Core;

use YakNet\Sentinel\AI\Brain;
use YakNet\Sentinel\Security\Shield;
use YakNet\Sentinel\Notification\Broadcaster;

class Sentinel
{
    private static ?Sentinel $instance = null;
    private Brain $brain;
    private Shield $shield;
    private Broadcaster $broadcaster;
    private array $config;

    private function __construct(array $config = [])
    {
        $this->config = $config;
        $this->brain = new Brain($config['gemini_api_key'] ?? null);
        $this->shield = new Shield();
        $this->broadcaster = new Broadcaster($config['notifications'] ?? []);
    }

    public static function register(array $config = []): self
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
            self::$instance->boot();
        }
        return self::$instance;
    }

    private function boot(): void
    {
        // 1. Start Security Shield (WAF)
        if ($this->config['enable_shield'] ?? true) {
            $this->shield->protect();
        }

        // 2. Register Error Handlers
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleException(\Throwable $e): void
    {
        // Analyze with AI
        $analysis = $this->brain->analyzeException($e);
        
        // Broadcast notification
        $this->broadcaster->alert($e, $analysis);

        // Display professional error page (Sentinel Pulse)
        $this->renderErrorPage($e, $analysis);
    }

    public function handleError(int $level, string $message, string $file, int $line): bool
    {
        if (!(error_reporting() & $level)) return false;
        
        $e = new \ErrorException($message, 0, $level, $file, $line);
        $this->handleException($e);
        return true;
    }

    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $e = new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
            $this->handleException($e);
        }
    }

    private function renderErrorPage(\Throwable $e, ?array $analysis): void
    {
        // For CLI, output text
        if (php_sapi_name() === 'cli') {
            echo "\n[Sentinel Alert] " . $e->getMessage() . "\n";
            if ($analysis) echo "AI Fix: " . $analysis['fix'] . "\n";
            return;
        }

        // For Web, output a beautiful UI
        require __DIR__ . '/../../templates/error_page.php';
        exit;
    }
}
