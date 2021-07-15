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
	if(validateTaxRate().validatePaymentChannel().validateAccount($account, $category).validateContactId($contact_Id).validateCategory($category) != "")
	{
		echo '<div>ID: '.$agreement_number.'</div><div><h5 style="color:red;">'.validateTaxRate().validatePaymentChannel().validateAccount($account, $category).validateContactId($contact_Id).validateCategory($category).'</h5></div>';
		exit();
	}

	//echo 'success';
	if($category == 1)
	{
		processCategory1($clientid, $clientsecret, $callback, $contact_Id, $agreement_number, $loan_description, $loan_amount, $account, $category, $date_of_payment, $amount_type, $_SESSION['accounttype']);
		exit();
	}

	if($category == 2)
	{
		processCategory2($clientid, $clientsecret, $callback, $contact_Id, $agreement_number, $loan_description, $loan_amount, $account, $category, $date_of_payment, $amount_type, $_SESSION['accounttype']);
		exit();
	}

	if($category == 3)
	{
		processCategory3($clientid, $clientsecret, $callback, $contact_Id, $agreement_number, $loan_description, $loan_amount, $account, $category, $date_of_payment, $amount_type, $_SESSION['accounttype']);
		exit();
	}
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

function processCategory1($clientid, $clientsecret, $callback, $contact_Id, $agreement_number, $loan_description, $loan_amount, $account, $category, $date_of_payment, $amount_type, $account_type)
{
	include('controllers/config/dbconn.php');
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
	if($account_type == 2)
	{
		$amounttype = "";
		if($amount_type == 0)
		{
			$amounttype = "Exclusive";
		}
		else
		{
			$amounttype = "Inclusive";
		}
		//$newdate = explode('.', $date_of_payment);
		//$date_of_payment = $newdate[2].'-'.$newdate[1].'-'.$newdate[0];
		$queryheader = "CALL `insert_agreement_header`('$agreement_number', '".$_SESSION['contactid']."', '$amounttype', '$date_of_payment', '".$_SESSION['paymentbankaccount']."', 'receive', '$account', '$loan_amount', '".$_SESSION['taxrate']."', '$loan_description');";
		if(mysqli_query($conn,$queryheader))
		{
		  $message .= '<dt>Line no: '.$_POST['currentcount'].'</dt>
          <dd>- Agreement no: '.$agreement_number.'</dd>
          <dd>- Contact ID: '.$contact_Id.'</dd>
          <dd style="color:green;">- Payment successfully uploaded.</dd><hr>';
		}
		else
		{
		  $message .= '<dt>Line no: '.$_POST['currentcount'].'</dt>
          <dd>- Agreement no: '.$agreement_number.'</dd>
          <dd>- Contact ID: '.$contact_Id.'</dd>
          <dd style="color:red;">- Payment upload failed.</dd><hr>';
		}
		echo $message;
		exit();
	}
	else
	{
		try
		{
			$lastinvoice = "";
			//$newdate = explode('.', $date_of_payment);
			//$date_of_payment = $newdate[2].'-'.$newdate[1].'-'.$newdate[0];
			$apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where = null, $order = 'Date', $i_ds = null, $invoice_numbers = null, $contact_i_ds = $_SESSION['contactid'], $statuses = null, $page = null, $include_archived = null, $created_by_my_app = null, $unitdp = null);
			$cnt =  count($apiResponse->getInvoices());
			$arr  = (json_decode($apiResponse, true));
			$pastinvoice = "";
			$invoicenum = "";
			$amountdue = 0;
			for ($i=0; $i < $cnt; $i++) { 
				if($arr["Invoices"][$i]["Status"]=="AUTHORISED"){
				$pastinvoice = $arr["Invoices"][$i]["InvoiceID"];
				$invoicenum = $arr["Invoices"][$i]["InvoiceNumber"];

				$amountdue = $arr["Invoices"][$i]["AmountDue"];
					if($amountdue < $loan_amount)
					{

						$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
						$pay = new \XeroAPI\XeroPHP\Models\Accounting\Payment;
						$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
				    	$bankacc = new \XeroAPI\XeroPHP\Models\Accounting\Account;
				    	$litems->setDescription($loan_description.'|'.$agreement_number.'|'.$date_of_payment);
						$litems->setAccountCode($account);
						$arr_lineitems = []; array_push($arr_lineitems,$litems);
				    	$pay -> setInvoice($itrans->setInvoiceID($pastinvoice),$itrans->setType('ACCREC'));
				    	$pay -> setAccount($bankacc->setCode($_SESSION['paymentbankaccount']));
				    
				    	$pay -> setDate($date_of_payment);
				    	$pay -> setAmount($amountdue);
				    
				    	$apiInstance->createPayment($xeroTenantId, $pay, true);
				    	$loan_amount = $loan_amount - $amountdue;
				    	$lastinvoice = $arr["Invoices"][$i]["InvoiceID"];
					    if($loan_amount == 0 ){
					    	break;
					    }
					}
					else
					{
						$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
						$pay = new \XeroAPI\XeroPHP\Models\Accounting\Payment;
						$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
				    	$bankacc = new \XeroAPI\XeroPHP\Models\Accounting\Account;
				    	$litems->setDescription($loan_description.'|'.$agreement_number.'|'.$date_of_payment);
						$litems->setAccountCode($account);
						$arr_lineitems = []; array_push($arr_lineitems,$litems);
				    	$pay -> setInvoice($itrans->setInvoiceID($pastinvoice),$itrans->setType('ACCREC'));
				    	$pay -> setAccount($bankacc->setCode($_SESSION['paymentbankaccount']));
				    
				    	$pay -> setDate($date_of_payment);
				    	$pay -> setAmount($loan_amount);
				    
				    	$apiInstance->createPayment($xeroTenantId, $pay, true);
				    	$loan_amount = 0;
				    	break;
					}
					//break;
				}
			}

			if($loan_amount > 0){
				$btrans = new \XeroAPI\XeroPHP\Models\Accounting\BankTransaction;
				$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
				$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
				$cont = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
				$bankacc = new \XeroAPI\XeroPHP\Models\Accounting\Account;
				$litems->setDescription($loan_description.'|'.$agreement_number.'|'.$date_of_payment);
				$litems->setUnitAmount($loan_amount);
				$litems->setAccountCode($overpayment[0]);
				$arr_lineitems = []; array_push($arr_lineitems, $litems);
				$btrans->setType('RECEIVE');
				$btrans->setContact($cont->setContactId($_SESSION['contactid']));
				$btrans->setLineItems($arr_lineitems);
				$btrans->setReference($invoicenum);
				$btrans -> setBankAccount($bankacc->setCode($_SESSION['paymentbankaccount']));
				//echo $btrans;
				$apiInstance->createBankTransactions($xeroTenantId, $btrans, false);
			}
			echo $messagealert .= '<dt>Line no: '.$_POST['currentcount'].'</dt>
          <dd>- Agreement no: '.$agreement_number.'</dd>
          <dd>- Contact ID: '.$contact_Id.'</dd>
          <dd style="color:green;">- Payment successfully uploaded.</dd><hr>';

		}
		catch (\XeroAPI\XeroPHP\ApiException $e) {
		    $error = AccountingObjectSerializer::deserialize(
		        $e->getResponseBody(),
		        '\XeroAPI\XeroPHP\Models\Accounting\Error',
		        []
		    );
		    $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
		    echo $messagealert = '<dt>Line no: '.$_POST['currentcount'].'</dt>
          <dd>- Agreement no: '.$agreement_number.'</dd>
          <dd>- Contact ID: '.$contact_Id.'</dd>
          <dd style="color:red;">- '.$message.'</dd><hr>';
		    //echo $_SESSION['opchannel'];
		  }
		
	}
}

