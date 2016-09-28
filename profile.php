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

if (!isset($_SESSION['id'])){ //user is not logged in
	header('Location: profilenotlogin.php'); //if user is not logged in, direct to the sign in page.
}

$userid = $_GET["userid"];
$user = mysql_query("SELECT * FROM users WHERE id='$userid' ORDER BY id ASC;") OR die(mysql_query());      
$row_user = mysql_fetch_assoc($user);
$username = $row_user['username'];
$schoolid = $row_user['schoolid'];
$imagelocation = $row_user['imagelocation'];
$description = $row_user['description'];

if ($schoolid != 0){
	$result_school = mysql_query("SELECT * FROM universities WHERE id='$schoolid' ORDER BY id ASC;") or die();
	$row_school =  mysql_fetch_array($result_school);
	$schoolname = $row_school['schoolname'];
} else {
	if($_SESSION['id'] != $userid){
		$schoolname = "Not available";
	} else {
		$schoolname = "Add your school";
	}
}

$k = 0;
$courseids = array();
$coursenumbers = array();
$usercourse_students = array();
$usercourse_tutors = array();
$usercourses_ids = array();
$university_ids = array();
$university_names[$k] = array();
$result_usercourses = mysql_query("SELECT * FROM usercourses WHERE userid='$userid' ORDER BY id ASC;") or die();
while ($row_usercourses = mysql_fetch_assoc($result_usercourses)){
	$usercourses_ids[$k] = $row_usercourses['id'];
	$usercourse_students[$k] = $row_usercourses['student'];
	$usercourse_tutors[$k] = $row_usercourses['tutor'];
	$courseids[$k] = $row_usercourses['courseid'];
	
	$result_classes = mysql_query("SELECT * FROM classes WHERE id='$courseids[$k]' ORDER BY id ASC;") or die();
	$row_classes =  mysql_fetch_array($result_classes);
	$coursenumbers[$k] = $row_classes['coursenumber'];
	$university_ids[$k] = $row_classes['university'];
	
	$result_universities = mysql_query("SELECT * FROM universities WHERE id='$university_ids[$k]' ORDER BY id ASC;") or die();
	$row_universities =  mysql_fetch_array($result_universities);
	$university_names[$k] = $row_universities['schoolname'];
	
	$k++;
}

//get user local timezone
$timezonename = "";
if (isset($_SESSION['timezonename'])){
	$timezonename = $_SESSION['timezonename'];
}
?>

<!-- define DOCTYPE for the website at the top of the page -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xn="http://www.renren.com/2009/xnml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>tuttr.me - <?php echo $username; ?></title>
<link rel="shortcut icon" type="image/x-icon" href="images/tuttr_icon.ico">
<link href="home.css" rel="stylesheet" type="text/css" />
<link href="button.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link href="home_ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<script type="text/javascript" src="home.js"></script>
<script type="text/javascript" src="profile.js"></script>
</head>

<body onload=init(<?php echo json_encode($timezonename.'|profile.php?userid='.$userid); ?>) >

<div class="header">
<div class="header_bg">
<ul class='profile' onselectstart='return false'>
<a href='profile.php?userid=<?php echo $_SESSION['id']; ?>'><li class='menu' onmouseover=this.className='menu_onmouseover' onmouseout=this.className='menu'>
<?php
if ($_SESSION['id'] != 0){ //active user
	$temp_userid = $_SESSION['id'];
	$users_image = mysql_query("SELECT * FROM users WHERE id='$temp_userid' ORDER BY id ASC;") OR die(mysql_query());      
	$row_users_image = mysql_fetch_assoc($users_image);
	$userimage = $row_users_image['imagelocation'];
} else { //anonymous user
	$userimage = "photos/users/default_profile.jpg";
}

echo "<div class='course_users_row_picture'><img src=".$userimage." width='20' height='20'></div>
	  <div class='course_users_row_username'>".$_SESSION['username']."</div>"; 
