<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/loanschannel.xml');
  $output = '<table id="datatable" class="table table-hover table-striped">';
  $output .= '<tr><td><b>LMS Code</b></td><td><b>Disbursement Channel Name</b></td><td><b>Action</b></td></tr></thead><tbody>';

  $accounts = $xml->getElementsByTagName("Loan");
  foreach ($accounts as $account) {
    $code = $account->getElementsByTagName("lmscode")->item(0)->nodeValue;
    $xcode = $account->getElementsByTagName("xerocode")->item(0)->nodeValue;
    $xname = $account->getElementsByTagName("xeroname")->item(0)->nodeValue;
    $output .= '<tr><td>'.$code.'</td>';
    $output .= '<td style="display:none;">'.$xcode.'</td>';
    $output .= '<td>'.$xname.'</td>';
    $output .= '<td><button class="btn btn-fill pull-center" id="'.$code.'" onclick="removeLoanChannel(this)">Remove</button></td></tr>';
  }


                                                        
  $output .='</tbody></table>';

  echo $output;

  

  
?>