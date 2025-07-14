<?php

namespace PhpOffice;
include "../PHPExcel/PhpOffice/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;

ini_set('error_log', 'logs/excel.log');
  
//include "utilities.php";

function changeExtension($filename, $new_extension) 
{
   $info = pathinfo($filename);
   return $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . '.' . ltrim($new_extension, '.');
}


if(isset($_POST['Action'])) 
{
  $Action = $_POST['Action'];

  if ($Action == "ConvertToExcel")
  {
    $fileText = $_POST['FileText'];


    // read file input
     
    $json = file_get_contents("../reports/" . $fileText);
    
    $date = json_decode($json, true);
    

    // prepare file out
    $newFileName =  changeExtension($fileText, '.xlsx');
    $OutFilename = '../reports/' . $newFileName;

    $result = false;

    // create excel
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    $tasks = array();

    $filename = "stats20250204.txt";


    $dateString = "-";
    if (preg_match('/stats(\d{8})\.txt/', $fileText, $matches)) {
      $dateString = $matches[1];  
     
    }


         
    array_push($tasks, 'Name');
    array_push($tasks, 'Type');
    array_push($tasks, 'Hours');
   
  
    //header
    $sheet->fromArray($tasks, null, 'A1');
    

    for( $i = 0; $i < 3 /*count($list)*/; $i++)
    {
        $tasks = array();

        
        array_push($tasks, $dateString);
        array_push($tasks, 'D');
        array_push($tasks, 'A');
        
        $sheet->fromArray($tasks, null, 'A' . ($i + 2));
        
    }



    $writer = new Xlsx($spreadsheet);     
    $writer->save($OutFilename);
    
    $result = true;
    $jresult = array('Action' => $Action,
                     'FileText' => $fileText,
                     'InputData' => $date,
                     'OutFilename' => $OutFilename,
                     'Result' => $result);

    echo json_encode($jresult);

    exit;
  }
}




?>
