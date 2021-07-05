<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/accounts.xml');

  $accounts = $xml->getElementsByTagName("Account");


  $code = $_GET["code"];
  $newcode = explode("-", $code);
  $flag = 0;

  foreach ($accounts as $value) {
    // code...
    if($newcode[1] == $value->getElementsByTagName("accountcode")->item(0)->nodeValue){
      $flag = 1;
      $xml->getElementsByTagName("Accounts")->item(0)->removeChild($value);
      $xml->save("../xml/accounts.xml");
    }
  }




  

  
?>