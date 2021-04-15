<?php 
include_once('../controllers/data.php');

if (isset($_POST['action']))
{
		if ($_POST['action'] == "postdata")
		{

			$custName = $_POST['custName'];
			$custFirstname = $_POST['custFirstname'];
			$custLastName = $_POST['custLastName'];
			$custEmail = $_POST['custEmail'];
			$custSkype = $_POST['custSkype'];
			$custbankAcc = $_POST['custbankAcc'];
			$custTaxNum = $_POST['custTaxNum'];
			$custArTaxType = $_POST['custArTaxType'];
			$custAPTaxType = $_POST['custAPTaxType'];
			$insertrec = new executeUpload();

			$insertrec->executeUploading($custName,$custFirstname,$custLastName,$custEmail,$custSkype,$custbankAcc,$custTaxNum,$custArTaxType,$custAPTaxType); 
			
			
		}

}
?>