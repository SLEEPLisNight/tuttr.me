<?php 
header('Content-type: text/html; charset=utf-8');
include 'connect_to_mysql.php';  // connect to mysql database.
require("UrlLinker.php");

$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
$ip = $_SESSION['ip'];

$autologinuser = mysql_query("SELECT * FROM users WHERE autologin = 1 ORDER BY id ASC;") OR die(mysql_query());
while ($row_autologinuser = mysql_fetch_assoc($autologinuser))
{
if ($ip = $row_autologinuser['ip']){
    $_SESSION['username']=$row_autologinuser['username']; //assign session
    $_SESSION['id']=$row_autologinuser['id']; //assign session
} 
}

//renren check id bangding
$API_KEY = "ca131a518fd642d0b20a72a897648aac";
if (isset($_COOKIE[$API_KEY.'_user'])){
$renren_userid = $_COOKIE[$API_KEY.'_user'];
$renrenbangding = mysql_query("SELECT * FROM users WHERE renrenuserid = '$renren_userid' AND active='1' ORDER BY id ASC;") OR die(mysql_query());
$rownum_renrenbangding = mysql_num_rows($renrenbangding);
if ($rownum_renrenbangding == 1){
$row_renrenbangding = mysql_fetch_assoc($renrenbangding);
$_SESSION['username']=$row_renrenbangding['username']; //assign session
$_SESSION['id']=$row_renrenbangding['id']; //assign session
$renren_bangdingcheck = 1;
} else {
$renren_bangdingcheck = 0;
}
} else {
$renren_bangdingcheck = 2;
}

if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
$username = $_SESSION['username'];
} else {
$userid = 0; // assign SESSION 'id' value to $userid.
$username = "Unknown";
}

$courseid = $_GET["courseid"];
$schoolid = $_GET["schoolid"];
$result_course = mysql_query("SELECT * FROM classes WHERE id='$courseid' ORDER BY id ASC;") or die();
$row_course =  mysql_fetch_array($result_course);
$coursenumber = $row_course['coursenumber'];

$_SESSION['courseid'] = $courseid;
$_SESSION['coursenumber'] = $coursenumber;

$_SESSION['schoolid'] = $schoolid;
$universities = mysql_query("SELECT * FROM universities WHERE id='$schoolid' ORDER BY id ASC;") OR die(mysql_query());      
$row_universities = mysql_fetch_assoc($universities);
$schoolname = $row_universities['schoolname'];
$_SESSION['schoolname'] = $schoolname;

if ($courseid==null){
 header('Location: home.php?schoolid='.$schoolid.''); //if user is not logged in, return to the sign in page.
}

if (isset($_GET["role"])){
$getrole = $_GET["role"];
}

//get user local timezone
$timezonename = "";
if (isset($_SESSION['timezonename'])){
	$timezonename = $_SESSION['timezonename'];
}

//update usercourses lastcoursemsgid for $userid
$first_row_coursemsg_result = mysql_query("SELECT * FROM coursemsg WHERE courseid='$courseid' ORDER BY timesubmit DESC") OR die(mysql_query());
$first_row_coursemsg =  mysql_fetch_array($first_row_coursemsg_result);
$lastcoursemsgid = $first_row_coursemsg['id'];
mysql_query ("UPDATE usercourses SET lastcoursemsgid='$lastcoursemsgid' WHERE courseid='$courseid' AND userid='$userid'") or die(mysql_error());

?>

<!-- define DOCTYPE for the website at the top of the page -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xn="http://www.renren.com/2009/xnml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title><?php echo $coursenumber." - ".$schoolname; ?></title>
<link rel="shortcut icon" type="image/x-icon" href="images/tuttr_icon.ico">
<link href="home.css" rel="stylesheet" type="text/css" />
<link href="button.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link href="home_ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!-- post picture -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<script type="text/javascript" src="course.js"></script>
<script type="text/javascript" src="init.js"></script>
<script type="text/javascript" src="course_chat.js"></script>

<link rel="stylesheet" href="highlight/styles/tomorrow.css" type="text/css" />
<script type="text/javascript" src="highlight/highlight.pack.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
</head>

<body onload=init(<?php echo json_encode($timezonename.'|course.php?schoolid='.$schoolid.'&courseid='.$courseid); ?>)>

<div class="header">
<div class="header_bg">
<ul class='profile' onselectstart='return false'>
<a href='profile.php?userid=<?php echo $userid; ?>'><li class='menu' onmouseover=this.className='menu_onmouseover' onmouseout=this.className='menu'>
<?php
if ($userid != 0){ //active user
	$users_image = mysql_query("SELECT * FROM users WHERE id='$userid' ORDER BY id ASC;") OR die(mysql_query());      
	$row_users_image = mysql_fetch_assoc($users_image);
	$userimage = $row_users_image['imagelocation'];
} else { //anonymous user
	$userimage = "photos/users/default_profile.jpg";
}

