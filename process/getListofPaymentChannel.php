<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/paymentchannellist.xml');
  $output = '';

  $accounts = $xml->getElementsByTagName("Loan");
  foreach ($accounts as $account) {
    $code = $account->getElementsByTagName("lmscode")->item(0)->nodeValue;
    $xcode = $account->getElementsByTagName("xerocode")->item(0)->nodeValue;
    $xname = $account->getElementsByTagName("xeroname")->item(0)->nodeValue;
    $output .= '<option value="'.$code.'@'.$xcode.'@'.$xname.'">'.$code.' - '.$xname.'</option>';
  }

  echo $output;

  

  
?>