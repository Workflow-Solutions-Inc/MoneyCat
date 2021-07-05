<?php

session_start();
//include("dbconn.php");



	$username = $_POST["username"];
	$password = $_POST["password"];
	if(($username=="admin")&&($password=="admin")){
		header('location: ../../Main.php');
	}else{
		?>
			<script type="text/javascript">
			alert("That user doesn't exist!");
			window.location="../../login.php?invalid=1";
			</script>
		<?php 
	}



?>






<?php
/*
session_start();
include("dbconn.php");

if($_GET["action"] == "login"){
$username = $_GET['user'];
$password = $_GET['pass'];
$dataareaid = '';
if($username && $password){

	$sql = "SELECT userid,cast(aes_decrypt(password,'password') as char(50)) as pass,defaultdataareaid from userfile where userid='$username'";
	$result = $conn->query($sql);

	//if($numrows!=0)
	if ($result->num_rows > 0){
		//code to login
		while ($row = $result->fetch_assoc())
		{
			$dbusername = $row['userid'];
			$dbpassword = $row['pass'];
			$dataareaid = $row['defaultdataareaid'];
			//echo $dbusername;
			//echo $dbpassword;
			//echo $username;
			//echo $password;
			}
			//check to see if they match!
			if($username == $dbusername && $password == $dbpassword)
			{
				//echo "Login successful! <a href='index.php'>Click</a> here to enter the member page!";
				$_SESSION['user'] = $dbusername;
				$_SESSION['defaultdataareaid'] = $dataareaid;
				header('location: menu.php');
			}
			else{
				header('location: index.php?wrongpassword');
				//echo "Login successful! ";
				?>
					<script type="text/javascript">
					alert("Invalid Login!")
					window.location="index.php?invalid"
					</script>
				<?php
			}
	}
	else {
		?>
			<script type="text/javascript">
			alert("That user doesn't exist!")
			window.location="index.php?invalid"
			</script>
		<?php 
   }
}

else 
	die("Please enter a username and a password!");
}
else if($_GET["action"] == "logout"){
	session_unset();
	header('location:index.php');
}
*/

?>