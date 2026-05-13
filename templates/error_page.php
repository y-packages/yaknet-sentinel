<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YakNet Sentinel | Korumalı Hata Sayfası</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #09090b; --card: #18181b; --accent: #ef4444; --ai-blue: #3b82f6;
            --text: #f8fafc; --text-dim: #a1a1aa;
        }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; margin: 0; padding: 2rem; display: flex; flex-direction: column; align-items: center; }
        .container { max-width: 1000px; width: 100%; }
        header { text-align: center; margin-bottom: 3rem; }
        h1 { font-family: 'Cinzel', serif; color: var(--accent); font-size: 2.5rem; margin: 0; }
        .badge { background: rgba(239, 68, 68, 0.1); color: var(--accent); padding: 4px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; border: 1px solid var(--accent); }
        .error-card { background: var(--card); border: 1px solid rgba(255,255,255,0.05); border-radius: 12px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .ai-card { background: linear-gradient(135deg, #1e3a8a, #1e1b4b); border: 1px solid var(--ai-blue); border-radius: 12px; padding: 2rem; margin-bottom: 2rem; position: relative; overflow: hidden; }
        .ai-card::before { content: '🧠'; position: absolute; right: -20px; top: -20px; font-size: 8rem; opacity: 0.1; }
        pre { background: #000; padding: 1.5rem; border-radius: 8px; overflow-x: auto; color: #10b981; font-size: 0.9rem; border: 1px solid #18181b; }
        h2 { font-family: 'Cinzel', serif; font-size: 1.2rem; margin-top: 0; display: flex; align-items: center; gap: 10px; }
        .footer { text-align: center; color: var(--text-dim); font-size: 0.85rem; margin-top: 2rem; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="badge">Sentinel Active Protection</div>
            <h1>YakNet Sentinel</h1>
            <p style="color:var(--text-dim);">Uygulamanız güvenli bir şekilde durduruldu.</p>
        </header>

        <div class="error-card">
            <h2>🚨 Hata Detayı</h2>
            <div style="font-size: 1.2rem; margin-bottom: 1rem; color: var(--accent); font-weight: 600;">
                <?php echo htmlspecialchars($e->getMessage()); ?>
            </div>
            <div style="color:var(--text-dim); margin-bottom: 1rem;">
                Dosya: <?php echo htmlspecialchars($e->getFile()); ?> (Satır: <?php echo $e->getLine(); ?>)
            </div>
        </div>

        <?php if ($analysis): ?>
        <div class="ai-card">
            <h2>✨ AI Tanısı (Brain v2.0)</h2>
            <p><strong>Nedir?</strong> <?php echo htmlspecialchars($analysis['explanation']); ?></p>
            <div style="background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                <h3 style="margin-top:0; font-size: 0.9rem; color: var(--ai-blue);">Önerilen Çözüm:</h3>
                <code style="color: #60a5fa;"><?php echo nl2br(htmlspecialchars($analysis['fix'])); ?></code>
            </div>
        </div>
        <?php endif; ?>

        <div class="error-card">
            <h2>📜 Stack Trace</h2>
            <pre><?php echo htmlspecialchars($e->getTraceAsString()); ?></pre>
        </div>

        <div class="footer">
            YakNet Sentinel &copy; 2026 | "Hataları sadece yakalamıyoruz, onları iyileştiriyoruz."
        </div>
    </div>
</body>
</html>
