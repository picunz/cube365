<?php



ini_set('error_log', 'logs/error_generic.log');
   
 
//////////////////// Generic  /////////////////////////


function executeQueryForIdxReorder($dbConn, $table)
{
  $sql1 = "SET @row_index = 0;";
  $dbConn->query($sql1);

  $sql2  = "UPDATE " . $table . " ";
  $sql2 .= "SET Idx = (@row_index := @row_index + 1) ";
  $sql2 .= "ORDER BY WhenCreated";

  $result = $dbConn->query($sql2);
  return $result;
}

include_once 'dbconfig.php';

$Action = $_POST['Action'];

if(isset($_POST['Action'])) 
{

  /*
  ██████╗ ███████╗████████╗ ██╗     ██╗███████╗████████╗
  ██╔════╝ ██╔════╝╚══██╔══╝██║     ██║██╔════╝╚══██╔══╝
  ██║  ███╗█████╗     ██║   ██║     ██║███████╗   ██║   
  ██║   ██║██╔══╝     ██║   ██║     ██║╚════██║   ██║   
  ╚██████╔╝███████╗   ██║   ███████╗██║███████║   ██║   
  ╚═════╝ ╚══════╝   ╚═╝   ╚══════╝╚═╝╚══════╝   ╚═╝   
  */                                                                
 
  if ($Action == "GetList")
  {
    $table = $_POST['Table'];

     
    $deviceId = "-";
    if (isset($_POST['DeviceId'])) {
      $deviceId = $_POST['DeviceId'];
    }
 
    

    $sql = "SELECT * FROM " . $table . " WHERE 1 ";
    if ($deviceId != "-") {
      $sql.= " AND  DeviceId = '$deviceId' ";
    }
    
    
    
    $result = $conn->query($sql);
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);
 

    $jresult = array('Action' => $Action,
                     'DeviceId' => $deviceId,
                     'Sql' => $sql,
                     'Table' => $table,
                     'Result' => $result,
                     'List' => $rows);
    echo json_encode($jresult);

    exit;
  }

  /*

  ██████╗ ███████╗████████╗    ██╗████████╗███████╗███╗   ███╗
  ██╔════╝ ██╔════╝╚══██╔══╝    ██║╚══██╔══╝██╔════╝████╗ ████║
  ██║  ███╗█████╗     ██║       ██║   ██║   █████╗  ██╔████╔██║
  ██║   ██║██╔══╝     ██║       ██║   ██║   ██╔══╝  ██║╚██╔╝██║
  ╚██████╔╝███████╗   ██║       ██║   ██║   ███████╗██║ ╚═╝ ██║
   ╚═════╝ ╚══════╝   ╚═╝       ╚═╝   ╚═╝   ╚══════╝╚═╝     ╚═╝
                                                              

  */
  else if ($Action == "GetItem")
  {
    $table = $_POST['Table'];
    $id = $_POST['Id'];

    $sql = "SELECT * FROM " . $table . " WHERE Id = '$id' ";
    
    
     
    
    $result = $conn->query($sql);
    
    $rows = $result->fetchAll(PDO::FETCH_ASSOC);

    
    $rowCount = count($rows); 
    
    
    $conn = null;

    $jresult = array('Action' => $Action,
                     'Id' => $id,
                     'Table' => $table,
                     'Sql' => $sql,
                     'RowCount' => $rowCount,
                     'Result' => $result,
                     'Item' => $rows[0]
                    );
    echo json_encode($jresult);

    exit;
  }
 
  /*  
  ██╗   ██╗██████╗ ██████╗  █████╗ ████████╗███████╗
  ██║   ██║██╔══██╗██╔══██╗██╔══██╗╚══██╔══╝██╔════╝
  ██║   ██║██████╔╝██║  ██║███████║   ██║   █████╗  
  ██║   ██║██╔═══╝ ██║  ██║██╔══██║   ██║   ██╔══╝  
  ╚██████╔╝██║     ██████╔╝██║  ██║   ██║   ███████╗
  ╚═════╝ ╚═╝     ╚═════╝ ╚═╝  ╚═╝   ╚═╝   ╚══════╝                                              
  */

  else if ($Action == "Update")
  {    
    $Table = $_POST['Table'];
    $Id = $_POST['Id'];
    $Fields = json_decode($_POST['Fields'], true);

    $sqlUpdates = ''; 
    foreach ($Fields as $key => $value) {
        
      if ($value=="") {
        $sqlUpdates .= "$key" . " = " . "NULL" . " , ";
      } 
      else {

       

        $decodedData = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
          $sqlUpdates .= "$key" . " = " . "'" . json_encode($decodedData) . "'" . " , ";
        }
        else 
        {
          $sqlUpdates .= "$key" . " = " . "'" . htmlspecialchars($value) . "'" . " , ";
        }

        
      }
    }
    $sqlUpdates = rtrim($sqlUpdates, ', ');
 

    $sql = "UPDATE $Table SET " . $sqlUpdates . " WHERE Id=" .$Id;
    
   
    
    $result = $conn->query($sql);
    

    $conn = null;

    $jresult = array('Action' => $Action,
                     'Table' => $Table,
                     'Id' => $Id, 
                     'Fields' => $Fields, 
                     'SqlUpdates' => $sqlUpdates,
                     'Sql' => $sql,
                     'Result' => $result);
    echo json_encode($jresult);

    exit;
  }

  /*  
  ██╗███╗   ██╗███████╗███████╗██████╗ ████████╗
  ██║████╗  ██║██╔════╝██╔════╝██╔══██╗╚══██╔══╝
  ██║██╔██╗ ██║███████╗█████╗  ██████╔╝   ██║   
  ██║██║╚██╗██║╚════██║██╔══╝  ██╔══██╗   ██║   
  ██║██║ ╚████║███████║███████╗██║  ██║   ██║   
  ╚═╝╚═╝  ╚═══╝╚══════╝╚══════╝╚═╝  ╚═╝   ╚═╝   
  */                                                          
                                                          
                                                          
  else if ($Action == "Insert")
  {
    $Table = $_POST['Table'];
    $Fields = json_decode($_POST['Fields'], true);

    $insertKeys = '('; 
    $insertValues = '(';
    foreach ($Fields as $key => $value) {
        
      $insertKeys .= $key . ',';

      $decodedData = json_decode($value, true);
      if (json_last_error() === JSON_ERROR_NONE) {
        $insertValues .= "'" . json_encode($decodedData) . "'" . " , ";
      }
      else 
      {
        $insertValues .=  "'" . htmlspecialchars($value) . "'" . " , ";
      }
    }

    // audit

    $now = getISODate();
    $insertKeys .= 'LastAccess,';
    $insertValues .=  "'" . htmlspecialchars($now) . "'" . " , ";

    $insertKeys .= 'WhenCreated,';
    $insertValues .=  "'" . htmlspecialchars($now) . "'";
    
    $insertKeys = rtrim($insertKeys, ', ');
    $insertKeys .= ')';
    $insertValues .= ')';

    $insertValues = rtrim($insertValues, ',');
 
    $sqlInsert = "INSERT INTO $Table " . $insertKeys . " VALUES " . $insertValues;

     
 
    $result = $conn->query($sqlInsert);
   

    $id = $conn->insert_id;


    $idxOrder = false;
    if (str_contains($insertKeys, 'Idx')) {
      executeQueryForIdxReorder($conn, $Table);
      $idxOrder = true;
    }
   
    $conn = null;

    $result = array('Action' => $Action, 
                    'Table' => $Table, 
                    'Fields' => $Fields, 
                    'IdxOrder' => $idxOrder,
                    'SqlInsert' => $sqlInsert, 
                    'Id' => $id,
                    'Result' => $result);
    echo json_encode($result);
  
  }

  /*  
  ██████╗ ███████╗██╗     ███████╗████████╗███████╗
  ██╔══██╗██╔════╝██║     ██╔════╝╚══██╔══╝██╔════╝
  ██║  ██║█████╗  ██║     █████╗     ██║   █████╗  
  ██║  ██║██╔══╝  ██║     ██╔══╝     ██║   ██╔══╝  
  ██████╔╝███████╗███████╗███████╗   ██║   ███████╗
  ╚═════╝ ╚══════╝╚══════╝╚══════╝   ╚═╝   ╚══════╝
  */                                                      
                                                      
                                                      
  else if ($Action == "Delete")
  {
    $Table = $_POST['Table'];
    $Id = $_POST['Id'];
 
    $fileToDelete = "-";
    $filename = "-";
    $deleteResult = NULL;
    if (isset($_POST['DeleteFile'])) {  // delete also the file in DB
      $fileToDelete = $_POST['DeleteFile'];

      $sql = "SELECT * FROM $Table WHERE Id = '$Id'";

      $result = $conn->query($sql);
      $rows = $result->fetchAll(PDO::FETCH_ASSOC);
      $filename = $rows[0][$fileToDelete];


      $fullPath = "-";
      $deleteResult = false;
      if ($filename != NULL) {

        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/bicistore-races/resources' . '/' . $filename;
        $deleteResult = unlink($fullPath);
      }
    }
 

    $sql = "DELETE FROM $Table WHERE Id = '$Id'";

    $result = $conn->query($sql);


    $idxReorder = false;
     
    $resultReorder = false;
    if (isset($_POST['IdxReorder'])) { 
      $idxReorder = true;
      $resultReorder = executeQueryForIdxReorder($conn, $Table);
    }
    

    $conn = null;

    $result = array('Action' => $Action, 
                    'Table' => $Table, 
                    'Id' => $Id,
                    'SqlInsert' => $sql, 
                    'FileToDelete' => $fileToDelete,
                    'DeleteResult' => $deleteResult,
                    'Filename' => $filename,
                    'IdxReorder' => $idxReorder,
                    'ResultReorder' => $resultReorder,
                    'Result' => $result);
    echo json_encode($result);
  }

  else if ($Action == "CheckTableExists") {

    $table = $_POST['Table'];
 
    $driver = $conn->getAttribute(PDO::ATTR_DRIVER_NAME);

    if ($driver === 'mysql') {
        $sql = "SHOW TABLES LIKE :table";
    } elseif ($driver === 'sqlite') {
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name = :table";
    } else {
        throw new Exception("Database driver non supportato: $driver");
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':table', $table, PDO::PARAM_STR);
    $stmt->execute();

    //$exists = $stmt->rowCount() > 0;
    $exists = $stmt->fetchColumn();

 
    $result = array('Action' => $Action, 
                    'Table' => $table, 
                    'Driver' => $driver,
                    'Result' => $exists);
    echo json_encode($result);
  }
  



  /********** utils *****************************/

  else if ($Action == "GetRoot")
  {
    
    $jresult = array('Action' => $Action,
                     'Root' => $_SERVER['DOCUMENT_ROOT']);
    echo json_encode($jresult);

    exit;
  }


  else if ($Action == "LogForUser")
  {
    $userFullName = $_POST["userFullName"];
    $message = $_POST["message"];
    
    
    $dateLog = getISODate();
    $dateDay = explode("T", $dateLog);

    $filename = $userFullName . '_' . $dateDay[0] .  '.txt';


    

    $filename = $_SERVER['DOCUMENT_ROOT'] . '/cube365/logs' . '/' . $filename;

    $messageLog = json_decode($message);
  
    if ($messageLog == null) {
      $messageLog = $message;
    }

    $logObj = array('Date' => $dateLog,
                    'Log' => $messageLog);


    $ret = file_put_contents($filename, PHP_EOL . json_encode($logObj), FILE_APPEND);
    
    $jresult = array('Action' => $Action,
                     'Message' => $message,
                     'MessageWrote' => json_encode($logObj),
                     'Result' => $ret,
                     'UserFullName' => $userFullName,
                     'DateLog' => $dateLog);
    echo json_encode($jresult);

    exit;
  }
 
}
 