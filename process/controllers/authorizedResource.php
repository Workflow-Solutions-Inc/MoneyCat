<?php
  ini_set('display_errors', 'On');
  require __DIR__ . '/vendor/autoload.php';
  require_once('config/xeroconfig.php');
  //require_once('C:\Users\SysDev - PC3\vendor\autoload.php');
  require_once('storage.php');
  include("config/dbconn.php");

  // Use this class to deserialize error caught
  use XeroAPI\XeroPHP\AccountingObjectSerializer;

  // Storage Classe uses sessions for storing token > extend to your DB of choice
  $storage = new StorageClass();
  $xeroTenantId = (string)$storage->getSession()['tenant_id'];

  if ($storage->getHasExpired()) {
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
  }

  $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken( (string)$storage->getSession()['token'] );
  $config->setHost("https://api.xero.com/api.xro/2.0");        

  $apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
      new GuzzleHttp\Client(),
      $config
  );

$jsonToarray = json_decode($apiInstance->getOrganisations($xeroTenantId),true);
//echo $apiInstance->getOrganisations($xeroTenantId);
$orgid = $jsonToarray["Organisations"][0]["OrganisationID"];
$orgname = $jsonToarray["Organisations"][0]["Name"];
$orgtype = $jsonToarray["Organisations"][0]["OrganisationType"];
$countrycode = $jsonToarray["Organisations"][0]["CountryCode"];
$orgstatus = $jsonToarray["Organisations"][0]["OrganisationStatus"];

insertorg($orgid, $orgname, $orgtype, $countrycode, $orgstatus);


function insertorg($param1, $param2,$param3, $param4,$param5){
  include("config/dbconn.php");
  $orgcompareid = "";
  $query = "SELECT * FROM organisation where ID = '".$param1."'";
  $result = $conn->query($query);
  while ($row = $result->fetch_assoc()){
    $orgcompareid = $row["ID"];
  }
  if($orgcompareid == ""){
      $sql = "INSERT into organisation (ID, name, type, countrycode, status) values('".$param1."','".$param2."','".$param3."','".$param4."','".$param5."')";
              if(mysqli_query($conn,$sql))
                {
                  //echo "New Rec Created";
                }
              else
                {
                  echo "error".$sql."<br>".$conn->error;
                }
  }else{
     $sql = "UPDATE organisation SET name = '".$param2."', type = '".$param3."', countrycode = '".$param4."', status = '".$param5."' WHERE ID = '".$param1."'";
              if(mysqli_query($conn,$sql))
                {
                  //echo "New Rec Created";
                }
              else
                {
                  echo "error".$sql."<br>".$conn->error;
                }
  }
}


session_start();
$_SESSION["organisationID"] = $orgid;
$_SESSION["organisationName"] = $orgname;
$_SESSION["opchannel"] = '4010213@Overpayment';




//header('location: MainSelection.php');
  header('location: ../../userlogged.php');

?>
