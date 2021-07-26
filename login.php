<?php
	session_start();
	
    if(isset($_SESSION['user']))
    {
    	session_unset();
        session_destroy();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Welcome Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

<!--===================================== Design and Style ==========================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--=================================================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(images/bg-01.jpg);">
					<span class="login100-form-title-1">
						Welcome
					</span>
				</div>
				<?php
					if (isset($_SESSION['error'])) {
						# code...
						echo '<div class="error">';
						echo $_SESSION['error'];
						echo '</div>';
						session_destroy();
					}
				?>

				<form class="login100-form validate-form" action="process/loginprocess.php" method="post">
					<div class="wrap-input100 validate-input m-b-26" data-validate="Email is required">
						<span class="label-input100">Email</span>
						<input class="input100" value="
						<?php
							if (isset($_SESSION['email'])) {
								# code...
								echo $_SESSION['email'];
							}
						?>
						" minlength="2" maxlength="30" placeholder="Email" id="login-name" name="email" required="required" pattern="[^*()/><\][\\\x22,;|]+" type="email" autofocus>
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
						<span class="label-input100">Password</span>
						<input class="input100" id="passInput" class="logintext-password" value="" minlength="2" maxlength="30" placeholder="Password" id="login-pass" name="password" required="required" type = "password">
						<span class="focus-input100"></span>
						
						<!-- hidden input for the mode function -->
						<input class="input100" value="login" minlength="2" maxlength="30" placeholder="Email" id="login-name" name="mode" required="required" pattern="[^*()/><\][\\\x22,;|]+" type="hidden" autofocus>
					</div>

					<div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div>
						<!------------------- Forgot password ------------------->
						<!-- <div>
							<a href="forgotpassword.php" class="txt1">
								Forgot Password?
							</a>
						</div> -->
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Login
						</button>
					</div>
					<!------------------------- Sign up ----------------------->
					<!-- <div class="register">Don't have an account? <a class="registerLink" href="register.php">Sign Up</a>

					</div> -->
				</form>
				
			</div>
		</div>
	</div>

	<script src="js/main.js"></script>

<script type="text/javascript" src="js/custom.js"></script>
</body>
</html>