<?php  
require_once('inc/basepath.php');
$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); 
?>
<!doctype html>
<html lang="en">
<body>


    <div class="sidebar" data-color="gray" data-image="assets/img/sidebar-4.jpg">

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

        <div class="sidebar-wrapper">
            <div class="logo">
                <label class="simple-text">DAT Reports</label>
            </div>

            <ul class="nav">
               
                 <li   class="<?php if($curPageName=='SalesRelief.php'){echo 'active';}?>">
                     <a href="SalesRelief.php">
                        <i class="pe-7s-cash"></i>
                        <p>Sales Relief</p> </a>
                </li>
                <li class="<?php if($curPageName=='PurchaseRelief.php'){echo 'active';}?>">
                    <a href="PurchaseRelief.php">
                        <i class="pe-7s-credit"></i>
                        <p>Purchase Relief</p>   </a>
                </li>
                 <!-- <li>
                    <a href="../Vouchers/voucher.php">
                        <i class="pe-7s-note2"></i>
                        <p>Vouchers / Integrations</p>
                    </a>
                </li> -->
            </ul>
        </div>
    </div>




</body>



</html>
