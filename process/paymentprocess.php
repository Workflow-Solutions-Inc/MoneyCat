<?php 
session_start();
ini_set('display_errors', 'On');
//require __DIR__ . '/vendor/autoload.php';
//require_once('xeroconfig.php');
require_once('controllers/config/xeroconfig.php');
require_once('controllers/config/dbconn.php');
require_once('C:\Users\SysDev - PC3\vendor\autoload.php');
require_once('controllers/storage.php');
include_once('controllers/customer.php');



// Storage Class uses sessions for storing access token (demo only)
// you'll need to extend to your Database for a scalable solution
use XeroAPI\XeroPHP\AccountingObjectSerializer;

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


if (isset($_POST['action']))
{
	if ($_POST['action'] == "postdata")
	{

		$contact_Id = $_POST['contact_Id'];
		//echo $custName;
		if(isset($_POST['agreement_number'])){
			$agreement_number = $_POST['agreement_number'];
		}else{
			$agreement_number = "";
		}			if(isset($_POST['loan_description'])){
			$loan_description = $_POST['loan_description'];
		}else{
			$loan_description = "";
		}
		if(isset($_POST['loan_amount'])){
			$loan_amount = $_POST['loan_amount'];
		}else{
			$loan_amount = "";
		}
		if(isset($_POST['account'])){
			$account = $_POST['account'];
		}else{
			$account = "";
		}
		if(isset($_POST['category'])){
			$category = $_POST['category'];
		}else{
			$category = "";
		}
		if(isset($_POST['date_of_payment'])){
			$date_of_payment = $_POST['date_of_payment'];
		}else{
			$date_of_payment = "";
		}
		if(isset($_POST['amount_type'])){
			$amount_type = $_POST['amount_type'];
		}else{
			$amount_type = "";
		}

		$xml = new DOMDocument;
		$xml->formatOutput = true; 
		$xml->preserveWhiteSpace = false;
		$xml->load('../xml/paymentchannel.xml');

		$xml2 = new DOMDocument;
		$xml2->formatOutput = true; 
		$xml2->preserveWhiteSpace = false;
		$xml2->load('../xml/taxrates2.xml');

		$xml3 = new DOMDocument;
		$xml3->formatOutput = true; 
		$xml3->preserveWhiteSpace = false;
		$xml3->load('../xml/accounts.xml');




		$btrans = new \XeroAPI\XeroPHP\Models\Accounting\BankTransaction;
		$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
		$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
		$cont = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
		$bankacc = new \XeroAPI\XeroPHP\Models\Accounting\Account;

		$cust = new CUSTOMER();
		$myid = $cust->getcustomerid($contact_Id);
		$flag = 0; 
		
		$taxtype = "";
		$mytaxes = $xml2->getElementsByTagName('taxrate');
		foreach ($mytaxes as $mytax){
			$taxtype = $mytax->getElementsByTagName('taxtype')->item(0)->nodeValue;
			$flag+=1;
		}

		$paymentbankaccount = "";
		$mypayments = $xml->getElementsByTagName('Payment');
		foreach ($mypayments as $mypayment){
			$paymentbankaccount = $mypayment->getElementsByTagName('xerocode')->item(0)->nodeValue;
			$flag+=1;
		}

		if($flag == 1 || $flag == 0)
		{
			echo 2;
		}else if($flag == 2)
		{
			if($category==1)
			{

				$accounts = $xml3->getElementsByTagName("Account");
				foreach($accounts as $acc){
					if($acc->getElementsByTagName("accountcode")->item(0)->nodeValue == $account)
					{
						$loan_description = $acc->getElementsByTagName("accountname")->item(0)->nodeValue;
						if($acc->getElementsByTagName("accounttype")->item(0)->nodeValue == 2)
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
									$newdate = explode('.', $date_of_payment);
									$date_of_payment = $newdate[2].'-'.$newdate[1].'-'.$newdate[0];
									$queryheader = "CALL `insert_agreement_header`('$agreement_number', '$myid', '$amounttype', '$date_of_payment', '$paymentbankaccount', 'receive', '$account', '$loan_amount', '$taxtype', '$loan_description');";
						      if(mysqli_query($conn,$queryheader))
						      {

						      }
						      else
						      {
						        echo "error ".$queryheader."<br>".$conn->error;
						      }
									echo 5;
							// <- Receivable payment
						}
						else
						{
							// payment for invoice ->
							$lastinvoice = "";
							$newdate = explode('.', $date_of_payment);
							$date_of_payment = $newdate[2].'-'.$newdate[1].'-'.$newdate[0];
						$apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where = null, $order = 'Date', $i_ds = null, $invoice_numbers = null, $contact_i_ds = $myid, $statuses = null, $page = null, $include_archived = null, $created_by_my_app = null, $unitdp = null);
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
							    $pay -> setAccount($bankacc->setCode($paymentbankaccount));
							    
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
							    $pay -> setAccount($bankacc->setCode($paymentbankaccount));
							    
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
							$litems->setAccountCode($_SESSION['opchannel']);
							$arr_lineitems = []; array_push($arr_lineitems, $litems);
							$btrans->setType('RECEIVE');
							$btrans->setContact($cont->setContactId($myid));
							$btrans->setLineItems($arr_lineitems);
							$btrans->setReference($invoicenum);
							$btrans -> setBankAccount($bankacc->setCode($paymentbankaccount));
							//echo $btrans;
							$apiInstance->createBankTransactions($xeroTenantId, $btrans, false);
						}
							
								
						    echo 1;
							
							// <- payment for invoice
						}
						
					}
					
				}
			}
			else if($category == 2)
			{
				//accrual of charges ->
				$xmldatas = $xml3->getElementsByTagName("Account");
				foreach ($xmldatas as $xmldata) 
				{
					if($xmldata->getElementsByTagName("accountcode")->item(0)->nodeValue == $account)
					{
						$loan_description = $xmldata->getElementsByTagName("accountname")->item(0)->nodeValue;
						if($xmldata->getElementsByTagName("accounttype")->item(0)->nodeValue == "2")
						{
    							//received money ->
									
									// <- received money
									echo "error";
							}
							else
							{
    							//invoice ->
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
									$newdate = explode('.', $date_of_payment);
									$date_of_payment = $newdate[2].'-'.$newdate[1].'-'.$newdate[0];
									$queryheader = "CALL `insert_agreement_header`('$agreement_number', '$myid', '$amounttype', '$date_of_payment', '$paymentbankaccount', 'invoice', '$account', '$loan_amount', '$taxtype','$loan_description');";
						      if(mysqli_query($conn,$queryheader))
						      {

						      }
						      else
						      {
						        echo "error ".$queryheader."<br>".$conn->error;
						      }

									// <- Invoice

									echo 5;

							}
						}
					}
				}
				// <- accrual of charges
				
			}
		
}
		
				if($category == 3){
				//write off payment ->
					$lastinvoice = "";
							$newdate = explode('.', $date_of_payment);
							$date_of_payment = $newdate[2].'-'.$newdate[1].'-'.$newdate[0];
						$apiResponse = $apiInstance->getInvoices($xeroTenantId, $if_modified_since = null, $where = null, $order = 'Date', $i_ds = null, $invoice_numbers = null, $contact_i_ds = $myid, $statuses = null, $page = null, $include_archived = null, $created_by_my_app = null, $unitdp = null);
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
							$litems->setAccountCode($_SESSION['opchannel']);
							//$litems->setTaxType($taxtype);
							$arr_lineitems = []; array_push($arr_lineitems, $litems);
							$btrans->setType('RECEIVE');
							$btrans->setContact($cont->setContactId($myid));
							$btrans->setLineItems($arr_lineitems);
							$btrans->setReference($invoicenum);
							$btrans -> setBankAccount($bankacc->setCode($account));
							//echo $btrans;
							$apiInstance->createBankTransactions($xeroTenantId, $btrans, true);
						}
							

								echo 1;
				// <- write off payment
			}




		
	}


?>