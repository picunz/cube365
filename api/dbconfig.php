<?php header('Access-Control-Allow-Origin: *');
 
 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
  
//phpinfo();

require_once 'settings.php';

if (!file_exists(__DIR__ . '/dbsettings.php')) {
    die("Configurazione database non trovata.");
}

$config = include __DIR__ . '/dbsettings.php';
 

Settings::load($config);

$conn = Settings::initDb($config);
