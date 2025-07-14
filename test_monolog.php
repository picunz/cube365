<?php

echo 'test';


// Autoload di Composer
require 'vendor/autoload.php';

// Usa Monolog
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Crea il logger
$log = new Logger('my_logger_ach');

// Aggiungi un handler per scrivere su un file di log
$log->pushHandler(new StreamHandler('app.log', Logger::DEBUG));

// Esempio di log
$log->info('Informazioni di base');
$log->warning('Attenzione!');
$log->error('Si è verificato un errore!');
$log->debug('Messaggio di debug');


?>