<?php

/*
function log($message)
{
   file_put_contents('./log_'.date("j.n.Y").'.log', $message . "\n", FILE_APPEND); 
}
*/

/********************** */

$pngOpCode = array(
   "LT" => "lavatrice.png",
   "LS" => "lavastoviglie.png",
   "DS" => "dosatori.png",
   "TC" => "test.png",
   "KCL" => "clean.png",
   "KIDCK" => "id.png",
   "KIDRS" => "id.png",
   "KBCLN" => "clean.png",
   "KTP" => "test.png" 
);

$arrayOpCode = array(
   "LT" => "Lavatrice",
   "LS" => "Lavastoviglie",
   "DS" => "Dosatori",
   "TC" => "Test",
   "KCL" => "Lavaggio",
   "KIDCK" => "Verifica ID Kaw",
   "KIDRS" => "Consegna ID Kaw",
   "KBCLN" => "Bay Cleaning Kaw",
   "KTP" => "Test Prodotto Kaw"
 );
 $arrayOpCodeFlipped = array_flip($arrayOpCode);

 $arrayCauseCode = array(
   "BLD" => "Montaggio",
   "FLR" => "Guasto",
   "MNT" => "Manutenzione",
   "TST" => "Test",
   "OTH" => "Altro"
 );
 $arrayCauseCodeFlipped = array_flip($arrayCauseCode);

 $activityNameByCode = array(
   "LT" => "JK",
   "LS" => "JK",
   "DS" => "JK",
   "TC" => "JK",
   "KCL" => "KAW",
   "KIDCK" => "KAW",
   "KIDRS" => "KAW",
   "KBCLN" => "KAW",
   "KTP" => "KAW"
 );


function convertFromDbToIsoDate($dbDate)
{
   $isoDate = str_replace(" ", "T", "$dbDate");
   $pos =  strrpos($isoDate, ":", 0);
   $isoDate = substr($isoDate, 0, $pos);
   return $isoDate;
}

function getDateFromIsoDate($isoDate)
{
   $pos =  strrpos($isoDate, "T", 0);
   
   $date = substr($isoDate, 0, $pos); 
   return $date;
}

function getTimeFromIsoDate($isoDate)
{
   $pos =  strrpos($isoDate, "T", 0);
   
   $time = substr($isoDate, $pos+1); 
   return $time;
}

function getISODate()
{
   $now = new DateTime('now', new DateTimeZone('Europe/Rome'));
   $date = $now->format('Y-m-d');
   $time = $now->format('H:i:s');
   $dt = $date . 'T' . $time;
   return $dt;
}

function getDayDate()
{
   $now = new DateTime('now',new DateTimeZone('Europe/Rome'));
   $date = $now->format('Y-m-d');
   return $date;
}

function getDateFromDateTimeDb($datetime)
{
   $dt = trim($datetime); 
   $arr = explode(" ", $dt);
   if (count($arr) == 1)
   {
      $arr = explode("T", $dt);
   } 
   return $arr[0];
}

function getTimeFromDateTimeDb($datetime)
{
   $dt = trim($datetime); 
   $arr = explode(" ", $dt);
   
   if (count($arr) == 1)
   {
      $arr = explode("T", $dt);
   }
   $time = $arr[1];

   if (substr_count($time, ':') == 2)
   {
      $arrtime = explode(":", $time);
      $time = $arrtime[0].":".$arrtime[1];
   } 

   return $time;
}

function getTechById($id)
{
   include "dbconfig.php";

   $sql = "SELECT * FROM Techs WHERE Id =" . $id ;
   $queryresult = $conn->query($sql);
   $conn->close();

   if ($queryresult === FALSE) {
      return null;
   }
   
   $rowCount = mysqli_num_rows($queryresult);   

   if ($rowCount > 0)
   {
      $techArray = array();
      while($r = $queryresult->fetch_assoc()) {
         $techArray[] = $r;
      }
      return $techArray[0];
   }
   return null;
} 

 

