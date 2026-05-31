<?php

namespace YakNet\Sentinel\Security;

use Symfony\Component\HttpFoundation\Request;

class Shield
{
    /** @var array<string, string> */
    private array $patterns = [
        'sql_injection' => '/(UNION|SELECT|INSERT|UPDATE|DELETE|DROP|ALTER|--|\#|\/\*)/i',
        'xss' => '/(<script|javascript:|onerror=|onload=)/i',
        'rce' => '/(system\(|exec\(|passthru\(|shell_exec\()/i',
        'lfi' => '/(\.\.\/|\.\.\\\\)/i'
    ];

    public function protect(): void
    {
        $request = Request::createFromGlobals();
        
        $this->scanArray($request->query->all(), 'GET');
        $this->scanArray($request->request->all(), 'POST');
        $this->scanArray($request->cookies->all(), 'COOKIE');
    }

    /**
     * @param array<string, mixed> $data
     */
    private function scanArray(array $data, string $source): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->scanArray($value, $source);
                continue;
            }

            foreach ($this->patterns as $type => $pattern) {
                if (preg_match($pattern, (string)$value)) {
                    $this->logAttack($type, $key, $value, $source);
                    $this->blockRequest($type);
                }
            }
        }
    }

    /**
     * @param mixed $value
     */
    private function logAttack(string $type, string $key, $value, string $source): void
    {
        // Log to file/database
        $log = sprintf(
            "[%s] ATTACK DETECTED: %s | Key: %s | Source: %s | IP: %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($type),
            $key,
            $source,
            $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN'
        );
        file_put_contents('sentinel_security.log', $log, FILE_APPEND);
    }

    private function blockRequest(string $type): void
    {
        header('HTTP/1.1 403 Forbidden');
        echo "<h1>403 Forbidden - YakNet Sentinel Shield</h1>";
        echo "<p>Saldırı girişimi tespit edildi ($type). IP adresiniz kaydedildi.</p>";
        exit;
    }
}
