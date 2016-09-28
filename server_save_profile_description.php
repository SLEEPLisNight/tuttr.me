<?php
include 'connect_to_mysql.php';

if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}

function utf8_urldecode($str){
	$str = htmlspecialchars($str, ENT_QUOTES);
	$str = mysql_real_escape_string($str);
	return $str;
}	

$description = utf8_urldecode($_GET['description']);
		
mysql_query ("UPDATE users SET description='$description' WHERE id='$userid'") or die(mysql_error());

echo "saved";


?>