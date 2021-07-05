<?php  
require_once "PHPExcel/Classes/PHPExcel.php";
session_start();
	$orgid = "";
if(isset($_SESSION["organisationID"])){
    $orgid = $_SESSION["organisationID"];
}

//database
include("../process/controllers/config/dbconn.php");

//create phpexcel object
$excel = new PHPExcel();

$id = '';
$start = 15;
$count = 1;
$quart = $_GET["quart"];
$year = $_GET["month3"];
//selecting active sheet
//$excel -> setActiveSheetIndex(0);
//PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
$excel = PHPExcel_IOFactory::load("Quarterly Summary List of Purchases w Excel, PDF, CSV and DAT file.xlsx");

			$orgquery = "select * from organisation where id = '$orgid';";
	$res = $conn->query($orgquery);
	while ($orgrow = $res->fetch_assoc()) 
    { 
    	$excel -> setActiveSheetIndex(0)
		-> setCellValue('B6', $orgrow["tin"])
		-> setCellValue('B7', $orgrow["name"]);
    }


    	$query ="select monthname(invoicedate) as invdate, ci.taxtype, ci.customerName,
				CONCAT(IFNULL(ci.firstname, ''),' ',IFNULL(ci.middlename, ''),' ',IFNULL(ci.lastname, '')) AS 'name',
				CONCAT(IFNULL(ci.addressline1, ''),' ',IFNULL(ci.addressline2, ''),' ',IFNULL(ci.addressline3, '')) AS 'address',
				iv.invoicesubtotal as 'grosspurchased',
				ifnull((select sum(lineamount) from invoicetrans where Invoiceid = iv.id and taxtype = 'NONE'),0 )as 'exempt',
				ifnull((select sum(lineamount) from invoicetrans where Invoiceid = iv.id and taxtype != 'NONE' and taxamount = 0 and lineamount >=0),0 ) as 'zero',,
				ifnull((select sum(lineamount - taxamount) from invoicetrans where Invoiceid = iv.id and taxtype != 'NONE' and taxamount != 0),0 ) as 'taxpurchase',
				iv.invoicetotaltax,
				iv.invoicetotaltax as 'purchaseservice',
				ifnull(null,0) as 'purchasecapitalgoods',
				ifnull(null,0) as 'purchaseothergoods',
				ifnull((select sum(lineamount - taxamount) from invoicetrans where Invoiceid = iv.id and taxtype != 'NONE' and taxamount != 0),0 ) + iv.invoicetotaltax as 'grosstaxpurchase'
				from invoices iv
				left join custinfo ci on ci.customerId = iv.customerId
				where invoicetype = 'ACCPAY' and year(iv.invoicedate) = '$year' and month(iv.invoicedate) between $quart and $quart+2;";
	    $result = $conn->query($query);
	    while ($row = $result->fetch_assoc()) 
	    { 
	    	$excel -> setActiveSheetIndex(0)
			-> setCellValue('A'.$start, $row["invdate"])
			-> setCellValue('B'.$start, $row["taxtype"])
			-> setCellValue('C'.$start, $row["customerName"])
			-> setCellValue('D'.$start, $row["name"])
			-> setCellValue('E'.$start, $row["address"])
			-> setCellValue('F'.$start, $row["grosspurchased"])
			-> setCellValue('G'.$start, $row["exempt"])
			-> setCellValue('H'.$start, $row["zero"])
			-> setCellValue('I'.$start, $row["taxpurchase"])
			-> setCellValue('J'.$start, $row["invoicetotaltax"])
			-> setCellValue('K'.$start, $row["purchaseservice"])
			-> setCellValue('L'.$start, $row["purchasecapitalgoods"])
			-> setCellValue('M'.$start, $row["purchaseothergoods"])
			-> setCellValue('N'.$start, $row["grosstaxpurchase"]);

			$start+=1;
			$count+=1;
	    }

//redirect browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Quarterly Summary List of Purchases '.date('Y-m-d h:i:sa').'.xlsx"');
header('Cache-Control: max-age=0');

//write result to a file
 $file = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
 $file->save('php://output');

?>