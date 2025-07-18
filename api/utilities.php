<?php


 
 
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

?>