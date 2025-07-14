<?php

// createCustomerExcel.php

namespace PhpOffice;
include "autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;
 
 

if(isset($_POST['Action'])) 
{
  $Action = $_POST['Action'];

  if ($Action == "CustomersExcel")
  {
    $customerList = $_POST['CustomerList'];
    $OutFilename = $_POST['OutFilename'];
           
   
    $list = json_decode($customerList);

    
    //$spreadsheet = IOFactory::load('customers.xlsx');

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();


    // info customer

    $dateStr = date("m") . "/" .  date("d") . "/" . date("Y");

    

    
    $row = 0;
     

    // info h20
    

    // header
    $customers = array();

         
    array_push($customers, 'Cliente');
    array_push($customers, 'Struttura');
    array_push($customers, 'Indirizzo');
    array_push($customers, 'Telefono');
    array_push($customers, 'Referente cliente');
    array_push($customers, 'Referente struttura');
    array_push($customers, 'Note');
    array_push($customers, 'Intervallo di manutenzione');
    array_push($customers, 'Rivenditore');
    array_push($customers, 'Tecnico intervenuto');
    array_push($customers, 'Numero lavatrici');
    array_push($customers, 'Numero lavastoviglie');
    array_push($customers, 'Numero dosatori');
  
    $sheet->fromArray($customers, null, 'A1');
    

    for( $i = 0; $i < count($list); $i++)
    {
        $customers = array();

        /*
        array_push($customers, $list[$i]->{'CustomerName'});
        array_push($customers, $list[$i]->{'CompanyName'});

        array_push($customers, $list[$i]->{'Address'});
        array_push($customers, $list[$i]->{'Phone'});
        array_push($customers, $list[$i]->{'ReferenceName'});
        array_push($customers, $list[$i]->{'Name'});
        array_push($customers, $list[$i]->{'Description'});
        array_push($customers, $list[$i]->{'MaintenanceInterval'});

        

        array_push($customers, $list[$i]->{'Reseller'});
        array_push($customers, $list[$i]->{'TechUpdated'});
        array_push($customers, $list[$i]->{'CountLT'});
        array_push($customers, $list[$i]->{'CountLS'});
        array_push($customers, $list[$i]->{'CountDS'});
 
        $sheet->fromArray($customers, null, 'A' . ($i + 2));
        */
    }


    $Filename = $OutFilename;
    $OutFilename = "../../" . $OutFilename;
    
    $writer = new Xlsx($spreadsheet);     
    $writer->save($OutFilename);
    
    
    $result = array('Action' => $Action,
                    'FileName' => $Filename,
                    'OutFilename' => $OutFilename,
                    'CustomerList' => $customerList,
                    
                  
                    'List' => $list
                );

    echo json_encode($result);
    exit;
  }
}

?>
