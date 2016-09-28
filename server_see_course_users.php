<?php
include 'connect_to_mysql.php';

$courseid = $_GET['courseid'];
$role = $_GET['role'];
		
if ($role == 1){
	$result_usercourses = mysql_query("SELECT * FROM usercourses WHERE courseid='$courseid' AND student=1") OR die(mysql_query());
} else if ($role == 2){
	$result_usercourses = mysql_query("SELECT * FROM usercourses WHERE courseid='$courseid' AND tutor=1") OR die(mysql_query());
} 
while ($row_usercourses = mysql_fetch_assoc($result_usercourses)){
	 $msg_userid = $row_usercourses['userid'];
	 if ($msg_userid != 0){ //active user
		$course_msg_user = mysql_query("SELECT * FROM users WHERE id='$msg_userid' ORDER BY id ASC;") OR die(mysql_query());      
		$row_msg_user = mysql_fetch_assoc($course_msg_user);
		$msg_username = $row_msg_user['username'];
		$course_users_imagelocation = $row_msg_user['imagelocation'];
	 } else { //anonymous user
		 $msg_username = "Unknown";
		 $course_users_imagelocation = "";
	 }
	 
	 echo "<div class='course_users_row'>
	 
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$course_users_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a></h4>
		 
		   </div>";
	   
}

?>