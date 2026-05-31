<?php

namespace YakNet\Sentinel\Reporting;

class Reporter
{
    /**
     * @param array<string, mixed>|null $analysis
     */
    public function logToFile(\Throwable $e, ?array $analysis): void
    {
        $data = [
            'timestamp' => date('Y-m-d H:i:s'),
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'ai_fix' => $analysis['fix'] ?? 'None',
        ];

        file_put_contents('sentinel_errors.json', json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
    }
}
