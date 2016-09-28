<?php
header('Content-type: text/html; charset=utf-8');
include 'connect_to_mysql.php';
if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}
$ip = $_SESSION['ip'];

if (isset($_SESSION['timezonename'])){
	$timezonename = $_SESSION['timezonename'];
}

function utf8_urldecode($str){
	$str = htmlspecialchars($str, ENT_QUOTES);
	$str = mysql_real_escape_string($str);
    return $str;
}	
  		
	   $timesubmit = time();	
 	   $pmsgid= $_GET['pmsgid'];
 	   $touserid= $_GET['otheruserid'];
 	   $touserip= $_GET['otheruserip'];
 	   $content = utf8_urldecode($_GET['content']);
 	  
 $sql_insert = mysql_query("INSERT INTO duitasuopmsgreply (touserid,touserip,userid,userip,content,timesubmit,readit,pmsgid) VALUES('$touserid','$touserip','$userid','$ip','$content','$timesubmit','0','$pmsgid')") or die(mysql_error());  

 $result = mysql_query("SELECT * FROM duitasuopmsgreply WHERE touserid='$touserid' AND touserip='$touserip' AND userid='$userid' AND userip='$ip' AND timesubmit='$timesubmit'") or die();
 $row =  mysql_fetch_array($result);
 $content_reply = $row['content'];
 
 $duitasuo_pmsg_reply_fromuser = mysql_query("SELECT * FROM users WHERE id='$userid' ORDER BY id ASC;") OR die(mysql_query());      
 $row_pmsg_reply_fromuser = mysql_fetch_assoc($duitasuo_pmsg_reply_fromuser);
 $fromusername = $row_pmsg_reply_fromuser['username'];
 if ($row_pmsg_reply_fromuser['imagelocation']==""){
	$fromimagelocation = "photos/users/default_profile.jpg";
 } else {
	$fromimagelocation = $row_pmsg_reply_fromuser['imagelocation'];
 }

 $replypmsgtime = date('Y-m-d g:ia',strtotime($timezonename,$timesubmit));

 echo "<div class='pmsgs'>
       <h6 onselectstart='return false'><a href='profile.php?userid=".$userid."'>
	   <div class='course_users_row_picture'><img src=".$fromimagelocation." width='20' height='20'></div>
	   <div class='course_users_row_username'>".$fromusername."</div>
	   </a><div class='course_users_row_username'> sent at ".$replypmsgtime.":</div></h6>
       <p>".$content_reply."</p>
       </div>";
       
?>   