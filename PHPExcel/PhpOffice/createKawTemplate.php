<?php
namespace PhpOffice;
include "autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;


if(!empty($_POST)){
    //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //Setting name

    
    $today = date("Ymd");
    $CntDisposition = "Content-Disposition: attachment;filename=\"";
    $CntDisposition = $CntDisposition . $today . "\"_output_file.xlsx";
    header($CntDisposition);
    header('Cache-Control: max-age=0');
    
    
    //$spreadsheet = new Spreadsheet();

    //$filename = require __DIR__ . '/file.xlsx';

    $spreadsheet = IOFactory::load('KawTemplate.xlsx');
    
    $sheet = $spreadsheet->getActiveSheet();
    
    // Store data from the activeSheet to the varibale in the form of Array
    //$data = array(1,$sheet->toArray(null,true,true,true));
    //var_dump($data);
    $sheet->setCellValue('C5', 'Via Albero 4');
    $sheet->setCellValue('C29', '248 U-Wax');
    $sheet->setCellValue('H29', '100');
    $sheet->setCellValue('I29', '100');

    
 
 
   
    //Saving 
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output'); 
}
else{
    echo "no data posted4";
/* 
    $spreadsheet = new Spreadsheet();
 
    $sheet = $spreadsheet->getActiveSheet();
    
     
    $sheet->setCellValue('A1', 'a');
    $sheet->setCellValue('B1', 'b');
        
    
    $writer = new Xlsx($spreadsheet);
     
    $writer->save('out.xlsx');
*/
    //////////////////////////////////////////


    //$spreadsheet = IOFactory::load('file.xlsx');
    $spreadsheet = IOFactory::load('KawTemplate.xlsx');
    
    $sheet = $spreadsheet->getActiveSheet();
    
    // Store data from the activeSheet to the varibale in the form of Array
    //$data = array(1,$sheet->toArray(null,true,true,true));
    //var_dump($data);
    $sheet->setCellValue('C5', 'Via Albero 4');
    $sheet->setCellValue('C29', '248 U-Wax');
    $sheet->setCellValue('H29', '100');
    $sheet->setCellValue('A28', 'TEs');

    

    $writer = new Xlsx($spreadsheet);     
    $writer->save('out2.xlsx');

    echo 'ok';
}

?>
