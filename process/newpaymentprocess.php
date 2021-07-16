<?php 
session_start();
ini_set('display_errors', 'On');
//require __DIR__ . '/vendor/autoload.php';
//require_once('xeroconfig.php');
require_once('controllers/config/xeroconfig.php');
require_once('vendor/autoload.php');
require_once('controllers/storage.php');
include_once('controllers/customer.php');
// Storage Class uses sessions for storing access token (demo only)
// you'll need to extend to your Database for a scalable solution
use XeroAPI\XeroPHP\AccountingObjectSerializer;
$overpayment = explode('@', $_SESSION['opchannel']);
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


$_SESSION['taxrate'] = "";
$_SESSION['paymentbankaccount'] = "";
$_SESSION['contactid'] = "";
$_SESSION['accounttype'] = "";

if ($_POST['action'] == "postdata")
{
	$contact_Id = getValue($_POST['contact_Id']);
	$agreement_number = getValue($_POST['agreement_number']);
	$loan_description = getValue($_POST['loan_description']);
	$loan_amount = getValue($_POST['loan_amount']);
	$account = getValue($_POST['account']);
	$category = getValue($_POST['category']);
	$date_of_payment = getValue($_POST['date_of_payment']);
	$amount_type = getValue($_POST['amount_type']);

	$contact_Idarray = explode("|",$contact_Id);
	$agreement_numberarray = explode("|",$agreement_number);
	$loan_descriptionarray = explode("|",$loan_description);
	$loan_amountarray = explode("|",$loan_amount);
	$accountarray = explode("|",$account);
	$categoryarray = explode("|",$category);
	$date_of_paymentarray = explode("|",$date_of_payment);
	$amount_typearray = explode("|",$amount_type);
	validatePaymentChannel();

	//category 2 ->
	try{
		$itrans_array = new \XeroAPI\XeroPHP\Models\Accounting\Invoices;
		$itranslines = [];
		$messagealert = "";
		for($i = 0; $i < count($contact_Idarray)-1; $i++)
		{

			if($categoryarray[$i] == 2)
			{
				$curcount = $i + 1;
				validateAccount($accountarray[$i], $categoryarray[$i]);
				if($_SESSION["accounttype"] == 1){
					$cust = new CUSTOMER();
					$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
					$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
					$cont = new \XeroAPI\XeroPHP\Models\Accounting\Contact;

					$litems->setDescription($loan_descriptionarray[$i].'|'.$agreement_number[$i].'|'.$date_of_paymentarray[$i]);
					$litems->setQuantity(1);
					$litems->setUnitAmount($loan_amountarray[$i]);
					$litems->setAccountCode($accountarray[$i]);
					$arr_lineitems = []; 
					array_push($arr_lineitems, $litems);
					$itrans->setType('ACCREC');
					$itrans->setContact($cont->setContactId($cust->getcustomerid($contact_Idarray[$i])));
				 	$itrans->setDate($date_of_paymentarray[$i]);
				 	$itrans->setDueDate($date_of_paymentarray[$i]);
					$itrans->setLineItems($arr_lineitems);
					//$itrans->setLineAmountTypes('EXCLUSI');
					$itrans->setStatus("AUTHORISED");

					array_push($itranslines, $itrans);
		        	$itrans_array ->setInvoices($itranslines);
		        	$curcount = $i + 1;
		        	$messagealert .= '<dt>Line no: '.$curcount.'</dt>
		          <dd>- Agreement no: '.$agreement_numberarray[$i].'</dd>
		          <dd>- Contact ID: '.$contact_Idarray[$i].'</dd>
		          <dd style="color:green;">- Payment successfully uploaded.</dd><hr>';
				}else{
					$messagealert .= '<dt>Line no: '.$curcount.'</dt>
		          <dd>- Agreement no: '.$agreement_numberarray[$i].'</dd>
		          <dd>- Contact ID: '.$contact_Idarray[$i].'</dd>
		          <dd>- Contact ID: '.$accountarray[$i].'</dd>
		          <dd style="color:grey;">- Payment not uploaded please check account use if it is set as invoice payment.</dd><hr>';
				}
				
			}
		}
		echo $messagealert;
		$apiInstance->createInvoices($xeroTenantId, $itrans_array, true);
		
	}
	catch (\XeroAPI\XeroPHP\ApiException $e) {
	    $error = AccountingObjectSerializer::deserialize(
	        $e->getResponseBody(),
	        '\XeroAPI\XeroPHP\Models\Accounting\Error',
	        []
	    );
	    $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
	    //echo $message;
  	}
	
	//<- category 2
	//category 1 ->
  	try{
		$btransarray = new \XeroAPI\XeroPHP\Models\Accounting\BankTransactions;
		$btransline = [];
		$messagealert = "";
		for($i = 0; $i < count($contact_Idarray)-1; $i++)
		{
			if($categoryarray[$i] == 1)
			{
				$curcount = $i + 1;
				validateAccount($accountarray[$i], $categoryarray[$i]);
				if($_SESSION["accounttype"] == 2){
					$cust = new CUSTOMER();
					$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
					$btrans = new \XeroAPI\XeroPHP\Models\Accounting\BankTransaction;
					$cont = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
					$bankacc = new \XeroAPI\XeroPHP\Models\Accounting\Account;
					$litems->setDescription($loan_descriptionarray[$i].'|'.$agreement_number[$i].'|'.$date_of_paymentarray[$i]);
					$litems->setUnitAmount($loan_amountarray[$i]);
					$litems->setAccountCode($accountarray[$i]);
					$arr_lineitems = []; 
					array_push($arr_lineitems, $litems);
					$btrans->setType('RECEIVE');
					//$btrans->setLineAmountTypes($amounttypedbase);
					$btrans->setContact($cont->setContactId($cust->getcustomerid($contact_Idarray[$i])));
					$btrans->setLineItems($arr_lineitems);
					$btrans->setReference('DM');
					$btrans -> setBankAccount($bankacc->setCode($_SESSION['paymentbankaccount']));
					array_push($btransline, $btrans);
		        	$btransarray ->setBankTransactions($btransline);
		        	$messagealert .= '<dt>Line no: '.$curcount.'</dt>
			          <dd>- Agreement no: '.$agreement_numberarray[$i].'</dd>
			          <dd>- Contact ID: '.$contact_Idarray[$i].'</dd>
			          <dd style="color:green;">- Payment successfully uploaded.</dd><hr>';
				}
			}

			else
			{

			}
		}
		echo $messagealert;
		$apiInstance->createBankTransactions($xeroTenantId, $btransarray, true);
		
	}
	catch (\XeroAPI\XeroPHP\ApiException $e) {
	    $error = AccountingObjectSerializer::deserialize(
	        $e->getResponseBody(),
	        '\XeroAPI\XeroPHP\Models\Accounting\Error',
	        []
	    );
	    $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
	    //echo $message;
  	}
	//<- category 1


}