echo "<div class='course_users_row_picture'><img src=".$userimage." width='20' height='20'></div>
	  <div class='course_users_row_username'>".$username."</div>"; 
?>
</li></a>
<a><li class='menu' onmouseover=this.className='menu_onmouseover' onmouseout=this.className='menu' onclick=see_my_coursemsgs() >My Posts</li></a> 
<a><li class='menu' onmouseover=this.className='menu_onmouseover' onmouseout=this.className='menu' onclick=see_unread_comment() >Notification <div id="new_notification" ></div></li></a>
<a><li class='menu' onmouseover=this.className='menu_onmouseover' onmouseout=this.className='menu' onclick="see_pmsg()" >Inbox <div id="new_message" ></div></li></a>
<a><li class='menu' onmouseover=this.className='menu_onmouseover' onmouseout=this.className='menu' onclick="login_signup()" >
<?php
if (!isset($_SESSION['id'])){ //user is not logged in
	echo "Log In";
} else {
	echo "Log Out";
}
?>
</li></a>
</ul>
</div>
</div>

<div id="wrapper">

<div id="main_content">

<div class="content">

<div id="duitasuo_post"> 

  <div class="details"> 
  
	<p class="label">I am a </p> 
    
    <p class="role switch"> 
      <input type="text" id="role" name="role" style="display: none;" value="student">
      <a id="role1" class="cb-enable selected" onclick="role_switch('1')"><span>student</span></a> 
      <a id="role2" class="cb-disable" onclick="role_switch('2')"><span>tutor</span></a> 
    </p> 

  </div>
  
  <div class="flirt">  
    <textarea rows="4" type="text" id="words" name="words" onfocus="if(this.value=='and I have a question...'){this.value=''}; this.style.color='#7b8f9d';" onblur="if(this.value==''){this.value=''; this.style.color='#7b8f9d';};">and I have a question...</textarea>
  </div>  
  
  <div id="image_preview" style='display:none'><img id="previewing" class='photo_show' src="noimage.png" /></div>
  
  <form id="uploadimage" method='post' action='' enctype='multipart/form-data' class="file_upload" onmouseover="this.className='file_upload_onmouseover'" onmouseout="this.className='file_upload'">
	<span>Upload Photo</span>
    <input type="file" name="file" id="file" class="upload" />
	<input type="text" id="uploadimage_schoolid" style='display:none'/>
	<input type="text" id="uploadimage_courseid" style='display:none'/>
	<input type='submit' id='upload_photo' value='Upload' style='display:none' />
  </form>
  
  <a class="post_code" onmouseover="this.className='post_code_onmouseover'" onmouseout="this.className='post_code'" onclick="add_code()">&lt;/code&gt;</a>

  <?php echo "<a id='post_button' name='post_button' class='post_button' onclick='post_coursemsg(".$schoolid.",".$courseid.")' >Post</a>"; ?>
  <div style="float:left;padding-top:5px;padding-left:20px;color:red;display:none;" id="post_error" class="email_message"></div> 

</div>


<div id="duitasuo_display">

<div id="post_status" onselectstart="return false">
<div id="post_status_control" class="post_status_control">
<?php 
	echo "<a id='refresh_posts' class='refresh_posts' onclick=refresh_coursemsg('0','".$courseid."')>Refresh</a>";
?>
</div>
</div>

<div id="posts" class="posts">
<?php
if (isset($getrole)){
	$sql = mysql_query("SELECT * FROM coursemsg WHERE courseid='$courseid' AND role='$getrole' ORDER BY timesubmit DESC;") or die();
} else {
	$sql = mysql_query("SELECT * FROM coursemsg WHERE courseid='$courseid' ORDER BY timesubmit DESC;") or die();
}
$nr = mysql_num_rows($sql); // Get total of Num rows from the database query
$pn = 1; 
$itemsPerPage = 10; 
$lastPage = ceil($nr / $itemsPerPage);
// This line sets the "LIMIT" range... the 2 values we place to choose a range of rows from database in our query
$limit = 'LIMIT ' .($pn - 1) * $itemsPerPage .',' .$itemsPerPage; 

$timenow = time();
if (isset($getrole)){
	$duitasuo_msg = mysql_query("SELECT * FROM coursemsg WHERE courseid='$courseid' AND role='$getrole' ORDER BY timesubmit DESC") OR die(mysql_query());
} else {
	$duitasuo_msg = mysql_query("SELECT * FROM coursemsg WHERE courseid='$courseid' ORDER BY timesubmit DESC $limit") OR die(mysql_query());
}

