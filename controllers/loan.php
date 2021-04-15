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

/**
 * 
 */
class Loans 
{
	public function createLoans()
	{

	}

	private function execute()
	{

	}

	private function checkExistenceLoan()
	{
		include('config/configuration.php');

 		$ret = false;

		$resqry = "SELECT * FROM mc_loans where agreementid = '".$contactid."' ";
				$result = $conn->query($resqry);
				if ($result->num_rows > 0)
				{
					$ret = true;
				}
		return $ret;
	}

	private function createNewLoanTransaction()
	{
		include('config/configuration.php');
		$sqlQry = "insert into mc_contacts (contactid,fullname,email,full_address,phone_type,phone_number,TaxIdNumber,createdDateTime,updatedDateTime)
							values ('".$contactid."','".$fullname."','".$email."','".$full_address."','".$phone_type."','".$TaxIdNumber."',now(),null);";
        if(mysqli_query($conn,$sqlQry))
        {
          	return "";
        }
        else
        {
          echo "error".$sqlQry."<br>".$conn->error;
        }
	}
	private function updateLoanTransaction()
	{

	}
}

?>