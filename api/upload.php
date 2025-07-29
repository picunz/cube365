<?php

include "utilities.php";

include "../sendmail.php";

ini_set('error_log', 'logs/error_upload.log');
   
 
//////////////////// Generic  /////////////////////////

include_once 'dbconfig.php';

if(isset($_POST['Action'])) 
{
  $Action = $_POST['Action'];
  
/*

██╗   ██╗██████╗ ██╗      ██████╗  █████╗ ██████╗     ██╗███╗   ███╗ █████╗  ██████╗ ███████╗
██║   ██║██╔══██╗██║     ██╔═══██╗██╔══██╗██╔══██╗    ██║████╗ ████║██╔══██╗██╔════╝ ██╔════╝
██║   ██║██████╔╝██║     ██║   ██║███████║██║  ██║    ██║██╔████╔██║███████║██║  ███╗█████╗  
██║   ██║██╔═══╝ ██║     ██║   ██║██╔══██║██║  ██║    ██║██║╚██╔╝██║██╔══██║██║   ██║██╔══╝  
╚██████╔╝██║     ███████╗╚██████╔╝██║  ██║██████╔╝    ██║██║ ╚═╝ ██║██║  ██║╚██████╔╝███████╗
 ╚═════╝ ╚═╝     ╚══════╝ ╚═════╝ ╚═╝  ╚═╝╚═════╝     ╚═╝╚═╝     ╚═╝╚═╝  ╚═╝ ╚═════╝ ╚══════╝
                                                                                             
*/ 

  if ($Action == "UploadImage")
  {
    $imageData = $_POST['ImageData'];
    $fileName = $_POST['FileName'];
 
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $bin = base64_decode($imageData);

    $image = imagecreatefromstring($bin);

    if (!$image) {
      die('Base64 value is not a valid image');
    }
  
    $destFileName = $_SERVER['DOCUMENT_ROOT'] . '/resources' . '/' . $fileName;
    $resultUpFile = imagepng($image, $destFileName, 0);

//    $resultUpFile = "";

    $result = array('Action' => $Action, 
                    'DestFileName' => $destFileName, 
                    'FileName' => $fileName, 
                    'ResultUpFile' => $resultUpFile,
                    'ImageData' => $imageData);
    echo json_encode($result);
  }

  /*
  
██╗   ██╗██████╗ ██╗      ██████╗  █████╗ ██████╗ ██████╗ ███████╗███████╗ ██████╗ ██╗   ██╗██████╗  ██████╗███████╗
██║   ██║██╔══██╗██║     ██╔═══██╗██╔══██╗██╔══██╗██╔══██╗██╔════╝██╔════╝██╔═══██╗██║   ██║██╔══██╗██╔════╝██╔════╝
██║   ██║██████╔╝██║     ██║   ██║███████║██║  ██║██████╔╝█████╗  ███████╗██║   ██║██║   ██║██████╔╝██║     █████╗  
██║   ██║██╔═══╝ ██║     ██║   ██║██╔══██║██║  ██║██╔══██╗██╔══╝  ╚════██║██║   ██║██║   ██║██╔══██╗██║     ██╔══╝  
╚██████╔╝██║     ███████╗╚██████╔╝██║  ██║██████╔╝██║  ██║███████╗███████║╚██████╔╝╚██████╔╝██║  ██║╚██████╗███████╗
 ╚═════╝ ╚═╝     ╚══════╝ ╚═════╝ ╚═╝  ╚═╝╚═════╝ ╚═╝  ╚═╝╚══════╝╚══════╝ ╚═════╝  ╚═════╝ ╚═╝  ╚═╝ ╚═════╝╚══════╝
                                                                                                                    
  */ 

  else if ($Action == "UploadResource")
  {
    $size = $_FILES['FileInfo']['size'];
    $name = $_FILES['FileInfo']['name'];
    $tmpname = $_FILES['FileInfo']['tmp_name'];
    $upFile = $_FILES['FileInfo'];

    $fileName = $_POST['FileName'];
    $description = $_POST['Description'];
    $raceId = $_POST['RaceId'];
    $userFullName = $_POST['UserFullName'];
    $userId = $_POST['UserId'];
    $type = $_POST['Type'];
    $idx = $_POST['Index'];
 
     

    $whenCreatedDt = getISODate();

    // update db
    $sql = "INSERT INTO Resources(FileName, Type, Description, RaceId, Idx, WhenCreated, ByUser, UserId) ";
    $sql .= " VALUES ('$fileName', '$type', '$description', '$raceId', '$idx', '$whenCreatedDt', '$userFullName', '$userId' )";
    $result = $conn->query($sql);

    $id = $conn->insert_id;
    $conn = null;

    $ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $subfolder = '/resources';
    if ($type == "PDF_CERT") {
      $subfolder = '/resources/certificates';
    }

    $destPath = $_SERVER['DOCUMENT_ROOT'] . '/cube365' . $subfolder . '/' . $fileName;

    $resultUpFile = move_uploaded_file($tmpname, $destPath);
    
    $result = array('Action' => $Action, 
                    'DestFileName' => $destPath, 
                    'FileName' => $fileName, 
                    'ResultUpFile' => $resultUpFile,
                    'Size' => $size,
                    'Ext' => $ext,
                    'Description' => $description,
                    'RaceId' => $raceId,
                    'UserFullName' => $userFullName,
                    'UserId' => $userId,
                    'Type' => $type,
                    'Index' => $idx,
                    'Id' => $id,
                    'WhenCreatedDt' => $whenCreatedDt);
    echo json_encode($result);
  }

}
 