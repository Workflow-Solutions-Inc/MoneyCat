<?php  
session_start();
include("controllers/config/dbconn.php");
	$orgid = "";
	if(isset($_SESSION["organisationID"])){
	    $orgid = $_SESSION["organisationID"];
	}
	$rdo = $_POST["rdo"];
	$tin = $_POST["tin"];
	$withcateg = $_POST["withcateg"];
	$taxclass = $_POST["taxclass"];
	$Signatory = $_POST["Signatory"];
	//echo $orgid;

	$sql = "UPDATE organisation set rdo = '".$rdo."', tin = '".$tin."', withholding = '".$withcateg."', taxclass = '".$taxclass."', signatory = '".$Signatory."' WHERE ID ='".$orgid."' ";
                        if(mysqli_query($conn,$sql))
                        {
                          //echo "New Rec Created";

                        }
                        else
                        {
                          echo "error".$sql."<br>".$conn->error;
                        }
    ?>
			<script type="text/javascript">
			alert("Saved");
			//header('location: RegisterAlphalist.php')
			window.location="../RegisterAlphalist.php?Success";
			</script>
		<?php 
	
?>