<?php

// createKawExcel.php

namespace PhpOffice;
include "autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;
 

function test42()
{
    return 42;
}


if(isset($_POST['Action'])) 
{
  $Action = $_POST['Action'];

  if ($Action == "GenerateExcel")
  {
    $OutFilename = $_POST['OutFilename'];
    $ktpModules = $_POST['ktpModules'];
    $ktpH2OInfo = $_POST['ktpH2OInfo'];
    $customer = $_POST['customer'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $description = $_POST['description'];
    $techname = $_POST['techname'];
    

    $jsonktpModules = json_decode($ktpModules);
    $jsonktpH2OInfo = json_decode($ktpH2OInfo);

    $spreadsheet = IOFactory::load('KawTemplate.xlsx');
    
    $sheet = $spreadsheet->getActiveSheet();


    // info customer

    $dateStr = date("m") . "/" .  date("d") . "/" . date("Y");

    $sheet->setCellValue('C4', $customer);
    $sheet->setCellValue('C5', $address);
    $sheet->setCellValue('C9', $phone);
    $sheet->setCellValue('A19', $description);
    $sheet->setCellValue('I15', $techname);
    $sheet->setCellValue('I16', $dateStr);

    // data
    $startCellPortalTunnel = 28;
    $startCellPiste = 47;

    $id = 0;
    $row = 0;
    for($i = 0; $i < count($jsonktpModules); $i++ )
    {
        $type = $jsonktpModules[$i]->{'type'};
        //$id = $jsonktpModules[$i]->{'id'}; 
        $moduleName = $jsonktpModules[$i]->{'name'};
        
        $offset = ($type == "1" || $type == "2") ? $startCellPortalTunnel : $startCellPiste;

        

        /*
        if ($type == "1" || $type == "2")
        {
            $row = $startCellPortalTunnel + $id*2;
        }
        else if ($type == "3")
        {
            $row = $startCellPiste + $id*2;
        }
        */

        $functions = $jsonktpModules[$i]->{'functions'};

        for($j = 0; $j < count($functions); $j++ )
        {
            $row = $offset + $id*2;
            
            $functionName = $functions[$j]->{'function'};
            $addr = "A" . (string)$row;
            $sheet->setCellValue($addr, $functionName);

            $curProduct = $functions[$j]->{'curProduct'};
            $addr = "C" . (string)$row;
            $sheet->setCellValue($addr, $curProduct);

            $kProduct = $functions[$j]->{'kProduct'};
            $addr = "C" . (string) ($row+1);
            $sheet->setCellValue($addr, $kProduct);

            $curPreDil = $functions[$j]->{'curPreDil'};
            $addr = "F" . (string) ($row);
            if ($curPreDil == "") {
                $curPreDil = "pur";
            }
            $sheet->setCellValue($addr, $curPreDil);

            $kPreDil = $functions[$j]->{'kPreDil'};
            $addr = "F" . (string) ($row+1);
            if ($kPreDil == "") {
                $kPreDil = "pur";
            }
            $sheet->setCellValue($addr, $kPreDil);

            $doserSystem = $functions[$j]->{'doserSystem'};
            $addr = "G" . (string) ($row);
            $sheet->setCellValue($addr, $doserSystem);

            $curPercentile = $functions[$j]->{'curPercentile'};
            $addr = "H" . (string) ($row);
            $sheet->setCellValue($addr, $curPercentile);

            $kPercentile = $functions[$j]->{'kPercentile'};
            $addr = "H" . (string) ($row+1);
            $sheet->setCellValue($addr, $kPercentile);

            $curCons = $functions[$j]->{'curCons'};
            $addr = "I" . (string)$row;
            $sheet->setCellValue($addr, $curCons);

            $kCons = $functions[$j]->{'kCons'};
            $addr = "I" . (string) ($row+1);
            $sheet->setCellValue($addr, $kCons);

            // out of border

            $addr = "M" . (string) ($row);
            $sheet->setCellValue($addr, $moduleName);

            $note = $functions[$j]->{'note'};
            $addr = "M" . (string) ($row+1);
            $sheet->setCellValue($addr, $note);

            $id++;
        }

        /*
        $function = $jsonktpModules[$i]->{'function'};
        $addr = "A" . (string)$row;
        $sheet->setCellValue($addr, $function);

        $curProduct = $jsonktpModules[$i]->{'curProduct'};
        $addr = "C" . (string)$row;
        $sheet->setCellValue($addr, $curProduct);

        $kProduct = $jsonktpModules[$i]->{'kProduct'};
        $addr = "C" . (string) ($row+1);
        $sheet->setCellValue($addr, $kProduct);

        $curPreDil = $jsonktpModules[$i]->{'curPreDil'};
        $addr = "F" . (string) ($row);
        if ($curPreDil == "") {
            $curPreDil = "pur";
        }
        $sheet->setCellValue($addr, $curPreDil);

        $kPreDil = $jsonktpModules[$i]->{'kPreDil'};
        $addr = "F" . (string) ($row+1);
        if ($kPreDil == "") {
            $kPreDil = "pur";
        }
        $sheet->setCellValue($addr, $kPreDil);

        $doserSystem = $jsonktpModules[$i]->{'doserSystem'};
        $addr = "G" . (string) ($row);
        $sheet->setCellValue($addr, $doserSystem);

        $curPercentile = $jsonktpModules[$i]->{'curPercentile'};
        $addr = "H" . (string) ($row);
        $sheet->setCellValue($addr, $curPercentile);

        $kPercentile = $jsonktpModules[$i]->{'kPercentile'};
        $addr = "H" . (string) ($row+1);
        $sheet->setCellValue($addr, $kPercentile);

        $curCons = $jsonktpModules[$i]->{'curCons'};
        $addr = "I" . (string)$row;
        $sheet->setCellValue($addr, $curCons);

        $kCons = $jsonktpModules[$i]->{'kCons'};
        $addr = "I" . (string) ($row+1);
        $sheet->setCellValue($addr, $kCons);
        */
    }

    // info h20
    
    $sheet->setCellValue('J19', $jsonktpH2OInfo->{'conductor'});
    $sheet->setCellValue('J20', $jsonktpH2OInfo->{'durezza'});

    $sheet->setCellValue('J21', $jsonktpH2OInfo->{'ph'});
    $sheet->setCellValue('J22', $jsonktpH2OInfo->{'recycle'});
    $sheet->setCellValue('J23', $jsonktpH2OInfo->{'type'});
    $sheet->setCellValue('J24', $jsonktpH2OInfo->{'sweet'});
    

    $OutFilename = "../../" . $OutFilename;
    $writer = new Xlsx($spreadsheet);     
    $writer->save($OutFilename);
    
    
    $result = array('Action' => $Action,
                    'LastRow' => $addr,
                    'LastVal' => $function,
                    'OutFilename' => $OutFilename,
                    'JsonktpModules' => $jsonktpModules,
                    'jsonktpH2OInfo' => $jsonktpH2OInfo,
                    'Customer' => $customer,
                    'Address' => $address,
                    'Phone' => $phone,
                    'Longdesc' => $description,
                    'Techname' => $techname
                );

    echo json_encode($result);
    exit;
  }
}

?>
