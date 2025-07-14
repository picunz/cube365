<?php
/*
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
*/

require '../vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

ini_set('error_log', 'logs/error_races.log');
  

 

/** functions **/

function getS3Client()
{
    try {
        $s3 = new S3Client([
            'region'  => 'eu-milan-1',
            'endpoint' => 'https://ax6youxk7eeb.compat.objectstorage.eu-milan-1.oraclecloud.com',
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => '67d7035b6483adbfda7353452afe3c2535dbfe81',
                'secret' => 'CYRy3YblZk9KEIfcD5LuYMAT+V8Q7+Htrbol3hydYEs=',
            ],
        ]);

        return $s3;

    } catch (Exception $e) 
    {
        echo "Error: " . $e->getMessage();
        return null;
    }
}

function getISODate()
{
   $now = new DateTime('now', new DateTimeZone('Europe/Rome'));
   $date = $now->format('Y-m-d');
   $time = $now->format('H:i:s');
   $dt = $date . 'T' . $time;
   return $dt;
}
   

function getTaskAndTypeFromSide($taskArray, $side)
{
    $filteredList = array_filter($taskArray, function($row) use ($side) {
        return ($row['Side'] === $side);
    });

    $task = "-";
    $type = "-";
    if (!empty($filteredList)) {

        foreach ($filteredList as $side) {
            $task  = $side['Task'];
            $type =  $side['Type'];
        }
    }
    return $task . ":" . $type;
}

/** APIs **/

