<?php 
ini_set('display_errors', 'On');
  //require __DIR__ . '/vendor/autoload.php';
  //require_once('xeroconfig.php');
  require_once('controllers/config/xeroconfig.php');
  require_once('controllers/config/dbconn.php');
  require_once('vendor/autoload.php');
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

			$flag = 0;

			$query = "SELECT * FROM customer_bridge where cust_id = '".$_POST['custId']."'";
			$result = $conn->query($query);
					if ($result->num_rows > 0)
					{
						$flag = 1;
					}

			if($flag == 0){
				$custName = $_POST['custName'];
					//echo $custName
					//$custLastName = $_POST['custLastName'];
					if(isset($_POST['custEmail'])){
						$custEmail = $_POST['custEmail'];
					}else{
						$custEmail = "";
					}
					if(isset($_POST['AddressLine'])){
						$AddressLine = $_POST['AddressLine'];
					}else{
						$AddressLine = "";
					}
					if(isset($_POST['custTaxNum'])){
						$custTaxNum = $_POST['custTaxNum'];
					}else{
						$custTaxNum = "";
					}
					if(isset($_POST['phonetype'])){
						$phonetype = $_POST['phonetype'];
					}else{
						$phonetype = "";
					}
					if(isset($_POST['phone_number'])){
						$phone_number = $_POST['phone_number'];
					}else{
						$phone_number = "";
					}

					$contact = new \XeroAPI\XeroPHP\Models\Accounting\Contact;
					$address = new \XeroAPI\XeroPHP\Models\Accounting\Address;
					$phone = new \XeroAPI\XeroPHP\Models\Accounting\Phone;

		    $contact->setName($custName);
		    $contact->setEmailAddress($custEmail);
		    $contact->setTaxNumber($custTaxNum);

		    //echo $contact;
		    $apiInstance->createContacts($xeroTenantId, $contact, true);

		    $cust = new CUSTOMER();
				$cust->putcustomer($_POST['custId'],$custName);
				echo 1;

				// syncher for customer
				 


 				



			}else{
				echo 2;
			}

			 
			
			
		}

}


?>
