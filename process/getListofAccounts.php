<?php 
  $xml = new DOMDocument;
  $xml->formatOutput = true; 
  $xml->preserveWhiteSpace = false;
  $xml->load('../xml/accounts.xml');
  $selval = "";
  $output = '<table id="datatable" class="table table-hover table-striped">';
  $output .= '<tr>
              <td><b>Name</b></td>
              <td><b>Code</b></td>
              <td><b>Description</b></td>
              <td><b>Type</b></td>
              <td><b>Action</b></td>
              </tr>
              </thead>
              <tbody>';

  $accounts = $xml->getElementsByTagName("Account");
  foreach ($accounts as $account) {
    $name = $account->getElementsByTagName("accountname")->item(0)->nodeValue;
    $code = $account->getElementsByTagName("accountcode")->item(0)->nodeValue;
    $desc = $account->getElementsByTagName("accountdescription")->item(0)->nodeValue;
    $type = $account->getElementsByTagName("accounttype")->item(0)->nodeValue;
    $taxrate = $account->getElementsByTagName("taxrate")->item(0)->nodeValue;
    $output .= '<tr onclick="boxboardDetails(this)" id="prodWrapper" style="cursor: pointer;"><td>'.$name.'</td>';
    $output .= '<td>'.$code.'</td>';
    $output .= '<td>'.$desc.'</td>';
    $output .= '<td style="display:none;">'.$taxrate.'</td>';
    $output .= '<td style="display:none;">'.$type.'</td>';
    $output .= '<td style="display:none;">'.$code.'@'.$desc.'</td>';
    if($type == 1){
      $output .= '<td><select class="form-control" name="'.$code.'" id="'.$code.'" onchange="updateAccountType(this)">
                  <option value = "1" selected="selected">Invoice Payment</option>
                  <option value = "2">Receive Payment</option>
                </select></td>';
    }else{
      $output .= '<td><select class="form-control" name="'.$code.'" id="'.$code.'" onchange="updateAccountType(this)">
                  <option value = "1">Invoice Payment</option>
                  <option value = "2"  selected="selected">Receive Payment</option>
                </select></td>';
    }
    $output.= '<td><input class="btn  btn-fill " type="button" value="Remove" id = "id-'.$code.'" onclick="removeaccount(this) "></td></tr>';
    
  }


                                                        
  $output .='</tbody></table>';

  echo $output;

  

  
?>