while ($row = mysql_fetch_assoc($duitasuo_msg)){
	$role = $row['role'];
	$imagelocation = $row['imagelocation'];
	$words = $row['words'];
	$timeago = $timenow - $row['timesubmit'];
 		if ($timeago < 60){
			$timesubmit = "just now";
 		} else if ($timeago < 3600){
			$timesubmit = (int)($timeago/60);
			$timesubmit = $timesubmit." minutes ago";
   		} else if ($timeago < 86400){
			$timesubmit = (int)($timeago/3600);
			$timesubmit = $timesubmit." hours ago";
   		} else if ($timeago >= 86400){
			$timesubmit = date('F j, Y',strtotime($timezonename,$row['timesubmit']))." at ".date('g:ia',strtotime($timezonename,$row['timesubmit']));
   		}
 $likes = $row['likes'];
 if ($likes == 0){
	$likes = "0 likes";
 }
 else {
	$likes = $likes." likes";
 }
 $msgid = $row['id'];
 
 $msg_userid = $row['userid'];
 if ($msg_userid != 0){ //active user
	$course_msg_user = mysql_query("SELECT * FROM users WHERE id='$msg_userid' ORDER BY id ASC;") OR die(mysql_query());      
	$row_msg_user = mysql_fetch_assoc($course_msg_user);
	$msg_username = $row_msg_user['username'];
	$msg_imagelocation = $row_msg_user['imagelocation'];
 } else { //anonymous user
	$msg_username = "Unknown";
	$msg_imagelocation = "photos/users/default_profile.jpg";
 }
 
 $msg_ip = $row['ip'];
 
 if ($userid != 0 && $userid == $msg_userid) {
	$msgowner = 1;
 } else if ($userid == 0 && $msg_userid == 0 && $ip == $msg_ip){
	$msgowner = 1;
 } else {
	$msgowner = 0;
 }
 
 if ($imagelocation != ""){
	 list($width, $height, $type, $attr) = getimagesize($imagelocation);
	 if ($width > 558){
		$height = (558*$height)/$width;
		$width = 558;
	 }
	 
	 if ($msgowner == 1){
	 echo "<div class='post_owner'>
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a> (<a id='role_msg_".$msgid."' href='course.php?schoolid=".$schoolid."&courseid=".$courseid."&role=".$role."'>".$role."</a>): </h4>
		   <img class='image_posted' src='".$imagelocation."' width='".$width."' height='".$height."'/>
		   <div id='content_msg_".$msgid."'>
		   <p id='words_msg_".$msgid."' class='post_words_owner'>".$words."</p>   
		   </div>
		   <div id='content_msg_edit_".$msgid."' style='display:none'>
		   </div>";
	 } else {
	 echo "<div class='post'>
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a> (<a id='role_msg_".$msgid."' href='course.php?schoolid=".$schoolid."&courseid=".$courseid."&role=".$role."'>".$role."</a>): </h4>
		   <img class='image_posted' src='".$imagelocation."' width='".$width."' height='".$height."'/>
		   <p id='words_msg_".$msgid."' class='post_words_girl'>".$words."</p>";
	 }
 } else {
	 if ($msgowner == 1){
	 echo "<div class='post_owner'>
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a> (<a id='role_msg_".$msgid."' href='course.php?schoolid=".$schoolid."&courseid=".$courseid."&role=".$role."'>".$role."</a>): </h4>
		   <div id='content_msg_".$msgid."'>
		   <p id='words_msg_".$msgid."' class='post_words_owner'>".$words."</p>   
		   </div>
		   <div id='content_msg_edit_".$msgid."' style='display:none'>
		   </div>";
	 } else {
	 echo "<div class='post'>
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a> (<a id='role_msg_".$msgid."' href='course.php?schoolid=".$schoolid."&courseid=".$courseid."&role=".$role."'>".$role."</a>): </h4>
		   <p id='words_msg_".$msgid."' class='post_words_girl'>".$words."</p>";
	 }
 }
       
 echo "<ul class='post_bottom' onselectstart='return false'>
       <li class='post_time'>".$timesubmit."</li>
       <li id='geili_".$msgid."' class='post_time'>".$likes."</li>
       <a><li class='post_action' id='leave_comment_".$msgid."' onclick='leave_comment(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>comment</li></a>";
       
		if ($userid != 0){       
			$geili_result = mysql_query("SELECT * FROM coursemsglikes WHERE userid='$userid' AND msgid='$msgid'") or die();
			$geili_row = mysql_fetch_assoc($geili_result);
			if ($geili_row['likes'] == "1") {
				echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
			} else {
				echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
			}       
		} else {
			$geili_result = mysql_query("SELECT * FROM coursemsglikes WHERE userid='0' AND ip='$ip' AND msgid='$msgid'") or die();
			$geili_row = mysql_fetch_assoc($geili_result);
			if ($geili_row['likes'] == "1") {
				echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
			} else {
				echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
			}
		}
	   
 //echo "<a href='javascript:void(0);'><li class='post_action' onclick='feed_link_renren(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>告诉人人好友</li></a>"; 
 
 if ($msgowner == 1){
	echo "<a><li class='post_action' id='edit_msg_".$msgid."' onclick='edit_msg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>edit</li></a>
		  <a><li class='post_action' id='cancel_msg_".$msgid."' onclick='cancel_msg(".$msgid.",".$courseid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>delete</li></a>";
 }
 echo "</ul>
       
       <div id='leave_comment_input_".$msgid."' onselectstart='return false'>
       </div>
       
       <div id='comments_".$msgid."' class='comments'>";

$duitasuo_comment = mysql_query("SELECT * FROM coursecomment WHERE coursemsgid='$msgid' ORDER BY timesubmit ASC;") OR die(mysql_query());
while ($row_comment = mysql_fetch_assoc($duitasuo_comment))
{ 
 $comment_userid = $row_comment['userid'];
 $comment_comment = $row_comment['comment'];
 $comment_id = $row_comment['id'];  
 $timeago_comment = $timenow - $row_comment['timesubmit'];
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
			$timesubmit_comment = date('F j, Y',strtotime($timezonename,$row_comment['timesubmit']))." at ".date('g:ia',strtotime($timezonename,$row_comment['timesubmit']));
   		}
 
 $duitasuo_comment_user = mysql_query("SELECT * FROM users WHERE id='$comment_userid' ORDER BY id ASC;") OR die(mysql_query());      
 $row_comment_user = mysql_fetch_assoc($duitasuo_comment_user);
 $comment_username = $row_comment_user['username'];
 
 $comment_likes = $row_comment['likes'];
 if ($comment_likes == 0){
	$comment_likes = "0 likes";
 } else {
	$comment_likes = $comment_likes." likes";
 } 
 
 $comment_ip = $row_comment['ip'];
 
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
			$geili_comment_result = mysql_query("SELECT * FROM coursecommentlikes WHERE userid='$userid' AND commentid='$comment_id'") or die();
			$geili_comment_row = mysql_fetch_assoc($geili_comment_result);
			if ($geili_comment_row['likes'] == "1") {
				echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
			} else {
				echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
			}       
		} else {
			$geili_comment_result = mysql_query("SELECT * FROM coursecommentlikes WHERE userid='0' AND ip='$ip' AND commentid='$comment_id'") or die();
			$geili_comment_row = mysql_fetch_assoc($geili_comment_result);
			if ($geili_comment_row['likes'] == "1") {
				echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
			} else {
				echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
			}
		}
 
 if ($msgowner == 1 && $commentowner == 0){      
	echo "<a><li class='post_action' onclick='open_pmsg(".$comment_id.",\"".$comment_username."\",".$comment_userid.",\"".$comment_ip."\",".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>send message</li></a>
		  <a><li class='post_action' onclick='cancel_comment(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>delete</li></a>";
 } else if ($commentowner == 1){
	echo "<a><li class='post_action' onclick='cancel_comment(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>delete</li></a>";
 }  
       
 echo "</ul>
       </div>";
 
}   
       
 echo  "</div>
       </div>";

}