?>
</li></a>
<a href='home.php?schoolid=<?php echo $_SESSION['schoolid']; ?>'><li class='menu' onmouseover=this.className='menu_onmouseover' onmouseout=this.className='menu'><?php echo $_SESSION['schoolname']; ?></li></a>
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

	<div class="profile_top"> 
  
	<?php
	if(strcmp($imagelocation, "") != 0){
		echo "<p class='label'><img src=".$imagelocation." class='photo_show' width='90' height='90'></p>";	
	} else { //check if user has uploaded picture already
		echo "<p class='label'><img src='photos/users/default_profile.jpg' class='photo_show' width='90' height='90'></p>";
	}
	?>
	
	<div class="profile_username"><?php echo $username ?> 
	<?php 
	if($_SESSION['id'] != $userid){
		echo "<a class='profile_sendmessage' onclick='open_pmsg(0,".json_encode($username).",".$userid.",0,0)'>(send message)</a>";
	}
	?>
	</div> 

	</div>
  
	<?php 
	if($_SESSION['id'] == $userid){
		echo "<form method='post' action='profile.php?userid=".$userid."' enctype='multipart/form-data' class='profile_picture_upload' onmouseover=this.className='profile_picture_upload_onmouseover' onmouseout=this.className='profile_picture_upload'>
		<span>Upload picture</span>
		<input type='file' name='myfile' class='upload' onchange='submit()'/>
		<input type='submit' id='upload_photo' value='Upload' style='display:none'>
		</form>";
	}
	
	/* function to get the file extension */
		function getExtension($str){
			 $i = strrpos($str,".");
			 if (!$i) { return ""; }
			 $l = strlen($str) - $i;
			 $ext = substr($str,$i+1,$l);
			 return $ext;
		}
		
	/* upload photo */
		if(isset($_FILES['myfile'])){
			$name = preg_replace('/\s+/', '_', $_FILES['myfile']['name']);
			$extension = getExtension($name);
			$extension = strtolower($extension);
			$tmp_name= $_FILES['myfile']['tmp_name'];

			if($name){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif") && ($extension != "bmp")){
					//print error message
					echo "<div class='profile_picture_upload_message'>(Please choose an image file)</div>";
				} else {	
					if(is_dir("photos/users/") == false){
						mkdir("photos/users/", 0777, true);
					}
					
					$timenow = time();
					$picture_name = "profile_picture_".$userid."_".$timenow."_".$name;
					$location = "photos/users/".$picture_name;
					move_uploaded_file($tmp_name,$location);
				   
					$sql_update =  mysql_query("UPDATE users SET imagelocation = '$location' WHERE id = $userid");
					echo "<div class='profile_picture_upload_message'>(Upload successfully. <a href='profile.php?userid=$userid'>Refresh</a>)</div>";
					header('Location: profile.php?userid='.$userid.'');
				}
			}
		} 
	?>
  
  <div class="profile_school"><div class='profile_schoolname'>School: </div>
  <?php 
  if($_SESSION['id'] != $userid){
	  if ($schoolid != 0){
		  echo "<div id='profile_schoolname_id' class='profile_schoolname'><a href='home.php?schoolid=".$schoolid."'>".$schoolname."</a></div>";
	  } else {
		  echo "<div id='profile_schoolname_id' class='profile_schoolname'>".$schoolname."</div>";
	  }
  } else {
	  if ($schoolid != 0){
		  echo "<div id='profile_schoolname_id' class='profile_schoolname'><a href='home.php?schoolid=".$schoolid."'>".$schoolname."</a> <a onclick='addschool()'>(edit)</a></div>";
	  } else {
		  echo "<div id='profile_schoolname_id' class='profile_schoolname'><a onclick='addschool()'>".$schoolname."</a></div>";
	  } 
  }
  ?>
  </div>  
  
  <div class="profile_school"><div class='profile_schoolname'>Classes: </div>
  <?php 
  if($_SESSION['id'] != $userid){
		if (count($courseids) != 0){
			echo "<div id='profile_coursenumber_id_first' class='profile_classname'><a href='course.php?schoolid=".$university_ids[0]."&courseid=".$courseids[0]."'>".$coursenumbers[0]."</a>(<a href='home.php?schoolid=".$university_ids[0]."'>".$university_names[0]."</a>): ";
			if ($usercourse_students[0] == 0 && $usercourse_tutors[0] == 0){
				echo "No Roles";
			} else if ($usercourse_students[0] == 1 && $usercourse_tutors[0] == 0){
				echo "Student";
			} else if ($usercourse_students[0] == 0 && $usercourse_tutors[0] == 1){
				echo "Tutor";
			} else if ($usercourse_students[0] == 1 && $usercourse_tutors[0] == 1){
				echo "Student &amp; Tutor";
			}
			echo "</div>";
	
			for ($i = 1; $i < count($courseids); $i++){
				echo "<div id='profile_coursenumber_id' class='profile_classname'><a href='course.php?schoolid=".$university_ids[$i]."&courseid=".$courseids[$i]."'>".$coursenumbers[$i]."</a>(<a href='home.php?schoolid=".$university_ids[$i]."'>".$university_names[$i]."</a>): ";
				if ($usercourse_students[$i] == 0 && $usercourse_tutors[$i] == 0){
					echo "No Roles";
				} else if ($usercourse_students[$i] == 1 && $usercourse_tutors[$i] == 0){
					echo "Student";
				} else if ($usercourse_students[$i] == 0 && $usercourse_tutors[$i] == 1){
					echo "Tutor";
				} else if ($usercourse_students[$i] == 1 && $usercourse_tutors[$i] == 1){
					echo "Student &amp; Tutor";
				}
				echo "</div>";
			}
		} else {
			echo "<div id='profile_coursenumber_id_first' class='profile_classname'>No Classes</div>";
		}
  } else {
		if (count($courseids) != 0){
			echo "<div id='profile_coursenumber_id_first' class='profile_classname'><a href='course.php?schoolid=".$university_ids[0]."&courseid=".$courseids[0]."'>".$coursenumbers[0]."</a>(<a href='home.php?schoolid=".$university_ids[0]."'>".$university_names[0]."</a>):";
			if ($usercourse_students[0] == 0 && $usercourse_tutors[0] == 0){
				echo "<input type='checkbox' onchange='updateroles($usercourses_ids[0],1,this)'>Student <input type='checkbox' onchange='updateroles($usercourses_ids[0],2,this)'>Tutor ";
			} else if ($usercourse_students[0] == 1 && $usercourse_tutors[0] == 0){
				echo "<input type='checkbox' onchange='updateroles($usercourses_ids[0],1,this)' checked>Student <input type='checkbox' onchange='updateroles($usercourses_ids[0],2,this)'>Tutor ";
			} else if ($usercourse_students[0] == 0 && $usercourse_tutors[0] == 1){
				echo "<input type='checkbox' onchange='updateroles($usercourses_ids[0],1,this)'>Student <input type='checkbox' onchange='updateroles($usercourses_ids[0],2,this)' checked>Tutor ";
			} else if ($usercourse_students[0] == 1 && $usercourse_tutors[0] == 1){
				echo "<input type='checkbox' onchange='updateroles($usercourses_ids[0],1,this)' checked>Student <input type='checkbox' onchange='updateroles($usercourses_ids[0],2,this)' checked>Tutor ";
			}
			echo "<a onclick='deletecourse(".$courseids[0].",".$userid.")'>(delete)</a></div>";
			
			for ($i = 1; $i < count($courseids); $i++){
				echo "<div id='profile_coursenumber_id' class='profile_classname'><a href='course.php?schoolid=".$university_ids[$i]."&courseid=".$courseids[$i]."'>".$coursenumbers[$i]."</a>(<a href='home.php?schoolid=".$university_ids[$i]."'>".$university_names[$i]."</a>):";
				if ($usercourse_students[$i] == 0 && $usercourse_tutors[$i] == 0){
					echo "<input type='checkbox' onchange='updateroles($usercourses_ids[$i],1,this)'>Student <input type='checkbox' onchange='updateroles($usercourses_ids[$i],2,this)'>Tutor ";
				} else if ($usercourse_students[$i] == 1 && $usercourse_tutors[$i] == 0){
					echo "<input type='checkbox' onchange='updateroles($usercourses_ids[$i],1,this)' checked>Student <input type='checkbox' onchange='updateroles($usercourses_ids[$i],2,this)'>Tutor ";
				} else if ($usercourse_students[$i] == 0 && $usercourse_tutors[$i] == 1){
					echo "<input type='checkbox' onchange='updateroles($usercourses_ids[$i],1,this)'>Student <input type='checkbox' onchange='updateroles($usercourses_ids[$i],2,this)' checked>Tutor ";
				} else if ($usercourse_students[$i] == 1 && $usercourse_tutors[$i] == 1){
					echo "<input type='checkbox' onchange='updateroles($usercourses_ids[$i],1,this)' checked>Student <input type='checkbox' onchange='updateroles($usercourses_ids[$i],2,this)' checked>Tutor ";
				}
				echo "<a onclick='deletecourse(".$courseids[$i].",".$userid.")'>(delete)</a></div>";
			}
			
			echo "<div id='profile_coursenumber_id' class='profile_classname'><a onclick='addcourse()'>Add more</a></div>";
		} else {
			echo "<div id='profile_coursenumber_id_first' class='profile_classname'><a onclick='addcourse()'>Add your class</a></div>";
		}
  }
  ?>
  </div>
	
	<div class="profile_school"><div class='profile_schoolname'>Description: </div>
	<div id='profile_description_id' class='profile_description'>
	<?php 
		if($_SESSION['id'] != $userid){
			echo "<div class='profile_description_textarea'>".$description."</div>";
		} else {
			echo "<textarea class='profile_description_textarea' rows='4' type='text' id='description' name='description' >".$description."</textarea>
				  <a class='save_profile_description' onmouseover=this.className='save_profile_description_onmouseover' onmouseout=this.className='save_profile_description' onclick='save_profile_description()'>save</a>";
		}
	?>
	</div>
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