function processCategory2($clientid, $clientsecret, $callback, $contact_Id, $agreement_number, $loan_description, $loan_amount, $account, $category, $date_of_payment, $amount_type, $account_type)
{
	include('controllers/config/dbconn.php');

	if($account_type != 1)
	{
		echo $message = '<dt>Line no: '.$_POST['currentcount'].'</dt>
          <dd>- Agreement no: '.$agreement_number.'</dd>
          <dd>- Contact ID: '.$contact_Id.'</dd>
          <dd style="color:red;">- Invalid Account type</dd><hr>';
		//echo $account_type;
		exit();
	}
	$amounttype = "";
	if($amount_type == 0)
	{
		$amounttype = "Exclusive";
	}
	else
	{
		$amounttype = "Inclusive";
	}
	// -> Invoice
	//$newdate = explode('.', $date_of_payment);
	//$date_of_payment = $newdate[2].'-'.$newdate[1].'-'.$newdate[0];
	$queryheader = "CALL `insert_agreement_header`('$agreement_number', '".$_SESSION['contactid']."', '$amounttype', '$date_of_payment', '".$_SESSION['paymentbankaccount']."', 'invoice', '$account', '$loan_amount', '".$_SESSION['taxrate']."','$loan_description');";
	if(mysqli_query($conn,$queryheader))
	{
	  $message = '<dt>Line no: '.$_POST['currentcount'].'</dt>
          <dd>- Agreement no: '.$agreement_number.'</dd>
          <dd>- Contact ID: '.$contact_Id.'</dd>
          <dd style="color:green;">- Payment successfully uploaded.</dd><hr>';
	}
	else
	{
	  $message = '<dt>Line no: '.$_POST['currentcount'].'</dt>
          <dd>- Agreement no: '.$agreement_number.'</dd>
          <dd>- Contact ID: '.$contact_Id.'</dd>
          <dd style="color:red;">- Payment upload failed</dd><hr>';
	}
	echo $message;
}

