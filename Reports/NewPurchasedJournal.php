<?php  session_start();
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

if(isset($_GET['apv'])){
  $apv = $_GET['apv'];
}else{
  $apv = 0;
}

if(isset($_GET['Accounts'])){
  $Accounts = $_GET['Accounts'];
}else{
  $Accounts = 0;
}

$output = '';
 $output .= '
    <label><b>Company Name: '.$orgname.' </b></label><br>
    <label><b>Purchased Journal Books</label></b><br>
    <label><b>For the month of: '.$frmdate.' to '.$todate.'</b></label><br><br>
    <table border = "1"> 
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th colspan = "3">ACCOUNTS</th>

    </table>
   <table border="1">  
                    <tr>  ';
                      if($Date == 1){
                        $output .= ' <th>DATE</th>  ';
                      }
                      if($payee == 1){
                        $output .= ' <th>PAYEE</th> '; 
                      } 
                      if($tin == 1){
                        $output .= ' <th>TIN</th>';
                      } 
                      if($address == 1){
                        $output .= ' <th>ADDRESS</th>';
                      } 
                      if($apv == 1){
                        $output .= ' <th>APV</th>  ';
                      } 
                      if($Accounts == 1){
                        $output .= ' <th>ACCOUNT TITLE</th>';
                        $output .= ' <th>DEBIT</th>';
                        $output .= ' <th>CREDIT</th>';
                      }        
                         
                   $output .= '  </tr>
  ';


  $query = "select id
            from invoices
            where invoicetype = 'ACCPAY' and company = '".$orgid."' and date(invoicedate) between '".$frmdate."' and '".$todate."'";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) 
    { 
        $output .= '
            <tr> 
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>';
        $invoiceid = $row["id"];
        $query2 = "call get_purchasedjournal('".$invoiceid."');";
        $result2 = $conn->query($query2);
        while ($row2 = $result2->fetch_assoc()) 
        { 

          $output .= '
            <tr>  ';
                      if($Date == 1){
                        $output .= ' <td>'.$row2["invoicedate"].'</td>  ';
                      }
                      if($payee == 1){
                        $output .= ' <td>'.$row2["CustomerName"].'</td>  ';
                      } 
                      if($tin == 1){
                        $output .= ' <td>'.$row2["tin"].'</td>  ';
                      } 
                      if($address == 1){
                        $output .= ' <td>'.$row2["address"].'</td>  ';
                      } 
                      if($apv == 1){
                        $output .= ' <td>'.$row2["Voucherno"].'</td>  ';
                      } 
                      if($Accounts == 1){
                        $output .= ' <td>'.$row2["acctitle"].'</td>  ';
                        $output .= ' <td>'.$row2["debit"].'</td>  ';
                        $output .= ' <td>'.$row2["credit"].'</td>  ';
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
header('Content-Disposition: attachment; filename='.$orgname.'_Purchased_Journal_'.$reportTitle.'.xls');
echo $output ;
?>