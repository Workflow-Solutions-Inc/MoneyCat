<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/accounts.xml');
  $arr = explode('@', $_GET['rate']);
  $code = $_GET["code"];
  $newval = 0;

  $accounts = $xml->getElementsByTagName("Account");
  foreach ($accounts as $account) {
    // code...
    if($code == $account->getElementsByTagName("accountcode")->item(0)->nodeValue){
      $newOrder = $xml->createElement("Account");
      
      if($account->getElementsByTagName("accounttype")->item(0)->nodeValue == 1){
        $newval = 2;
      }else{
        $newval = 1;
      }   
      $newOrder->appendChild($xml->createElement("accounttype",$newval));
      $newOrder->appendChild($xml->createElement("accountcode", $account->getElementsByTagName("accountcode")->item(0)->nodeValue));
      $newOrder->appendChild($xml->createElement("accountname", $account->getElementsByTagName("accountname")->item(0)->nodeValue));
      $newOrder->appendChild($xml->createElement("taxrate", $account->getElementsByTagName("taxrate")->item(0)->nodeValue));
      $newOrder->appendChild($xml->createElement("accountdescription", $account->getElementsByTagName("accountdescription")->item(0)->nodeValue));
      
      $xml->getElementsByTagName("Accounts")->item(0)->replaceChild($newOrder,$account);
      $xml->save("../xml/accounts.xml");
    }
    

  }

  

  
?>