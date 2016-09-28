<?php
include 'connect_to_mysql.php';

$courseid = $_GET['courseid'];
if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}
		
mysql_query ("DELETE FROM usercourses WHERE userid ='$userid' AND courseid ='$courseid'") or die(mysql_error());

?>