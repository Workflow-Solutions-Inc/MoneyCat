<?php
    session_start();

    if(!isset($_SESSION['user']))
    {
        header('location: login.php');
    }
    else
    {
        $user = $_SESSION["user"];
    }
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <title>Main</title>

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />


    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />
</head>
<body>

<div class="wrapper">
    <div class="sidebar" data-color="gray" data-image="assets/img/sidebar-4.jpg">

    <!--   you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple" -->


      <div class="sidebar-wrapper">
            <div class="logo">
                <a href="http://www.creative-tim.com" class="simple-text">
                   EyeTax
                </a>
            </div>

            <ul class="nav">
                <li class="active">
                    <a href="../itaxUsers/Main.html">
                        <i class="pe-7s-browser"></i>
                        <p>Main</p>
                    </a>
                <li>
                    <a href="https://www.xero.com/ph/">
                        <i class="pe-7s-science"></i>
                        <p>Integrate with Xero</p>
                    </a>
                </li>
                <!-- <li class="active-pro"> -->
                <li>
                    <a href="https://www.xero.com/ph/features-and-tools/accounting-software/">
                        <i class="pe-7s-rocket"></i>
                        <p>Go To Xero</p>
                    </a>
                </li>
                <li>
                    <a href="login.php">
                        <i class="pe-7s-door-lock"></i>
                        <p>Log Out</p>
                    </a>
                </li>
            </ul>
      </div>
    </div>

    <div class="main-panel">
    <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Dashboard</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-left">
                      
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                         <li>
                            <p style="margin-top: 20px;">
                                <?php
                                    echo $_SESSION['name'];
                                ?>
                            </p>
                        </li>
                        <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <p>
                     <i class="pe-7s-menu"></i> Menu
                    <b class="caret"></b>
                  </p>

                              </a>
                              <ul class="dropdown-menu">
                                <li><a href="process/controllers/authorization.php" onclick="">Connect To Xero</a></li>
                              </ul>
                        </li>
                        <li>
                            <a href="login.php">
                                <p><i class="pe-7s-door-lock"></i> Log Out</p>
                            </a>
                        </li>
            <li class="separator hidden-lg hidden-md"></li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">What is EyeTax ?</h4>
                            </div>
                            <div class="content">
                                <div class="header">
                                    <h4 class="title">What's Inside ? </h4>
                                    <p class="category"> - Xero Integration</p>
                                    <p class="category"> - Generate your Xero Data to Excel.</p>
                                    <p class="category"> - Generate your Xero Data to DAT.</p>
                                    <p class="category"> - Generate your Voucher to Xero.</p>
                                </div>

                                 <div class="header">
                                    <h4 class="title">About the Product</h4>
                                    <p class="category">A tool created through partnership with Xero.</p>
                                </div>

                                 <div class="header">
                                    <h4 class="title">About us</h4>
                                    <strong>A Company that fulfills and innovates your needs.</strong><br>
                                    <address>
                                    <strong>Workflow Solutions Inc.</strong><br>
                                    Unit 502 F&L Bldg., Commonwealth Ave., Brgy. Holy Spirit<br>
                                     Quezon City, Philippines
                                  </address>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-user">
                            <div class="image">
                                <img src="assets/img/workflowbanner1.jpg" alt="..."/>
                            </div>
                            <div class="content">
                                <div class="author">
                                     <a href="#">
                                    <img class="avatar border-gray" src="assets/img/workflowprofile.jpg" alt="..."/>

                                      <h4 class="title">EyeTax<br />
                                         <small>A tool that will help you with your xero data.</small>
                                      </h4>
                                    </a>
                                </div>
                                <p class="description text-center"> "There is no worse tyranny than to force a man <br>
                                                    to pay for what he does not want <br>
                                                   merely because you think it would be good for him."
                                </p>
                            </div>
                            <hr>
                            <div class="text-center">
                                <button href="#" class="btn btn-simple"><i class="fa fa-facebook-square"></i></button>
                                <button href="#" class="btn btn-simple"><i class="fa fa-twitter"></i></button>
                                <button href="#" class="btn btn-simple"><i class="fa fa-google-plus-square"></i></button>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    <ul>
                        
                    </ul>
                </nav>
                <p class="copyright pull-right">
                    &copy; <script>document.write(new Date().getFullYear())</script> Work Flow Solutions INC.</a>, a Company that fulfill your needs.
                </p>
            </div>
        </footer>

    </div>
</div>


</body>

    <!--   Core JS Files   -->
    <script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
  <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

  <!--  Charts Plugin -->
  <script src="assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>

    <!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
  <script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

  <!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
  <script src="assets/js/demo.js"></script>



</html>
