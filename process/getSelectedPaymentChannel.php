<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/paymentchannel.xml');
  $output = '';

  $Payments = $xml->getElementsByTagName("Payment");
  foreach ($Payments as $Payment) {
    $code = $Payment->getElementsByTagName("lmscode")->item(0)->nodeValue;
    $xcode = $Payment->getElementsByTagName("xerocode")->item(0)->nodeValue;
    $xname = $Payment->getElementsByTagName("xeroname")->item(0)->nodeValue;
    $output .= $code.'@'.$xcode.'@'.$xname;
  }

  echo $output;

  

  
?>