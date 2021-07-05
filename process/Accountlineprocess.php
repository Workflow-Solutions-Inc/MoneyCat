<?php
	//code added by jonald
	//session_start();
		include("controllers/config/dbconn.php");
		$selval = $_GET['selval'];
		$code = $_GET["code"];
		//$decodeproof = base64_decode($proof);
	//echo $proof;
	$sql = "update accounts set accounttype = '$selval' where accountcode = '$code'";
		if(mysqli_query($conn,$sql))
		{
			echo "Rec Updated";

			
		}
		else
		{
			echo "error".$sql."<br>".$conn->error;
		}
	//echo $sql;

	//end of edit
  ?>