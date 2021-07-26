<?php

session_start();

//database connection
include("controllers/config/dbconn.php");

//stored procedure calling
$query = "call sp_login('".$_POST["mode"]."','".$_POST["email"]."','".$_POST["password"]."','','');";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$message = $row["message"];

//validation
	if ($message == "Username does not exists." or $message == "Password is incorrect.")
	{
		?>
		<script type="text/javascript">
			<?php
				$_SESSION['error'] = $message;
			?>
			window.location.href = "../login.php";
		</script>
		<?php
	}
	else
	{
		?>
		<script type="text/javascript">
			//landing page
			<?php
				$_SESSION['user'] = $_POST["email"];
				$_SESSION['name'] = str_replace("Login successful. Welcome ", "", $message);
				$_SESSION['name'] = "User: " . str_replace(".", "", $_SESSION['name']);
			?>
			window.location.href = "../index.php";
		</script>
		<?php
	}


?>



