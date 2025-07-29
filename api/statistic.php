<?php

 

ini_set('error_log', 'logs/error_races.log');
  

include_once 'dbconfig.php';

/** functions **/


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


    /*
    ██╗   ██╗██████╗ ██████╗  █████╗ ████████╗███████╗    ███████╗████████╗ █████╗ ████████╗███████╗
    ██║   ██║██╔══██╗██╔══██╗██╔══██╗╚══██╔══╝██╔════╝    ██╔════╝╚══██╔══╝██╔══██╗╚══██╔══╝██╔════╝
    ██║   ██║██████╔╝██║  ██║███████║   ██║   █████╗      ███████╗   ██║   ███████║   ██║   █████╗  
    ██║   ██║██╔═══╝ ██║  ██║██╔══██║   ██║   ██╔══╝      ╚════██║   ██║   ██╔══██║   ██║   ██╔══╝  
    ╚██████╔╝██║     ██████╔╝██║  ██║   ██║   ███████╗    ███████║   ██║   ██║  ██║   ██║   ███████╗
    ╚═════╝ ╚═╝     ╚═════╝ ╚═╝  ╚═╝   ╚═╝   ╚══════╝    ╚══════╝   ╚═╝   ╚═╝  ╚═╝   ╚═╝   ╚══════╝
    */
   
    if ($Action == "UpdateState")
    {
        $deviceId = $_POST['DeviceId'];
        $state = $_POST['State'];

        date_default_timezone_set('Europe/Rome');
        $currentDate = date('Ymd');

        $now = getISODate();

        $nowTime= explode("T", $now)[1];

        

        $table = "Device_" . $deviceId;

        // get info task by side (/state)
        $taskName = $state;
        $taskType = "-";
        $sqlST = "SELECT * FROM Tasks WHERE DeviceId ='$deviceId'";
        $queryresult = $conn->query($sqlST);
        
        $rows = $queryresult->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = count($rows); 


        $row = false;
        if ($rowCount > 0)
        {
            $row = $rows[0];
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
        
        $rows = $queryresult->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = count($rows);

        $newDay = false;
        $jActivities = "";  
        if ($rowCount > 0)  // update
        {
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
        
        $conn = null;
        

        $jresult = array('Action' => $Action,
                        'State' => $state,
                        'jActivities' => $jActivities,
                        'DeviceId' => $deviceId,
                        'ResultUpdate' => $resultU,
                        'RowTasks'=> $jTasks,
                        'NewDay' => $newDay,
                        'CurrentDate' => $currentDate,
                        'Debug' => $now
                        );
        echo json_encode($jresult);

        exit;
    }

    /*
    ██████╗ ███████╗████████╗     ████████╗ █████╗ ███████╗██╗  ██╗███████╗
    ██╔════╝ ██╔════╝╚══██╔══╝    ╚══██╔══╝██╔══██╗██╔════╝██║ ██╔╝██╔════╝
    ██║  ███╗█████╗     ██║          ██║   ███████║███████╗█████╔╝ ███████╗
    ██║   ██║██╔══╝     ██║          ██║   ██╔══██║╚════██║██╔═██╗ ╚════██║
    ╚██████╔╝███████╗   ██║          ██║   ██║  ██║███████║██║  ██╗███████║
    ╚═════╝ ╚══════╝   ╚═╝          ╚═╝   ╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝╚══════╝
    */
    else if ($Action == "GetTasks") // from device
    {
        $deviceId = $_POST['DeviceId'];
        
         

        $table = "Device_" . $deviceId;
    
        $sqlST = "SELECT * FROM Tasks WHERE DeviceId ='$deviceId'";
        $queryresult = $conn->query($sqlST);

        $rows = $queryresult->fetchAll(PDO::FETCH_ASSOC); 
        $rowCount = count($rows); 

        $row = false;
        if ($rowCount > 0)
        {
            $row = $rows[0];
            $jTasks = json_decode($row["Content"], true);
        }

        $conn = null;

        $jresult = array('Action' => $Action,
                        'DeviceId' => $deviceId,
                        'RowTasks'=> $jTasks
                        );
        echo json_encode($jresult);

        exit;
    }
 
    /*
    ███████╗███████╗███╗   ██╗██████╗     ████████╗ █████╗ ███████╗██╗  ██╗
    ██╔════╝██╔════╝████╗  ██║██╔══██╗    ╚══██╔══╝██╔══██╗██╔════╝██║ ██╔╝
    ███████╗█████╗  ██╔██╗ ██║██║  ██║       ██║   ███████║███████╗█████╔╝ 
    ╚════██║██╔══╝  ██║╚██╗██║██║  ██║       ██║   ██╔══██║╚════██║██╔═██╗ 
    ███████║███████╗██║ ╚████║██████╔╝       ██║   ██║  ██║███████║██║  ██╗
    ╚══════╝╚══════╝╚═╝  ╚═══╝╚═════╝        ╚═╝   ╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
    */

    else if($Action == "SendTask")
    {
        $deviceId = $_POST['DeviceId'];
        $jTask = json_decode($_POST['Task'], true);

        $Side = $jTask["Side"];
        $name = $jTask["Name"];
        $type = $jTask["Type"];


       

        $conn->exec("
            CREATE TABLE IF NOT EXISTS Tasks (
                Id INTEGER PRIMARY KEY AUTOINCREMENT,
                DeviceId TEXT UNIQUE,
                Content JSON,
                Description TEXT
            )
        ");

        $sqlS = "SELECT * FROM Tasks  WHERE DeviceId='$deviceId'";
        $queryresult = $conn->query($sqlS);
        $tasksArray = $queryresult->fetchAll(PDO::FETCH_ASSOC);

        if (count($tasksArray) === 0) {
            $insert = $conn->prepare("INSERT INTO Tasks (DeviceId, Content) VALUES (:deviceId, :content)");
            $insert->execute([
                ':deviceId' => $deviceId,
                ':content' => '{}'
            ]);

            // aggiorna array con il nuovo valore appena inserito
            $tasksArray = [[
                'DeviceId' => $deviceId,
                'Content' => '{}'
            ]];
        }

        $content = '';
        if (count($tasksArray) > 0 && isset($tasksArray[0]["Content"])) {
            $content = $tasksArray[0]["Content"];
        }
        
        
        $jContent = json_decode($content, true);

        array_push($jContent, $jTask);

        $jsonStr = json_encode($jContent/*, JSON_PRETTY_PRINT*/);

        $sql = "UPDATE Tasks SET Content = '$jsonStr'  WHERE DeviceId='$deviceId'";

        try {
            $conn->query($sql);
            $result = true;
        } 
        catch (PDOException $e) {
            echo "Errore PDO: " . $e->getMessage();
            $result = false;
        }
        
        $conn = null;


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

    /*
    ██████╗ ███████╗ ██████╗ ██╗███████╗████████╗███████╗██████╗     ██████╗ ███████╗██╗   ██╗██╗ ██████╗███████╗
    ██╔══██╗██╔════╝██╔════╝ ██║██╔════╝╚══██╔══╝██╔════╝██╔══██╗    ██╔══██╗██╔════╝██║   ██║██║██╔════╝██╔════╝
    ██████╔╝█████╗  ██║  ███╗██║███████╗   ██║   █████╗  ██████╔╝    ██║  ██║█████╗  ██║   ██║██║██║     █████╗  
    ██╔══██╗██╔══╝  ██║   ██║██║╚════██║   ██║   ██╔══╝  ██╔══██╗    ██║  ██║██╔══╝  ╚██╗ ██╔╝██║██║     ██╔══╝  
    ██║  ██║███████╗╚██████╔╝██║███████║   ██║   ███████╗██║  ██║    ██████╔╝███████╗ ╚████╔╝ ██║╚██████╗███████╗
    ╚═╝  ╚═╝╚══════╝ ╚═════╝ ╚═╝╚══════╝   ╚═╝   ╚══════╝╚═╝  ╚═╝    ╚═════╝ ╚══════╝  ╚═══╝  ╚═╝ ╚═════╝╚══════╝
    */

    if($Action == "RegisterDevice")
    {
        $deviceId = $_GET['DeviceId'];
 
        $result = false;

        $driver = $conn->getAttribute(PDO::ATTR_DRIVER_NAME);

        // create table if not exists
        $tableName = "Device_" . $deviceId;
        $sql = "CREATE TABLE IF NOT EXISTS " . $tableName . "(";

        if ($driver === 'mysql') {
            $sql .= "Id INT AUTO_INCREMENT PRIMARY KEY,";
        }
        else if ($driver === 'sqlite') {
            $sql .= "Id INTEGER  PRIMARY KEY,";
        }
        
        $sql .= "Created DATETIME(6) NOT NULL,";
        $sql .= "LastUpdate DATETIME(6) NOT NULL,";
        $sql .= "Date DATE NOT NULL,";
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

        $rows = $resultG->fetchAll(PDO::FETCH_ASSOC);
        $rowCount = count($rows);  
        
        $content = false;
        if ($rowCount > 0)
        {
            $content = $rows[0];
            $jContent = $rows[0]["Content"];
        }

        $conn = null;
    
         
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
    ██████╗ ███████╗██╗     ███████╗████████╗███████╗████████╗ █████╗ ███████╗██╗  ██╗██████╗ ██╗   ██╗███████╗██╗██████╗ ███████╗
    ██╔══██╗██╔════╝██║     ██╔════╝╚══██╔══╝██╔════╝╚══██╔══╝██╔══██╗██╔════╝██║ ██╔╝██╔══██╗╚██╗ ██╔╝██╔════╝██║██╔══██╗██╔════╝
    ██║  ██║█████╗  ██║     █████╗     ██║   █████╗     ██║   ███████║███████╗█████╔╝ ██████╔╝ ╚████╔╝ ███████╗██║██║  ██║█████╗  
    ██║  ██║██╔══╝  ██║     ██╔══╝     ██║   ██╔══╝     ██║   ██╔══██║╚════██║██╔═██╗ ██╔══██╗  ╚██╔╝  ╚════██║██║██║  ██║██╔══╝  
    ██████╔╝███████╗███████╗███████╗   ██║   ███████╗   ██║   ██║  ██║███████║██║  ██╗██████╔╝   ██║   ███████║██║██████╔╝███████╗
    ╚═════╝ ╚══════╝╚══════╝╚══════╝   ╚═╝   ╚══════╝   ╚═╝   ╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝╚═════╝    ╚═╝   ╚══════╝╚═╝╚═════╝ ╚══════╝
    */ 
    if($Action == "DeleteTaskBySide")
    {
        $deviceId = $_GET['DeviceId'];
        $side = $_GET['Side'];
  

        $sqlS = "SELECT * FROM Tasks  WHERE DeviceId='$deviceId'";
    
        $queryresult = $conn->query($sqlS);
        
        $tasksArray = $queryresult->fetchAll(PDO::FETCH_ASSOC);

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

        $result = false;
        try {
            $conn->query($sqlU);
            $result = true;
        } 
        catch (PDOException $e) {
            echo "Errore PDO: " . $e->getMessage();
            $result = false;
        }
        

        $conn == null;

        $jresult = array('Action' => $Action,
                         'DeviceId' => $deviceId,
                         'Side' => $side,
                         'Result' => $result,
                         'Content' => $jContentStr
                        );
        echo json_encode($jresult);
        exit;
    }

    
  

    /*
    ██╗   ██╗██████╗ ██████╗  █████╗ ████████╗███████╗    ███████╗████████╗ █████╗ ████████╗██╗   ██╗███████╗
    ██║   ██║██╔══██╗██╔══██╗██╔══██╗╚══██╔══╝██╔════╝    ██╔════╝╚══██╔══╝██╔══██╗╚══██╔══╝██║   ██║██╔════╝
    ██║   ██║██████╔╝██║  ██║███████║   ██║   █████╗      ███████╗   ██║   ███████║   ██║   ██║   ██║███████╗
    ██║   ██║██╔═══╝ ██║  ██║██╔══██║   ██║   ██╔══╝      ╚════██║   ██║   ██╔══██║   ██║   ██║   ██║╚════██║
    ╚██████╔╝██║     ██████╔╝██║  ██║   ██║   ███████╗    ███████║   ██║   ██║  ██║   ██║   ╚██████╔╝███████║
     ╚═════╝ ╚═╝     ╚═════╝ ╚═╝  ╚═╝   ╚═╝   ╚══════╝    ╚══════╝   ╚═╝   ╚═╝  ╚═╝   ╚═╝    ╚═════╝ ╚══════╝
    */
    if ($Action == "UpdateStatus")
    {
        $side = $_POST['Side'];
        $userId = $_POST['UserId'];
    
        $sql = "UPDATE Status SET Side = $side  WHERE UserId=" .$userId;
        
        $ret = 0;
        if ($conn->query($sql) === TRUE) {
            $ret = 1;
        } 
    
        $conn = null;
        
        $result = array('Action' => $Action, 
                        'Ret' => $ret, 
                        'Side' => $side,
                        'UserId' => $userId);
        echo json_encode($result);
    }
}
 