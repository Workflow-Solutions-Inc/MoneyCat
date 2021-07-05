<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/paymentchannel.xml');
  $newval = 0;
  $arrdata = explode("@", $_GET['code']);

  $Payments = $xml->getElementsByTagName("Payment");
  foreach ($Payments as $Payment) {
    // code...
      $NewData = $xml->createElement("Payment");
  
      $NewData->appendChild($xml->createElement("lmscode",$arrdata[0]));
      $NewData->appendChild($xml->createElement("xerocode",$arrdata[1]));
      $NewData->appendChild($xml->createElement("xeroname",$arrdata[2]));
      
      $xml->getElementsByTagName("Payments")->item(0)->replaceChild($NewData,$Payment);
      $xml->save("../xml/paymentchannel.xml");
    

  }

  

  
?>