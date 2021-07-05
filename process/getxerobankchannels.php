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

  
  $apiResponse = $apiInstance->getAccounts($xeroTenantId, $if_modified_since = null, $where = 'Type == "BANK" AND Status == "ACTIVE"', $order = null);
  $xerodata  = (json_decode($apiResponse, true));
  //echo $apiInstance->getTaxRates($xeroTenantId, $where = null, $order = null, $tax_type = null);
  $output = "";
  // get count of taxrates
  $cnt =  count($apiResponse->getAccounts());
  for ($i=0; $i <= $cnt ; $i++) { 
  	// code...
  	if($xerodata["Accounts"][$i]["Type"] == "BANK"){
      $output.= '<option value="'.$xerodata["Accounts"][$i]["Code"].'@'.$xerodata["Accounts"][$i]["Name"].'">'.$xerodata["Accounts"][$i]["Name"].'</option>';
  	}
  	

  }

  echo $output;




?>