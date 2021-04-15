<?php
/*
 Backend Controllers for Money Cat PH

 Created By: Workflow Solutions Inc.
 Developer: John David Bachao
 Developed Date: 01-17-2021
 Description :


*/


/**
 * 
 */
class Customer 
{
	
	public function createCustomerRecords($contactid,$fullname,$email,$full_address,$phone_type,$TaxIdNumber)
	{
		$this->execute($contactid,$fullname,$email,$full_address,$phone_type,$TaxIdNumber);
	}

	private function execute($contactid,$fullname,$email,$full_address,$phone_type,$TaxIdNumber)
	{
		if ($this->checkContactExistence($contactid,$fullname,$email,$full_address,$phone_type,$TaxIdNumber))
		{
			updateExistingContact($contactid,$fullname,$email,$full_address,$phone_type,$TaxIdNumber);
		}
		else
		{
			createNewContact($contactid,$fullname,$email,$full_address,$phone_type,$TaxIdNumber);
		}
	}


	private function checkContactExistence($contactid,$fullname,$email,$full_address,$phone_type,$TaxIdNumber)
	{
		include('config/configuration.php');

 		$ret = false;

		$resqry = "SELECT * FROM mc_contacts where contactid = '".$contactid."' ";
				$result = $conn->query($resqry);
				if ($result->num_rows > 0)
				{
					$ret = true;
				}
		return $ret;
	}

	private function createNewContact($contactid,$fullname,$email,$full_address,$phone_type,$TaxIdNumber)
	{
		include('config/configuration.php');
		$sqlQry = "insert into mc_contacts (agreementid,
					contactid,
					loan_type,
					loan_amount,
					tax_type,
					tax_amount,
					amount_type,
					account,
					category,createdDateTime,updatedDateTime)
							values ('".$agreementid."','".$contactid."','".$loan_type."','".$loan_amount."','".$tax_type."','".$tax_amount."','".$amount_type."','".$account."','".$category."',now(),null);";
        if(mysqli_query($conn,$sqlQry))
        {
          	return "";
        }
        else
        {
          echo "error".$sqlQry."<br>".$conn->error;
        }
	}

	private function updateExistingContact($contactid,$fullname,$email,$full_address,$phone_type,$TaxIdNumber)
	{
		include('config/configuration.php');
		$sqlQry = "update mc_contacts set
						
						fullname = '".$fullname."',
						email = '".$email."',
						full_address = '".$full_address."',
						phone_type = '".$phone_type."',
						phone_number = '".$phone_number."',
						TaxIdNumber = '".$TaxIdNumber."',
						updatedDateTime = now()
						WHERE contactid = '".$contactid."'
						";
        if(mysqli_query($conn,$sqlQry))
        {
          	return "";
        }
        else
        {
          echo "error".$sqlQry."<br>".$conn->error;
        }
	}


}


?>