function getActivityById($id)
{
   include "dbconfig.php";

   $sql = "SELECT * FROM Activities WHERE Id=" . $id;

   $queryresult = $conn->query($sql);
   $conn->close(); 

   if ($queryresult === FALSE) {
      return null;
   }

   $rowCount = mysqli_num_rows($queryresult);   

   if ($rowCount > 0)
   {
      $activityList = array();
      while($r = $queryresult->fetch_assoc()) {
         $activityList[] = $r;
      }
      return $activityList[0];
   }
   return null;
}

function getCalendarById($id)
{
   include "dbconfig.php";

   $sql = "SELECT * FROM Calendar WHERE Id=" . $id;

   $queryresult = $conn->query($sql);
   $conn->close(); 

   if ($queryresult === FALSE) {
      return null;
   }

   $rowCount = mysqli_num_rows($queryresult);   

   if ($rowCount > 0)
   {
      $CalendarList = array();
      while($r = $queryresult->fetch_assoc()) {
         $CalendarList[] = $r;
      }
      return $CalendarList[0];
   }
   return null;
}
 
function getDestMailsByOperationCode($operationCode)
{
   include "dbconfig.php";

   if ($operationCode == null)
   {
      $destMail = "DestMailOffice";
   }
   else
   {
      $destMail = "DestMail" . $operationCode;
   }

   $sql = "SELECT * FROM Options WHERE Name='" . $destMail . "'" ;

   $queryresult = $conn->query($sql);
   $conn->close(); 

   
   if ($queryresult === FALSE) {
      return null;
   }
   $rowCount = mysqli_num_rows($queryresult);   

   if ($rowCount > 0)
   {
      $optionList = array();
      while($r = $queryresult->fetch_assoc()) {
         $optionList[] = $r;
      }
       
      return $optionList[0]["Value"];
   }
   return null;
}
 


function getTechIdByFullName($name, $surname)
{
   include "dbconfig.php";

   $sql = "SELECT * FROM Techs WHERE Name ='" . $name . "' AND Surname = '" . $surname ."'" ;
   $queryresult = $conn->query($sql);
   $conn->close();
   //return $sql;
   
   $rowCount = mysqli_num_rows($queryresult);   
 
   if ($rowCount > 0)
   {
      $techArray = array();
      while($r = $queryresult->fetch_assoc()) {
         $techArray[] = $r;
      }
     // $conn->close();
      return $techArray[0]["Id"];
   }
   //$conn->close();
   return null;
}
 


function updateResourceDb($pathfile, $index, $resourceType, $label, $description, $activityId, $reportId)
{
   include "dbconfig.php";

   $ext = pathinfo($pathfile, PATHINFO_EXTENSION);

   $description = addslashes($description);

   $sql = "INSERT INTO Resources(PathFile, FileType, ResourceType, IndexResource, Label, Description, ActivityId, ReportId) VALUES ('" . $pathfile . "','" . $ext . "','$resourceType','" . $index . "','" . $label . "' ,'" . $description . "', '". $activityId . "', '". $reportId . "' )";
   $result = $conn->query($sql);

   $conn->close();

   $jResult = array('retdb' => $result, 
                    'pathfile' => $pathfile, 
                    'ext' => $ext, 
                    'activityid' => $activityId,
                    'label'=>$label, 
                    'description'=>$description, 
                    'indexfoto' =>  $index, 
                    'reportId' =>  $reportId, 
                    'sql' =>$sql);
   return json_encode($jResult);
}


