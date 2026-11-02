<?php
// Svuota la cache opcache di PHP
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache cleared!";
} else {
    echo "OPcache not enabled";
}

// Mostra anche l'ambiente rilevato
$isDocker = (getenv('APP_ENV') === 'docker');
echo "<br>APP_ENV: " . (getenv('APP_ENV') ?: 'not set');
echo "<br>isDocker: " . ($isDocker ? 'true' : 'false');
echo "<br>Host: " . ($isDocker ? 'db' : '31.11.39.98');
?>