if (isset($getgender)){
} else if (isset($gettime)){
} else if (isset($getplace)){
} else {
	if ($lastPage != "1" && $pn != $lastPage && $nr != 0){
		echo "<a id='nextpage' class='nextpage' onclick=refresh_coursemsg(".$pn.",'".$courseid."')>load more</a>";
    }
}
	
?>       
</div> <!-- end div id="posts" -->
</div> <!-- end div id="duitasuo_display" -->

</div> <!-- end div class="content" -->

<div id="sidebar" class="sidebar">
<div id="sidebar_box" class="sidebar_box">

<div id="location">
<b><font color="#6A6A6A"><a href='home.php?schoolid=<?php echo $schoolid; ?>'><?php echo $schoolname ?></a></font></b> (<a href='index.php'>change</a>)
</div>

<?php
$k = 0;
$courseids = array();
$coursenumbers = array();
$usercourses_ids = array();
$usercourse_students = array();
$usercourse_tutors = array();
$lastcoursemsgids = array();
$result_usercourses = mysql_query("SELECT * FROM usercourses WHERE userid='$userid' ORDER BY id ASC;") or die();
while ($row_usercourses = mysql_fetch_assoc($result_usercourses)){
	$usercourses_ids[$k] = $row_usercourses['id'];
	$usercourse_students[$k] = $row_usercourses['student'];
	$usercourse_tutors[$k] = $row_usercourses['tutor'];
	$courseids[$k] = $row_usercourses['courseid'];
	$lastcoursemsgids[$k] = $row_usercourses['lastcoursemsgid'];
	
	$result_classes = mysql_query("SELECT * FROM classes WHERE id='$courseids[$k]' AND university='$schoolid' ORDER BY id ASC;") or die();
	$num_rows =  mysql_num_rows($result_classes);	
	if ($num_rows == 1){
		$row_classes =  mysql_fetch_array($result_classes);
		$coursenumbers[$k] = $row_classes['coursenumber'];
		
		$h = 0;
		$check_unread_coursemsg = mysql_query("SELECT * FROM coursemsg WHERE courseid='$courseids[$k]' ORDER BY timesubmit DESC") OR die(mysql_query());
		while ($row_check_unread_coursemsg = mysql_fetch_assoc($check_unread_coursemsg)){
			if ($row_check_unread_coursemsg['id'] == $lastcoursemsgids[$k]){
				break;
			}
			$h++;
		}
		
		if ($courseid == $courseids[$k]){
			if ($usercourse_students[$k] == 0 && $usercourse_tutors[$k] == 0){
				echo "<div class='user_classes_selected'>
					  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>No Roles</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
					  </div>";
				echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
					  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)'>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)'>Tutor 
					  </div>";
			} else if ($usercourse_students[$k] == 1 && $usercourse_tutors[$k] == 0){
				echo "<div class='user_classes_selected'>
					  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>Student</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
				      </div>";
				echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
					  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)' checked>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)'>Tutor 
					  </div>";
			} else if ($usercourse_students[$k] == 0 && $usercourse_tutors[$k] == 1){
				echo "<div class='user_classes_selected'>
					  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>Tutor</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
					  </div>";
				echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
					  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)'>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)' checked>Tutor 
					  </div>";
			} else if ($usercourse_students[$k] == 1 && $usercourse_tutors[$k] == 1){
				echo "<div class='user_classes_selected'>
					  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>Student &amp; Tutor</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
					  </div>";
				echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
					  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)' checked>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)' checked>Tutor 
					  </div>";
			}
		} else {
			if ($h > 0){
				if ($usercourse_students[$k] == 0 && $usercourse_tutors[$k] == 0){
					echo "<div class='user_classes'>
						  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div class='new_coursemsgs'>".$h."</div> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>No Roles</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
						  </div>";
					echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
						  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)'>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)'>Tutor 
						  </div>";
				} else if ($usercourse_students[$k] == 1 && $usercourse_tutors[$k] == 0){
					echo "<div class='user_classes'>
						  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div class='new_coursemsgs'>".$h."</div> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>Student</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
						  </div>";
					echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
						  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)' checked>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)'>Tutor 
						  </div>";
				} else if ($usercourse_students[$k] == 0 && $usercourse_tutors[$k] == 1){
					echo "<div class='user_classes'>
						  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div class='new_coursemsgs'>".$h."</div> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>Tutor</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
						  </div>";
					echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
						  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)'>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)' checked>Tutor 
						  </div>";
				} else if ($usercourse_students[$k] == 1 && $usercourse_tutors[$k] == 1){
					echo "<div class='user_classes'>
						  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div class='new_coursemsgs'>".$h."</div> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>Student &amp; Tutor</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
						  </div>";
					echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
						  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)' checked>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)' checked>Tutor 
						  </div>";
				}
			} else {
				if ($usercourse_students[$k] == 0 && $usercourse_tutors[$k] == 0){	
					echo "<div class='user_classes'>
						  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>No Roles</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
						  </div>";
					echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
						  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)'>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)'>Tutor 
						  </div>";
				} else if ($usercourse_students[$k] == 1 && $usercourse_tutors[$k] == 0){
					echo "<div class='user_classes'>
						  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>Student</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
						  </div>";
					echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
						  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)' checked>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)'>Tutor 
						  </div>";
				} else if ($usercourse_students[$k] == 0 && $usercourse_tutors[$k] == 1){
					echo "<div class='user_classes'>
						  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>Tutor</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
						  </div>";
					echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
						  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)'>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)' checked>Tutor 
						  </div>";
				} else if ($usercourse_students[$k] == 1 && $usercourse_tutors[$k] == 1){
					echo "<div class='user_classes'>
						  <b><font color='#6A6A6A'><a href='course.php?schoolid=".$schoolid."&courseid=".$courseids[$k]."'>".$coursenumbers[$k]."</a></font></b> <div id='roles_".$k."' onclick='show_update_roles($k)' class='course_user_roles'>Student &amp; Tutor</div> <a class='delete_class' onclick='deletecourse(".$courseids[$k].",".$schoolid.",".$courseid.")'>x</a>
						  </div>";
					echo "<div id='update_roles_".$k."' class='update_roles_class' style='display:none'>You are: 
						  <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],1,this)' checked>Student <input type='checkbox' onchange='updateroles($k,$usercourses_ids[$k],2,this)' checked>Tutor 
						  </div>";
				}
			}
		}
		
		$k++;
	}
}
?>

