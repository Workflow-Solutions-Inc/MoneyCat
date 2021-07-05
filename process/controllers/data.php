<?php
  ini_set('display_errors', 'On');
  //require __DIR__ . '/vendor/autoload.php';
  //require_once('xeroconfig.php');
  require_once('config/xeroconfig.php');
  require_once('C:\Users\SysDev - PC3\vendor\autoload.php');
  require_once('storage.php');

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


  function executeUploading($custName,$custFirstname,$custLastName,$custEmail,$custSkype,$custbankAcc,$custTaxNum,$custArTaxType,$custAPTaxType)
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

    //echo $contact;
    $apiInstance->createContacts($xeroTenantId, $contact);
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