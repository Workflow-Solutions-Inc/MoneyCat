<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/loanschannel.xml');
  
  $newcode = $_GET['code'];

  $Loans = $xml->getElementsByTagName("Loan");
  foreach ($Loans as $Loan) {
    $code = $Loan->getElementsByTagName("lmscode")->item(0)->nodeValue;
    if($code == $newcode){

      $xml->getElementsByTagName("Loans")->item(0)->removeChild($Loan);
      $xml->save("../xml/loanschannel.xml");
      echo "Removed";
    }
   
  }
      

  

  
?>