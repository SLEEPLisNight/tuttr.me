<?php
include 'connect_to_mysql.php';  // connect to mysql database.
require("UrlLinker.php");

if (isset($_SESSION['timezonename'])){
	$timezonename = $_SESSION['timezonename'];
}

$top_posts_num = 0;
$timenow = time();
$duitasuo_msg = mysql_query("SELECT * FROM duitasuomsg WHERE newmsg = 0 ORDER BY timesubmit DESC") OR die(mysql_query());
while ($row = mysql_fetch_assoc($duitasuo_msg)){
	$role = $row['role'];
	$imagelocation = $row['imagelocation'];
	$words = $row['words'];
	$timeago = $timenow - $row['timesubmit'];
 		if ($timeago < 60){
 		$timesubmit = "just now";
 		}
   		else if ($timeago < 3600){
   		$timesubmit = (int)($timeago/60);
   		$timesubmit = $timesubmit." minutes ago";
   		}
   		else if ($timeago < 86400){
   		$timesubmit = (int)($timeago/3600);
   		$timesubmit = $timesubmit." hours ago";
   		}
   		else if ($timeago >= 86400){
   		$timesubmit = date('F j, Y',strtotime($timezonename, $row['timesubmit']))." at ".date('g:ia',strtotime($timezonename, $row['timesubmit']));
   		}
 $likes = $row['likes'];
 if ($likes == 0){
 $likes = "0 likes";
 }
 else {
 $likes = $likes." likes";
 }
 
 $msgid = $row['id'];
 mysql_query("UPDATE duitasuomsg SET newmsg='1' WHERE id='$msgid'") or die(mysql_error());
 
 $msg_schoolid = $row['schoolid'];
 $duitasuo_schoolname = mysql_query("SELECT * FROM schools where id='$msg_schoolid'") OR die(mysql_query());
 $row_schoolname = mysql_fetch_assoc($duitasuo_schoolname);
 $msg_schoolname = $row_schoolname['schoolname'];

 $msg_userid = $row['userid'];
 if ($msg_userid != 0){ //active user
	$msg_user = mysql_query("SELECT * FROM users WHERE id='$msg_userid' ORDER BY id ASC;") OR die(mysql_query());      
	$row_msg_user = mysql_fetch_assoc($msg_user);
	$msg_username = $row_msg_user['username'];
	$msg_imagelocation = $row_msg_user['imagelocation'];
 } else { //anonymous user
	$msg_username = "Unknown";
	$msg_imagelocation = "photos/users/default_profile.jpg";
 }
 
 if ($imagelocation != ""){
	 list($width, $height, $type, $attr) = getimagesize($imagelocation);
	 if ($width > 315){
		$height = (315*$height)/$width;
		$width = 315;
	 }
 
	 echo "<div class='top_post_firstchild'>
	 
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a> <div class='course_users_row_role'>(".$role.") from <a href='home.php?schoolid=".$msg_schoolid."'>".$msg_schoolname."</a>: </div></h4>
		   <img class='image_posted' src='".$imagelocation."' width='".$width."' height='".$height."'/>
		   <p class='words'>".linkUrlsInHtml($words)."</p>
		   
		   <ul class='top_post_bottom' onselectstart='return false'>
		   <li class='top_post_time'>".$timesubmit."</li>
		   <li id='geili_".$msgid."' class='top_post_geili'>".$likes."</li>
		   </ul>
		   
		   </div>";
 } else {
	 echo "<div class='top_post_firstchild'>
	 
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a> <div class='course_users_row_role'>(".$role.") from <a href='home.php?schoolid=".$msg_schoolid."'>".$msg_schoolname."</a>: </div></h4>
		   <p class='words'>".linkUrlsInHtml($words)."</p>
		   
		   <ul class='top_post_bottom' onselectstart='return false'>
		   <li class='top_post_time'>".$timesubmit."</li>
		   <li id='geili_".$msgid."' class='top_post_geili'>".$likes."</li>
		   </ul>
		   
		   </div>";
 }
       
  $top_posts_num++;
  if ($top_posts_num >0){
   break;
  }     
  
}

if ($top_posts_num == 0){
	echo "nonewposts";
}

?>