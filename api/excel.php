<?php

namespace PhpOffice;
include "../PHPExcel/PhpOffice/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use PhpOffice\PhpSpreadsheet\IOFactory;

ini_set('error_log', 'logs/excel.log');
  

function changeExtension($filename, $new_extension) 
{
   $info = pathinfo($filename);
   return $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . '.' . ltrim($new_extension, '.');
}


if(isset($_POST['Action'])) 
{
  $Action = $_POST['Action'];

  /*
 ██████╗ ██████╗ ███╗   ██╗██╗   ██╗███████╗██████╗ ████████╗    ████████╗ ██████╗     ███████╗██╗  ██╗ ██████╗███████╗██╗     
██╔════╝██╔═══██╗████╗  ██║██║   ██║██╔════╝██╔══██╗╚══██╔══╝    ╚══██╔══╝██╔═══██╗    ██╔════╝╚██╗██╔╝██╔════╝██╔════╝██║     
██║     ██║   ██║██╔██╗ ██║██║   ██║█████╗  ██████╔╝   ██║          ██║   ██║   ██║    █████╗   ╚███╔╝ ██║     █████╗  ██║     
██║     ██║   ██║██║╚██╗██║╚██╗ ██╔╝██╔══╝  ██╔══██╗   ██║          ██║   ██║   ██║    ██╔══╝   ██╔██╗ ██║     ██╔══╝  ██║     
╚██████╗╚██████╔╝██║ ╚████║ ╚████╔╝ ███████╗██║  ██║   ██║          ██║   ╚██████╔╝    ███████╗██╔╝ ██╗╚██████╗███████╗███████╗
 ╚═════╝ ╚═════╝ ╚═╝  ╚═══╝  ╚═══╝  ╚══════╝╚═╝  ╚═╝   ╚═╝          ╚═╝    ╚═════╝     ╚══════╝╚═╝  ╚═╝ ╚═════╝╚══════╝╚══════╝
                                                                                                                               
  */

  if ($Action == "ConvertToExcel")
  {
    $table = $_POST['Table'];

    $list = json_decode($table);

     
    // prepare file out
    $OutFilename = '../excels/archive.xlsx';

    $result = false;

    // create excel
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    $tasks = array();
 

 
    array_push($tasks, 'Date');
    array_push($tasks, 'LastUpdate');
    array_push($tasks, 'Face');
    array_push($tasks, 'Task');
    array_push($tasks, 'Type');
    array_push($tasks, 'ElapsedTime');
   
  
    //header
    $sheet->fromArray($tasks, null, 'A1');
    

    $precDate = null;
    $useAltColor = false;

    for( $i = 0, $line = 0; $i < count($list); $i++)
    {
        

        $activities = json_decode($list[$i]->{'Activities'});
        
        $date = $list[$i]->{'Date'};

        for($t = 0; $t < count($activities); $t++)
        {
          $tasks = array();

          array_push($tasks, $list[$i]->{'Date'});
          array_push($tasks, $list[$i]->{'LastUpdate'});
          array_push($tasks, $activities[$t]->{'Side'});
          array_push($tasks, $activities[$t]->{'Name'});
          array_push($tasks, $activities[$t]->{'Type'});
          array_push($tasks, $activities[$t]->{'ElapsedTime'});
          
          //array_push($tasks, 'a');

          $line++;

          $index = $line + 2;

          $sheet->fromArray($tasks, null, 'A'. $index);

          if ($date !== $precDate) {
              $useAltColor = !$useAltColor;
              $precDate = $date;
          }

          $color = 'FFFFFF';
          if ($useAltColor) {
            $color = '29b6f6';
          }
          $sheet->getStyle("A" . $index . ":F"  . $index)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
          
        }
        
        
        
    }

    foreach (range('A', 'C') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }
          



    $writer = new Xlsx($spreadsheet);     
    $writer->save($OutFilename);
    
    $result = true;
    $jresult = array('Action' => $Action,
                     'Table' => json_decode($table),
                     'List' => $list,
                     'OutFilename' => $OutFilename,
                     'Debug' => $activities,
                     'Result' => $result);

    echo json_encode($jresult);

    exit;
  }
}
