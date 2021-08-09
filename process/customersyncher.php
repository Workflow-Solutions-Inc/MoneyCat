<?php 
session_start();
$orgid = "";
if(isset($_SESSION["organisationID"]))
{
    $orgid = $_SESSION["organisationID"];
}
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


/*$jsonToarray = json_decode($apiInstance->getContacts($xeroTenantId, $if_modified_since = date("Y-m-d", strtotime("yesterday")), $where = 'ContactStatus == "ACTIVE"', $order = null, $i_ds = null, $page = null, $include_archived = null),true);
        $cnt2=  count($jsonToarray["Contacts"]);*/
        //echo $cnt2;
        $counter = 1;
        do{
          $jsonToarray = json_decode($apiInstance->getContacts($xeroTenantId, $if_modified_since = null, $where = 'ContactStatus == "ACTIVE"', $order = null, $i_ds = null, $page = $counter, $include_archived = null),true);
          $cnt2=  count($jsonToarray["Contacts"]);
          for ($i=0; $i < $cnt2 ; $i++) {
            $custID_ = $jsonToarray["Contacts"][$i]["ContactID"]; 
            if(isset($jsonToarray["Contacts"][$i]["Name"]) )
            {
              $custName_ = $jsonToarray["Contacts"][$i]["Name"];
            }
            else
            {
              $custName_ = "";
            }
            if(isset($jsonToarray["Contacts"][$i]["FirstName"]) )
            {
              $custName_fname = $jsonToarray["Contacts"][$i]["FirstName"];
            }
            else
            {
              $custName_fname = "";
            }
            if(isset($jsonToarray["Contacts"][$i]["LastName"]) )
            {
              $custName_lname = $jsonToarray["Contacts"][$i]["LastName"];
            }
            else
            {
              $custName_lname = "";
            }
             if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine1"]) )
            {
              $custAdd_line1 = $jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine1"];
            }
            else
            {
              $custAdd_line1 = "";
            }


            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine2"]) )
            {
              $custAdd_line2 = $jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine2"];
            }
            else
            {
              $custAdd_line2 = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine3"]) )
            {
              $custAdd_line3 = $jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine3"];
            }
            else
            {
              $custAdd_line3 = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["City"]) )
            {
              $custCity = $jsonToarray["Contacts"][$i]["Addresses"][$i]["City"];
            }
            else
            {
              $custCity = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["Region"]) )
            {
              $custRegion = $jsonToarray["Contacts"][$i]["Addresses"][$i]["Region"];
            }
            else
            {
              $custRegion = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["PostalCode"]) )
            {
              $custPostalCode = $jsonToarray["Contacts"][$i]["Addresses"][$i]["PostalCode"];
            }
            else
            {
              $custPostalCode = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["Country"]) )
            {
               $custCountry = $jsonToarray["Contacts"][$i]["Addresses"][$i]["Country"];
            }
            else
            {
              $custCountry = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["AttentionTo"]) )
            {
               $custAttentionTo = $jsonToarray["Contacts"][$i]["Addresses"][$i]["AttentionTo"];
            }
            else
            {
              $custAttentionTo = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["TaxNumber"][$i]) )
            {
               $custTax = $jsonToarray["Contacts"][$i]["TaxNumber"];
            }
            else
            {
              $custTax = "";
            }
            inserttocontacts($custID_,$custName_,$custName_fname,$custName_lname,$custAdd_line1,$custAdd_line2,$custAdd_line3,$custCity,$custRegion,$custPostalCode,$custCountry, $custAttentionTo, $custTax,$orgid);
        }
          $counter++;
          $jsonToarray = json_decode($apiInstance->getContacts($xeroTenantId, $if_modified_since = null, $where = 'ContactStatus == "ACTIVE"', $order = null, $i_ds = null, $page = $counter, $include_archived = null),true);
          $cnt2=  count($jsonToarray["Contacts"]);
          if($cnt2 == 0)
          {
            $counter = 0;
          }

        }while($counter != 0);

        $sql = "call sync_custinfo_bridge()";
        if(mysqli_query($conn,$sql))
        {
          #cho $sql;
        }
        else
        {
          #echo "error".$sql."<br>".$conn->error;
      
        }
        mysqli_close($conn);
        

        /*for ($i=0; $i < $cnt2 ; $i++) {
            $custID_ = $jsonToarray["Contacts"][$i]["ContactID"]; 
            if(isset($jsonToarray["Contacts"][$i]["Name"]) )
            {
              $custName_ = $jsonToarray["Contacts"][$i]["Name"];
            }
            else
            {
              $custName_ = "";
            }
            if(isset($jsonToarray["Contacts"][$i]["FirstName"]) )
            {
              $custName_fname = $jsonToarray["Contacts"][$i]["FirstName"];
            }
            else
            {
              $custName_fname = "";
            }
            if(isset($jsonToarray["Contacts"][$i]["LastName"]) )
            {
              $custName_lname = $jsonToarray["Contacts"][$i]["LastName"];
            }
            else
            {
              $custName_lname = "";
            }
             if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine1"]) )
            {
              $custAdd_line1 = $jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine1"];
            }
            else
            {
              $custAdd_line1 = "";
            }


            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine2"]) )
            {
              $custAdd_line2 = $jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine2"];
            }
            else
            {
              $custAdd_line2 = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine3"]) )
            {
              $custAdd_line3 = $jsonToarray["Contacts"][$i]["Addresses"][$i]["AddressLine3"];
            }
            else
            {
              $custAdd_line3 = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["City"]) )
            {
              $custCity = $jsonToarray["Contacts"][$i]["Addresses"][$i]["City"];
            }
            else
            {
              $custCity = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["Region"]) )
            {
              $custRegion = $jsonToarray["Contacts"][$i]["Addresses"][$i]["Region"];
            }
            else
            {
              $custRegion = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["PostalCode"]) )
            {
              $custPostalCode = $jsonToarray["Contacts"][$i]["Addresses"][$i]["PostalCode"];
            }
            else
            {
              $custPostalCode = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["Country"]) )
            {
               $custCountry = $jsonToarray["Contacts"][$i]["Addresses"][$i]["Country"];
            }
            else
            {
              $custCountry = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["Addresses"][$i]["AttentionTo"]) )
            {
               $custAttentionTo = $jsonToarray["Contacts"][$i]["Addresses"][$i]["AttentionTo"];
            }
            else
            {
              $custAttentionTo = "";
            }

            if(isset($jsonToarray["Contacts"][$i]["TaxNumber"][$i]) )
            {
               $custTax = $jsonToarray["Contacts"][$i]["TaxNumber"];
            }
            else
            {
              $custTax = "";
            }
            inserttocontacts($custID_,$custName_,$custName_fname,$custName_lname,$custAdd_line1,$custAdd_line2,$custAdd_line3,$custCity,$custRegion,$custPostalCode,$custCountry, $custAttentionTo, $custTax,$orgid);
        }

        $sql = "call sync_custinfo_bridge()";
				if(mysqli_query($conn,$sql))
				{
					#cho $sql;
				}
				else
				{
					#echo "error".$sql."<br>".$conn->error;
			
				}
				mysqli_close($conn);*/

  function inserttocontacts($custID_,$custName_,$custName_fname,$custName_lname,$custAdd_line1,$custAdd_line2,$custAdd_line3,$custCity,$custRegion,$custPostalCode,$custCountry, $custAttentionTo, $custTax,$orgid)
  {
    include("controllers/config/dbconn.php");
    $chkr = "";
    $query = "SELECT customerId from custinfo where customerID = '$custID_' and company = '$orgid'; ";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc())
    {
      $chkr = $row["customerId"];
    }
      
    if($chkr=="")
    {
      $sql = "INSERT into custinfo (customerId, customerName, addressline3, addressline1, addressline2, postalid, region, city, firstname, lastname, company) values('".$custID_."','".$custName_."','".$custAdd_line3."','".$custAdd_line1."','".$custAdd_line2."','".$custPostalCode."','".$custRegion."','".$custCity."','".$custName_fname."','".$custName_lname."','".$orgid."')";
      if(mysqli_query($conn,$sql))
      {
         //echo "New Rec Created";
      }
      else
      {
        echo "error".$sql."<br>".$conn->error;
      }
    }
    else
    {
        $sql = "update custinfo set customerName = '".$custName_."', addressline3 = '".$custAdd_line3."', addressline1 = '".$custAdd_line1."', addressline2 = '".$custAdd_line2."'
        , postalid = '".$custPostalCode."', region = '".$custRegion."', city = '".$custCity."', firstname = '".$custName_fname."', lastname = '".$custName_lname."' where customerId = '".$custID_."' and company = '".$orgid."'";
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


?>
