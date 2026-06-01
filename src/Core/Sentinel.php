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
    /** @var array<string, mixed> */
    private array $config;

    /**
     * @param array<string, mixed> $config
     */
    private function __construct(array $config = [])
    {
        $this->config = $config;
        
        $apiKey = $config['gemini_api_key'] ?? null;
        $this->brain = new Brain(is_string($apiKey) ? $apiKey : null);
        
        $this->shield = new Shield();
        
        $notifications = $config['notifications'] ?? [];
        $this->broadcaster = new Broadcaster(is_array($notifications) ? $notifications : []);
    }

    /**
     * @param array<string, mixed> $config
     */
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

    /**
     * @param array<string, mixed>|null $analysis
     */
    private function renderErrorPage(\Throwable $e, ?array $analysis): void
    {
        // For CLI, output text
        if (php_sapi_name() === 'cli') {
            echo "\n[Sentinel Alert] " . $e->getMessage() . "\n";
            if ($analysis) echo "AI Fix: " . $analysis['fix'] . "\n";
            return;
        }

        // Check if YakNet Divan (Poetic Error Handler) is available and active
        if (class_exists('\\YakNet\\Divan\\Core\\Divan')) {
            $envVal = getenv('POETIC_ERRORS') ?: ($_ENV['POETIC_ERRORS'] ?? null);
            if ($envVal === null && isset($_SERVER['POETIC_ERRORS'])) {
                $envVal = $_SERVER['POETIC_ERRORS'];
            }
            $divanActive = ($envVal !== null) ? filter_var($envVal, FILTER_VALIDATE_BOOLEAN) : true;

            if ($divanActive) {
                $divan = \YakNet\Divan\Core\Divan::register([
                    'gemini_api_key' => $this->config['gemini_api_key'] ?? null
                ]);
                $divan->render($e);
                exit;
            }
        }

        // For Web, output a beautiful UI
        require __DIR__ . '/../../templates/error_page.php';
        exit;
    }
}
