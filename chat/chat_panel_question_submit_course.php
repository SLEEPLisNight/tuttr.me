﻿<?php
include '../connect_to_mysql.php'; 
if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}
$userchatid = $_SESSION['chatid'];
$courseid = $_SESSION['courseid'];
$anonymous = $_GET['anonymous'];

function utf8_urldecode($str){
	$str = htmlspecialchars($str, ENT_QUOTES);
	$str = mysql_real_escape_string($str);
	return $str;
}

$result_chatname = mysql_query("SELECT * FROM coursechatname WHERE userchatid='$userchatid' AND userid='$userid' AND courseid='$courseid' ORDER BY id ASC;") or die();
$chatname_row = mysql_fetch_array($result_chatname);
$chatnameid = $chatname_row['id'];	

if ($anonymous == 1){
$num = rand(1,1000);
$username = "Unknown ".$num;
mysql_query ("UPDATE coursechatname SET username='$username', loginas=1 WHERE id='$chatnameid'") or die(mysql_error());
} else {
mysql_query ("UPDATE coursechatname SET loginas=2 WHERE id='$chatnameid'") or die(mysql_error());
}

?>