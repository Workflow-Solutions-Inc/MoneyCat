<?php  
require_once('inc/basepath.php');
$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); 
?>
<!doctype html>
<html lang="en">
<body>


    <div class="sidebar" data-color="gray" data-image="assets/img/sidebar-4.jpg">

    	<div class="sidebar-wrapper">
            <div class="logo">
                <label class="simple-text">Uploader</label>
            </div>

            <ul class="nav">
                
                 <li   class="<?php if($curPageName=='uploader.php'){echo 'active';}?>">
                     <a href="uploader.php">
                        <i class="pe-7s-cash"></i>
                        <p>Upload Contact</p> </a>
                </li>
                <li class="<?php if($curPageName=='loanuploader.php'){echo 'active';}?>">
                    <a href="loanuploader.php">
                        <i class="pe-7s-credit"></i>
                        <p>Upload Loans</p>   </a>
                </li>
                <li class="<?php if($curPageName=='paymentuploader.php'){echo 'active';}?>">
                    <a href="paymentuploader.php">
                        <i class="pe-7s-credit"></i>
                        <p>Upload Payments</p>   </a>
                </li>

                <li class="<?php if($curPageName=='setup.php'){echo 'active';}?>">
                    <a href="setup.php">
                        <i class="pe-7s-tools"></i>
                        <p>Setup</p>   </a>
                </li>
            </ul>
        </div>
    </div>




</body>



</html>
