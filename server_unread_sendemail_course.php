<?php
include 'connect_to_mysql.php';  // connect to mysql database.
if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}
if (isset($_SESSION['schoolid'])){
$schoolid = $_SESSION['schoolid']; 
} else {
$schoolid = 1;
}
if (isset($_SESSION['courseid'])){
$courseid = $_SESSION['courseid']; 
} else {
$courseid = 1;
}
$ip = $_SESSION['ip'];

if ($userid != 0){
$duitasuo_user = mysql_query("SELECT * FROM users WHERE id='$userid' ORDER BY id ASC;") OR die(mysql_query());      
$row_user = mysql_fetch_assoc($duitasuo_user);
$username = $row_user['username'];
$email = $row_user['email'];
} else {
$username = $ip;
$email = "admin@tuttr.me";
}

$update = $_GET['update'];

				if ($update == 0){
				$subject = "You have a new comment from tuttr.me";
				$body = "Hi $username, \n\nYou have an unread comment, please click the link below to check:\n\nhttp://tuttr.me/course.php?schoolid=".$schoolid."&courseid=".$courseid."\n\nThank you.\n\n\ntuttr.me Inc.";
				} else {
				$subject = "You have a new message from tuttr.me";
				$body = "Hi $username, \n\nYou have an unread message, please click the link below to check:\n\nhttp://tuttr.me/course.php?schoolid=".$schoolid."&courseid=".$courseid."\n\nThank you.\n\n\ntuttr.me Inc.";
				}
				
				$from = "admin@tuttr.me";
				$to = $email;
				$headers = "From: Admin (no reply)" . "\r\n" .
				"CC: jameswang218@gmail.com";

				//mail($to,$subject,$body,$headers);
	
?>