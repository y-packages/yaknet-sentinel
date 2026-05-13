# YakNet Sentinel 🛡️ (AI-Powered Guardian Framework)

[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue.svg)](https://php.net)
[![Stability](https://img.shields.io/badge/stability-enterprise-red.svg)](https://yak.net.tr)
[![AI Driven](https://img.shields.io/badge/AI-Self--Healing-purple.svg)](https://gemini.google.com)

**YakNet Sentinel**, PHP uygulamalarınızı sadece korumakla kalmayan, aynı zamanda onları "akıllı" hale getiren kapsamlı bir güvenlik ve hata yönetimi çerçevesidir (Guardian Framework).

## 🌋 Neden Sentinel?

Geleneksel hata yakalayıcılar sadece "ne olduğunu" söyler. **Sentinel** ise "neden olduğunu" açıklar ve "nasıl düzelteceğinizi" gösterir. Üstelik, projenizin önünde bir kalkan (Shield) gibi durarak saldırıları daha başlamadan engeller.

## 🚀 Devasa Özellikler

- **🧠 Sentinel Brain (AI):** PHP hatalarını yapay zeka ile analiz eder, Türkçe açıklamalar yapar ve otomatik kod yamama (patching) önerileri sunar.
- **🛡️ Sentinel Shield (WAF):** SQL Injection, XSS, RCE ve LFI saldırılarını gerçek zamanlı olarak tespit eder ve engeller.
- **🚨 Sentinel Broadcast:** Hataları anında Telegram, Slack veya E-posta üzerinden zengin raporlarla bildirir.
- **📊 Sentinel Pulse:** Modern, fütüristik ve bilgilendirici bir hata sayfası sunar.
- **⚙️ High-Performance Engine:** PSR-4 uyumlu, düşük gecikmeli (low-latency) çekirdek yapı.

## 📦 Kurulum

```bash
composer require yaknet/sentinel
```

## 🛠️ Hızlı Başlangıç

Projenizin giriş noktasında (genellikle `bootstrap.php` veya `index.php`) Sentinel'i kaydedin:

```php
use YakNet\Sentinel\Core\Sentinel;

Sentinel::register([
    'gemini_api_key' => $_ENV['GEMINI_API_KEY'],
    'enable_shield'  => true,
    'notifications'  => [
        'telegram' => [
            'token'   => 'your_bot_token',
            'chat_id' => 'your_chat_id'
        ]
    ]
]);
```

## 💻 CLI Kullanımı

Güvenlik taraması yapmak için:
```bash
bin/sentinel shield:scan
```

## 📜 Lisans

Bu proje **YakNet Bilişim** tarafından geliştirilmiştir ve MIT Lisansı ile korunmaktadır.