function updateReportDb($appointmentDate, $techname, $customer, $company, $address, $pathfile, $opCodePage, $causeCode, $hours, $finalDescription, $pmUser, $breakCause, $breakDescription, $nextAppointmentDateTime, $externalReportId, $os, $appVersion)
{
   include "dbconfig.php";

   $ExceptionMessage = "";

   try 
   {
      $dt = getISODate();
   
      $finalDescription = addslashes($finalDescription);

      if ($nextAppointmentDateTime != 'null') {
         $nextAppointmentDateTime = "'" . $nextAppointmentDateTime . "'";
      }

      if ($breakCause != 'null') {
         $breakCause = "'" . $breakCause . "'";
      }

      $sql = "INSERT INTO Reports(UploadDateTime, AppointmentDateTime, TechName, Customer, Company, Address, PathFile, OperationCode, CauseCode, Hours, Description, PMUser, BreakCause, BreakDescription, NextAppointmentDateTime, FromDevice, FromVersion, ReportId) ";
      $sql .=" VALUES ('$dt','$appointmentDate', '$techname','$customer','$company', '$address', '$pathfile', '$opCodePage','$causeCode','$hours', '$finalDescription', '$pmUser', $breakCause, '$breakDescription', $nextAppointmentDateTime, '$os', '$appVersion', $externalReportId)";
      $result = $conn->query($sql);
      $reportIdTest = $conn->insert_id; 

      $conn->close();
   }
   catch (Exception $e) 
   {
      $ExceptionMessage = $e->getMessage();   
   }

   $arrayResult = array('Function' => 'updateReportDb', 
                        'Sql' => $sql,
                        'InsertDate' => $dt,
                        'ExceptionMessage' => $ExceptionMessage,
                        'Result' => $result,
                        'ReportId' => $reportIdTest,
                        'ExternalReportId' => $externalReportId);

   return json_encode($arrayResult);
}

function SendMailWithAttach($from, $to, $subject, $message, $mailAttach)
{  

   $mailBoundary = md5(time());

   

   // To send HTML mail, the Content-type header must be set
   $headers[] = 'MIME-Version: 1.0';
   $headers[] = "Content-type: multipart/mixed; boundary=\"$mailBoundary\"";

   // Additional headers
   $headers[] = 'From: ' . $from . ' <assistenza@cube365.com>';
   
   $mailHead = implode("\r\n", $headers);


   $headers = 'From: assistenza@cube365.com' . "\r\n" .
    'Reply-To: assistenza@cube365.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

   // (C) DEFINE THE EMAIL MESSAGE
   $mailBody = implode("\r\n", [
      "--$mailBoundary",
      "Content-type: text/html; charset=utf-8",
      "",
      $message
   ]);

  // (D) MANUALLY ENCODE & ATTACH THE FILE
   $mailBody .= implode("\r\n", [
      "",
      "--$mailBoundary",
      "Content-Type: application/octet-stream; name=\"". basename($mailAttach) . "\"",
      "Content-Transfer-Encoding: base64",
      "Content-Disposition: attachment",
      "",
      chunk_split(base64_encode(file_get_contents($mailAttach))),
      "--$mailBoundary--"
   ]);


  $from = '-f' . $from . '@cube365.com';
 
  $ret = mail($to, $subject, $mailBody, $mailHead);
  
  return $ret;
}
  
function replaceFileExtension($filename, $new_extension) 
{
   $info = pathinfo($filename);
   return $info['filename'] . '.' . $new_extension;
}

function getGui()
{
   return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)); 
}

 
function getElapsedDay($date)
{
   $now = time();
   $your_date = strtotime($date);
   $datediff = $now - $your_date;
   $daydiff = round($datediff / (60 * 60 * 24));
   return $daydiff;
}

function removeSecondsFromTime($time)
{
   $arrTime = explode(":", $time);
   if (count($arrTime) == 3) {
      $time = $arrTime[0] . ':' . $arrTime[1];
   }
   return $time;
}

function isMaintenanceActivityToShow($targetdate, $intervalDay)
{
   if ($targetdate == null)
   {
      return true;
   }
   $newTargetDate = new DateTime('now');
   $newTargetDate->modify('+' . $intervalDay .' day'); 
   $newTargetDate = $newTargetDate->format('Y-m-d h:i:s');

   return ($targetdate < $newTargetDate);
}

function replaceExtension($filename, $new_extension) 
{
   $info = pathinfo($filename);
   return $info['filename'] . '.' . $new_extension;
}

