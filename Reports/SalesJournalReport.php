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

if(isset($_GET['invno'])){
  $invno = $_GET['invno'];
}else{
  $invno = 0;
}

if(isset($_GET['custname'])){
  $custname = $_GET['custname'];
}else{
  $custname = 0;
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

if(isset($_GET['accrec'])){
  $accrec = $_GET['accrec'];
}else{
  $accrec = 0;
}

if(isset($_GET['exsale'])){
  $exsale = $_GET['exsale'];
}else{
  $exsale = 0;
}

//new
if(isset($_GET['zerosale'])){
  $zerosale = $_GET['zerosale'];
}else{
  $zerosale = 0;
}

if(isset($_GET['vatsale'])){
  $vatsale = $_GET['vatsale'];
}else{
  $vatsale = 0;
}

if(isset($_GET['outvat'])){
  $outvat = $_GET['outvat'];
}else{
  $outvat = 0;
}

$output = '';
                
 $output .= '
    <label><b>Company Name: '.$orgname.' </b></label><br>
    <label><b>Sales Journal Books</label></b><br>
    <label><b>For the month of: '.$frmdate.' to '.$todate.'</b></label><br><br>
    <table border = "1"> 
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th></th>
      <th>DEBIT</th>
      <th colspan = "4">CREDIT</th>

    </table>
   <table border="1">  
                    <tr>  ';
                         
                      if($Date == 1){
                        $output .= ' <th>DATE</th>  ';
                      }
                      if($invno == 1){
                        $output .= ' <th>INVOICE NO.</th>  ';
                      } 
                      if($custname == 1){
                        $output .= ' <th>CUSTOMER NAME</th>  ';
                      } 
                      if($tin == 1){
                        $output .= ' <th>TIN</th>  ';
                      } 
                      if($address == 1){
                        $output .= ' <th>ADDRESS</th>  ';
                      } 
                      if($accrec == 1){
                        $output .= ' <th>ACCOUNTS RECEIVABLE</th>  ';
                      } 
                      if($vatsale == 1){
                       $output .= ' <th>VATABLE SALES</th>  ';
                      } 
                      if($zerosale == 1){
                       $output .= ' <th>ZERO RATED SALES</th>  ';
                      } 
                      if($exsale == 1){
                        $output .= ' <th>EXEMPT SALES</th>';
                      } 
                      if($outvat == 1){
                        $output .= ' <th>OUTPUT VAT</th>  ';
                      } 
                         
  
                         
                   $output .= '  </tr>
  ';
 
  $query = "select id
            from invoices
            where invoicetype = 'ACCREC' and company = '".$orgid."' and date(invoicedate) between '".$frmdate."' and '".$todate."'";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) 
    { 
        $invoiceid = $row["id"];
        $query2 = "call get_salesjournal('".$invoiceid."');";
        $result2 = $conn->query($query2);
        while ($row2 = $result2->fetch_assoc()) 
        { 

          $output .= '
            <tr>  ';
                      if($Date == 1){
                        $output .= ' <td>'.$row2["invoicedate"].'</td>  ';
                      }
                      if($invno == 1){
                        $output .= ' <td>'.$row2["Invoiceid"].'</td>  ';
                      } 
                      if($custname == 1){
                        $output .= ' <td>'.$row2["CustomerName"].'</td>  ';
                      } 
                      if($tin == 1){
                        $output .= ' <td>'.$row2["tin"].'</td>  ';
                      } 
                      if($address == 1){
                        $output .= ' <td>'.$row2["address"].'</td>  ';
                      } 
                      if($accrec == 1){
                        $output .= ' <td>'.$row2["accrec"].'</td>  ';
                      } 
                      if($vatsale == 1){
                        $output .= ' <td>'.$row2["vatsales"].'</td>';
                      } 
                      if($zerosale == 1){
                        $output .= ' <td>'.$row2["zerorelated"].'</td> ';
                      } 
                      if($exsale == 1){
                        $output .= ' <td>'.$row2["exempt"].'</td>  ';
                      } 
                      if($outvat == 1){
                        $output .= ' <td>'.$row2["outvat"].'</td>';
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
header('Content-Disposition: attachment; filename='.$orgname.'_Sales_Journal_'.$reportTitle.'.xls');
echo $output ;
?>