<?php
$currentDateTime = date('y-M-d h:i a');
date_default_timezone_set('Asia/Manila');
/*
$host="cloudservernewsoft.cyaggyha3ec8.us-east-1.rds.amazonaws.com";
$port=3306;
$socket="";
$user="newsoftcloud";
$password="newsoft2019";
$dbname="paysys2019_dev";
*/

/*web hosting*/
/*$host="216.218.206.54";
$port=3306;
$socket="";
$user="ticketce_ticketc";
$password="newsoft2019";
$dbname="ticketce_paysys2019_dev2";*/


/*AWS*/
/*$host="newsoftcloudserver2019-l-asia.cfeynnhvh6kn.ap-northeast-1.rds.amazonaws.com";
$port=3306;
$socket="";
$user="admin";
$password="newsoft2019";
$dbname="paysys2019_newsoftdev5";*/
$host="156.67.217.132";
$port=3306;
$socket="";
$user="wfsiadmin";
$password="wfsi2021admin";
$dbname="eyetax_dev";

// $host="newsoftcloud-t3medium-asia-hk.cxhly8drtukq.ap-east-1.rds.amazonaws.com";
// $port=3306;
// $socket="";
// $user="rootmaster";
// $password="newsoft2019";
// $dbname="xero_db";
/*
$host="SERVER";
$port=3306;
$socket="";
$user="root";
$password="newsoft2019!";
$dbname="paysys2019_newsoftdev4";*/


$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());

//$conn->close();

							
?>