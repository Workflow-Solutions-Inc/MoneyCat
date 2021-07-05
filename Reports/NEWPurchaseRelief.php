<?php  

include("../process/controllers/config/dbconn.php");
$frmdate = htmlentities($_GET['frmdate']);
$todate = htmlentities($_GET['todate']);
$output = '';

$query = "call SP_getDatResult('".$frmdate."','".$todate."','ACCPAY')";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) 
    {
         $res = $row["result"]." \r\n";
         $output .= $res;
         //echo  $res;
    }

header('Content-Type: application/notepad');
header('Content-Disposition: attachment; filename=PurchaseRelief_'. date("Y-m-d").'.dat');
echo $output ;

?>