if(isset($_POST['Action'])) 
{
  $Action = $_POST['Action'];

  if ($Action == "UpdateStatistics")
  {
    $userId = $_POST['UserId'];
    $content = $_POST['Stats'];

    $currentDate = date('Ymd');
    $file = "../reports/stats" . $currentDate . ".txt";

    clearstatcache();
    $file_existed = file_exists($file);

    //***  get file tasks for update stats ***/

    $taskFile = "tasks_" . $userId . ".csv";

    $taskFile_existed = file_exists($taskFile);

    if (!$taskFile_existed) {

        echo "Error: " . $taskFile . " not found";

        exit;
    }

    
    $handle = fopen($taskFile, "r");
    
    
    $list = array();
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        
        array_push($list, array("Side" => $data[0], "Task" => $data[1], "Type" => rtrim($data[2], ';')));
    }
    fclose($handle);

    

    $jsonArray = json_decode($content, true);
    $jsonArray['LEFT']= getTaskAndTypeFromSide($list, 'LEFT');
    $jsonArray['RIGHT']= getTaskAndTypeFromSide($list, 'RIGHT');
    $jsonArray['FRONT']= getTaskAndTypeFromSide($list, 'FRONT');
    $jsonArray['BACK']= getTaskAndTypeFromSide($list, 'BACK');
    $jsonArray['BASE']= getTaskAndTypeFromSide($list, 'BASE');
    $jsonArray['TOP']= getTaskAndTypeFromSide($list, 'TOP');
    $content = json_encode($jsonArray);

    file_put_contents($file, $content);
 
    $jresult = array('Action' => $Action,
                     'Stats' => $content,
                     'OverWrite' => $file_existed,
                     'File' => $file,
                     'UserId' => $userId);
    echo json_encode($jresult);

    exit;
  }

  else if ($Action == "UpdateState")
  {
    $deviceId = $_POST['DeviceId'];
    $state = $_POST['State'];

    $currentDate = date('Ymd');

    $now = getISODate();

    $nowTime= explode("T", $now)[1];

    include "dbconfig.php";

    $table = "Device_" . $deviceId;

    // get info task by side (/state)
    $taskName = $state;
    $taskType = "-";
    $sqlST = "SELECT * FROM Tasks WHERE DeviceId ='$deviceId'";
    $queryresult = $conn->query($sqlST);
    $rowCount = mysqli_num_rows($queryresult); 
    $row = false;
    if ($rowCount > 0)
    {
        $row = $queryresult->fetch_assoc();
        $jTasks = json_decode($row["Content"], true);

        $result = array_filter($jTasks, fn($item) => $item["Side"] === $state);
        if (!empty($result)) {
            $found = array_values($result)[0]; 
            $taskName = $found['Name'];
            $taskType = $found['Type'];
        }
    }

    // check activities
    $sqlS = "SELECT * FROM $table WHERE Date='$currentDate'";
    $queryresult = $conn->query($sqlS);
    $rowCount = mysqli_num_rows($queryresult); 
    $newDay = false;
    $jActivities = "";  
    if ($rowCount > 0)  // update
    {
        $rows = array();
        while($r = $queryresult->fetch_assoc()) {
            $rows[] = $r;
        }
        $jActivities = json_decode($rows[0]["Activities"], true);
    }
    else // create
    {   
        $newDay = true;
        $jActivities = "[]"; 
        $sqlI = "INSERT INTO $table (Date, Activities, LastUpdate, Created)";
        $sqlI .= " VALUES ('$currentDate', '$jActivities', '$now', '$now')";
        $resultI = $conn->query($sqlI);
        $jActivities = json_decode("[]", true);
    }

    $found = false;
    foreach ($jActivities as &$item) {   
        if ($item['Side'] === $state) { // update it
            $found = true;

            $lastTime = $item["Time"];
            $nowTime = explode("T", $now)[1];
            

            $diff = strtotime($nowTime) - strtotime($lastTime);

            //$item["CounterSec"] = $item["CounterSec"] + $diff;

            $item["Counter"] = $item["Counter"] + 1;

            $item["CounterSec"] = $item["Counter"] * 10;

            $item["Time"] = $nowTime;

            $elapsedTime = gmdate("H:i:s",  $item["CounterSec"]);
            $item["ElapsedTime"] = $elapsedTime;

            $item["Name"] = $taskName;
            $item["Type"] = $taskType;

            break;
        }
    }
    if (!$found) {  // create it

        
        $elapsedTime = gmdate("H:i:s", 0);
        $newObject = ["Side" => $state, "Counter" => 0, "CounterSec" => 0, "Time" => $nowTime, "ElapsedTime" => $elapsedTime, "Name" => $taskName, "Type" => $taskType];
        
        array_push($jActivities, $newObject);
    }

    $activities = json_encode($jActivities, JSON_PRETTY_PRINT);

    $sqlU = "UPDATE $table SET Activities = '$activities', LastUpdate = '$now' WHERE Date='$currentDate'";
    $resultU = $conn->query($sqlU);
    
    $conn->close();
    

    $jresult = array('Action' => $Action,
                     'State' => $state,
                     'jActivities' => $jActivities,
                     'DeviceId' => $deviceId,
                     'ResultUpdate' => $resultU,
                     'RowTasks'=> $jTasks,
                     'NewDay' => $newDay,
                     'Debug' => $now
                    );
    echo json_encode($jresult);

    exit;
  }

  else if ($Action == "GetTasks") // from device // non ancora usata fsasl device
  {
    $deviceId = $_POST['DeviceId'];
    
    include "dbconfig.php";

    $table = "Device_" . $deviceId;
  
    $sqlST = "SELECT * FROM Tasks WHERE DeviceId ='$deviceId'";
    $queryresult = $conn->query($sqlST);
    $rowCount = mysqli_num_rows($queryresult); 
    $row = false;
    if ($rowCount > 0)
    {
        $row = $queryresult->fetch_assoc();
        $jTasks = json_decode($row["Content"], true);
    }

    $conn->close();

    $jresult = array('Action' => $Action,
                     'DeviceId' => $deviceId,
                     'RowTasks'=> $jTasks
                    );
    echo json_encode($jresult);

    exit;
  }

  else if ($Action == "GetActivities")
  {
    $deviceId = $_POST['DeviceId'];
    
    $currentDate = date('Ymd');

    $now = getISODate();

    $nowTime= explode("T", $now)[1];

    include "dbconfig.php";

    $table = "Device_" . $deviceId;

    // check activities
    $sqlS = "SELECT * FROM $table WHERE Date='$currentDate'";
    $queryresult = $conn->query($sqlS);
    $rowCount = mysqli_num_rows($queryresult); 
    $jActivities = "";  
    if ($rowCount > 0)  // update
    {
        $rows = array();
        while($r = $queryresult->fetch_assoc()) {
            $rows[] = $r;
        }
        $jActivities = json_decode($rows[0]["Activities"], true);
    }
   
    $activities = json_encode($jActivities, JSON_PRETTY_PRINT);


    // compute statistic percentile
    $sum = 0;
    foreach ($jActivities as &$item) {   
        $sum += $item['CounterSec'];
    }
    foreach ($jActivities as &$item) {
        if ($sum > 0) 
        {
            $item['Perc'] = intval(($item['CounterSec'] / $sum) * 100); // arrotonda al 2° decimale
        } else {
            $item['Perc'] = 0;
        }
    }
    
    $activities = json_encode($jActivities, JSON_PRETTY_PRINT);
   
    $conn->close();
    

    $jresult = array('Action' => $Action,
                     'jActivities' => $jActivities,
                     'DeviceId' => $deviceId,
                     'RowCount' => $rowCount,
                     'Sum' => $sum
                    );
    echo json_encode($jresult);

    exit;
  }


  else if($Action == "SendTask")
  {
    $deviceId = $_POST['DeviceId'];
    $jTask = json_decode($_POST['Task'], true);

    $Side = $jTask["Side"];
    $name = $jTask["Name"];
    $type = $jTask["Type"];


    include "dbconfig.php";

    $sqlS = "SELECT * FROM Tasks  WHERE DeviceId='$deviceId'";
    
    $queryresult = $conn->query($sqlS);
    
    $tasksArray = array();
    while($r = $queryresult->fetch_assoc()) {
        $tasksArray[] = $r;
    }
    
    $content = $tasksArray[0]["Content"];
    
    $jContent = json_decode($content, true);

    array_push($jContent, $jTask);

    $jsonStr = json_encode($jContent/*, JSON_PRETTY_PRINT*/);


    //$jsonTasks = $_POST['Task'];

    $sql = "UPDATE Tasks SET Content = '$jsonStr'  WHERE DeviceId='$deviceId'";

    
    if ($conn->query($sql) === TRUE) {
        $result = true;
    } else {
        $result =  $conn->error;
    }
    
    $conn->close();


    $jresult = array('Action' => $Action,
                     'Task' => $jTask, 
                     'Ret' => $result,
                     'Debug' => $jContent,
                     'DeviceId' => $deviceId);
    echo json_encode($jresult);
    exit;
  }
}

