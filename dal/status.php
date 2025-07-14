<?php

include "utilities.php";

include "../sendmail.php";

ini_set('error_log', 'logs/error_upload.log');
   
 


if(isset($_POST['Action'])) 
{
  $Action = $_POST['Action'];
  
  if ($Action == "UpdateStatus")
  {
    $side = $_POST['Side'];
    $userId = $_POST['UserId'];
 
    include "dbconfig.php";

    $sql = "UPDATE Status SET Side = $side  WHERE UserId=" .$userId;
    
    $ret = 0;
    if ($conn->query($sql) === TRUE) {
      $ret = 1;
    } 
 
    $conn->close();
    
    $result = array('Action' => $Action, 
                    'Ret' => $ret, 
                    'Side' => $side,
                    'UserId' => $userId);
    echo json_encode($result);
  }
 

}


?>
 