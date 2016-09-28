<?php
include 'connect_to_mysql.php';

$schoolid = $_GET['schoolid'];
if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}
		
mysql_query ("UPDATE users SET schoolid='$schoolid' WHERE id='$userid'") or die(mysql_error());

?>