<!-- Facebook Login
<div id="renren_connect">
<div class="renren_connect_box">
<?php 
if (isset($_COOKIE[$API_KEY.'_user'])){
?>
<p class="renren_connect_pic"><xn:profile-pic uid="loggedinuser" size="tiny" linked="true" connect-logo="false"></xn:profile-pic></p>
<p class="renren_connect_username">人人网登录：<xn:name uid="loggedinuser"></xn:name></p><br>
<p class="renren_connect_logout"><xn:login-button autologoutlink="true"></xn:login-button></p>
<?php
} else {
?>
<p class="renren_connect_login"><xn:login-button autologoutlink="true"></xn:login-button></p>
<?php
}
?>
</div>
</div>

<div id="schoollikes">
<span class="schoollikes_name"><b><?php echo $coursenumber ?></b> 人品：</span>
<?php 
echo "<iframe scrolling='no' frameborder='0' allowtransparency='true' src='http://www.connect.renren.com/like?url=http%3A%2F%2Fwww.duitasuo.com%2Fhome.php%3Fcourseid%3D".$courseid."&showfaces=false' style='width: 120px;height: 22px;'></iframe>"; 
?>
</div>

<div id="schoolshares">
<span class="schoolshares_name"><b><?php echo $coursenumber ?></b> 分享：</span>
<?php 
echo "<a name='xn_share' type='button_count_right' href='http://www.duitasuo.com/course.php?courseid=".$courseid."'>分享</a>
<script src='http://static.connect.renren.com/js/share.js' type='text/javascript'>
</script>";
?>
</div>
-->