<div id="schoollikes">
<span class="schoollikes_name"><b><?php echo $username ?></b> 人品：</span>
<?php 
echo "<iframe scrolling='no' frameborder='0' allowtransparency='true' src='http://www.connect.renren.com/like?url=http%3A%2F%2Fwww.duitasuo.com%2Fhome.php%3Fschoolid%3D".$schoolid."&showfaces=false' style='width: 120px;height: 22px;'></iframe>"; 
?>
</div>

<div id="schoolshares">
<span class="schoolshares_name"><b><?php echo $username ?></b> 分享：</span>
<?php 
echo "<a name='xn_share' type='button_count_right' href='http://www.duitasuo.com/home.php?schoolid=".$schoolid."'>分享</a>
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

<div id="profile_school_search"> 
<a><div class="close" onclick="cancel_addschool()">x</div></a>
<input type="text" id="profile_school_search_input" onkeyup="school_search()" oninput="school_search()" onpropertychange="school_search()" onfocus="if(this.value=='Find Your School'){this.value=''};" onblur="if(this.value==''){this.value='Find Your School';};" value="Find Your School"/>
<span id="profile_school_search_results">
</span> 
</div>

<div id="profile_course_search"> 
<a><div class="close" onclick="cancel_addcourse()">x</div></a>
<input type="text" id="profile_course_search_input" onkeyup="profile_course_search(<?php echo $schoolid; ?>)" oninput="profile_course_search(<?php echo $schoolid; ?>)" onpropertychange="profile_course_search(<?php echo $schoolid; ?>)" onfocus="if(this.value=='Find Your Class (Math 101)'){this.value=''};" onblur="if(this.value==''){this.value='Find Your Class (Math 101)';};" value="Find Your Class (Math 101)"/>
<span id="profile_course_search_results">
</span> 
</div>

