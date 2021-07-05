<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/accounts.xml');

  $accounts = $xml->getElementsByTagName("Account");


  $code = $_GET["code"];
  $name = $_GET["name"];
  $desc = $_GET["desc"];
  $taxtype = $_GET["tax"];
  $flag = 0;

  foreach ($accounts as $value) {
    // code...
    if($code == $value->getElementsByTagName("accountcode")->item(0)->nodeValue){
      $flag = 1;
      $newOrder = $xml->createElement("Account");
      $newOrder->appendChild($xml->createElement("accounttype",$taxtype));
      $newOrder->appendChild($xml->createElement("accountcode", $code));
      $newOrder->appendChild($xml->createElement("accountname", $name));
      $newOrder->appendChild($xml->createElement("taxrate", $value->getElementsByTagName("taxrate")->item(0)->nodeValue));
      $newOrder->appendChild($xml->createElement("accountdescription", $desc));
      
      $xml->getElementsByTagName("Accounts")->item(0)->replaceChild($newOrder,$value);
      $xml->save("../xml/accounts.xml");
    }
  }

  if($flag==0){
    $newData = $xml->createElement("Account");

    $newData->appendChild($xml->createElement("accounttype",$taxtype));
    $newData->appendChild($xml->createElement("accountcode", $code));
    $newData->appendChild($xml->createElement("accountname", $name));
    $newData->appendChild($xml->createElement("taxrate",  $value->getElementsByTagName("taxrate")->item(0)->nodeValue));
    $newData->appendChild($xml->createElement("accountdescription", $desc));

    $xml->getElementsByTagName("Accounts")->item(0)->appendChild($newData);
    $xml->save("../xml/accounts.xml");
    //echo "Saved";
  }

 echo $flag;

  

  
?>