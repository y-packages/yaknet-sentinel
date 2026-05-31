<?php

namespace YakNet\Sentinel\AI;

use Gemini;

class Brain
{
    private ?\Gemini\Client $client = null;

    public function __construct(private readonly ?string $apiKey)
    {
        if ($this->apiKey) {
            $this->client = Gemini::client($this->apiKey);
        }
    }

    /**
     * @return array{explanation: string, fix: string, severity: string}|null
     */
    public function analyzeException(\Throwable $e): ?array
    {
        if (!$this->client) return null;

        $prompt = <<<PROMPT
You are a Senior PHP Expert and YakNet Sentinel AI.
An exception occurred in a PHP application:
Error: {$e->getMessage()}
File: {$e->getFile()} (Line {$e->getLine()})
Stack Trace: {$e->getTraceAsString()}

Please provide:
1. **Explanation**: A simple explanation of why this happened in Turkish.
2. **Fix**: The exact code fix or suggestion to resolve it.
3. **Severity**: Low, Medium, High, or Critical.

Return your response in a structured format.
PROMPT;

        try {
            // 2026'nın en stabil ve güçlü Flash modeli
            $result = $this->client->generativeModel('gemini-2.5-flash')->generateContent($prompt);
            $text = $result->text();
            
            return [
                'explanation' => $this->extractSection($text, 'Explanation'),
                'fix' => $this->extractSection($text, 'Fix'),
                'severity' => $this->extractSection($text, 'Severity')
            ];
        } catch (\Throwable $t) {
            return [
                'explanation' => 'Yapay zeka bağlantısı kurulamadı.',
                'fix' => 'Hata: ' . $t->getMessage(),
                'severity' => 'Unknown'
            ];
        }
    }

    private function extractSection(string $text, string $section): string
    {
        // Try with and without asterisks, and case-insensitive
        $pattern = "/(?:\\*\\*|#|)\\s*{$section}\\s*(?::|)\\s*(.*?)(?=(?:\\*\\*|#|)\\s*(?:Explanation|Fix|Severity)|$)/si";
        if (preg_match($pattern, $text, $matches)) {
            return trim($matches[1]);
        }
        
        // Fallback: If it's a small text, just return the whole thing for the explanation
        if ($section === 'Explanation' && strlen($text) < 1000) {
            return $text;
        }

        return 'Analiz edilemedi.';
    }
}
