<?php 
  session_start();
  ini_set('display_errors', 'On');
  require_once('controllers/config/xeroconfig.php');
  require_once('vendor/autoload.php');
  require_once('controllers/storage.php');
  use XeroAPI\XeroPHP\AccountingObjectSerializer;
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

    echo 1;
  
?>