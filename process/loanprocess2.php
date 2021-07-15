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
	// Storage Classe uses sessions for storing token > extend to your DB of choice
	$storage = new StorageClass();
	$xeroTenantId = (string)$storage->getSession()['tenant_id'];
	//session_start();

	$provider = new \League\OAuth2\Client\Provider\GenericProvider([
	  'clientId'                => $clientid,   
	  'clientSecret'            => $clientsecret,
	  'redirectUri'             => $callback,
	  'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
	  'urlAccessToken'          => 'https://identity.xero.com/connect/token',
	  'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
	]);

	  $newAccessToken = $provider->getAccessToken('refresh_token', [
	    'refresh_token' => $storage->getRefreshToken()
	  ]);
	  
	  // Save my token, expiration and refresh token
	  $storage->setToken(
	      $newAccessToken->getToken(),
	      $newAccessToken->getExpires(), 
	      $xeroTenantId,
	      $newAccessToken->getRefreshToken(),
	      $newAccessToken->getValues()["id_token"] );

	    

	$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$storage->getSession()['token'] );
	$config->setHost("https://api.xero.com/api.xro/2.0");        

	$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
	    new GuzzleHttp\Client(),
	    $config
	);
	try
	{
		$btrans_array = new \XeroAPI\XeroPHP\Models\Accounting\BankTransactions;
	    $btranslines = [];
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
				$btrans = new \XeroAPI\XeroPHP\Models\Accounting\BankTransaction;
			    $itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
			    $litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
			    $processfeelitems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
			    $cont = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
			    $bankacc = new \XeroAPI\XeroPHP\Models\Accounting\Account;
			    $pay = new \XeroAPI\XeroPHP\Models\Accounting\Payment;

				$amounttype = "";
				if($amount_typearray[$i] == 0){
				  $amounttype = "Exclusive";
				}else{
				  $amounttype = "Inclusive";
				}

				$litems->setDescription($loan_descriptionarray[$i].'|'.$agreement_numberarray[$i].'|'.$Date_of_loanarray[$i].'|'.$Due_Date_of_loanarray[$i]);
				$litems->setUnitAmount($loan_amountarray[$i]);
				$litems->setAccountCode($accountarray[$i]);
				$litems->setTaxType($_SESSION['taxrate']);
				//$litems->setTaxType('NONE');

				$arr_lineitems = []; 
				array_push($arr_lineitems, $litems);
				$btrans->setType('SPEND');

				$btrans->setContact($cont->setContactId($_SESSION['contactid']));
				$btrans->setLineItems($arr_lineitems);
				$btrans->setReference("DM");
				$btrans->setLineAmountTypes('Inclusive');
				$btrans->setDate($Date_of_loanarray[$i]);
				$btrans -> setBankAccount($bankacc->setCode($_SESSION['bankaccount']));
				//echo $btrans;
				array_push($btranslines, $btrans);
        		$btrans_array ->setBankTransactions($btranslines);
				// $messagealert .= '<div>ID: '.$agreement_numberarray[$i].' <h5 style="color:green;">LOAN SUCCESSFULLY UPLOADED</h5></div><hr>';
				$messagealert .= '<dt>Line no: '.$currentcount.'</dt>
	              <dd>- Agreement no: '.$agreement_numberarray[$i].'</dd>
	              <dd>- Contact ID: '.$contact_Idarray[$i].'</dd>
	              <dd style="color:green;">- Loan Successfully uploaded</dd><hr>';
			}else{
			    //$messagealert .= '<div>ID: '.$agreement_numberarray[$i].' <h5 style="color:red;">'.validateAll($contact_Idarray[$i], $bankaccountarray[$i]).'</h5></div><hr>';
			    $messagealert .= '<dt>Line no: '.$currentcount.'</dt>
              <dd>- Agreement no: '.$agreement_numberarray[$i].'</dd>
              <dd>- Contact ID: '.$contact_Idarray[$i].'</dd>
              '.validateAll($contact_Idarray[$i], $bankaccountarray[$i]).'<hr>';
			}

		}
        //echo $btrans_array;
		$apiInstance->createBankTransactions($xeroTenantId, $btrans_array, true);
		echo $messagealert;
	}
	catch (\XeroAPI\XeroPHP\ApiException $e) {
    $error = AccountingObjectSerializer::deserialize(
        $e->getResponseBody(),
        '\XeroAPI\XeroPHP\Models\Accounting\Error',
        []
    );
    $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
    $messagealert.= '<div>ID: '.$agreement_numberarray[$i].' <h5 style="color:red;">'.$message.'</h5></div><hr>';
    echo $messagealert;
  }
	
	
}


?>
