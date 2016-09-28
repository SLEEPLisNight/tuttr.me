<?php
header('Content-type: text/html; charset=utf-8');
include 'connect_to_mysql.php';  // connect to mysql database.
if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}

$ip = $_SESSION['ip'];
$schoolid = $_SESSION['schoolid'];

if (isset($_SESSION['timezonename'])){
	$timezonename = $_SESSION['timezonename'];
}

$pmsgid = $_GET['pmsgid'];
$updateread = $_GET['updateread'];

if ($updateread == 1){
mysql_query ("UPDATE duitasuopmsg SET readit = 1 WHERE id='$pmsgid'") or die(mysql_error());
mysql_query ("UPDATE duitasuopmsgreply SET readit = 1 WHERE pmsgid='$pmsgid'") or die(mysql_error());
}

$duitasuo_pmsg = mysql_query("SELECT * FROM duitasuopmsg WHERE id='$pmsgid' ORDER BY id ASC;") OR die(mysql_query());
$row = mysql_fetch_assoc($duitasuo_pmsg);

 $pmsgid = $row['id'];
 $msgid = $row['msgid'];
 $cmtid = $row['cmtid'];
 $touserid = $row['touserid'];
 $touserip = $row['touserip'];
 $fromuserid = $row['userid'];
 $fromuserip = $row['userip'];
 $title = $row['title'];
 $content = $row['content'];
 $timesubmit = $row['timesubmit'];
 $readit = $row['readit'];
 $block = $row['block'];
 
 if ($userid != 0 && $userid == $touserid) {
 $pmsgreceiver = 1;
 $otheruserid = $fromuserid;
 $otheruserip = $fromuserip;
 } else if ($userid != 0 && $userid == $fromuserid) {
 $pmsgreceiver = 0;
 $otheruserid = $touserid;
 $otheruserip = $touserip;
 } else if ($userid == 0 && $touserid == 0 && $ip == $touserip){
 $pmsgreceiver = 1;
 $otheruserid = $fromuserid;
 $otheruserip = $fromuserip;
 } else if ($userid == 0 && $fromuserid == 0 && $ip == $fromuserip){
 $pmsgreceiver = 0;
 $otheruserid = $touserid;
 $otheruserip = $touserip;
 } 
 
 $duitasuo_pmsg_user = mysql_query("SELECT * FROM users WHERE id='$otheruserid' ORDER BY id ASC;") OR die(mysql_query());      
 $row_pmsg_user = mysql_fetch_assoc($duitasuo_pmsg_user);
 $otherusername = $row_pmsg_user['username'];
 
 $sendpmsgtime = date('Y-m-d g:ia',strtotime($timezonename,$timesubmit));
 
 echo "<div id='pmsg_rows_form'>
       <h3>Subject: ".$title."</h3>
       <h4>Between: <a href='profile.php?userid=".$otheruserid."'>".$otherusername."</a> and you</h4>";
       
 $duitasuo_pmsg_fromuser = mysql_query("SELECT * FROM users WHERE id='$fromuserid' ORDER BY id ASC;") OR die(mysql_query());      
 $row_pmsg_fromuser = mysql_fetch_assoc($duitasuo_pmsg_fromuser);
 $fromusername = $row_pmsg_fromuser['username'];
 if ($row_pmsg_fromuser['imagelocation']==""){
	$fromimagelocation = "photos/users/default_profile.jpg";
 } else {
	$fromimagelocation = $row_pmsg_fromuser['imagelocation'];
 }
      
 echo "<div class='pmsgs'>
       <h6 onselectstart='return false'><a href='profile.php?userid=".$fromuserid."'>
	   <div class='course_users_row_picture'><img src=".$fromimagelocation." width='20' height='20'></div>
	   <div class='course_users_row_username'>".$fromusername."</div>
	   </a><div class='course_users_row_username'> sent at ".$sendpmsgtime.":</div></h6>
       <p>".$content."</p>
       </div>";
       
 echo "<div id='pmsg_replys'>";  
 
 $duitasuo_pmsg_reply = mysql_query("SELECT * FROM duitasuopmsgreply WHERE pmsgid='$pmsgid' ORDER BY timesubmit ASC;") OR die(mysql_query());
 while ($row_pmsg_reply = mysql_fetch_assoc($duitasuo_pmsg_reply))
 {
 $pmsg_reply_fromuserid = $row_pmsg_reply['userid'];
 $pmsg_reply_timesubmit = $row_pmsg_reply['timesubmit'];
 $pmsg_reply_content = $row_pmsg_reply['content'];
 
 $duitasuo_pmsg_reply_fromuser = mysql_query("SELECT * FROM users WHERE id='$pmsg_reply_fromuserid' ORDER BY id ASC;") OR die(mysql_query());      
 $row_pmsg_reply_fromuser = mysql_fetch_assoc($duitasuo_pmsg_reply_fromuser);
 $pmsg_reply_fromusername = $row_pmsg_reply_fromuser['username'];
 if ($row_pmsg_reply_fromuser['imagelocation']==""){
	$pmsg_reply_fromimagelocation = "photos/users/default_profile.jpg";
 } else {
	$pmsg_reply_fromimagelocation = $row_pmsg_reply_fromuser['imagelocation'];
 }
 
 $replypmsgtime = date('Y-m-d g:ia',strtotime($timezonename,$pmsg_reply_timesubmit));
 
 echo "<div class='pmsgs'>
       <h6 onselectstart='return false'><a href='profile.php?userid=".$pmsg_reply_fromuserid."'>
	   <div class='course_users_row_picture'><img src=".$pmsg_reply_fromimagelocation." width='20' height='20'></div>
	   <div class='course_users_row_username'>".$pmsg_reply_fromusername."</div>
	   </a><div class='course_users_row_username'> sent at ".$replypmsgtime.":</div></h6>
       <p>".$pmsg_reply_content."</p>
       </div>";
 }
 
 echo "</div>";    
       
 if ($block == 0){      
 echo "<div id='reply_pmsg' onselectstart='return false'> 
       <textarea rows='3' type='text' id='reply_pmsg_text' class='reply_pmsg_textarea'></textarea>  
       <a id='reply_pmsg_button' class='leave_comment_input_submit' onclick='reply_pmsg(".$pmsgid.",".$otheruserid.",\"".$otheruserip."\")'>Send</a>
       <a class='close_pmsg_button' onclick='set_pmsg_close_question(".$pmsgid.")'>End Conversation</a>
       <a class='close_pmsg_button' onclick='set_pmsg_unread(".$pmsgid.")'>Mark Unread</a>
       <a class='close_pmsg_button' onclick='see_pmsg()'>Back to Inbox</a>
       </div>
       </div>";
 } else {
 echo "</div>
       <div class='close_pmsg' onselectstart='return false'>
       <div class='close_pmsg_notice'>This conversation is ended.</div>
       <a class='backto_pmsg_button' onclick='see_pmsg()'>Back to Inbox</a>
       </div>";
 } 

?>