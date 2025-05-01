<?php
session_start();
include "db_config.php";

if (empty($_POST['username']) || empty($_POST['password'])) {
    die('Please fill both the username and password field!');
}

function isMobile () {
  return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "mobile"));
}

$username = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
$username = str_replace(array('\'','"','<','>','-','#','&'), '',$username);
$username = strip_tags($username);

$password = $_POST['password'];

$uname = "demo";
$upass = "demo";

if(($username==$uname) && ($password==$upass)){

$_SESSION['name'] = $uname;
$_SESSION['loggedin'] = TRUE;

$device = isMobile() ? "Mobile" : "Desktop";

$date = new DateTime( "now", new DateTimeZone( "Europe/Athens" ) );
$date = $date->format( "Y-m-d H:i:s" );


        if($_POST['device']!=1){
                header('Location: https://lps.dev-maister.gr/demo/ai/api_1_v11_distilled.php');  
        }

	} else {
		header('Location: https://lps.dev-maister.gr/demo/');
	}