function convertInPdf($imagefile)
{
   if (!extension_loaded('imagick'))
   {
      //echo 'imagick not installed';
      return 'imagick not installed';
   }
   
   try {
       
      $image = new Imagick();
      $retRead = $image->readImage($imagefile);
      $retWrite = $image->writeImages($imagefile.".pdf", false);
 
   }
   catch (Exception $e) {
      return 'Caught exception: ' . $e->getMessage();   
   }

   return $imagefile;
}

function generateAuthenticationCode()
{
   return rand(1111,9999);
}

 
function prepareLinkPhone($number)
{
  return "tel:" . $number;
}

function prepareLinkWhatsapp($number)
{
  return "https://wa.me/" . $number;
}

function send_whatsapp($message="Test")
{
  $phone="+393346710868";  
  $apikey="9901042";       

  $url='https://api.callmebot.com/whatsapp.php?source=php&phone='.$phone.'&text='.urlencode($message).'&apikey='.$apikey;

  if($ch = curl_init($url))
  {
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $html = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return (int) $status;
  }
  else
  {
    return false;
  }
}

function setNextMaintenanceDate($dbConn, $nextMaintenanceDate, $activityId, 
                                $technamedotted, $insertDate, $opCodePage, $targetAddress, $targetPhone, $customer, $company, $reference, $name, $description, $techId, $customerId)
{
    
   $sqlMnt = "";
   $sqlMntUpd = "";
   $resultMnt = "";
   $resultMntUpd = "";
   $newActivityId = "";

   if ($activityId != 0)
   {
      $fieldsActivity = 'InsertDate, Name, Surname, Email, OperationCode, TargetCauseCode, TargetAddress, TargetDistrict, TargetPhone, TargetDate, TargetCustomerName, TargetCompanyName, TargetReferenceUser, TargetCompanyUser, TargetDescription, SendTarget, AcceptTarget, CompleteTarget, PMUser, Notify, ReportId, TechId, CustomerId, CustomerIsReseller, CustomerIsNew, Presence, SelectedDevices, VisibleForPM, SendedMailForNew, Changes';

      // clone row
      $sqlMnt = 'INSERT INTO Activities (' . $fieldsActivity . ') ';
      $sqlMnt.= 'SELECT ' . $fieldsActivity . ' ';
      $sqlMnt.= 'FROM Activities WHERE Id = ' . $activityId;
      $resultMnt = $dbConn->query($sqlMnt);

      $newActivityId = $dbConn->insert_id;

      $sqlMntUpd = "UPDATE Activities SET InsertDate = '$insertDate', TargetCauseCode = 'MNT', TargetDate = '$nextMaintenanceDate', SendTarget = 0, AcceptTarget = 0, CompleteTarget = 0, PMUser = 'SYSTEM' ";
      $sqlMntUpd.= "WHERE Id  =" . $newActivityId ;
      $resultMntUpd = $dbConn->query($sqlMntUpd); 
   }
   else
   {
      $fullname = explode(".", $technamedotted);
      
      $sqlMnt = "INSERT INTO Activities (InsertDate, Name, Surname, Email, OperationCode, TargetCauseCode, TargetAddress, TargetDistrict, TargetPhone, TargetDate, TargetCustomerName, TargetCompanyName, TargetReferenceUser, TargetCompanyUser, TargetDescription, SendTarget, AcceptTarget, CompleteTarget, PMUser, Notify, ReportId, TechId, CustomerId, CustomerIsReseller, CustomerIsNew) ";
      $sqlMnt.= "VALUES('$insertDate','$fullname[0]', '$fullname[1]', null, '$opCodePage', 'MNT', '$targetAddress', '--', '$targetPhone', '$nextMaintenanceDate', '$customer', '$company', '$reference', '$name', '$description', 0, 0, 0, 'SYSTEM', null, null, '$techId', '$customerId', null, false)";
      $resultMnt = $dbConn->query($sqlMnt); 
   }

   return [$newActivityId, $sqlMnt, $sqlMntUpd, $resultMnt, $resultMntUpd];
}

/////////////////// cube ////




?>