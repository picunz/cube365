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

        $tableA = "Activities";
        $sqlA = "";

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

/*
            $sqlA = "INSERT INTO $tableA (Date, DeviceId, Details, LastUpdate, Created)";
            $sqlA .= " VALUES ('$currentDate', '$deviceId', '$jActivities', '$now', '$now')";
            $resultA = $conn->query($sqlA);
            $jActivities = json_decode("[]", true);
            */
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

        // update Activities table
        $sqlUAct = "UPDATE $tableA SET Details = '$activities', LastUpdate = '$now' WHERE Date='$currentDate' AND DeviceId='$deviceId'";
        $resultUAct = $conn->query($sqlUAct);

        // *** update state in State table

        // check if exists
        $sqlCe = "SELECT * FROM Status WHERE DeviceId ='$deviceId'";
        $queryCheck = $conn->query($sqlCe);
        $rows = $queryCheck->fetchAll(PDO::FETCH_ASSOC);
        $rowDevicesCount = count($rows); 

        if ($rowDevicesCount > 0) {

            // update existing row
            $sqlUs = "UPDATE Status SET Side = '$state', Timestamp = '$now' WHERE DeviceId='$deviceId'";
        } else {
            // insert new row
            $sqlUs = "INSERT INTO Status (DeviceId, Side, Timestamp) VALUES ('$deviceId', '$state', '$now')";
        }
        
        $retUs = 0;
        if ($conn->query($sqlUs) === TRUE) {
            $retUs = 1;
        } 
    
        $conn = null;
         

        $jresult = array('Action' => $Action,
                        'State' => $state,
                        'sqlA' => $sqlA,
                        'jActivities' => $jActivities,
                        'DeviceId' => $deviceId,
                        'rowDevicesCount' => $rowDevicesCount,
                        'ResultUpdate' => $resultU,
                        'ResultUpdateState' => $retUs,
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


       
        $driver = $conn->getAttribute(PDO::ATTR_DRIVER_NAME);

        $sql = "";
        if ($driver === 'mysql') {
            $sql = "
                CREATE TABLE IF NOT EXISTS Tasks (
                    Id INT AUTO_INCREMENT PRIMARY KEY,
                    DeviceId VARCHAR(255) UNIQUE,
                    Content JSON,
                    Description TEXT
                )
            ";
        } elseif ($driver === 'sqlite') {
            $sql = "
                CREATE TABLE IF NOT EXISTS Tasks (
                    Id INTEGER PRIMARY KEY AUTOINCREMENT,
                    DeviceId TEXT UNIQUE,
                    Content JSON,
                    Description TEXT
                )
            ";
        } else {
            throw new Exception('Database non supportato: ' . $driver);
        }

        $conn->exec($sql);

        $sqlS = "SELECT * FROM Tasks  WHERE DeviceId='$deviceId'";
        $queryresult = $conn->query($sqlS);
        $tasksArray = $queryresult->fetchAll(PDO::FETCH_ASSOC);

        if (count($tasksArray) === 0) {
            $insert = $conn->prepare("INSERT INTO Tasks (DeviceId, Content) VALUES (:deviceId, :content)");
            $insert->execute([
                ':deviceId' => $deviceId,
                ':content' => '{}'
            ]);
        }

        $content = '{}';
        if (count($tasksArray) > 0 && isset($tasksArray[0]["Content"])) {
            $content = $tasksArray[0]["Content"];
        }
        
        
        $jContent = json_decode($content, true);

        // check if task with same Side already exists
        $existingTaskIndex = -1;
        foreach ($jContent as $index => $task) {
            if ($task['Side'] === $Side) {
                $existingTaskIndex = $index;
                break;
            }
        }

        // update existing task or add new one
        if ($existingTaskIndex !== -1) {
            $jContent[$existingTaskIndex] = $jTask;
        } else {
            array_push($jContent, $jTask);
        }

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
                        'DeviceId' => $deviceId
                 );
        echo json_encode($jresult);
        exit;
    }

     /*

         .d8888b.                         888      888                       
        d88P  Y88b                        888      888                       
        Y88b.                             888      888                       
         "Y888b.    .d88b.  88888b.   .d88888      888      .d88b.   .d88b.  
            "Y88b. d8P  Y8b 888 "88b d88" 888      888     d88""88b d88P"88b 
              "888 88888888 888  888 888  888      888     888  888 888  888 
        Y88b  d88P Y8b.     888  888 Y88b 888      888     Y88..88P Y88b 888 
         "Y8888P"   "Y8888  888  888  "Y88888      88888888 "Y88P"   "Y88888 
                                                                        888 
                                                                  Y8b d88P 
                                                                   "Y88P"  
                                                                                      
    */

    else if ($Action == "SendLog")
    {
        $deviceId = $_POST['DeviceId'];
        $level = $_POST['Level'];
        $message = $_POST['Message'];
        $value = $_POST['Value'];
    
        date_default_timezone_set('Europe/Rome');
        $currentDate = date('Ymd');
        $now = date('Y-m-d H:i:s');

        $sql = "INSERT INTO Logs (DeviceId, Level, Message, Value, Timestamp)";
        $sql .= " VALUES ('$deviceId', '$level', '$message', '$value', '$now')";
        
        $ret = 0;
        $resultInsert = $conn->query($sql);
        if ($resultInsert !== false) {
            $ret = 1;
        }

        $id = $ret === 1 ? (int)$conn->lastInsertId() : 0;
    
        $conn = null;
        
        $result = array('Action' => $Action, 
                        'Ret' => $ret, 
                        'Now' => $now,
                        'Id' => $id,
                        'Level' => $level,
                        'Message' => $message,
                        'Value' => $value,
                        'DeviceId' => $deviceId);
        echo json_encode($result);
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
            $result =  $conn->errorInfo();
        }

        // insert null task is not exists
        $sqlT = "INSERT IGNORE INTO Tasks (DeviceId, Content) VALUES ('$deviceId', '{}')";

        if ($conn->query($sqlT) === TRUE) {
            $resultTask = true;
        } else {
            $resultTask =  $conn->errorInfo();
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

 
}
 