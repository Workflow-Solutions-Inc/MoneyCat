<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/taxrates.xml');
  $taxname = "";
  $taxvalue = "";

  $taxrates = $xml->getElementsByTagName("taxrate");
  foreach ($taxrates as $taxrate) {
    $taxname = $taxrate->getElementsByTagName("taxname")->item(0)->nodeValue;
    $taxvalue = $taxrate->getElementsByTagName("taxvalue")->item(0)->nodeValue;
    $taxtype = $taxrate->getElementsByTagName("taxtype")->item(0)->nodeValue;
  }

  echo $taxvalue.'@'.$taxname.'@'.$taxtype;

  

  
?>