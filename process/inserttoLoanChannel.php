<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/loanschannel.xml');
  
  $newcode = $_GET['code'];
  $xero = $_GET['xero'];
  $xeroarr = explode("@", $xero);
  $flag = 0;

  $Loans = $xml->getElementsByTagName("Loan");
  foreach ($Loans as $Loan) {
    $code = $Loan->getElementsByTagName("lmscode")->item(0)->nodeValue;
    $xcode = $Loan->getElementsByTagName("xerocode")->item(0)->nodeValue;
    if($code == $newcode){

      $flag = 1;
      echo "Code Already Exist!";
    }
   
  }

  if($flag==0){
    $newData = $xml->createElement("Loan");

      $newData->appendChild($xml->createElement("lmscode",$newcode));
      $newData->appendChild($xml->createElement("xerocode",$xeroarr[0]));
      $newData->appendChild($xml->createElement("xeroname",$xeroarr[1]));

      $xml->getElementsByTagName("Loans")->item(0)->appendChild($newData);
      $xml->save("../xml/loanschannel.xml");
      echo "Saved";
  }

 
      

  

  
?>