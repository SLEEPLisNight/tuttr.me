<?php
include 'connect_to_mysql.php';

$id = $_GET['id'];
$role = $_GET['role'];
$value = $_GET['value'];
		
if ($role == 1){
	mysql_query ("UPDATE usercourses SET student='$value' WHERE id='$id'") or die(mysql_error());
} else if ($role == 2){
	mysql_query ("UPDATE usercourses SET tutor='$value' WHERE id='$id'") or die(mysql_error());
} 

//for update roles
$result_usercourses = mysql_query("SELECT * FROM usercourses WHERE id='$id'") or die();
$row_result_usercourses =  mysql_fetch_array($result_usercourses);
$usercourses_student = $row_result_usercourses['student'];
$usercourses_tutor = $row_result_usercourses['tutor'];

if ($usercourses_student == 0 && $usercourses_tutor == 0){
	echo "No Roles";
} else if ($usercourses_student == 1 && $usercourses_tutor == 0){
	echo "Student";
} else if ($usercourses_student == 0 && $usercourses_tutor == 1){
	echo "Tutor";
} else if ($usercourses_student == 1 && $usercourses_tutor == 1){
	echo "Student &amp; Tutor";
} 

?>