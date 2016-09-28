<?php
include 'connect_to_mysql.php';

$courseid = $_GET['courseid'];
if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}

$result_usercourses = mysql_query("SELECT * FROM usercourses WHERE userid='$userid' AND courseid='$courseid' ORDER BY id ASC;") or die();
$num_rows =  mysql_num_rows($result_usercourses);
		
if ($num_rows == 0){
	mysql_query ("INSERT INTO usercourses (userid,courseid,student) VALUES ('$userid','$courseid',1)") or die(mysql_error());
}

?>