<!-- 暂时取消功能
<div id="renren_guanzhu">
<iframe scrolling="no" frameborder="0" src="http://widget.renren.com/fanBoxWidget?appId=139738&pageImg=true&pageName=true&pageFriend=true&characterColor=ff0000&linkColor=255&borderColor=0&mainBackground=0&subBackground=0&desc=%E5%85%B3%E6%B3%A8%E6%9C%80%E6%96%B0%E5%8A%A8%E6%80%81" style="width: 316px;height: 170px;"></iframe>
</div>
-->

<div id="course_users">
<h2><?php echo $coursenumber ?> <a id="course_tutors" onclick="see_course_users(2,<?php echo $courseid; ?>)">Tutors</a> | <a id="course_students" onclick="see_course_users(1,<?php echo $courseid; ?>)">Students</a></h2>

<div id="course_students_tutors">
<?php 
$result_usercourses = mysql_query("SELECT * FROM usercourses WHERE courseid='$courseid' AND tutor=1") OR die(mysql_query());
while ($row_usercourses = mysql_fetch_assoc($result_usercourses)){
	 $msg_userid = $row_usercourses['userid'];
	 if ($msg_userid != 0){ //active user
		$course_msg_user = mysql_query("SELECT * FROM users WHERE id='$msg_userid' ORDER BY id ASC;") OR die(mysql_query());      
		$row_msg_user = mysql_fetch_assoc($course_msg_user);
		$msg_username = $row_msg_user['username'];
		$course_users_imagelocation = $row_msg_user['imagelocation'];
	 } else { //anonymous user
		$msg_username = "Unknown";
		$course_users_imagelocation = "photos/users/default_profile.jpg";
	 }
	 
	 echo "<div class='course_users_row'>
	 
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$course_users_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a></h4>
		 
		   </div>";
	   
}
?>
</div>

</div>

<div id="top_msgs">
<h2><?php echo $coursenumber ?> Top 10 Posts </h2>
<div id="top_posts">
<?php 

$top_posts_num = 0;
$timenow = time();
$top_duitasuo_msg = mysql_query("SELECT * FROM coursemsg WHERE courseid='$courseid' ORDER BY likes DESC") OR die(mysql_query());
while ($top_row = mysql_fetch_assoc($top_duitasuo_msg)){
	$role = $top_row['role'];
	$imagelocation = $top_row['imagelocation'];
	$words = $top_row['words'];
	$timeago = $timenow - $top_row['timesubmit'];
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
   		$timesubmit = date('F j, Y',strtotime($timezonename,$top_row['timesubmit']))." at ".date('g:ia',strtotime($timezonename,$top_row['timesubmit']));
   		}
 $likes = $top_row['likes'];
 if ($likes == 0){
 $likes = "0 likes";
 }
 else {
 $likes = $likes." likes";
 }
 $msgid = $top_row['id'];
 
 $msg_userid = $top_row['userid'];
 if ($msg_userid != 0){ //active user
	$course_msg_user = mysql_query("SELECT * FROM users WHERE id='$msg_userid' ORDER BY id ASC;") OR die(mysql_query());      
	$row_msg_user = mysql_fetch_assoc($course_msg_user);
	$msg_username = $row_msg_user['username'];
	$msg_imagelocation = $row_msg_user['imagelocation'];
 } else { //anonymous user
	$msg_username = "Unknown";
	$msg_imagelocation = "photos/users/default_profile.jpg";
 }
 
 if ($imagelocation != ""){
	 list($width, $height, $type, $attr) = getimagesize($imagelocation);
	 if ($width > 305){
		$height = (305*$height)/$width;
		$width = 305;
	 }
 
	 echo "<div class='top_post'>
	 
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a> <div class='course_users_row_role'>(<a id='role_msg_".$msgid."' href='course.php?schoolid=".$schoolid."&courseid=".$courseid."&role=".$role."'>".$role."</a>): </div></h4>
		   <img class='image_posted' src='".$imagelocation."' width='".$width."' height='".$height."'/>
		   <p class='top_post_words'>".$words."</p>
		   
		   <ul class='top_post_bottom' onselectstart='return false'>
		   <li class='top_post_time'>".$timesubmit."</li>
		   <li id='geili_".$msgid."' class='top_post_geili'>".$likes."</li>
		   </ul>
		   
		   </div>";
 } else {
	 echo "<div class='top_post'>
	 
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a> <div class='course_users_row_role'>(<a id='role_msg_".$msgid."' href='course.php?schoolid=".$schoolid."&courseid=".$courseid."&role=".$role."'>".$role."</a>): </div></h4>
		   <p class='top_post_words'>".$words."</p>
		   
		   <ul class='top_post_bottom' onselectstart='return false'>
		   <li class='top_post_time'>".$timesubmit."</li>
		   <li id='geili_".$msgid."' class='top_post_geili'>".$likes."</li>
		   </ul>
		   
		   </div>";
 }
       
	$top_posts_num++;
	if ($top_posts_num >9){ //only 10 posts
		break;
	}     
}
?>
</div>
</div>

