<?php 
require_once('inc/sidebarexcelreports.php');
require_once('inc/header.php');
include("process/controllers/config/dbconn.php");
?>

<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Setup</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="assets/css/light-bootstrap-dashboard.css?v=1.4.0" rel="stylesheet"/>
  <link href="assets/css/demo.css" rel="stylesheet" />
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

    <!-- js  -->
    <style type="text/css">
    
</style>

</head>
<body>

<div class="wrapper">
    <div class="main-panel">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                      <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Loan Disbursement Setup</h4>
                                    <p class="category">Note: Setup all the necessary data before uploading a json file.</p>
                                </div>
                                <div class="content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                              <i class="pe-7s-date"></i>   <label>Tax Rates</label>
                                                <select style="margin-left: 30px; width: auto;" class="form-control" onclick="validateconnectiontoapi();" id="taxrates" onchange="updateTaxrate()">
                                                    
                                                </select>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <i class="pe-7s-date"></i> <label>Add Disbursement Channel</label>
                                                <br>
                                                <div class="col-md-12">
                                                    <div class="col-md-4"><input class="form-control" type="text" placeholder="Account code" id="loanaccountcode"></div>
                                                    <div class="col-md-4">
                                                        <select style="margin-left: 7px;" class="form-control" id="xerochannel">
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2"><input class="btn  btn-fill pull-right" type="button" value="Save Channel" onclick="saveLoanChannel()"></div>      
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            <div class="form-group">
                                                <i class="pe-7s-date"></i>   <label>List of Disbursement Channel</label>
                                                <br>
                                                <div style="height:400px; overflow-y: auto; margin-left: 30px; width: auto;" id="listofloanchannel">
                                              
                                            </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>     
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Payment Setup</h4>
                                    <p class="category">Note: Setup all the necessary data before uploading a json file.</p>
                                </div>
                                <div class="content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" style="display: none;">
                                              <i class="pe-7s-date"></i>   <label>Tax Rates</label>
                                                <select class="form-control"  onclick="validateconnectiontoapi();" id="taxrates2" onchange="updateTaxrate2()">
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <i class="pe-7s-date"></i> <label>Select Bank Account</label>
                                                <br>
                                                <div class="col-md-12">
                                                    <div class="col-md-12">
                                                        <select style="width: auto;" class="form-control"  onclick="validateconnectiontoapi();" id="paymentchannel" onchange="updatePaymentChannel()">
                                                        </select>
                                                    </div>     
                                                </div>
                                            </div>                                       
                                            <div class="form-group" style="display: none;">
                                                <i class="pe-7s-date"></i><label>List of Disbursement Channel</label>
                                                <br>
                                                <div style="height:400px; overflow-y: scroll;" id="listofpaymentchannel">
                                              
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                              <i class="pe-7s-date"></i>   <label>Add Accounts</label>
                                              <br>
                                                <div class="col-md-12">
                                                    <div class="col-md-3">
                                                        <select class="form-control" id="accountcode">
                                                    
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3"><input class="form-control" type="text" id ="accountname" placeholder="Account name" ></div>
                                                    <div class="col-md-3" style="display:none;"><input class="form-control" type="text" id ="accountdesc" placeholder="Description" ></div>
                                                    <div class="col-md-3">
                                                        <select class="form-control" id="accounttype">
                                                            <option value = "2">Receive Payment</option>
                                                            <option value = "1">Invoice Payment</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3"><input class="btn  btn-fill pull-right" type="button" value="Save Account" onclick="addupdate()"></div>      
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <i class="pe-7s-date"></i><label>List of Accounts</label>
                                                <br>
                                                <div style="height: 400; overflow-y: auto; margin-left: 30px; width: auto;" id="listofaccounts" >
                                              
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <i class="pe-7s-date"></i><label>Overpayment Setup</label>
                                                <br>
                                                <div class="col-md-12">
                                                        <select style="margin-left: 15px; width: auto;" class="form-control" onclick="validateconnectiontoapi();" id="accountcodeoverpayment" onchange="updateoverpaymentchannel()">
                                                    
                                                        </select>
                                                    </div>
                                              
                                                    
                                            </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>     
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <?php include("inc/footer.php"); ?>
    </div>
</div>




</body>


<div class="modal fade" id="pleasereconnectmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Please Reconnect to xero</h3>
        </div>
      <div class="modal-body">
        <!-- <h3 style="color:seagreen;"><i id="progresslabel">Processing..</i> <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
        <span class="sr-only">Loading...</span></h3> -->
        <a href="process/controllers/authorization.php">Reconnect to xero</a>
        <div>
            
        </div>
      </div>
    </div>
  </div>
</div>





</html>
<script src="assets/js/jquery.3.2.1.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

<!--  Charts Plugin -->
<script src="assets/js/chartist.min.js"></script>

<!--  Notifications Plugin    -->
<script src="assets/js/bootstrap-notify.js"></script>

<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
<script src="assets/js/light-bootstrap-dashboard.js?v=1.4.0"></script>

<!-- Light Bootstrap Table DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>
<script type="text/javascript" src="script/setup.js" ></script>
<script type="text/javascript">
    window.onload = function(value){
        getTaxrates();
        getListofAccounts();
        getListofLoanChannel();
        getListofPaymentChannel();
        getListofLoanXeroChannel();
        getPaymentChannelList();
        getxeroaccounts();
        getselectedopchannel();
    }
</script>
