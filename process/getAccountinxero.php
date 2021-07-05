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

  
 /* $apiResponse = $apiInstance->getTaxRates($xeroTenantId, $where = null, $order = "Name", $tax_type = null);
  $taxrate  = (json_decode($apiResponse, true));
  //echo $apiInstance->getTaxRates($xeroTenantId, $where = null, $order = null, $tax_type = null);
  $output = "";
  // get count of taxrates
  $cnt =  count($apiResponse->getTaxRates());
  for ($i=0; $i <= $cnt ; $i++) { 
  	// code...
  	if($taxrate["TaxRates"][$i]["Status"] == "ACTIVE"){
  		$output .= '<option value="'.$taxrate["TaxRates"][$i]["DisplayTaxRate"].'@'.$taxrate["TaxRates"][$i]["Name"].'@'.$taxrate["TaxRates"][$i]["TaxType"].'">'.$taxrate["TaxRates"][$i]["DisplayTaxRate"].'% - '.$taxrate["TaxRates"][$i]["Name"].'</option>';
  	}
  	

  }*/

  try
  {
    $apiResponse = $apiInstance->getAccounts($xeroTenantId, 'Type', 'Type != "BANK"');
    $accounts  = (json_decode($apiResponse, true));
    $output = "";
    for ($i=0; $i < count($apiResponse->getAccounts()); $i++) { 
      // code...
      
      if($accounts["Accounts"][$i]["Status"] == "ACTIVE"){
        //echo $accounts["Accounts"][$i]["Code"]."<br>";
        $output .= '<option value="'.$accounts["Accounts"][$i]["Code"].'@'.$accounts["Accounts"][$i]["Name"].'">'.$accounts["Accounts"][$i]["Code"].' - '.$accounts["Accounts"][$i]["Name"].'</option>';
      }
    }
    echo $output;
  }
  catch (\XeroAPI\XeroPHP\ApiException $e) {
    $error = AccountingObjectSerializer::deserialize(
        $e->getResponseBody(),
        '\XeroAPI\XeroPHP\Models\Accounting\Error',
        []
    );
   echo $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
  }
  

  




?>