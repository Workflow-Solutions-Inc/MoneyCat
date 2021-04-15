<?php
  ini_set('display_errors', 'On');
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

class executeUpload
{
  
  public function executeUploading($custName,$custFirstname,$custLastName,$custEmail,$custSkype,$custbankAcc,$custTaxNum,$custArTaxType,$custAPTaxType)
  {

    $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;

    $contact->setName($custName);
    $contact->setFirstName($custFirstname);
    $contact->setLastName($custLastName);
    $contact->setEmailAddress($custEmail);


    $contact->setSkypeUserName($custSkype);
    $contact->setBankAccountDetails($custbankAcc);
    $contact->setTaxNumber($custTaxNum);
    $contact->setAccountsReceivableTaxType($custArTaxType); //Must be 'INPUT' or 'OUTPUT'
    $contact->setAccountsPayableTaxType($custAPTaxType);//Must be 'INPUT' or 'OUTPUT'
    //$contact->setAddresses('POBOX','P O Box 123','Wellington','1410');


    $apiInstance->createContacts($xeroTenantId, $contact,true);
  }
}
/*Template code to upload details in xero*/
/*
$contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;

$contact->setName('Work Flow Solutions Inc.');
$contact->setFirstName('John David');
$contact->setLastName('Bachao');
$contact->setEmailAddress('jbachao@workflowsolutions.com.ph');


$contact->setSkypeUserName('jbachao100794');
$contact->setBankAccountDetails('01-0123-0123456-00');
$contact->setTaxNumber('12-345-678');
$contact->setAccountsReceivableTaxType('OUTPUT');
$contact->setAccountsPayableTaxType('INPUT');
//$contact->setAddresses('POBOX','P O Box 123','Wellington','1410');


$apiInstance->createContacts($xeroTenantId, $contact,true);*/

 ?>