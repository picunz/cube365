<?php header('Access-Control-Allow-Origin: *');
 
 
  $servername = "31.11.39.98";
  $username = "Sql1669990";
  $password = "25fidenzaLF25!";
  $dbname = "Sql1669990_2";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {

      die("Connection failed: " . $conn->connect_error);

  }
  
 ?>





