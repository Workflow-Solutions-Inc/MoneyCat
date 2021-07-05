<?php  
require_once "PHPExcel/Classes/PHPExcel.php";
session_start();
	$orgid = "";
if(isset($_SESSION["organisationID"])){
    $orgid = $_SESSION["organisationID"];
}

include("../process/controllers/config/dbconn.php");

//create phpexcel object
$excel = new PHPExcel();

//selecting active sheet
//$excel -> setActiveSheetIndex(0);
//PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
$excel = PHPExcel_IOFactory::load("QAP 1601E.xlsx");
$id = '';
$start = 16;
$count = 1;
$year = $_GET["monthyear"];
$quarter = $_GET["quart"];

	if($quarter==1){
		$excel -> setActiveSheetIndex(0)
		-> setCellValue('A3', "FOR THE QUARTER ENDING MARCH, ".$year);
	}else if($quarter == 4){
		$excel -> setActiveSheetIndex(0)
		-> setCellValue('A3', "FOR THE QUARTER ENDING JUNE, ".$year);
	}else if($quarter == 7){
		$excel -> setActiveSheetIndex(0)
		-> setCellValue('A3', "FOR THE QUARTER ENDING SEPTEMBER, ".$year);
	}else if($quarter == 9){
		$excel -> setActiveSheetIndex(0)
		-> setCellValue('A3', "FOR THE QUARTER ENDING DECEMBER, ".$year);
	}

	$orgquery = "select * from organisation where id = '$orgid';";
	$res = $conn->query($orgquery);
	while ($orgrow = $res->fetch_assoc()) 
    { 
    	$excel -> setActiveSheetIndex(0)
		-> setCellValue('B6', $orgrow["tin"])
		-> setCellValue('C7', $orgrow["name"]);
    }
    

    $query2 = "select it.invoiceid from invoicetrans it left join invoices iv on it.invoiceid = iv.ID where it.lineamount<0 and iv.invoicetype = 'ACCPAY' and year(iv.invoicedate) = '$year' and month(iv.invoicedate) between $quarter and $quarter+2";
    $result2 = $conn->query($query2);
    while ($row2 = $result2->fetch_assoc()) 
    { 
    	$id = $row2["invoiceid"];
    	$query = "call get_alphalist1601E('$id', $quarter, $year, 1)";
	    $result = $conn->query($query);
	    while ($row = $result->fetch_assoc()) 
	    { 
	    	$excel -> setActiveSheetIndex(0)
			-> setCellValue('A'.$start, $count)
			-> setCellValue('B'.$start, $row["tin"])
			-> setCellValue('C'.$start, $row["customerName"])
			-> setCellValue('D'.$start, $row["name"])
			-> setCellValue('E'.$start, $row["itemcode"])
			-> setCellValue('F'.$start, $row["description"])
			-> setCellValue('K'.$start, $row["quantity1"])
			-> setCellValue('L'.$start, $row["unitamount1"])
			-> setCellValue('M'.$start, $row["lineamount1"])
			-> setCellValue('N'.$start, $row["quantity2"])
			-> setCellValue('O'.$start, $row["unitamount2"])
			-> setCellValue('P'.$start, $row["lineamount2"])
			-> setCellValue('Q'.$start, $row["quantity3"])
			-> setCellValue('R'.$start, $row["unitamount3"])
			-> setCellValue('S'.$start, $row["lineamount3"])
			-> setCellValue('T'.$start, $row["quantity3"]+ $row["quantity2"]+ $row["quantity1"])
			-> setCellValue('U'.$start, $row["lineamount3"]+$row["lineamount2"]+$row["lineamount1"]);

			$start+=1;
			$count+=1;
	    }
	    $conn->close();
        include("../process/controllers/config/dbconn.php");
    }

    $query2 = "select sum(it.quantity) as income,sum(it.lineamount*-1) as withamount from invoicetrans it left join invoices iv on it.invoiceid = iv.ID where it.lineamount<0 and year(iv.invoicedate) = '$year' and month(iv.invoicedate) between $quarter and $quarter+2 and iv.invoicetype = 'ACCPAY'";
    $result2 = $conn->query($query2);
    while ($row2 = $result2->fetch_assoc()) 
    { 
    		$start+=1;
	    	$excel -> setActiveSheetIndex(0)
	    	-> setCellValue('B'.$start, 'GRAND TOTAL:')
			-> setCellValue('T'.$start, $row2["income"])
			-> setCellValue('U'.$start, $row2["withamount"]);
			$start+=2;
			$excel -> setActiveSheetIndex(0)
			-> setCellValue('B'.$start, 'END OF REPORT');
			
    }


//redirect browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="QAP 1601E '.date('Y-m-d h:i:sa').'.xlsx"');
header('Cache-Control: max-age=0');

//write result to a file
 $file = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
 $file->save('php://output');

?>