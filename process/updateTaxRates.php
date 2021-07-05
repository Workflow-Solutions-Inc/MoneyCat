<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/taxrates.xml');
  $arr = explode('@', $_GET['rate']);

  $taxrates = $xml->getElementsByTagName("taxrate");
  foreach ($taxrates as $taxrate) {
    // code...
    $newOrder = $xml->createElement("taxrate");
          
    $newOrder->appendChild($xml->createElement("taxname",$arr[1]));
    $newOrder->appendChild($xml->createElement("taxvalue",$arr[0]));
    $newOrder->appendChild($xml->createElement("taxtype",$arr[2]));
    
    $xml->getElementsByTagName("taxrates")->item(0)->replaceChild($newOrder,$taxrate);
    $xml->save("../xml/taxrates.xml");

  }

  

  
?>