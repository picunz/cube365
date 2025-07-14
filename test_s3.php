<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
 
try {
    $s3 = new S3Client([
        'version' => 'latest',
        'region'  => 'eu-milan-1',
    ]);

    echo "AWS SDK installato correttamente!";
} catch (Exception $e) 
{
    echo "Errore: " . $e->getMessage();
}

// Configura le credenziali AWS
$s3 = new S3Client([
    'region'  => 'eu-milan-1',
    'endpoint' => 'https://ax6youxk7eeb.compat.objectstorage.eu-milan-1.oraclecloud.com',
    'use_path_style_endpoint' => true,
    'credentials' => [
        'key'    => '67d7035b6483adbfda7353452afe3c2535dbfe81',
        'secret' => 'CYRy3YblZk9KEIfcD5LuYMAT+V8Q7+Htrbol3hydYEs=',
    ],
]);
// Nome del bucket e del file
$bucketName = 'bucket-oracle-kiand';
$fileName = 'test.txt';
$filePath = __DIR__ . '/' . $fileName;

// Contenuto del file (opzionale)
file_put_contents($filePath, "Ciao, questo è un file di test!");

// Carica il file su S3
try {
    $result = $s3->putObject([
        'Bucket' => $bucketName,
        'Key'    => $fileName,
        'Body'   => fopen($filePath, 'r'),
    ]);

    echo "✅ File caricato con successo!\n";
    echo "URL: " . $result['ObjectURL'] . "\n";

} catch (AwsException $e) {
    echo "Errore: " . $e->getMessage();
}
?>
