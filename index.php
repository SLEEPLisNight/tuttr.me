<?php 
header('Content-type: text/html; charset=utf-8');
include 'connect_to_mysql.php';  // connect to mysql database.
require("UrlLinker.php");

$ip = $_SERVER['REMOTE_ADDR'];
$autologinuser = mysql_query("SELECT * FROM users WHERE autologin = 1 ORDER BY id ASC;") OR die(mysql_query());
while ($row_autologinuser = mysql_fetch_assoc($autologinuser))
{ 
if ($ip = $row_autologinuser['ip']){
    $_SESSION['username']=$row_autologinuser['username']; //assign session
    $_SESSION['id']=$row_autologinuser['id']; //assign session
} 
}

//renren check cookie
$API_KEY = "ca131a518fd642d0b20a72a897648aac";
if (isset($_COOKIE[$API_KEY.'_expires'])){
if ($_COOKIE[$API_KEY.'_expires']>time()){
$checkcookie = $_COOKIE[$API_KEY.'_user'].$_COOKIE[$API_KEY.'_ss'].$_COOKIE[$API_KEY.'_session_key'].$_COOKIE[$API_KEY.'_expires']."6961f890ed2486fa7a1739f6e945a3b";
if (md5($checkcookie) == $_COOKIE[$API_KEY]){
$renrenlogin = 1;
} else {
$renrenlogin = 0;
}
} else {
$renrenlogin = 0;
}
} else {
$renrenlogin = 0;
}

//renren check id bangding
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

//get user local timezone
$timezonename = "";
if (isset($_SESSION['timezonename'])){
	$timezonename = $_SESSION['timezonename'];
}
?>

<!-- define DOCTYPE for the website at the top of the page -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xn="http://www.renren.com/2009/xnml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
<title>tuttr.me</title>
<link rel="shortcut icon" type="image/x-icon" href="images/tuttr_icon.ico">
<!-- include all the css and js files. -->
<link href="index.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link href="index_ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript" src="index.js"></script>
<script type="text/javascript" src="jquery-1.5.2.js"></script>

<link rel="stylesheet" href="highlight/styles/tomorrow.css" type="text/css" />
<script type="text/javascript" src="highlight/highlight.pack.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
</head>

<body onload=init(<?php echo json_encode($timezonename); ?>)> <!-- init to get user's local time zone into timezonename session -->
<!-- test staging branch is working -->
<div id="videobg_container">
<img id="videobg" src="images/bg.jpg" />
<div class="transparent_bg"></div>
</div>

<div id="wrapper">

<div id="header">
<h1>tuttr.me is where you can find peer tutors for your class</h1>
<h2>Find your school. Find your class. Find your tutors.</h2>
</div> 

<div id="main_content">

<div class="new_msgs">
<div class="new_msgs_box">
<div id="five_new_msgs">
<h2>New Posts</h2>
<div id="five_msgs">
<?php

$top_posts_num = 0;
$timenow = time();
$duitasuo_msg = mysql_query("SELECT * FROM duitasuomsg ORDER BY timesubmit DESC") OR die(mysql_query());
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
 
 $msg_schoolid = $row['schoolid'];
 $duitasuo_schoolname = mysql_query("SELECT * FROM universities where id='$msg_schoolid'") OR die(mysql_query());
 $row_schoolname = mysql_fetch_assoc($duitasuo_schoolname);
 $msg_schoolname = $row_schoolname['schoolname'];
 
 if ($imagelocation != ""){
	 list($width, $height, $type, $attr) = getimagesize($imagelocation);
	 if ($width > 315){
		$height = (315*$height)/$width;
		$width = 315;
	 }
	 
	 echo "<div class='top_post'>
	 
		   <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
		   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
		   <div class='course_users_row_username'>".$msg_username."</div>
		   </a> <div class='course_users_row_role'>(".$role.") from <a href='home.php?schoolid=".$msg_schoolid."'>".$msg_schoolname."</a>: </div></h4>
		   <img class='image_posted' src='".$imagelocation."' width='".$width."' height='".$height."'/>
		   <p class='words'>".$words."</p>
		   
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
		   </a> <div class='course_users_row_role'>(".$role.") from <a href='home.php?schoolid=".$msg_schoolid."'>".$msg_schoolname."</a>: </div></h4>
		   <p class='words'>".$words."</p>
		   
		   <ul class='top_post_bottom' onselectstart='return false'>
		   <li class='top_post_time'>".$timesubmit."</li>
		   <li id='geili_".$msgid."' class='top_post_geili'>".$likes."</li>
		   </ul>
		   
		   </div>";
 }
       
  $top_posts_num++;
  if ($top_posts_num >4){
   break;
  }     

}
?>
</div>
</div>
</div>
</div>


<div class="pop_schs">
<div class="pop_schs_box">
<div id="five_pop_schs">
<h2 title="Top 10 Most Popular Schools!">Top 10 Schools</h2>
<div id="five_schs">
<?php

$top_posts_num = 0;
$timenow = time();
$duitasuo_msg = mysql_query("SELECT * FROM universities ORDER BY likes DESC") OR die(mysql_query());
while ($row = mysql_fetch_assoc($duitasuo_msg))
{
 $schoolid = $row['id'];
 $schoolname = $row['schoolname'];
 $likes = $row['likes'];

 echo "<div class='top_school'>
 
       <div class='schoolname' onselectstart='return false'><a href='home.php?schoolid=".$schoolid."'>".$schoolname."</a></div>
       <div class='schoollikes' title='What are you waiting for? Go to post in your school'>".$likes."</div>
       
       </div>";
       
  $top_posts_num++;
  if ($top_posts_num >9){
   break;
  }     

}
?>
</div>
</div>
</div>
</div>