</div>
</div>

</div>


<div id="login_signup" class="login_signup">

<div class="login">
<div class="login_box">
<div id="login_form">
<h2>Log In</h2>
<?php
if (isset($_SESSION['id']))
      {
      if ($renren_bangdingcheck == 1){
	  echo "<div id='login_successful'>人人绑定登录：".$_SESSION['username']."   (<a href='home_cancel_bangding.php?schoolid=".$schoolid."'>取消绑定</a>)</div>";
      } else {
	  echo "<div id='login_successful'>You logged in as: ".$_SESSION['username']."   (<a href='home_logout.php?schoolid=".$schoolid."'>log out</a>)</div>";
      }
	  echo "<div class='buttons'>
      <p class='cancel_login_button2' onclick=close_signup()>Cancel</p>
      </div>
	  <div id='login_message'></div>";
      }
      else
      {
      ?>
<div id="login_field">
<input type="text" style="width: 195px; color: #999;" id="login_email" value="Email (email@domain.com)" onfocus="if(this.value=='Email (email@domain.com)'){this.value=''}; this.style.color='black';" onblur="if(this.value==''){this.value='Email (email@domain.com)'; this.style.color='#999';};">
<input type="password" style="width: 195px;" id="login_password" value="Password" onfocus="if(this.value=='Password'){this.value=''}; this.style.color='black';" onblur="if(this.value==''){this.value='Password'; this.style.color='#999';};">
<div class="savepassword">
<form name="autologinform" title="" class="logincheckbox">
<input type="checkbox" name="autologin" id="autologin" value="1" />Remember me
</form>
</div>
<div class="buttons">
<?php echo "<p id='login_button' class='login_button' onclick=course_login(".$schoolid.",".$courseid.")>Log in</p>"; ?>
<p class="cancel_login_button" onclick="close_signup()">Cancel</p>
</div>
<div id="login_message">
</div>
</div>
<?php
}
?>
</div>
</div>
</div>

<div class="signup">
<div class="login_box">
<div id="login_form">
<h2>Sign Up</h2>
<div id="signup_field">
<input type="text" style="width: 195px;" id="signup_username" value="Username" onfocus="if(this.value=='Username'){this.value=''}; this.style.color='black';" onblur="if(this.value==''){this.value='Username'; this.style.color='#999';};">
<input type="text" style="width: 195px;" id="signup_email" value="Email (email@domain.com)" onfocus="if(this.value=='Email (email@domain.com)'){this.value=''}; this.style.color='black';" onblur="if(this.value==''){this.value='Email (email@domain.com)'; this.style.color='#999';};">
<input type="password" style="width: 195px;" id="signup_password" value="Password" onfocus="if(this.value=='Password'){this.value=''}; this.style.color='black';" onblur="if(this.value==''){this.value='Password'; this.style.color='#999';};">
<div class="buttons">
<p id="finish_signup_button" class="finish_signup_button" onclick="finish_signup()">Sign up</p>
<p class="cancel_signup_button" onclick="close_signup()">Cancel</p>
</div>
<div id="signup_message">
</div>
</div>
</div>
</div>
</div>

</div>

<div id="private_msg" class="private_msg">
<div class="private_msg_box">
<div class="login_box">
<div id="private_msg_form">
<h2>Message</h2>
<div id="private_msg_field">
</div>
</div>
</div>
</div>
</div>

