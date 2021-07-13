<?php 
ini_set('display_errors', 'On');
require __DIR__ . '/vendor/autoload.php';
//require_once('xeroconfig.php');
require_once('controllers/config/xeroconfig.php');
require_once('controllers/config/dbconn.php');
//require_once('C:\Users\SysDev - PC3\vendor\autoload.php');
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
	$newAccessToken->getValues()["id_token"]);



$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$storage->getSession()['token'] );
$config->setHost("https://api.xero.com/api.xro/2.0");        

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
	new GuzzleHttp\Client(),
	$config
);

/*$xml = new DOMDocument;
$xml->load('../xml/agreementchecker.xml');

$xml2 = new DOMDocument;
$xml2->load('../xml/agreementtable.xml');

$agchks = $xml->getElementsByTagName("Invoice");
$agtbls = $xml2->getElementsByTagName("Invoice");
$id2 = "";
$output = "";
	foreach ($agchks as $agchk) 
	{
		$id = $agchk->getElementsByTagName("id")->item(0)->nodeValue;
		if($agchk->getElementsByTagName("type")->item(0)->nodeValue == "receive")
		{
			$cars2 = array();
			$counter = 0;
			
			foreach ($agtbls as $agtbl) 
			{
				if($id == $agtbl->getElementsByTagName("id")->item(0)->nodeValue && $agtbl->getElementsByTagName("type")->item(0)->nodeValue == "receive")
				{
					$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
					$litems->setDescription($id.'|'.$agchk->getElementsByTagName("date")->item(0)->nodeValue);
					$litems->setUnitAmount($agtbl->getElementsByTagName("amount")->item(0)->nodeValue);
					$litems->setAccountCode($agtbl->getElementsByTagName("account")->item(0)->nodeValue);
					$litems->setTaxType($agtbl->getElementsByTagName("taxtype")->item(0)->nodeValue);
					$cars[$counter] = $litems;
					$counter++;
				}
				
				
				
			}

			if($agtbl->getElementsByTagName("type")->item(0)->nodeValue == "receive" && $id == $agtbl->getElementsByTagName("id")->item(0)->nodeValue )
				{
					$btrans = new \XeroAPI\XeroPHP\Models\Accounting\BankTransaction;
					$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
					$cont = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
					$bankacc = new \XeroAPI\XeroPHP\Models\Accounting\Account;

					$btrans->setType('RECEIVE');
					$btrans->setLineAmountTypes($agchk->getElementsByTagName("amounttype")->item(0)->nodeValue);
					$btrans->setContact($cont->setContactId($agchk->getElementsByTagName("contact")->item(0)->nodeValue));
					$btrans->setLineItems($cars);
					$btrans->setReference('DM');
					$btrans -> setBankAccount($bankacc->setCode($agchk->getElementsByTagName("bankaccount")->item(0)->nodeValue));
					
					echo $btrans.'<br>';
					//$apiInstance->createBankTransactions($xeroTenantId, $btrans, true);
					//$output.= '<div style="margin-left:20px;"">ID:'.$id.'</div> <div>Status: <h5style ="color:green">SUCCESS</h5></div></div><hr>';
				}

			

			
		
			}
			else
			{
				
				$cars = array();
				$counter = 0;
				foreach ($agtbls as $agtbl) 
				{
					if($id == $agtbl->getElementsByTagName("id")->item(0)->nodeValue && $agtbl->getElementsByTagName("type")->item(0)->nodeValue == "invoice")
					{
						$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
						$litems->setDescription($id.'|'.$agchk->getElementsByTagName("date")->item(0)->nodeValue);
						$litems->setQuantity(1);
						$litems->setUnitAmount($agtbl->getElementsByTagName("amount")->item(0)->nodeValue);
						$litems->setAccountCode($agtbl->getElementsByTagName("account")->item(0)->nodeValue);
						$litems->setTaxType($agtbl->getElementsByTagName("taxtype")->item(0)->nodeValue);
						$cars[$counter] = $litems;
						$counter++;
					}

					if($agtbl->getElementsByTagName("type")->item(0)->nodeValue == "invoice" && $id == $agtbl->getElementsByTagName("id")->item(0)->nodeValue )
					{
						$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
						$cont = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
            $itrans->setType('ACCREC');
            $itrans->setContact($cont->setContactId($agchk->getElementsByTagName("contact")->item(0)->nodeValue));
            $newdate = explode('.', $agchk->getElementsByTagName("date")->item(0)->nodeValue);
            $itrans->setDate($newdate[2].'-'.$newdate[1].'-'.$newdate[0]);
            $itrans->setDueDate($newdate[2].'-'.$newdate[1].'-'.$newdate[0]);
            $itrans->setLineItems($cars);
            $itrans->setStatus("AUTHORISED");
            //$itrans->setInvoiceNumber($id);
            //$itrans->setReference($id);
            echo $itrans.'<br>';
            //$apiInstance->createInvoice($xeroTenantId, $itrans, true);

            //$output.= '<div style="margin-left:20px;"">ID:'.$id.'</div> <div>Status: <h5style ="color:green">SUCCESS</h5></div></div><hr>';
            //echo 1;
					}

				}

				
					
					
				
			}
		
		

	}

	echo $output;*/