function getValue($fieldname){
	if(isset($fieldname)){
	$value = $fieldname;
	}else{
	$value = "";
	}
	return $value;
}

function validateTaxRate(){
	$xml2 = new DOMDocument;
	$xml2->formatOutput = true; 
	$xml2->preserveWhiteSpace = false;
	$xml2->load('../xml/taxrates2.xml');
	$message = "";
	$mytaxes = $xml2->getElementsByTagName('taxrate');
	foreach ($mytaxes as $mytax)
	{
		$_SESSION['taxrate'] = $mytax->getElementsByTagName('taxtype')->item(0)->nodeValue;
	}
	if($_SESSION['taxrate'] == '')
	{
		$message = '<dd style="color:red;" >- Undefined Tax Rate</dd>';
	}
	return $message;
}

function validatePaymentChannel(){
	$xml = new DOMDocument;
	$xml->formatOutput = true; 
	$xml->preserveWhiteSpace = false;
	$xml->load('../xml/paymentchannel.xml');
	$message = "";
	$mypayments = $xml->getElementsByTagName('Payment');
	foreach ($mypayments as $mypayment){
		$_SESSION['paymentbankaccount'] = $mypayment->getElementsByTagName('xerocode')->item(0)->nodeValue;
	}
	if($_SESSION['paymentbankaccount'] == '')
	{
		$message = '<dd style="color:red;">- Undefined Payment Channel</dd>';
	}
	return $message;
}

function validateAccount($account, $category){
	$message = "";
	if($category != 3){
		$xml3 = new DOMDocument;
		$xml3->formatOutput = true; 
		$xml3->preserveWhiteSpace = false;
		$xml3->load('../xml/accounts.xml');
		
		$flag = 0;
		$accounts = $xml3->getElementsByTagName("Account");
		foreach($accounts as $acc){
			if($acc->getElementsByTagName("accountcode")->item(0)->nodeValue == $account)
			{
				$_SESSION['accounttype'] = $acc->getElementsByTagName("accounttype")->item(0)->nodeValue;
				$flag = 1;
			}
		}
		if($flag == 0){
			$message = '<dd style="color:red;">- Undefined Account</dd>';
		}
	}
	
	return $message;
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

function validateCategory($category)
{
	$message = "";
	if($category > 3 || $category == 0)
	{
		$message = '<dd style="color:red;">- Undefined Category</dd>';
	}
	return $message;
}





?>
