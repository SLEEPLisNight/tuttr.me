﻿<?php
header('Content-type: text/html; charset=utf-8');
include 'connect_to_mysql.php';

if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}

function utf8_urldecode($str) {
			$str = nl2br($str);
			$str = str_replace("'","\'",$str);
			$str = str_replace("<","&lt;",$str);
    		$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
    		return html_entity_decode($str,null,'gb2312');
    		}
			
$university = $_GET["university"];

$h=0;
$result = mysql_query("SELECT * FROM classes WHERE university = $university ORDER BY likes DESC") or die();
$number = mysql_num_rows($result);
if ($number > 0) {
   	while ($row =  mysql_fetch_array($result)){
   		$coursenumber[$h] = $row['coursenumber'];
   		$courseid[$h] = $row['id'];
   	 	$h++;
   	}
}

$name = utf8_urldecode($_GET["name"]);

//lookup all hints from array if length of name > 0
if (strlen($name) > 0){
	$totalnum = 0;
	for($i=0; $i<$number; $i++){
		if (preg_match("/".$name."/i",$coursenumber[$i])){
			$totalnum++;
		}
    }
  
$hint="";
$totalnum_break = 0;
for($i=0; $i<$number; $i++){
    if (preg_match("/".$name."/i",$coursenumber[$i])){
		if ($hint==""){
			$hint="<div class='choose_school' onmouseover=this.className='choose_school_onmouseover' onmouseout=this.className='choose_school'><a onclick=update_usercourses(".$courseid[$i].",".$userid.")><div class='choose_school_name'>".$coursenumber[$i]."</div></a></div>";
        } else {
			$hint=$hint."<div class='choose_school' onmouseover=this.className='choose_school_onmouseover' onmouseout=this.className='choose_school'><a onclick=update_usercourses(".$courseid[$i].",".$userid.")><div class='choose_school_name'>".$coursenumber[$i]."</div></a></div>";
        }
        $totalnum_break++;
    }
    if ($totalnum_break >9){
		break;
    }
}
  
if ($hint == ""){
	echo "<div class='choose_school_more'>This class is not available</div>";
} else {
	$result_left = $totalnum - 10;
  if ($result_left > 0){
	$hint=$hint."<div class='choose_school_more'>and ".$result_left." more...</div>";
  }
  echo $hint; 
}

}

?>