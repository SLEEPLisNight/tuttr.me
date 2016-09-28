<?php
header('Content-type: text/html; charset=utf-8');
include '../connect_to_mysql.php'; 

if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}
$ip = $_SESSION['ip'];

if (isset($_SESSION['timezonename'])){
	$timezonename = $_SESSION['timezonename'];
}

$otherid = $_GET['otherid'];

 if ($otherid != 0){
 $timenow = time();
 $msgs = 0;
 $result_msg = mysql_query("SELECT * FROM duitasuomsg WHERE userid = '$otherid' ORDER BY timesubmit DESC;") or die();
 while($row_msg = mysql_fetch_assoc($result_msg)){
 $otherschoolid = $row_msg['schoolid'];	
 $schools_result = mysql_query("SELECT * FROM universities WHERE id = '$otherschoolid' ORDER BY id ASC;") or die();
 $schools_row = mysql_fetch_array($schools_result);
 $otherschoolname = $schools_row['schoolname'];	
 
 $msgid = $row_msg['id'];
 $imagelocation = $row_msg['imagelocation'];
 $role = $row_msg['role'];
 $words = $row_msg['words'];
 $timeago = $timenow - $row_msg['timesubmit'];
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
   		$timesubmit = date('F j, Y',strtotime($timezonename,$row_msg['timesubmit']))." at ".date('g:ia',strtotime($timezonename,$row_msg['timesubmit']));
   		}
 $likes = $row_msg['likes'];
 if ($likes == 0){
	$likes = "0 likes";
 } else {
	$likes = $likes." likes";
 }
 
 $msg_userid = $row_msg['userid'];
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
	 if ($width > 558){
		$height = (558*$height)/$width;
		$width = 558;
	 }
	 
	 echo "<div class='post'>
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a> (".$role.") posted on <a href='home.php?schoolid=".$otherschoolid."'>".$otherschoolname."</a>: </h4>
		   <img class='image_posted' src='".$imagelocation."' width='".$width."' height='".$height."'/>
		   <p id='words_msg_".$msgid."' class='post_words_girl'>".$words."</p>";
 } else {
	 echo "<div class='post'>
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a> (".$role.") posted on <a href='home.php?schoolid=".$otherschoolid."'>".$otherschoolname."</a>: </h4>
		   <p id='words_msg_".$msgid."' class='post_words_girl'>".$words."</p>";
 }
	   
 echo "<ul class='post_bottom' onselectstart='return false'>
       <li class='post_time'>".$timesubmit."</li>
       <li id='geili_".$msgid."' class='post_time'>".$likes."</li>
       <a><li class='post_action' id='leave_comment_".$msgid."' onclick='leave_comment(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>comment</li></a>";
       
       if ($userid != 0){       
       $geili_result = mysql_query("SELECT * FROM duitasuogeili WHERE userid='$userid' AND msgid='$msgid'") or die();
       $geili_row = mysql_fetch_assoc($geili_result);
       if ($geili_row['geili'] == "1") {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }       
       }
       else {
       $geili_result = mysql_query("SELECT * FROM duitasuogeili WHERE userid='0' AND ip='$ip' AND msgid='$msgid'") or die();
       $geili_row = mysql_fetch_assoc($geili_result);
       if ($geili_row['geili'] == "1") {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }
       }
  
 //echo "<a href='javascript:void(0);'><li class='post_action' onclick='feed_link_renren(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>告诉人人好友</li></a>";   
 
 echo "</ul>
       
       <div id='leave_comment_input_".$msgid."' onselectstart='return false'>
       </div>
       
       <div id='comments_".$msgid."' class='comments'>";

 $duitasuo_comment = mysql_query("SELECT * FROM duitasuocomment WHERE msgid='$msgid' ORDER BY timesubmit ASC;") OR die(mysql_query());
 while ($row_comment2 = mysql_fetch_assoc($duitasuo_comment))
 { 
 $comment_userid = $row_comment2['userid'];
 $comment_comment = $row_comment2['comment'];
 $comment_id = $row_comment2['id'];  
 $timeago_comment = $timenow - $row_comment2['timesubmit'];
 		if ($timeago_comment < 60){
 		$timesubmit_comment = "just now";
 		}
   		else if ($timeago_comment < 3600){
   		$timesubmit_comment = (int)($timeago_comment/60);
   		$timesubmit_comment = $timesubmit_comment." minutes ago";
   		}
   		else if ($timeago_comment < 86400){
   		$timesubmit_comment = (int)($timeago_comment/3600);
   		$timesubmit_comment = $timesubmit_comment." hours ago";
   		}
   		else if ($timeago_comment >= 86400){
   		$timesubmit_comment = date('F j, Y',strtotime($timezonename,$row_comment2['timesubmit']))." at ".date('g:ia',strtotime($timezonename,$row_comment2['timesubmit']));
   		}
 
 $duitasuo_comment_user = mysql_query("SELECT * FROM users WHERE id='$comment_userid' ORDER BY id ASC;") OR die(mysql_query());      
 $row_comment_user = mysql_fetch_assoc($duitasuo_comment_user);
 $comment_username = $row_comment_user['username'];
 
 $comment_likes = $row_comment2['likes'];
 if ($comment_likes == 0){
 $comment_likes = "0 likes";
 }
 else {
 $comment_likes = $comment_likes." likes";
 } 
 
 $comment_ip = $row_comment2['ip'];
 
 if ($userid != 0 && $userid == $comment_userid) {
 $commentowner = 1;
 } else if ($userid == 0 && $comment_userid == 0 && $ip == $comment_ip){
 $commentowner = 1;
 } else {
 $commentowner = 0;
 }
 
 if ($commentowner == 1){
 echo "<div id='comment_".$comment_id."' onmouseover='show_comment_bottom(".$comment_id.",1)' onmouseout='unshow_comment_bottom(".$comment_id.")'>";
 } else if ($comment_userid != 0){
 echo "<div id='comment_".$comment_id."' onmouseover='show_comment_bottom(".$comment_id.",2)' onmouseout='unshow_comment_bottom(".$comment_id.")'>";
 }
 else {
 echo "<div id='comment_".$comment_id."' onmouseover='show_comment_bottom(".$comment_id.",3)' onmouseout='unshow_comment_bottom(".$comment_id.")'>";
 }
 
 if ($commentowner == 1){
 echo "<p class='comment_owner' ><a href='profile.php?userid=".$comment_userid."'>".$comment_username."</a>: ".$comment_comment."</p>";
 } else if ($comment_userid != 0){
 echo "<p class='comment_id' ><a href='profile.php?userid=".$comment_userid."'>".$comment_username."</a>: ".$comment_comment."</p>";
 } else {
 echo "<p class='comment_noid' ><a href='profile.php?userid=".$comment_userid."'>".$comment_username."</a>: ".$comment_comment."</p>";
 }
 
 echo "<ul id='comment_bottom_".$comment_id."' class='comment_bottom_none' onselectstart='return false' >";
 
 echo "<li class='post_time'>".$timesubmit_comment."</li>
       <li id='geili_comment_".$comment_id."' class='post_time'>".$comment_likes."</li>";
        
       if ($userid != 0){       
       $geili_comment_result = mysql_query("SELECT * FROM duitasuocommentgeili WHERE userid='$userid' AND commentid='$comment_id'") or die();
       $geili_comment_row = mysql_fetch_assoc($geili_comment_result);
       if ($geili_comment_row['geili'] == "1") {
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }       
       }
       else {
       $geili_comment_result = mysql_query("SELECT * FROM duitasuocommentgeili WHERE userid='0' AND ip='$ip' AND commentid='$comment_id'") or die();
       $geili_comment_row = mysql_fetch_assoc($geili_comment_result);
       if ($geili_comment_row['geili'] == "1") {
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }
       }
 
 if ($commentowner == 1){
 echo "<a><li class='post_action' onclick='cancel_comment(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>delete</li></a>";
 }       
       
 echo "</ul>
       </div>";
 
 }   
       
 echo  "</div>
        </div>";

$msgs++;
}

if ($msgs == 0){
echo "<div class='post'>
      <h4 onselectstart='return false'>No posts available.</h4>
      </div>";
}
} else {
echo "<div class='post'>
      <h4 onselectstart='return false'>The user has not logged in, posts are not available.</h4>
      </div>";
}

?>