<div id="chat_animation" class="chat_animation" onselectstart='return false'>
<div class="chat_animation_shade">
<div class="chat_animation_box">
<div class="refresh_others_pn" title="查看下一组用户" onclick="chat_animation_others()"></div>
<div id="chat_animation_background" onmousedown="chat_animation_click_move(event)"> 
<div class="kfc_logo" title="欢迎大家光临肯德基！"></div>
<div class="nba_logo" title="喜欢NBA的朋友就过来！"></div>
<div class="houstonrockets_logo" title="我们一起支持火箭！支持姚明！"></div>
<div id="chat_animation_user" class="chat_animation_user">
<div id="chat_animation_user_pic" class="chat_animation_user_pic" title="我在这里！"></div>
<div id="chat_animation_user_name" class="chat_animation_user_name" title="这是我的名字！"></div>
</div>
<div id="redflag">
</div>
<div id="chat_animation_other_users"></div>
</div>
</div>
</div>
</div>

<!---
<div id="send_gift" class="send_gift">

<div class="send_gift_main">
<div class="login_box">
<div id="private_msg_form">
<h2>送礼物</h2>
<div id="send_gift_field">
</div>
</div>
</div>
</div>

<div class="send_gift_pic">
<div class="login_box">
<div id="send_gift_pic_form">
<h2><div id="send_gift_pic_name">礼物照片</div></h2>
<div id="send_gift_pic_field" style="height: 235px;">
</div>
</div>
</div>
</div>

</div>
--->

<div id="copyright">
tuttr.me Inc. &copy; 2015
</div>

<div id="course_search"> 
<input type="text" id="course_search_input" onkeyup="course_search(<?php echo $schoolid.",".$courseid; ?>)" oninput="course_search(<?php echo $schoolid.",".$courseid; ?>)" onpropertychange="course_search(<?php echo $schoolid.",".$courseid; ?>)" onfocus="if(this.value=='Add Your Class (Math 101)'){this.value=''};" onblur="if(this.value==''){this.value='Add Your Class (Math 101)';};" value="Add Your Class (Math 101)"/>
<span id="course_search_results">
</span> 
</div>

</div> <!-- end of wrapper -->

<div id="post_search"> 
<input type="text" id="post_search_input" onkeyup="post_search(event)" onfocus="if(this.value=='Search Your Question'){this.value=''};" onblur="if(this.value==''){this.value='Search Your Question';};" value="Search Your Question"/>
</div>

<div id="bottombar" class="chat_bottom" onclick="show_chat_panel()" onmouseover="if(this.className=='chat_bottom'){this.className='chat_bottom_onmouseover'};" onmouseout="if(this.className=='chat_bottom_onmouseover'){this.className='chat_bottom'};" onselectstart='return false'>
<div id="chat">Online</div>
<div id="chat_friends"></div>
</div>

<div id="chat_panel" onselectstart='return false'>
<div class='chat_panel_head'>
<div class='chatbox_name'><?php echo $coursenumber ?></div>
<div class='chatbox_close' onmouseover=this.className='chatbox_close_onmouseover' onmouseout=this.className='chatbox_close' onclick='show_chat_panel()'>-</div>
</div>
<div id="chat_panel_users"></div>
</div>

<script type="text/javascript" src="http://static.connect.renren.com/js/v1.0/FeatureLoader.jsp"></script>
  <script type="text/javascript">
    XN_RequireFeatures(["EXNML"], function()
    {
      XN.Main.init("ca131a518fd642d0b20a72a897648aac", "/xd_receiver.html");
    });
	
	function refresh_after_renrenlogin(){
	window.location="index.php";
	}
	
	function feed_link_renren(id){
    XN_RequireFeatures(["EXNML"], function()
    {
      XN.Main.init("ca131a518fd642d0b20a72a897648aac", "/xd_receiver.html");
      XN.Connect.requireSession(sendFeed(id));   
    });
    }
	
	function sendFeed(id){
	var courseid = "<?php echo $courseid ?>";
	var coursenumber = "<?php echo $coursenumber ?>";
	var gender = document.getElementById("gender_msg_"+id+"").innerHTML;
	var time = document.getElementById("time_msg_"+id+"").innerHTML;
	var place = document.getElementById("place_msg_"+id+"").innerHTML;
	var descr = document.getElementById("descr_msg_"+id+"").innerHTML;
	var words = document.getElementById("words_msg_"+id+"").innerHTML;
	var content = time+"，我在"+place+"看到一个"+gender+"。"+descr+" "+words;
	
  		feedSettings = {
  			"template_bundle_id": 1,
			"template_data": {"images":[{"src":"http://www.duitasuo.com/images/tuitasuo_logo.jpg","href":"http://www.duitasuo.com/course.php?courseid="+courseid+""}], "site":"<a href=\"http://www.duitasuo.com\">对ta说</a>","feedtype":""+coursenumber+"","content":""+content+"","action":"click"},
  			"user_message_prompt": "邀请大家都来看看吧！",
  			"user_message": "好精彩啊！快来看看！"
  		};
  		XN.Connect.showFeedDialog(feedSettings);
  	}
</script>
</body>
</html>