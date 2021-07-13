<?php  
require_once('inc/basepath.php');
session_start();
    $orgid = "";
if(isset($_SESSION["organisationID"])){
    $orgid = $_SESSION["organisationID"];
    $orgname = $_SESSION["organisationName"];
}

?>
<!doctype html>
<html lang="en">
<body>


    <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Main Profiling</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                      
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <p>
                                         <i class="pe-7s-menu"></i> <?php echo $orgname; ?>
                                        <b class="caret"></b>
                                    </p>

                              </a>
                              <ul class="dropdown-menu">
                                <li><a href="<?php echo $base_path; ?>uploader.php">Uploader</a></li>
                                <!-- <li><a href="<?php echo $base_path; ?>SalesRelief.php">DAT Files</a></li>
                                <li><a href="<?php echo $base_path; ?>Alphalist.php">Alphalist</a></li>
                                <li><a href="<?php echo $base_path; ?>Accounts.php">Accounts</a></li> -->
                                <li><a href="<?php echo $base_path; ?>userlogged.php">Home</a></li>
                                
                                <!-- <li><button id="showaccount" style="border:none;">Accounts</button></li> -->
                                <li class="divider"></li>
                                <li><a href="<?php echo $base_path; ?>index.php">Disconnect To Xero</a></li>
                              </ul>
                        </li>
                        <li>
                            <!-- <a href="<?php echo $base_path; ?>login.php">
                                <p><i class="pe-7s-back-2"></i> Log Out</p>
                            </a> -->
                        </li>
                        <li class="separator hidden-lg hidden-md"></li>
                    </ul>
                </div>
            </div>
        </nav>




</body>



</html>
