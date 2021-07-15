<?php

require_once('inc/sidebarexcelreports.php');
require_once('inc/header.php');
include("process/controllers/config/dbconn.php");
?>
<!doctype html>
    <html lang="en">
    <style>
    #myProgress {
      width: 100%;
      background-color: #ddd;
    }

    #myBar {
      width: 10%;
      height: 30px;
      background-color: #04AA6D;
      text-align: center;
      line-height: 30px;
      color: white;
    }
    </style>
    <head>

        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="assets/img/favicon.png">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

        <title>Contacts - Upload your contact to xero</title>

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
                                        <h4 class="title">Contacts</h4>
                                        <p class="category">Note: Please select a json file extension</p>
                                    </div>
                                    <div class="content">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                  <i class="pe-7s-date"></i>   <label>Choose File</label>
                                                  <input type="File" name="myjson" id="myjson" class="form-control" required>
                                                  <br>
                                                  <div class="col-md-12">
                                                    <div class="col-md-5"></div>
                                                    <div class="col-md-5"></div>
                                                    <div class="col-md-1"><button onclick="validate()" class="btn  btn-fill pull-right" id="btnvalidate">validate</button></div>
                                                    <div class="col-md-1"><button onclick="upload()" class="btn  btn-fill pull-right" id="btnupload">Upload</button></div>
                                                </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>     
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-12" style="display: none;">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Contact Json</h4>
                        </div>
                        <pre style="overflow-y: scroll; height: 400px;" id="result">
                        </pre>
                    </div>
                </div>
                <div class="col-md-12" style="display:none;">
                    <div class="card">
                        <div class="header">
                            <h4 class="title">Upload Logs</h4>
                            <hr>
                            <h5 id="progressbarid" class="title">Progress: <b id="currentjsonupload">1</b> out of <b id="totaljsondata">1</b></h5>
                        </div>
                        <br>
                        <pre style="overflow-y: scroll; height: 400px;" id="uploadresult">
                        </pre>
                    </div>
                </div>
                <div class="col-md-12">
                    <div>
                        <button onclick="showPleaseWait3();" class="btn  btn-fill pull-right">View Upload Logs</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<!-- Modal -->
<div class="modal fade" id="pleaseWaitDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <!-- <h3 style="color:seagreen;"><i id="progresslabel">Processing..</i> <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>
        <span class="sr-only">Loading...</span></h3> -->
        <h3 style="color:seagreen;" id = "overalllabel"><i id="progresslabel">Processing..</i> <iframe src="https://giphy.com/embed/sSgvbe1m3n93G" width="100%" height="50%" frameBorder="0" class="giphy-embed"></iframe></h3>
        <div>
            
        </div>
      </div>
    </div>
  </div>
</div>

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

<div class="modal fade" id="logsmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Contact Upload Logs</h3>
        </div>
      <div class="modal-body">
        <pre id="testresult" style="overflow-y: scroll; height: 600px;margin : 0;">
            <dl id="testresult">
            </dl>
        </pre>
      </div>
      <div class="modal-footer">
          <button onclick="hidePleaseWait3()" class="btn  btn-danger pull-right">close</button>
      </div>
    </div>
  </div>
</div>

<!--   Core JS Files   -->
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
<script type="text/javascript" src="script/customer.js" ></script>



<script type="text/javascript">
    window.onload = function(value){
        //synccustomer();
        document.getElementById("btnupload").disabled = true;
    }

    
</script>

</html>
