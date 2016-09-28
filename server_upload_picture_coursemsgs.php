<?php
header('Content-type: text/html; charset=utf-8');
include 'connect_to_mysql.php';

$courseid = $_SESSION['courseid'];
if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}

if(isset($_FILES["file"]["type"])){
	$validextensions = array("jpeg", "jpg", "png", "bmp");
	$name = preg_replace('/\s+/', '_', $_FILES['file']['name']);
	$temporary = explode(".", $name);
	$file_extension = end($temporary);
	if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/bmp")) 
		&& ($_FILES["file"]["size"] < 10000000)//Approx. 10mb files can be uploaded.
		&& in_array($file_extension, $validextensions)) {
		if ($_FILES["file"]["error"] > 0){
			echo "Error File";
		} else {
			if(is_dir("photos/coursemsgs/") == false){
				mkdir("photos/coursemsgs/", 0777, true);
			}
				
			$timenow = time();
			$picture_name = "coursemsg_picture_".$courseid."_".$userid."_".$timenow."_".$name;
			$location = "photos/coursemsgs/".$picture_name;	
			move_uploaded_file($_FILES['file']['tmp_name'],$location);

			echo $location;
		}
	} else {
		echo "Error: Invalid file Size or Type";
	}
}
?>