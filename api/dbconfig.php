<?php header('Access-Control-Allow-Origin: *');
 
  //$servername = "31.11.39.98";
  $servername = "db";
  $username = "Sql1669990";
  $password = "25fidenzaLF25!";
  $dbname = "Sql1669990_2";

  /*
  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {

      die("Connection failed: " . $conn->connect_error);

  }
  
  */

try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Gestione errori
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Fetch più leggibile
        ]
    );
} catch (PDOException $e) {
    die("Connessione fallita: " . $e->getMessage());
}

/*
require_once __DIR__ . '/lib/Settings.php';

  
// Inizializza la connessione al database
Settings::initDb([
    'servername'     => $servername,
    'dbname'   => $dbname,
    'username'     => $username,
    'password' => '$password'
]);
*/
 ?>







