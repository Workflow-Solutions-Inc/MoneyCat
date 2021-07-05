  <?php  session_start();
include("../process/controllers/config/dbconn.php");
  $frmdate = htmlentities($_GET['frmdate']);
  $todate = htmlentities($_GET['todate']);
  $orgid = "";
  if(isset($_SESSION["organisationID"])){
      $orgid = $_SESSION["organisationID"];
      $orgname = $_SESSION["organisationName"];
  }
  $Date = "";
  $payee = "";
  $tin = "";
  $address = "";
  $checkvouch = "";
  $check = "";
  $particular = "";
  $accpay = "";
  $cash = "";
  $sundry = "";

  if(isset($_GET['Date'])){
    $Date = $_GET['Date'];
  }else{
    $Date = 0;
  }

  if(isset($_GET['payee'])){
    $payee = $_GET['payee'];
  }else{
    $payee = 0;
  }

  if(isset($_GET['tin'])){
    $tin = $_GET['tin'];
  }else{
    $tin = 0;
  }

  if(isset($_GET['address'])){
    $address = $_GET['address'];
  }else{
    $address = 0;
  }

  if(isset($_GET['checkvouch'])){
    $checkvouch = $_GET['checkvouch'];
  }else{
    $checkvouch = 0;
  }

  if(isset($_GET['check'])){
    $check = $_GET['check'];
  }else{
    $check = 0;
  }

  if(isset($_GET['particular'])){
    $particular = $_GET['particular'];
  }else{
    $particular = 0;
  }

  if(isset($_GET['accpay'])){
    $accpay = $_GET['accpay'];
  }else{
    $accpay = 0;
  }

  if(isset($_GET['cash'])){
    $cash = $_GET['cash'];
  }else{
    $cash = 0;
  }

  if(isset($_GET['sundry'])){
    $sundry = $_GET['sundry'];
  }else{
    $sundry = 0;
  }


  $output = '';
  $output .= '
    <label><b>Company Name: '.$orgname.' </b></label><br>
    <label><b>Cash Disbursement Books</label></b><br>
    <label><b>As of: '.$frmdate.' to '.$todate.'</b></label><br><br>
    <table border = "1"> 
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th>DEBIT</th>
      <th colspan = "2">CREDIT</th>
      <th colspan = "3">SUNDRY</th>

    </table>
   <table border="1">  
                    <tr>  ';

                        if($Date == 1){
                          $output .= '  <th>DATE.</th>  ';
                        }
                        if($payee == 1){
                          $output .= '  <th>PAYEE</th>  ';
                        } 
                        if($tin == 1){
                          $output .= '  <th>TIN</th>  ';
                        } 
                        if($address == 1){
                          $output .= '  <th>ADDRESS</th>  ';
                        } 
                        if($checkvouch == 1){
                          $output .= '  <th>VOUCHER NO.</th>';
                        } 
                        if($check == 1){
                          $output .= '  <th>CHECK NO.</th>  ';
                        } 
                        if($particular == 1){
                          $output .= '  <th>APV</th>  ';
                        } 
                        if($accpay == 1){
                          $output .= '  <th>ACCOUNTS PAYABLE</th>  ';
                        } 
                        if($cash == 1){
                          $output .= '  <th>CASH IN BANK</th>  ';
                          $output .= '  <th>CASH IN BANK2</th>  ';
                        }
                        if($sundry == 1){
                          $output .= '  <th>ACCOUNT TITLE</th>';
                          $output .= '  <th>DEBIT</th>';
                          $output .= '  <th>CREDIT</th>';
                        } 
                
                   $output .= '   </tr>
    ';
   
     $query2 = "select id
              from invoices
              where invoicetype = 'ACCPAY' and company = '".$orgid."' and invoicestatus = 'PAID' and date(invoicedate) between '".$frmdate."' and '".$todate."'";
      $result2 = $conn->query($query2);
      while ($row2 = $result2->fetch_assoc()) 
      { 

        $invoiceid = $row2["id"];
         $query = "call get_disbursement('".$invoiceid."');";
        $result = $conn->query($query);
          while ($row = $result->fetch_assoc()) 
          {
              $output .= '
                            <tr>  ';
                            if($Date == 1){
                              $output .= '   <td>'.$row["invoicedate"].'</td>  ';
                            }
                            if($payee == 1){
                              $output .= '   <td>'.$row["payee"].'</td>  ';
                            } 
                            if($tin == 1){
                              $output .= '   <td>'.$row["tin"].'</td> ';
                            } 
                            if($address == 1){
                              $output .= '   <td>'.$row["address"].'</td>  ';
                            } 
                            if($checkvouch == 1){
                              $output .= '   <td>'.$row["checkvouch"].'</td>  ';
                            } 
                            if($check == 1){
                             $output .= '   <td>'.$row["check"].'</td>  ';
                            } 
                            if($particular == 1){
                              $output .= '   <td>'.$row["apv"].'</td>  ';
                            } 
                            if($accpay == 1){
                              $output .= '   <td span style="text-align: right;">'.$row["accpayable"].'</td>  ';
                            } 
                            if($cash == 1){
                              $output .= '   <td span style="text-align: right;">'.$row["cash"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["cash2"].'</td>';
                            } 
                            if($sundry == 1){
                              $output .= '   <td>'.$row["particular2"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["debit"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["credit"].'</td>';
                            }
                                
                           $output .= '    </tr>';
          }
          $conn->close();
          include("../process/controllers/config/dbconn.php");
      }



      $query2 = "SELECT banktransid FROM banktrans where status != 'DELETED' and type = 'SPEND' and company = '$orgid' and date(btdate) between '$frmdate' and '$todate'";
      $result2 = $conn->query($query2);
      while ($row2 = $result2->fetch_assoc()) 
      { 
        $invoiceid = $row2["banktransid"];
         $query = "call get_disbursement2('".$invoiceid."');";
        $result = $conn->query($query);
          while ($row = $result->fetch_assoc()) 
          {
              $output .= '
                            <tr>  ';
                            if($Date == 1){
                              $output .= '   <td>'.$row["invoicedate"].'</td>  ';
                            }
                            if($payee == 1){
                              $output .= '   <td>'.$row["payee"].'</td>  ';
                            } 
                            if($tin == 1){
                              $output .= '   <td>'.$row["tin"].'</td> ';
                            } 
                            if($address == 1){
                              $output .= '   <td>'.$row["address"].'</td>  ';
                            } 
                            if($checkvouch == 1){
                              $output .= '   <td>'.$row["checkvouch"].'</td>  ';
                            } 
                            if($check == 1){
                             $output .= '   <td>'.$row["check"].'</td>  ';
                            } 
                            if($particular == 1){
                              $output .= '   <td>'.$row["apv"].'</td>  ';
                            } 
                            if($accpay == 1){
                              $output .= '   <td span style="text-align: right;">'.$row["accpayable"].'</td>  ';
                            } 
                            if($cash == 1){
                              $output .= '   <td span style="text-align: right;">'.$row["cash"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["cash2"].'</td>';
                            } 
                            if($sundry == 1){
                              $output .= '   <td>'.$row["particular2"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["debit"].'</td>';
                              $output .= '   <td span style="text-align: right;">'.$row["credit"].'</td>';
                            }
                                
                           $output .= '    </tr>';
          }
          $conn->close();
          include("../process/controllers/config/dbconn.php");
      }

      

  $reportTitle = "".$frmdate."-".$todate."";    
  $output .= '</table>';
  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment; filename='.$orgname.'_Disbursement_'.$reportTitle.'.xls');
      echo $output ;




  ?>