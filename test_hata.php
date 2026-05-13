<?php

require_once 'vendor/autoload.php';

use YakNet\Sentinel\Core\Sentinel;

// Sentinel'i yapılandır ve başlat
Sentinel::register([
    'gemini_api_key' => getenv('GEMINI_API_KEY') ?: $_ENV['GEMINI_API_KEY'] ?? '',
    'enable_shield' => true,
]);

echo "<h1>Sentinel Test Sayfası</h1>";
echo "<p>Şimdi bir hata tetikleniyor...</p>";

// Bilerek bir hata yapalım: Tanımlanmamış bir fonksiyonu çağıralım
buFonksiyonYok();
