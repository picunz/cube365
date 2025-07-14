<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__.'/PHPMailer/src/Exception.php';
require __DIR__.'/PHPMailer/src/PHPMailer.php';
require __DIR__.'/PHPMailer/src/SMTP.php';


include_once "utilities.php";


 

/***  functions ***/

function SendMailToUser($userEmail, $userFullname, $subject, $body)
{
  $mail = new PHPMailer(TRUE);

  try 
  {
    $mail->setFrom('kiand64@gmail.com', 'Cube 365');
    $mail->addAddress($userEmail, $userFullname);
    $mail->Subject = $subject;

    $mail->isHTML(true);

    $mail->AddEmbeddedImage('imgs/mail-header.png', 'logoimg');

    $htmlBody= "";

    $htmlBody.= "<html><body>";
    $htmlBody.= "<img src=\"cid:logoimg\" width=500>";
    $htmlBody.= "<p>" . $body . "</p></body></html>";
    $htmlBody.= "</body></html>";

    $mail->Body = $htmlBody;

    
    if (!$mail->send())
    {
      return $mail->ErrorInfo;
    }
    return $mail->ErrorInfo;
  }
  catch (Exception $e)
  {
    return $e->errorMessage();
  }
}

/***  web api ***/

if(isset($_POST['Action'])) 
{ 
  $Action = $_POST['Action'];

  if ($Action == "SendMailWithCodeToUser")
  {
    $code = $_POST['code'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    
    $userFullName = $name . ' ' . $surname;
    $ret = SendMailToUser($email, $userFullName,
                          "Autenticazione App Cube365", 
                          "Il codice di autenticazione per l'utente " . $name . " " . $surname . " &egrave;: " . $code);
    
    $result = array('Action' => $Action,
                    'Code' => $code,
                    'Email' => $email,
                    'Name' => $name,
                    'Surname' => $surname,
                    'Result' => $ret
                   );

    echo json_encode($result);

    exit;
  }

  if ($Action == "SendMailWithLinkToUser")
  {
    $userMail = $_POST['UserMail'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    
    $linkThisApp = 'https://www.kiand.it/cube365/index.html';
    $userFullName = $name . ' ' . $surname;
    $ret = SendMailToUser($userMail, $userFullName,
                          "App Cube365", 
                          "Ciao " . $name . " " . $surname . ", puoi navigare l'app anche dal tuo computer, cliccando su questo link: ' . $linkThisApp" );
    
    $result = array('Action' => $Action,
                    'Email' => $userMail,
                    'Name' => $name,
                    'Surname' => $surname,
                    'LinkThisApp' => $linkThisApp,
                    'Result' => $ret
                   );

    echo json_encode($result);

    exit;
  }

}







   