$output = "";
$idholder = "";
$counter = 0;
$vararray = array();
$sqlheader = "SELECT * FROM agreement_header order by id, agreementtype";
$res = $conn->query($sqlheader);      
while ($row = $res->fetch_assoc())
{
	if($idholder == ""){
			$vararray[$counter] = $row["id"];
			$idholder = $row["id"];
			$counter += 1;
	}else{
		if($idholder == $row["id"]){
			$idholder = $row["id"];
		}else{
			$vararray[$counter] = $row["id"];
			$idholder = $row["id"];
			$counter += 1;
		}
	}
	
}



foreach ($vararray as $value) {
	// code...
	$y = 0;
	$typedbase = "";
	$contactdbase = "";
	$amounttypedbase = "";
	$bankaccountdbase = "";
	$linesreceive = array();
	//echo $value."<br>";
	$sqllines = "SELECT * FROM agreement_header where id = '".$value."' and agreementtype = 'receive'";
	$res2 = $conn->query($sqllines);      
	while ($row2 = $res2->fetch_assoc())
	{
		$litems1 = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
		$litems1->setDescription($row2['description'].'|'.$value.'|'.$row2['agreementdate']);
		$litems1->setUnitAmount($row2['amount']);
		$litems1->setAccountCode($row2['account']);
		$linesreceive[$y] = $litems1;
		$typedbase = $row2['agreementtype'];
		$contactdbase = $row2['contact'];
		$amounttypedbase = $row2['amounttype'];
		$bankaccountdbase = $row2['bankaccount'];
		$y++;
	}

		if($typedbase == 'receive'){
			$btrans1 = new \XeroAPI\XeroPHP\Models\Accounting\BankTransaction;
			$cont1 = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
			$bankacc1 = new \XeroAPI\XeroPHP\Models\Accounting\Account;

			$btrans1->setType('RECEIVE');
			$btrans1->setLineAmountTypes($amounttypedbase);
			$btrans1->setContact($cont1->setContactId($contactdbase));
			$btrans1->setLineItems($linesreceive);
			$btrans1->setReference('DM');
			$btrans1 -> setBankAccount($bankacc1->setCode($bankaccountdbase));
			//echo $btrans1."<br>";
			$apiInstance->createBankTransactions($xeroTenantId, $btrans1, true);
			//$output.= '<div style="margin-left:20px;">ID:'.$value.' <div>Status: <h5 style ="color:green">SUCCESS</h5></div></div><hr>';
		}
		

	


	$x = 0;
	$typedbase = "";
	$contactdbase = "";
	$amounttypedbase = "";
	$agreementdatedbase = "";
	$linesinvoice = array();
	$sqllines2 = "SELECT * FROM agreement_header where id = '$value' and agreementtype = 'invoice'";
	$res3 = $conn->query($sqllines2);      
	while ($row3 = $res3->fetch_assoc())
	{
		$litems = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
		$litems->setDescription($row3['description'].'|'.$value.'|'.$row3["agreementdate"]);
		$litems->setQuantity(1);
		$litems->setUnitAmount($row3["amount"]);
		$litems->setAccountCode($row3["account"]);
		$linesinvoice[$x] = $litems;
		$typedbase = $row3['agreementtype'];
		$contactdbase = $row3['contact'];
		$amounttypedbase = $row3['amounttype'];
		$agreementdatedbase = $row3["agreementdate"];
		$x++;
	}

	if($typedbase == 'invoice'){
		$itrans = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
		$cont = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
	  $itrans->setType('ACCREC');
	  $itrans->setContact($cont->setContactId($contactdbase));
	 	$itrans->setDate($agreementdatedbase);
	 	$itrans->setDueDate($agreementdatedbase);
	  $itrans->setLineItems($linesinvoice);
	  $itrans->setLineAmountTypes($amounttypedbase);
	  $itrans->setStatus("AUTHORISED");
	  //$itrans->setInvoiceNumber($id);
	  //$itrans->setReference($id);
	 	//echo $itrans.'<br>';
	  $apiInstance->createInvoice($xeroTenantId, $itrans, true);
	  //$output.= '<div style="margin-left:20px;">ID:'.$value.' <div>Status: <h5 style ="color:green">SUCCESS</h5></div></div><hr>';
	}
	

}
echo $output;
?>
