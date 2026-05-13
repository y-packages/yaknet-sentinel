<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Erişim Engellendi | Sentinel Shield</title>
    <style>
        body { background: #000; color: #ef4444; font-family: monospace; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .box { border: 2px solid #ef4444; padding: 2rem; background: rgba(239, 68, 68, 0.1); text-align: center; box-shadow: 0 0 20px rgba(239, 68, 68, 0.5); }
        h1 { margin: 0; font-size: 3rem; }
        p { color: #fff; margin-top: 1rem; }
        .ip { color: #666; font-size: 0.8rem; margin-top: 2rem; }
    </style>
</head>
<body>
    <div class="box">
        <h1>ACCESS DENIED</h1>
        <p>Girdiğiniz veri zararlı bir yazılım örüntüsü (<?php echo $type; ?>) olarak algılandı.</p>
        <div class="ip">IP: <?php echo $_SERVER['REMOTE_ADDR']; ?> | Sentinel ID: <?php echo uniqid(); ?></div>
    </div>
</body>
</html>
