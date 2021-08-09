<?php
/*
 Backend Controllers for Kontrata PH

 Created By: Sypro-IT
 Developer: John David Bachao
 Developed Date: 01-17-2021
 Description : This class contains functions for all user use.
 Usage: Creating User, Forgot Password, Account Activation
 	All public functions must be call outside of this file.


*/

class CUSTOMER
{


	public function  putcustomer($custId, $custName)
	{
 		include('config/dbconn.php');

		$sql = "INSERT INTO customer_bridge (cust_id, cust_name)
		values 
		('$custId','$custName')";
		if(mysqli_query($conn,$sql))
		{
			#cho $sql;
		}
		else
		{
			#echo "error".$sql."<br>".$conn->error;
	
		}
	
	}
	public function  getcustomerid($custId)
	{
 		include('config/dbconn.php');
 		$custid = "";
		$sql = "SELECT cust_info
				FROM customer_bridge
				where cust_id = '$custId'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$custid = $row['cust_info'];
			}
		
		}
		return $custid;
	
	}
	public function  getinvoiceid($invid)
	{
 		include('config/dbconn.php');
 		$id = "";
		$sql = "SELECT distinct id from invoices where Invoiceid = '$invid' and invoicestatus != 'VOIDED';";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$id = $row['id'];
			}
		
		}
		return $id;
	
	}
	public function  getaccountid($accid)
	{
 		include('config/dbconn.php');
 		$id = "";
		$sql = "SELECT distinct accountID from accounts where accountcode = '$accid'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while ($row = $result->fetch_assoc())
			{
				$id = $row['accountID'];
			}
		
		}
		return $id;
	
	}

}


?>