</div> <!-- end of wrapper -->

<div id="post_search"> 
<input type="text" id="post_search_input" onkeyup="post_search(event)" onfocus="if(this.value=='Search Your Question'){this.value=''};" onblur="if(this.value==''){this.value='Search Your Question';};" value="Search Your Question"/>
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
	
function init(TimezoneAndPage){
	/*
	setInterval(function(){refresh_duitasuo('0');},600000);
	*/
	//var t1 = setInterval(function(){online_user_counter();},6000);
	var t2 = setInterval(function(){unread_comment();},2000);
	var t3 = setInterval(function(){unread_pmsg();},2000);
	//var t4 = setInterval(function(){chat_online_friends_counter();},2000);
	//var t5 = setInterval(function(){chat_unreceive_ids();},1000);
	//InitDragDrop();
	/*
	setInterval(function(){refresh_top_posts();},300000);
	*/
	get_user_timezone(TimezoneAndPage);
}

/* user local time zone */

function get_user_timezone(TimezoneAndPage){
	var TimezoneAndPageArray = new Array();
	TimezoneAndPageArray = TimezoneAndPage.split("|");

	var timezone = TimezoneAndPageArray[0] + "";
	var page = TimezoneAndPageArray[1] + "";
	
	if (timezone.length == 0){
		var visitortime = new Date();
		var visitortimezone = -visitortime.getTimezoneOffset()/60;
		
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp_timezone=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp_timezone=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp_timezone.onreadystatechange=function()
		  {
			if (xmlhttp_timezone.readyState==4 && xmlhttp_timezone.status==200){
				window.location = page;
			}
		  }
		xmlhttp_timezone.open("GET","timezone.php?time="+visitortimezone,true);
		xmlhttp_timezone.send();
	}
}

function submit(){
	document.getElementById('upload_photo').click();
}
</script>
</body>
</html>