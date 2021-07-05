<?php  
session_start();
include("../process/controllers/config/dbconn.php");
$frmdate = htmlentities($_GET['frmdate']);
$todate = htmlentities($_GET['todate']);
$orgid = "";
if(isset($_SESSION["organisationID"])){
    $orgid = $_SESSION["organisationID"];
    $orgname = $_SESSION["organisationName"];
}
if(isset($_GET['Date'])){
  $Date = $_GET['Date'];
}else{
  $Date = 0;
}

if(isset($_GET['customerName'])){
  $customerName = $_GET['customerName'];
}else{
  $customerName = 0;
}

if(isset($_GET['Explanation'])){
  $Explanation = $_GET['Explanation'];
}else{
  $Explanation = 0;
}

if(isset($_GET['tin'])){
  $tin = $_GET['tin'];
}else{
  $tin = 0;
}

if(isset($_GET['Address'])){
  $Address = $_GET['Address'];
}else{
  $Address = 0;
}

if(isset($_GET['debit'])){
  $debit = $_GET['debit'];
}else{
  $debit = 0;
}

if(isset($_GET['credit'])){
  $credit = $_GET['credit'];
}else{
  $credit = 0;
}

if(isset($_GET['sundry'])){
  $sundry = $_GET['sundry'];
}else{
  $sundry = 0;
}

$output = '';
                
 $output .= '
    <label><b>Company Name: '.$orgname.' </b></label><br>
    <label><b>Cash Receipts Books</label></b><br>
    <label><b>As of: '.$frmdate.' to '.$todate.'</b></label><br><br>
    <table border = "1"> 
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th colspan = "3">DEBIT</th>
      <th>CREDIT</th>
      <th colspan = "3">SUNDRY</th>

    </table>
   <table border="1">  
                    <tr>  ';
                         
                      if($Date == 1){
                        $output .= ' <th>DATE</th>  ';
                      }
                      if($customerName == 1){
                        $output .= ' <th>CUSTOMER NAME</th>  ';
                      } 
                      if($Explanation == 1){
                        $output .= ' <th>EXPLANATION</th>  ';
                      } 
                      if($tin == 1){
                        $output .= ' <th>TIN</th>  ';
                      } 
                      if($Address == 1){
                        $output .= ' <th>ADDRESS</th>  ';
                      } 
                      if($debit == 1){
                        $output .= ' <th>CASH IN BANK 1</th>  ';
                        $output .= ' <th>CASH IN BANK 2</th>';
                        $output .= ' <th>WITHHOLDING TAX</th>  ';
                      } 
                      if($credit == 1){
                         $output .= ' <th>ACCOUNTS RECEIVABLE</th>  ';
                      } 
                      if($sundry == 1){
                        $output .= ' <th>PARTICULARS</th>  ';
                        $output .= ' <th>DEBIT</th>  ';
                        $output .= ' <th>CREDIT</th>  ';
                      }  
                         
  
                         
                   $output .= '  </tr>
  ';
 
  $query = "select id
            from invoices
            where invoicetype = 'ACCREC' and company = '".$orgid."' and invoicestatus = 'PAID' and  date(invoicedate) between '".$frmdate."' and '".$todate."'";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) 
    { 
        $invoiceid = $row["id"];
        $query2 = "call get_Receipts('".$invoiceid."');";
        $result2 = $conn->query($query2);
        while ($row2 = $result2->fetch_assoc()) 
        {    
          $invDate = $row2["invoicedate"];
          $custName = $row2["customerName"];
          $invid = $row2["invoiceid"];
          $tinn = $row2["tin"];
          $address = $row2["address"];
          $cash = $row2["cash1"];
          $cash2 = $row2["cash2"];
          $withholdingtax = $row2["cwt"];
          $accrec = $row2["accrec"];
          $particular = $row2["particular"];
          $debitamount = $row2["debit"];
          $creditamount = $row2["credit"];

          $output .= '
            <tr>  ';
                      if($Date == 1){
                        $output .= ' <td>'.$invDate.'</td>  ';
                      }
                      if($customerName == 1){
                        $output .= ' <td>'.$custName.'</td>  ';
                      } 
                      if($Explanation == 1){
                        $output .= ' <td>'.$invid.'</td>  ';
                      } 
                      if($tin == 1){
                        $output .= ' <td>'.$tinn.'</td>  ';
                      } 
                      if($Address == 1){
                        $output .= ' <td>'.$address.'</td>  ';
                      } 
                      if($debit == 1){
                        $output .= ' <td>'.$cash.'</td>  ';
                        $output .= ' <td>'.$cash2.'</td> ';
                        $output .= ' <td>'.$withholdingtax.'</td>';
                      } 
                      if($credit == 1){
                        $output .= ' <td>'.$accrec.'</td>';
                      } 
                      if($sundry == 1){
                        $output .= ' <td>'.$particular.'</td>';
                        $output .= ' <td>'.$debitamount.'</td>';
                        $output .= ' <td>'.$creditamount.'</td>';
                      }  
                
               
           $output.=' </tr>
              ';
              
        }
       $conn->close();
        include("../process/controllers/config/dbconn.php");
        
         
    }

    $query = "select banktransid
            from banktrans
            where company = '".$orgid."' and type = 'RECEIVE' and date(btdate) between '".$frmdate."' and '".$todate."'";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) 
    { 
        $banktransid = $row["banktransid"];
        $query2 = "call get_Receipts2('".$banktransid."');";
        $result2 = $conn->query($query2);
        while ($row2 = $result2->fetch_assoc()) 
        {    
          $invDate = $row2["invoicedate"];
          $custName = $row2["customerName"];
          $invid = $row2["invoiceid"];
          $tinn = $row2["tin"];
          $address = $row2["address"];
          $cash = $row2["cash1"];
          $cash2 = $row2["cash2"];
          $withholdingtax = $row2["cwt"];
          $accrec = $row2["accrec"];
          $particular = $row2["particular"];
          $debitamount = $row2["debit"];
          $creditamount = $row2["credit"];

          $output .= '
            <tr>  ';
                      if($Date == 1){
                        $output .= ' <td>'.$invDate.'</td>  ';
                      }
                      if($customerName == 1){
                        $output .= ' <td>'.$custName.'</td>  ';
                      } 
                      if($Explanation == 1){
                        $output .= ' <td>'.$invid.'</td>  ';
                      } 
                      if($tin == 1){
                        $output .= ' <td>'.$tinn.'</td>  ';
                      } 
                      if($Address == 1){
                        $output .= ' <td>'.$address.'</td>  ';
                      } 
                      if($debit == 1){
                        $output .= ' <td>'.$cash.'</td>  ';
                        $output .= ' <td>'.$cash2.'</td> ';
                        $output .= ' <td>'.$withholdingtax.'</td>';
                      } 
                      if($credit == 1){
                        $output .= ' <td>'.$accrec.'</td>';
                      } 
                      if($sundry == 1){
                        $output .= ' <td>'.$particular.'</td>';
                        $output .= ' <td>'.$debitamount.'</td>';
                        $output .= ' <td>'.$creditamount.'</td>';
                      }  
                
               
           $output.=' </tr>
              ';
              
        }
       $conn->close();
        include("../process/controllers/config/dbconn.php");
        
         
    }

$reportTitle = "".$frmdate."-".$todate."";    
$output .= '</table>';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename='.$orgname.'_Receipts_'.$reportTitle.'.xls');
echo $output ;
?>