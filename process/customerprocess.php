<?php
session_start();
ini_set('display_errors', 'On');
require_once('controllers/config/xeroconfig.php');
require_once('vendor/autoload.php');
require_once('controllers/storage.php');
use XeroAPI\XeroPHP\AccountingObjectSerializer;

if ($_POST['action'] == "postdata")
{
  //validateContactId();
  //exit if id already exist
  $custName = getValue($_POST['custName']);
  $custEmail = getValue($_POST['custEmail']);
  $AddressLine = getValue($_POST['AddressLine']);
  $custTaxNum = getValue($_POST['custTaxNum']);
  $phonetype = getValue($_POST['phonetype']);
  $phone_number = getValue($_POST['phone_number']);

  $custName = preg_replace('/\s+/', ' ', $custName);
  
  insertCustomertoXero($clientid, $clientsecret, $callback, $custName, $custEmail, $AddressLine, $custTaxNum, $phonetype, $phone_number);
  //syncContacts($custName);
  
}

function getValue($fieldname)
{
  if(isset($fieldname))
  {
    $value = $fieldname;
  }
  else
  {
    $value = "";
  }

  return $value;
}

function validateContactId($custId)
{
  include('controllers/config/dbconn.php');
  $errormessage = "";
  $query = "SELECT * FROM customer_bridge where cust_id = '".$custId."'";
  $result = $conn->query($query);
  if ($result->num_rows > 0)
  {
    $errormessage = 1;
    //exit();
  }
  return $errormessage;
}

function insertCustomertoXero($clientid, $clientsecret, $callback, $custName, $custEmail, $AddressLine, $custTaxNum, $phonetype, $phone_number)
{
  // Storage Classe uses sessions for storing token > extend to your DB of choice
  try
  {
    $storage = new StorageClass();
    $xeroTenantId = (string)$storage->getSession()['tenant_id'];
    //session_start();

    $provider = new \League\OAuth2\Client\Provider\GenericProvider
    ([
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
  }
  catch (\XeroAPI\XeroPHP\ApiException $e)
  {
      header("location: ../uploader.php?invalid=0");
  }
  
  
  try
  {
    $startofcount = $_POST['counter'];
    $messagealert = "";
    $contact_array = new \XeroAPI\XeroPHP\Models\Accounting\Contacts;
    $contactlines = [];
    
    $custidarray = explode("|",$_POST['custId']);
    $custnamearray = explode("|",$custName);
    $custEmailarray = explode("|",$custEmail);
    $AddressLinearray = explode("|",$AddressLine);
    $custTaxNumarray = explode("|",$custTaxNum);
    $phonetypearray = explode("|",$phonetype);
    $phone_numberarray = explode("|",$phone_number);
    for($i = 0; $i < count($custnamearray) - 1; $i++)
    {
      $contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
      $address = new \XeroAPI\XeroPHP\Models\Accounting\Address;
      $phone = new \XeroAPI\XeroPHP\Models\Accounting\Phone;
      $contact->setName($custnamearray[$i]);
      $contact->setEmailAddress($custEmailarray[$i]);
      $contact->setTaxNumber($custTaxNumarray[$i]);
      $address->setAddressType("POBOX");
      $address->setAddressLine1($AddressLinearray[$i]);
      $arr_lineitems = []; 
      array_push($arr_lineitems, $address);
      $phone->setPhoneType('MOBILE');
      $phone->setPhoneNumber($phone_numberarray[$i]);
      $arr_lineitems2 = []; 
      array_push($arr_lineitems2, $phone);
      $contact->setAddresses($arr_lineitems);
      $contact->setPhones($arr_lineitems2);
      $startofcount++;
      if(validateContactId($custidarray[$i]) != 1)
      {
        array_push($contactlines, $contact);
        $contact_array ->setContacts($contactlines);
        //$messagealert .= '</div><br><div><h5 style="color:green;">ID: '.$custidarray[$i].' Successfully uploaded!</h5></div>'; 
        $messagealert .= '<dt>Line no: '.$startofcount.'</dt>
              <dd style="margin : 0;">- Contact ID: '.$custidarray[$i].'</dd>
              <dd style="margin : 0;">- Name: '.$custnamearray[$i].'</dd>
              <dd style="color:green;margin : 0">- Successfully Uploaded.</dd><hr>';
        syncContacts($custnamearray[$i], $custidarray[$i]);
      }
      else
      {
        '<dt>Line no: '.$startofcount.'</dt>
              <dd style="margin : 0;">- Contact ID: '.$custidarray[$i].'</dd>
              <dd style="margin : 0;">- Name: '.$custnamearray[$i].'</dd>
              <dd style="color:red;margin : 0">- Contact ID already exist.</dd><hr>';
        //$messagealert .= '</div><br><div><h5 style="color:red;">ID: '.$custidarray[$i].' Already exist!</h5></div>';
      }
    }

   $apiInstance->createContacts($xeroTenantId, $contact_array, true);
   echo $messagealert;

  }
  catch (\XeroAPI\XeroPHP\ApiException $e)
  {
      $error = AccountingObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\XeroAPI\XeroPHP\Models\Accounting\Error',
          []
      );
      $message = "ApiException - " . $error->getElements()[0]["validation_errors"][0]["message"];
      //echo $messagealert .= '</div><br><div><h5 style="color:red;">ID: '.$custidarray[$i].' '. $message.'</h5></div>';
      echo $messagealert;
  }
}

function syncContacts($custName,$custId)
{
  include_once('controllers/customer.php');
  $cust = new CUSTOMER();
  $cust->putcustomer($custId,$custName);
}

?>
