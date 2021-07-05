<?php 
ini_set('display_errors', 'On');
//require __DIR__ . '/vendor/autoload.php';
//require_once('xeroconfig.php');
require_once('controllers/config/xeroconfig.php');
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

$xml = new DOMDocument;
$xml->formatOutput = true; 
$xml->preserveWhiteSpace = false;
$xml->load('../xml/accounts.xml');

$xml2 = new DOMDocument;
$xml2->formatOutput = true; 
$xml2->preserveWhiteSpace = false;
$xml2->load('../xml/loanschannel.xml');

$xml3 = new DOMDocument;
$xml3->formatOutput = true; 
$xml3->preserveWhiteSpace = false;
$xml3->load('../xml/taxrates.xml');


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
		}
		if(isset($_POST['loan_description'])){
			$loan_description = $_POST['loan_description'];
		}else{
			$loan_description = "";
		}
		if(isset($_POST['account'])){
			$account = $_POST['account'];
		}else{
			$account = "";
		}
		if(isset($_POST['bankaccount'])){
			$bankaccount = $_POST['bankaccount'];
		}else{
			$bankaccount = "";
		}
    if(isset($_POST['Date_of_loan'])){
      $Date_of_loan = $_POST['Date_of_loan'];
    }else{
      $Date_of_loan = "";
    }
    if(isset($_POST['Due_Date_of_loan'])){
      $Due_Date_of_loan = $_POST['Due_Date_of_loan'];
    }else{
      $Due_Date_of_loan = "";
    }
    if(isset($_POST['loan_amount'])){
      $loan_amount = $_POST['loan_amount'];
    }else{
      $loan_amount = "";
    }
    if(isset($_POST['amount_type'])){
      $amount_type = $_POST['amount_type'];
    }else{
      $amount_type = "";
    }

$btrans = new \XeroAPI\XeroPHP\Models\Accounting\BankTransaction;
$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
$processfeelitems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
$cont = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
$bankacc = new \XeroAPI\XeroPHP\Models\Accounting\Account;
$pay = new \XeroAPI\XeroPHP\Models\Accounting\Payment;



$cust = new CUSTOMER();
$myid = $cust->getcustomerid($contact_Id); 
$taxtype = "";
$flag = 0;

$mytaxes = $xml3->getElementsByTagName('taxrate');
foreach ($mytaxes as $mytax){
$taxtype = $mytax->getElementsByTagName('taxtype')->item(0)->nodeValue;
$flag+=1;
}



if($myid!=""){

if($flag==1){
  $xmldatas = $xml->getElementsByTagName("Account");
$xmldatas2 = $xml2->getElementsByTagName("Loan");
foreach ($xmldatas as $xmldata) {
  // code...
  if($xmldata->getElementsByTagName("accountcode")->item(0)->nodeValue == $account){
    if($xmldata->getElementsByTagName("accounttype")->item(0)->nodeValue != "0"){
      
      foreach ($xmldatas2 as $xmldata2){
        if($xmldata2->getElementsByTagName('lmscode')->item(0)->nodeValue == $bankaccount){
            $amounttype = "";
            if($amount_type == 0){
              $amounttype = "Exclusive";
            }else{
              $amounttype = "Inclusive";
            }
            
            $litems->setDescription($loan_description.'|'.$agreement_number.'|'.$_POST['Date_of_loan'].'|'.$_POST['Due_Date_of_loan']);
            $litems->setUnitAmount($loan_amount);
            $litems->setAccountCode($account);
            $litems->setTaxType($taxtype);

            $arr_lineitems = []; array_push($arr_lineitems, $litems);
            $btrans->setType('SPEND');
            
            $btrans->setContact($cont->setContactId($myid));
            $btrans->setLineItems($arr_lineitems);
            $btrans->setReference("DM");
            $btrans->setLineAmountTypes('Inclusive');
            $btrans -> setBankAccount($bankacc->setCode($xmldata2->getElementsByTagName('xerocode')->item(0)->nodeValue));
            //echo $btrans;
            $apiInstance->createBankTransactions($xeroTenantId, $btrans, true);
            echo 1;
        }
      }
    }else{
      /*
      foreach ($xmldatas2 as $xmldata2){
        if($xmldata2->getElementsByTagName('lmscode')->item(0)->nodeValue == $bankaccount){
            
            $type = "ACCREC";
            $itrans->setType($type);
            $itrans->setContact($cont->setContactId($myid));
            $newdate = explode('.', $Date_of_loan);
            $itrans->setDate($newdate[2].'-'.$newdate[1].'-'.$newdate[0]);
            $newdate2 = explode('.', $Due_Date_of_loan);
            $itrans->setDueDate($newdate2[2].'-'.$newdate2[1].'-'.$newdate2[0]);
            $processfeelitems->setDescription($loan_description.' ,Agreement no: '.$agreement_number.' ,Date of loan: '.$_POST['Date_of_loan'].' ,Due Date of loan: '.$_POST['Due_Date_of_loan']);
            $processfeelitems->setQuantity(1);
            $processfeelitems->setUnitAmount($loan_amount);
            $processfeelitems->setAccountCode($account);
            $processfeelitems->setTaxType($taxtype);
            $arr_lineitems2 = []; array_push($arr_lineitems2, $processfeelitems);
            $itrans->setLineItems($arr_lineitems2);
            $itrans->setStatus("AUTHORISED");
            $itrans->setInvoiceNumber($agreement_number);
            $itrans->setReference($agreement_number);
            //echo $itrans;
            $apiInstance->createInvoice($xeroTenantId, $itrans, true);
            echo 1;
        }
      }
      */
      //echo "Invoice pasok nya";
      
    }
  }
}
}else{
  echo 2;
}



}else{
  echo 3;
}
//



// if($interest_amount != 0){
//     //add an interest invoice here
  

  //echo $itrans;
 // $apiInstance->createInvoice($xeroTenantId, $itrans, true);


//     //

//   }else{
//     // does nothing
//   }
//executeUploading($custName,$custFirstname,$custLastName,$custEmail,$custSkype,$custbankAcc,$custTaxNum,$custArTaxType,$custAPTaxType); 
		
		
	}
}


?>