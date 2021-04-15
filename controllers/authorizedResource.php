<?php
  ini_set('display_errors', 'On');
  //require __DIR__ . '/vendor/autoload.php';
  require_once('C:\Users\SysDev - PC1\vendor\autoload.php');
  require_once('storage.php');


  // Use this class to deserialize error caught
  use XeroAPI\XeroPHP\AccountingObjectSerializer;

  // Storage Classe uses sessions for storing token > extend to your DB of choice
  $storage = new StorageClass();
  $xeroTenantId = (string)$storage->getSession()['tenant_id'];

  if ($storage->getHasExpired()) {

  $provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '8FB8007E4B4F4EF4ADE4FF6918D591F5',   
    'clientSecret'            => 'ZQ_B3U5XnGYfEuM7JHuxjflTQVyPDYjqJ12Q4yJowwYmiGNo',
    'redirectUri'             => 'http://localhost:84/moneycatph/controllers/callback.php', 
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
  }

  $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$storage->getSession()['token'] );
  $config->setHost("https://api.xero.com/api.xro/2.0");        

  $apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
      new GuzzleHttp\Client(),
      $config
  );

//header('location: MainSelection.php');
 header('location: data.php');

?>
