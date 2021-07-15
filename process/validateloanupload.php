<?php
session_start();
ini_set('display_errors', 'On');
require_once('controllers/config/xeroconfig.php');
require __DIR__ . '/vendor/autoload.php';
require_once('controllers/storage.php');
use XeroAPI\XeroPHP\AccountingObjectSerializer;
include_once('controllers/customer.php');
$_SESSION['contactid']= "";
$_SESSION['taxrate'] = "";
$_SESSION['bankaccount'] = "";

if ($_POST['action'] == "postdata")
{

	$contact_Id = getValue($_POST['contact_Id']);
	$agreement_number = getValue($_POST['agreement_number']);
	$loan_description = getValue($_POST['loan_description']);
	$account = getValue($_POST['account']);
	$bankaccount = getValue($_POST['bankaccount']);
	$Date_of_loan = getValue($_POST['Date_of_loan']);
	$Due_Date_of_loan = getValue($_POST['Due_Date_of_loan']);
	$loan_amount = getValue($_POST['loan_amount']);
	$amount_type = getValue($_POST['amount_type']);
	/*if(str_replace(', ','',validateAll($contact_Id, $bankaccount)) != ""){
		$message = str_replace(', ','<br>',validateAll($contact_Id, $bankaccount));
		echo '<div>ID: '.$agreement_number.'</div><div><h5 style="color:red;">'.$message.'</h5></div>';
		exit();
	}*/

	insertLoan($clientid, $clientsecret, $callback, $agreement_number, $loan_description, $account, $Date_of_loan, $Due_Date_of_loan, $loan_amount, $amount_type,$contact_Id,$bankaccount);	
}


function getValue($fieldname){
	if(isset($fieldname)){
	$value = $fieldname;
	}else{
	$value = "";
	}

	return $value;
}

function validateContactId($contact_Id){
	$cust = new CUSTOMER();
	$myid = $cust->getcustomerid($contact_Id);
	$errormessage = ""; 
	if($myid == ""){
	$errormessage = '<dd style="color:red;">- Contact Does not Exist</dd>';
	}else{
		$_SESSION['contactid'] = $myid;
	}

	return $errormessage;
}

function validateTaxRate(){
	$xml3 = new DOMDocument;
	$xml3->formatOutput = true; 
	$xml3->preserveWhiteSpace = false;
	$xml3->load('../xml/taxrates.xml');
	$taxtype = "";
	$errormessage = "";
	$mytaxes = $xml3->getElementsByTagName('taxrate');
	foreach ($mytaxes as $mytax)
	{
	$taxtype = $mytax->getElementsByTagName('taxtype')->item(0)->nodeValue;
	}
	if($taxtype == "")
	{
		$errormessage = '<dd style="color:red;">- Tax Rate Does not Exist</dd>';
	}
	else
	{
		//$_SESSION['taxrate'] = $taxtype;
		$_SESSION['taxrate'] = "NONE";
	}
	return $errormessage;
}

function validateLoanChannel($bankaccount){
	$xml2 = new DOMDocument;
	$xml2->formatOutput = true; 
	$xml2->preserveWhiteSpace = false;
	$xml2->load('../xml/loanschannel.xml');
	$errormessage = "";
	$flag = 0;
	$xmldatas2 = $xml2->getElementsByTagName("Loan");
	foreach ($xmldatas2 as $xmldata2){
        if($xmldata2->getElementsByTagName('lmscode')->item(0)->nodeValue == $bankaccount)
        {
        	$flag = 1;
        	$_SESSION['bankaccount'] = $xmldata2->getElementsByTagName('xerocode')->item(0)->nodeValue;
        }
    } 
    if($flag==0){
    	$errormessage = '<dd style="color:red;">- Loan Channel Does not Exist</dd>';
    }
    return $errormessage;   
}

function validateAll($contact_Id, $bankaccount){
	$errormessage = "";
	$errormessage .= validateContactId($contact_Id);
	$errormessage .= validateTaxRate();
	$errormessage .= validateLoanChannel($bankaccount);
	$globalerror = $errormessage;
	return $errormessage;
	//return str_replace(', ','',$errormessage);
}

function insertLoan($clientid, $clientsecret, $callback, $agreement_number, $loan_description, $account, $Date_of_loan, $Due_Date_of_loan, $loan_amount, $amount_type,$contact_Id,$bankaccount){


	    $messagealert = "";
		$contact_Idarray = explode("|",$contact_Id);
		$agreement_numberarray = explode("|",$agreement_number);
		$loan_descriptionarray = explode("|",$loan_description);
		$accountarray = explode("|",$account);
		$bankaccountarray = explode("|",$bankaccount);
		$Date_of_loanarray = explode("|",$Date_of_loan);
		$Due_Date_of_loanarray = explode("|",$Due_Date_of_loan);
		$loan_amountarray = explode("|",$loan_amount);
		$amount_typearray = explode("|",$amount_type);

		for($i = 0; $i < count($contact_Idarray)-1; $i++)
		{
			$currentcount = $i + 1;
			if(validateAll($contact_Idarray[$i], $bankaccountarray[$i]) == "")
			{
				
			}else{
			   $messagealert .= '<dt>Line no: '.$currentcount.'</dt>
              <dd>- Agreement no: '.$agreement_numberarray[$i].'</dd>
              <dd>- Contact ID: '.$contact_Idarray[$i].'</dd>
              '.validateAll($contact_Idarray[$i], $bankaccountarray[$i]).'<hr>';
			}

		}
		echo $messagealert;

}


?>