function processCategory3($clientid, $clientsecret, $callback, $contact_Id, $agreement_number, $loan_description, $loan_amount, $account, $category, $date_of_payment, $amount_type, $account_type)
{
	include('controllers/config/dbconn.php');
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

	try
	{
		$lastinvoice = "";
			//$newdate = explode('.', $date_of_payment);
			//$date_of_payment = $newdate[2].'-'.$newdate[1].'-'.$newdate[0];
		$apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where = null, $order = 'Date', $i_ds = null, $invoice_numbers = null, $contact_i_ds = $_SESSION['contactid'], $statuses = null, $page = null, $include_archived = null, $created_by_my_app = null, $unitdp = null);
		$cnt =  count($apiResponse->getInvoices());

		$arr  = (json_decode($apiResponse, true));
		$pastinvoice = "";
		$invoicenum = "";
		$amountdue = 0;
		for ($i=0; $i < $cnt; $i++) { 
			
			if($arr["Invoices"][$i]["Status"]=="AUTHORISED"){
				$pastinvoice = $arr["Invoices"][$i]["InvoiceID"];
				$invoicenum = $arr["Invoices"][$i]["InvoiceNumber"];

				$amountdue = $arr["Invoices"][$i]["AmountDue"];
				if($amountdue < $loan_amount){

					$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
					$pay = new \XeroAPI\XeroPHP\Models\Accounting\Payment;
					$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
			    $bankacc = new \XeroAPI\XeroPHP\Models\Accounting\Account;
			    $litems->setDescription($loan_description.'|'.$agreement_number.'|'.$date_of_payment);
					$litems->setAccountCode($account);
					$arr_lineitems = []; array_push($arr_lineitems,$litems);
			    $pay -> setInvoice($itrans->setInvoiceID($pastinvoice),$itrans->setType('ACCREC'));
			    $pay -> setAccount($bankacc->setCode($account));
			    $pay -> setReference('WRITEOFF');
			    $pay -> setDate($date_of_payment);
			    $pay -> setAmount($amountdue);
			    
			    $apiInstance->createPayment($xeroTenantId, $pay, true);
			    $loan_amount = $loan_amount - $amountdue;
			    $lastinvoice = $arr["Invoices"][$i]["InvoiceID"];
			    if($loan_amount == 0 ){
			    	break;
			    }
				}else{
					$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
					$pay = new \XeroAPI\XeroPHP\Models\Accounting\Payment;
					$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
			    $bankacc = new \XeroAPI\XeroPHP\Models\Accounting\Account;
			    $litems->setDescription($loan_description.'|'.$agreement_number.'|'.$date_of_payment);
					$litems->setAccountCode($account);
					$arr_lineitems = []; array_push($arr_lineitems,$litems);
			    $pay -> setInvoice($itrans->setInvoiceID($pastinvoice),$itrans->setType('ACCREC'));
			    $pay -> setAccount($bankacc->setCode($account));
			    $pay -> setReference('WRITEOFF');
			    $pay -> setDate($date_of_payment);
			    $pay -> setAmount($loan_amount);
			    
			    $apiInstance->createPayment($xeroTenantId, $pay, true);
			    $loan_amount = 0;
			    break;
				}
				//break;
			}
		}

		if($loan_amount > 0){
			$btrans = new \XeroAPI\XeroPHP\Models\Accounting\BankTransaction;
			$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
			$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
			$cont = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
			$bankacc = new \XeroAPI\XeroPHP\Models\Accounting\Account;
			$litems->setDescription($loan_description.'|'.$agreement_number.'|'.$date_of_payment);
			$litems->setUnitAmount($loan_amount);
			$litems->setAccountCode($overpayment);
			//$litems->setTaxType($taxtype);
			$arr_lineitems = []; array_push($arr_lineitems, $litems);
			$btrans->setType('RECEIVE');
			$btrans->setContact($cont->setContactId($_SESSION['contactid']));
			$btrans->setLineItems($arr_lineitems);
			$btrans->setReference($invoicenum);
			$btrans -> setBankAccount($bankacc->setCode($account));
			//echo $btrans;
			$apiInstance->createBankTransactions($xeroTenantId, $btrans, true);
		}
		echo $messagealert .= '<dt>Line no: '.$_POST['currentcount'].'</dt>
          <dd>- Agreement no: '.$agreement_number.'</dd>
          <dd>- Contact ID: '.$contact_Id.'</dd>
          <dd style="color:green;">- Payment successfully uploaded.</dd><hr>';

	}
	catch (\XeroAPI\XeroPHP\ApiException $e) {
	    $error = AccountingObjectSerializer::deserialize(
	        $e->getResponseBody(),
	        '\XeroAPI\XeroPHP\Models\Accounting\Error',
	        []
	    );
	    $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
	    echo $messagealert .= '<dt>Line no: '.$_POST['currentcount'].'</dt>
          <dd>- Agreement no: '.$agreement_number.'</dd>
          <dd>- Contact ID: '.$contact_Id.'</dd>
          <dd style="color:red;">- '.$message.'</dd><hr>';
	    //echo $_SESSION['opchannel'];
	  }

}


?>