if (isset($_GET['Action']) && isset($_GET['DeviceId'])) 
{
    $Action = $_GET['Action'];
    
    

    if($Action == "RegisterDevice")
    {
        $deviceId = $_GET['DeviceId'];


        include "dbconfig.php";

        $result = false;

        // create table if not exists
        $tableName = "Device_" . $deviceId;
        $sql = "CREATE TABLE IF NOT EXISTS " . $tableName . "(";
        $sql .= "Id INT AUTO_INCREMENT PRIMARY KEY,";
        $sql .= "LastUpdate DateTime(6) NOT NULL,";
        $sql .= "Activities JSON NULL)";

        if ($conn->query($sql) === TRUE) {
            $result = true;
        } else {
            $result =  $conn->error;
        }

        // insert null task is not exists
        $sqlT = "INSERT IGNORE INTO Tasks (DeviceId, Content) VALUES ('$deviceId', '{}')";

        if ($conn->query($sqlT) === TRUE) {
            $resultTask = true;
        } else {
            $resultTask =  $conn->error;
        }

        
        // get tasks for this device
        $sqlGet = "SELECT *  FROM Tasks WHERE DeviceId = '$deviceId'";

        $resultG = $conn->query($sqlGet);

        $rowCount = mysqli_num_rows($resultG);  
        
        $content = false;
        if ($rowCount > 0)
        {
            $rows = array();
            while($r = $resultG->fetch_assoc()) {
            $rows[] = $r;
            }
        
            $content = $rows[0];
            $jContent = $rows[0]["Content"];
        }

        $conn->close();
    
         
        $jresult = array('Action' => $Action,
                         'DeviceId' => $deviceId,
                         'ResultTable' => $result,
                         'ResultTask' => $resultTask,
                         'Tasks' => $jContent
                       //  'List' => $rows
                        );
        echo json_encode($jresult);
        
        exit;
 
    }
/*
    else if($Action == "GetTasks") // non usato da web, uso getlist
    {
        $deviceId = $_GET['DeviceId'];

        $file = $deviceId . "\tasks.csv";

        if (!file_exists($file)) {
            $jresult = array('Action' => $Action,
                            'DeviceId' => $deviceId,
                            'File' => $file,
                            'Error' => 'File not exists');
            echo json_encode($jresult);
            exit;
        }

        $handle = fopen($file, "r");
        if ($handle == FALSE) {
            $jresult = array('Action' => $Action,
                            'DeviceId' => $deviceId,
                            'Error' => 'Handle error');
            echo json_encode($jresult);
            exit;
        }
       
        $content = file_get_contents($file);
        if (empty($content) || strlen($content) <10 ) {
            $jresult = array('Action' => $Action,
                            'DeviceId' => $deviceId,
                            'Content' => $content,
                            'Error' => 'File empty');
            echo json_encode($jresult);
            exit;
        }

        $rows = array();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            
            array_push($rows, array("Side" => $data[0], "Task" => $data[1], "Type" => rtrim($data[2], ';')));
        }
                    
        fclose($handle);
        
       
        $jresult = array('Action' => $Action,
                         'DeviceId' => $deviceId,
                         'List' => $rows
                        );
  
        echo json_encode($jresult);
        exit;
    }
*/
    if($Action == "DeleteTaskBySide")
    {
        $deviceId = $_GET['DeviceId'];
        $side = $_GET['Side'];


        include "dbconfig.php";


        $sqlS = "SELECT * FROM Tasks  WHERE DeviceId='$deviceId'";
    
        $queryresult = $conn->query($sqlS);
        
        $tasksArray = array();
        while($r = $queryresult->fetch_assoc()) {
            $tasksArray[] = $r;
        }

        $content = $tasksArray[0]["Content"];
        $jContent = json_decode($content, true);

      
        foreach ($jContent as $key => $object) {
            if ($object['Side'] == $side) {
                unset($jContent[$key]);
            }
        }

        // update
        //$jContentStr = json_encode($jContent);
        $jContentStr = json_encode(array_values($jContent), JSON_PRETTY_PRINT);


        $result = false;
        
        $sqlU = "UPDATE Tasks SET Content = '$jContentStr'  WHERE DeviceId='$deviceId'";
        if ($conn->query($sqlU) === TRUE) {
            $result = true;
        } else {
            $result =  $conn->error;
        }
        

        $conn->close();

        $jresult = array('Action' => $Action,
                         'DeviceId' => $deviceId,
                         'Side' => $side,
                         'Result' => $result,
                         'Content' => $jContentStr
                        );
        echo json_encode($jresult);
        exit;
    }

    if($Action == "GetFileStats")
    {
        /*
        $userId = $_GET['UserId'];

        $directory = "../reports/";

        $files = scandir($directory);
        $files = array_diff($files, array('.', '..'));

        $filteredFiles = array_filter($files, function($file) use ($directory) {
            return is_file($directory . '/' . $file) &&
                   strpos($file, 'stats') === 0 &&
                   pathinfo($file, PATHINFO_EXTENSION) === 'txt';
        });
        
        $filteredFiles = array_values($filteredFiles);
        
        $jresult = array('Action' => $Action,
                         'UserId' => $userId,
                         'List' => $filteredFiles);
        echo json_encode($jresult);
        */
        exit;
    }

    if($Action == "GetDailyStats")
    {
        /*
        $userId = $_GET['UserId'];
        $fileStats = $_GET['FileStats'];

        $fileDir = "../reports/";
         
        $pathFileStats = $fileDir . $fileStats;

        if (!file_exists($pathFileStats)) {
            $jresult = array('Action' => $Action,
                            'UserId' => $userId,
                            'File' => $pathFileStats,
                            'Error' => 'File stats not exists');
            echo json_encode($jresult);
            exit;
        }
        
        $contentJson = file_get_contents($pathFileStats);
        
        if ($contentJson === false) {
            $jresult = array('Action' => $Action,
                         'UserId' => $userId,
                         'Err' => 'Error file');
            echo json_encode($jresult);
            exit;
        }

        $dataArray = json_decode($contentJson, true);
        
        $jresult = array('Action' => $Action,
                         'UserId' => $userId,
                         'FileStats' => $pathFileStats,
                         'ContentFile' => $contentJson,
                         'JsonObj' => $dataArray);
        echo json_encode($jresult);
        */
        exit;
    }

    
}

?>
