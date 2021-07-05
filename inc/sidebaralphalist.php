<?php  
require_once('inc/basepath.php');
$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); 
?>
<!doctype html>
<html lang="en">
<body>


        <div class="sidebar" data-color="gray" data-image="assets/img/sidebar-4.jpg">

    <!--   you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple" -->


        <div class="sidebar-wrapper">
            <div class="logo">
                <a href="http://www.creative-tim.com" class="simple-text">
                   Alphalist
                </a>
            </div>

            <ul class="nav">
                
                <li class="<?php if($curPageName=='Alphalist.php'){echo 'active';}?>">
                    <a href="Alphalist.php">
                        <i class="pe-7s-news-paper"></i>
                        <p>Alphalist</p>
                    </a>
                </li>
                <li  class="<?php if($curPageName=='RegisterAlphalist.php'){echo 'active';}?>">
                    <a href="RegisterAlphalist.php">
                        <i class="pe-7s-browser"></i>
                        <p>Register</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>




</body>



</html>