<div class="login">
<div class="login_box">
<div id="login_form">
<h2>Log In</h2>
<?php
if (isset($_SESSION['id']))
      {
	  if ($renren_bangdingcheck == 1){
	  echo "<div id='login_successful'>人人绑定登录：".$_SESSION['username']."   (<a href='index_cancel_bangding.php' text-align='right'>取消绑定</a>)</div>";
      } else {
	  echo "<div id='login_successful'>You logged in as: ".$_SESSION['username']."   (<a href='index_logout.php' text-align='right'>log out</a>)</div>";
      }
	  }
      else
      {
      ?>
<div id="login_field">
<input type="text" style="width: 195px; color: #999;" id="login_email" value="Email (email@domain.com)" onfocus="if(this.value=='Email (email@domain.com)'){this.value=''}; this.style.color='black';" onblur="if(this.value==''){this.value='Email (email@domain.com)'; this.style.color='#999';};">
<input type="password" style="width: 195px;" id="login_password" value="Password" onfocus="if(this.value=='Password'){this.value=''}; this.style.color='black';" onblur="if(this.value==''){this.value='Password'; this.style.color='#999';};">
<div class="savepassword">
<form name="autologinform" title="" for="autologin" class="logincheckbox">
<input type="checkbox" name="autologin" id="autologin" value="1" />Remember me
</form>
</div>
<div class="buttons">
<p id="login_button" class="login_button" onclick="login()">Log in</p>
<p id="signup_button" class="signup_button" onclick="signup()">Sign up</p>
</div>
<div id="login_message">
</div>
</div>
<?php
}
?>
</div>

<!--
<div id="other_login_form">
<h2>Facebook Log In</h2>
<div id="other_logins">
<div class="other_login_buttons">
<?php 
if (isset($_COOKIE[$API_KEY.'_user'])){
?>
<p class="renren_connect_pic"><xn:profile-pic uid="loggedinuser" size="tiny" linked="true" connect-logo="false"></xn:profile-pic></p>
<p class="renren_connect_username">人人网登录：<xn:name uid="loggedinuser"></xn:name></p><br>
<p class="renren_connect_login"><xn:login-button autologoutlink="true"></xn:login-button></p>
<?php
} else {
?>
<p class="renren_connect_username"><xn:login-button autologoutlink="true"></xn:login-button></p>
<?php
}

if ($renren_bangdingcheck == 0){
echo "<p id='renren_bangding_button' class='renren_bangding_button' onclick='renren_bangding()'>人人绑定</p>";
}
?>
</div>
-->
<!--
<xn:login-button size="medium" background="blue" label="与人人连接" show-faces="true" face-size="small" max-rows="2" face-space="5" width="255"></xn:login-button>
-->
<!--
<?php 
if (isset($_COOKIE[$API_KEY.'_user'])){
?>
<xn:friendpile show-faces="connected" face-size="small" max-rows="5" face-space="5" width="255"></xn:friendpile>
<?php
}
?>
</div>
</div>
-->

</div>
</div>

<div id="copyright">
tuttr.me Inc. &copy; 2015
</div>

</div>

<div id="signup" class="signup">
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

<div id="campus_search"> 
<input type="text" id="campus_search_input" onkeyup="campus_search()" oninput="campus_search()" onpropertychange="campus_search()" onfocus="if(this.value=='Find Your School Now'){this.value=''};" onblur="if(this.value==''){this.value='Find Your School Now';};" value="Find Your School Now"/>
<span id="search_results">
</span> 
</div>

<div id="bangding_login_signup" class="bangding_login_signup">

<div class="bangding_login">
<div class="login_box">
<div id="login_form">
<h2>用已有账号与人人绑定</h2>
<div id="login_field">
帐号：<input type="text" style="width: 195px; color: #999;" id="bangding_login_email" value="用户邮箱" onfocus="if(this.value=='用户邮箱'){this.value=''}; this.style.color='black';" onblur="if(this.value==''){this.value='用户邮箱'; this.style.color='#999';};">
密码：<input type="password" style="width: 195px;" id="bangding_login_password">
<div class="savepassword">
<form name="bangding_autologinform" title="为了确保您的信息安全，请不要在网吧或者公共机房勾选此项！" class="logincheckbox">
<input type="checkbox" name="bangding_autologin" id="bangding_autologin" value="1" />下次自动登录
</form>
</div>
<div class="buttons">
<?php echo "<p id='bangding_login_button' class='bangding_login_button' onclick=bangding_login(".$renren_userid.")>绑定</p>"; ?>
<p class="cancel_bangding_login_button" onclick="close_bangding()">取消</p>
</div>
<div id="bangding_login_message">
</div>
</div>
</div>
</div>
</div>

<div class="bangding_signup">
<div class="login_box">
<div id="login_form">
<h2>注册账号并与人人绑定</h2>
<div id="signup_field">
名字：<input type="text" style="width: 195px;" id="bangding_signup_username">
邮箱：<input type="text" style="width: 195px;" id="bangding_signup_email">
密码：<input type="password" style="width: 195px;" id="bangding_signup_password">
<div class="buttons">
<?php echo "<p id='bangding_signup_button' class='bangding_signup_button' onclick='bangding_signup(".$renren_userid.")'>绑定</p>"; ?>
<p class="cancel_bangding_signup_button" onclick="close_bangding()">取消</p>
</div>
<div id="bangding_signup_message">
</div>
</div>
</div>
</div>
</div>

</div>


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
	
	
</script>
</body>
</html>