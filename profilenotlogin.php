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
?>

<!-- define DOCTYPE for the website at the top of the page -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xn="http://www.renren.com/2009/xnml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>tuttr.me</title>
<link rel="shortcut icon" type="image/x-icon" href="images/tuttr_icon.ico">
<link href="home.css" rel="stylesheet" type="text/css" />
<link href="button.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link href="home_ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript" src="home.js"></script>
<script type="text/javascript" src="profile.js"></script>
</head>

<body>

<div class="header">
<div class="header_bg">
<ul class='profile' onselectstart='return false'>
<a href='home.php?schoolid=<?php echo $_SESSION['schoolid']; ?>'><li class='menu' onmouseover=this.className='menu_onmouseover' onmouseout=this.className='menu'><?php echo $_SESSION['schoolname']; ?></li></a>
<a><li class='menu' onmouseover=this.className='menu_onmouseover' onmouseout=this.className='menu' onclick="login_signup()" >Log In</li></a>
</ul>
</div>
</div>

<div id="wrapper">

<div id="main_content">

<div class="content">

<div id="duitasuo_post"> 

	<div class="profile_top"> 

	<div class="profile_username">Please <a style="color:red;" onclick="login_signup()">login or signup</a> to access profile page</div> 

	</div>

</div>

<div id="duitasuo_display">

<div id="post_status" onselectstart="return false">
<div id="post_status_control" class="post_status_control">
</div>
</div>

<div id="posts" class="posts">
</div>

</div> <!-- end div id="duitasuo_display" -->

</div> <!-- end div class="content" -->

<div id="sidebar" class="sidebar">
<div id="sidebar_box" class="sidebar_box">

<!--
<div id="facebook_connect">
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
-->

<!-- 暂时取消功能
<div id="renren_guanzhu">
<iframe scrolling="no" frameborder="0" src="http://widget.renren.com/fanBoxWidget?appId=139738&pageImg=true&pageName=true&pageFriend=true&characterColor=ff0000&linkColor=255&borderColor=0&mainBackground=0&subBackground=0&desc=%E5%85%B3%E6%B3%A8%E6%9C%80%E6%96%B0%E5%8A%A8%E6%80%81" style="width: 316px;height: 170px;"></iframe>
</div>
-->

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
<?php echo "<p id='login_button' class='login_button' onclick=login(".$_SESSION['schoolid'].")>Log in</p>"; ?>
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

<div id="copyright">
tuttr.me Inc. &copy; 2015
</div>

<div id="new_notification" ></div>
<div id="new_message" ></div>

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
	var schoolid = "<?php echo $schoolid ?>";
	var schoolname = "<?php echo $schoolname ?>";
	var gender = document.getElementById("gender_msg_"+id+"").innerHTML;
	var time = document.getElementById("time_msg_"+id+"").innerHTML;
	var place = document.getElementById("place_msg_"+id+"").innerHTML;
	var descr = document.getElementById("descr_msg_"+id+"").innerHTML;
	var words = document.getElementById("words_msg_"+id+"").innerHTML;
	var content = time+"，我在"+place+"看到一个"+gender+"。"+descr+" "+words;
	
  		feedSettings = {
  			"template_bundle_id": 1,
			"template_data": {"images":[{"src":"http://www.duitasuo.com/images/tuitasuo_logo.jpg","href":"http://www.duitasuo.com/home.php?schoolid="+schoolid+""}], "site":"<a href=\"http://www.duitasuo.com\">对ta说</a>","feedtype":""+schoolname+"","content":""+content+"","action":"click"},
  			"user_message_prompt": "邀请大家都来看看吧！",
  			"user_message": "好精彩啊！快来看看！"
  		};
  		XN.Connect.showFeedDialog(feedSettings);
  	}

</script>
</body>
</html>