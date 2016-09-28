<?php
header('Content-type: text/html; charset=utf-8');
include 'connect_to_mysql.php';  // connect to mysql database.
if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}
$ip = $_SESSION['ip'];

$timenow = time();
$mymsgs = 0;
$matchscore = 20;

if (isset($_SESSION['timezonename'])){
	$timezonename = $_SESSION['timezonename'];
}

function utf8_urldecode($str) {
			$str = nl2br($str);
			$str = str_replace("'","\'",$str);
			$str = str_replace("<","&lt;",$str);
    		$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
    		return html_entity_decode($str,null,'gb2312');
}

$searchwords = utf8_urldecode($_GET["searchwords"]);

function compareStrings($s1, $s2) {
    //one is empty, so no result
    if (strlen($s1)==0 || strlen($s2)==0) {
        return 0;
    }

    //replace none alphanumeric charactors
    //i left - in case its used to combine words
    $s1clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $s1);
    $s2clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $s2);

    //remove double spaces
    while (strpos($s1clean, "  ")!==false) {
        $s1clean = str_replace("  ", " ", $s1clean);
    }
    while (strpos($s2clean, "  ")!==false) {
        $s2clean = str_replace("  ", " ", $s2clean);
    }

    //create arrays
    $ar1 = explode(" ",$s1clean);
    $ar2 = explode(" ",$s2clean);
    $l1 = count($ar1);
    $l2 = count($ar2);

    //flip the arrays if needed so ar1 is always largest.
    if ($l2>$l1) {
        $t = $ar2;
        $ar2 = $ar1;
        $ar1 = $t;
    }

    //flip array 2, to make the words the keys
    $ar2 = array_flip($ar2);


    $maxwords = max($l1, $l2);
    $matches = 0;

    //find matching words
    foreach($ar1 as $word) {
        if (array_key_exists($word, $ar2))
            $matches++;
    }

    return ($matches / $maxwords) * 100;    
}

function string_compare($str_a, $str_b){
    $length = strlen($str_a);
    $length_b = strlen($str_b);
 
	$i = 0;
    $segmentcount = 0;
    $segmentsinfo = array();
    $segment = '';
    while ($i < $length)
    {
        $char = substr($str_a, $i, 1);
        if (strpos($str_b, $char) !== FALSE)
        {
            $segment = $segment.$char;
            if (strpos($str_b, $segment) !== FALSE)
            {
                $segmentpos_a = $i - strlen($segment) + 1;
                $segmentpos_b = strpos($str_b, $segment);
                $positiondiff = abs($segmentpos_a - $segmentpos_b);
                $posfactor = ($length - $positiondiff) / $length_b; // <-- ?
                $lengthfactor = strlen($segment)/$length;
                $segmentsinfo[$segmentcount] = array( 'segment' => $segment, 'score' => ($posfactor * $lengthfactor));
            }
            else
            {
                 $segment = '';
                 $i--;
                 $segmentcount++;
             }
         }
         else
         {
             $segment = '';
            $segmentcount++;
         }
         $i++;
     }
 
     // PHP 5.3 lambda in array_map
     $totalscore = array_sum(array_map(function($v) { return $v['score'];  }, $segmentsinfo));
     return $totalscore;
}
 
 $result_msg = mysql_query("SELECT * FROM duitasuomsg ORDER BY timesubmit DESC;") or die();
 while($row_msg = mysql_fetch_assoc($result_msg)){
	 $words = $row_msg['words'];
	 
	 if (compareStrings($searchwords,$words) >= $matchscore){
		$msg_schoolid = $row_msg['schoolid'];
		$msg_universities = mysql_query("SELECT * FROM universities WHERE id='$msg_schoolid' ORDER BY id ASC;") OR die(mysql_query());      
		$row_msg_universities = mysql_fetch_assoc($msg_universities);
		$msg_schoolname = $row_msg_universities['schoolname'];
		
		$msgid = $row_msg['id'];
		$role = $row_msg['role'];
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
 }
 else {
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
 
 if ($userid != 0 && $row_msg['userid'] == $userid){
 $msgowner = 1;
 } else if ($userid == 0 && $row_msg['userid'] == 0 && $row_msg['ip'] == $ip){
 $msgowner = 1;
 } else {
 $msgowner = 0;
 }
 
 if ($msgowner == 1){
 echo "<div class='post_owner'>
 
       <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
	   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
	   <div class='course_users_row_username'>".$msg_username."</div>
	   </a> (".$role.") from <a href='home.php?schoolid=".$msg_schoolid."'>".$msg_schoolname."</a>: </h4>
       
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
	   </a> (".$role.") from <a href='home.php?schoolid=".$msg_schoolid."'>".$msg_schoolname."</a>: </h4>
       <p id='words_msg_".$msgid."' class='post_words_girl'>".$words."</p>";
 }
	   
 echo "<ul class='post_bottom' onselectstart='return false'>
       <li class='post_time'>".$timesubmit."</li>
       <li id='geili_".$msgid."' class='post_time'>".$likes."</li>
       <a><li class='post_action' id='leave_comment_".$msgid."' onclick='leave_comment_schoolmsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>comment</li></a>";
       
       if ($userid != 0){       
       $geili_result = mysql_query("SELECT * FROM duitasuogeili WHERE userid='$userid' AND msgid='$msgid'") or die();
       $geili_row = mysql_fetch_assoc($geili_result);
       if ($geili_row['geili'] == "1") {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg_schoolmsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg_schoolmsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }       
       }
       else {
       $geili_result = mysql_query("SELECT * FROM duitasuogeili WHERE userid='0' AND ip='$ip' AND msgid='$msgid'") or die();
       $geili_row = mysql_fetch_assoc($geili_result);
       if ($geili_row['geili'] == "1") {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg_schoolmsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg_schoolmsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }
       }
  
 //echo "<a href='javascript:void(0);'><li class='post_action' onclick='feed_link_renren(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>告诉人人好友</li></a>";   
 
 if ($msgowner == 1){
 echo "<a><li class='post_action' id='edit_msg_".$msgid."' onclick='edit_msg_schoolmsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>edit</li></a>
       <a><li class='post_action' id='cancel_msg_".$msgid."' onclick='cancel_msg_my_msgs_schoolmsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>delete</li></a>";
 }
 
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
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment_schoolmsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment_schoolmsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }       
       }
       else {
       $geili_comment_result = mysql_query("SELECT * FROM duitasuocommentgeili WHERE userid='0' AND ip='$ip' AND commentid='$comment_id'") or die();
       $geili_comment_row = mysql_fetch_assoc($geili_comment_result);
       if ($geili_comment_row['geili'] == "1") {
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment_schoolmsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment_schoolmsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }
       }
 
 if ($msgowner == 1 && $commentowner == 0){      
 echo "<a><li class='post_action' onclick='open_pmsg(".$comment_id.",\"".$comment_username."\",".$comment_userid.",\"".$comment_ip."\",".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>send message</li></a>
      <a><li class='post_action' onclick='cancel_comment_schoolmsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>delete</li></a>";
 } else if ($commentowner == 1){
 echo "<a><li class='post_action' onclick='cancel_comment_schoolmsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>delete</li></a>";
 }       
       
 echo "</ul>
       </div>";
 
 }   
       
 echo  "</div>
        </div>";
 
 $mymsgs++; 
	
 } //compare searchwords with words
}

/************** end of the duitasuomsg loop ************/



$result_coursemsg = mysql_query("SELECT * FROM coursemsg ORDER BY timesubmit DESC;") or die();
 while($row_coursemsg = mysql_fetch_assoc($result_coursemsg)){
	 $words = $row_coursemsg['words'];
	 if (compareStrings($searchwords,$words) >= $matchscore){
		$msg_courseid = $row_coursemsg['courseid'];
		$msg_classes = mysql_query("SELECT * FROM classes WHERE id='$msg_courseid' ORDER BY id ASC;") OR die(mysql_query());      
		$row_msg_classes = mysql_fetch_assoc($msg_classes);
		$msg_coursenumber = $row_msg_classes['coursenumber'];
		$msg_university_id = $row_msg_classes['university'];
		
		$msg_universities = mysql_query("SELECT * FROM universities WHERE id='$msg_university_id' ORDER BY id ASC;") OR die(mysql_query());      
		$row_msg_universities = mysql_fetch_assoc($msg_universities);
		$msg_schoolname = $row_msg_universities['schoolname'];
		
		$msgid = $row_coursemsg['id'];
		$role = $row_coursemsg['role'];
		$timeago = $timenow - $row_coursemsg['timesubmit'];
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
   		$timesubmit = date('F j, Y',strtotime($timezonename,$row_coursemsg['timesubmit']))." at ".date('g:ia',strtotime($timezonename,$row_coursemsg['timesubmit']));
   		}
		
 $likes = $row_coursemsg['likes'];
 if ($likes == 0){
 $likes = "0 likes";
 }
 else {
 $likes = $likes." likes";
 }
 
 $msg_userid = $row_coursemsg['userid'];
 if ($msg_userid != 0){ //active user
	$msg_user = mysql_query("SELECT * FROM users WHERE id='$msg_userid' ORDER BY id ASC;") OR die(mysql_query());      
	$row_msg_user = mysql_fetch_assoc($msg_user);
	$msg_username = $row_msg_user['username'];
	$msg_imagelocation = $row_msg_user['imagelocation'];
 } else { //anonymous user
	$msg_username = "Unknown";
	$msg_imagelocation = "photos/users/default_profile.jpg";
 }
 
 if ($userid != 0 && $row_coursemsg['userid'] == $userid){
	$msgowner = 1;
 } else if ($userid == 0 && $row_coursemsg['userid'] == 0 && $row_coursemsg['ip'] == $ip){
	$msgowner = 1;
 } else {
	$msgowner = 0;
 }
 
 if ($msgowner == 1){
 echo "<div class='post_owner'>
 
       <h4 onselectstart='return false'><a href='profile.php?userid=".$msg_userid."'>
	   <div class='course_users_row_picture'><img src=".$msg_imagelocation." width='20' height='20'></div>
	   <div class='course_users_row_username'>".$msg_username."</div>
	   </a> (".$role.") in <a href='course.php?schoolid=".$msg_university_id."&courseid=".$msg_courseid."'>".$msg_coursenumber."</a> from <a href='home.php?schoolid=".$msg_university_id."'>".$msg_schoolname."</a>: </h4>
       
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
	   </a> (".$role.") in <a href='course.php?schoolid=".$msg_university_id."&courseid=".$msg_courseid."'>".$msg_coursenumber."</a> from <a href='home.php?schoolid=".$msg_university_id."'>".$msg_schoolname."</a>: </h4>
       <p id='words_msg_".$msgid."' class='post_words_girl'>".$words."</p>";
 }
	   
 echo "<ul class='post_bottom' onselectstart='return false'>
       <li class='post_time'>".$timesubmit."</li>
       <li id='geili_".$msgid."' class='post_time'>".$likes."</li>
       <a><li class='post_action' id='leave_comment_".$msgid."' onclick='leave_comment_coursemsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>comment</li></a>";
       
       if ($userid != 0){       
       $geili_result = mysql_query("SELECT * FROM coursemsglikes WHERE userid='$userid' AND msgid='$msgid'") or die();
       $geili_row = mysql_fetch_assoc($geili_result);
       if ($geili_row['likes'] == "1") {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg_coursemsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg_coursemsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }       
       }
       else {
       $geili_result = mysql_query("SELECT * FROM coursemsglikes WHERE userid='0' AND ip='$ip' AND msgid='$msgid'") or die();
       $geili_row = mysql_fetch_assoc($geili_result);
       if ($geili_row['likes'] == "1") {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg_coursemsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_msg_".$msgid."' onclick='like_msg_coursemsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }
       }
  
 //echo "<a href='javascript:void(0);'><li class='post_action' onclick='feed_link_renren(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>告诉人人好友</li></a>";   
 
 if ($msgowner == 1){
 echo "<a><li class='post_action' id='edit_msg_".$msgid."' onclick='edit_msg_coursemsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>edit</li></a>
       <a><li class='post_action' id='cancel_msg_".$msgid."' onclick='cancel_msg_my_msgs_coursemsg(".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>delete</li></a>";
 }
 
 echo "</ul>
       
       <div id='leave_comment_input_".$msgid."' onselectstart='return false'>
       </div>
       
       <div id='comments_".$msgid."' class='comments'>";

 $duitasuo_comment = mysql_query("SELECT * FROM coursecomment WHERE coursemsgid='$msgid' ORDER BY timesubmit ASC;") OR die(mysql_query());
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
       $geili_comment_result = mysql_query("SELECT * FROM coursecommentlikes WHERE userid='$userid' AND commentid='$comment_id'") or die();
       $geili_comment_row = mysql_fetch_assoc($geili_comment_result);
       if ($geili_comment_row['likes'] == "1") {
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment_coursemsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment_coursemsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }       
       }
       else {
       $geili_comment_result = mysql_query("SELECT * FROM coursecommentlikes WHERE userid='0' AND ip='$ip' AND commentid='$comment_id'") or die();
       $geili_comment_row = mysql_fetch_assoc($geili_comment_result);
       if ($geili_comment_row['likes'] == "1") {
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment_coursemsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>unlike</li></a>";
       } else {
       echo "<a><li class='post_action' id='like_comment_".$comment_id."' onclick='like_comment_coursemsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>like</li></a>";
       }
       }
 
 if ($msgowner == 1 && $commentowner == 0){      
 echo "<a><li class='post_action' onclick='open_pmsg(".$comment_id.",\"".$comment_username."\",".$comment_userid.",\"".$comment_ip."\",".$msgid.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>send message</li></a>
      <a><li class='post_action' onclick='cancel_comment_coursemsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>delete</li></a>";
 } else if ($commentowner == 1){
 echo "<a><li class='post_action' onclick='cancel_comment_coursemsg(".$comment_id.")' onmouseover=this.className='post_action_onmouseover' onmouseout=this.className='post_action'>delete</li></a>";
 }       
       
 echo "</ul>
       </div>";
 
 }   
       
 echo  "</div>
        </div>";
 
 $mymsgs++; 
 
 }

}

if ($mymsgs == 0){
echo "<div class='post'>
      <h4 onselectstart='return false'>No results found.</h4>
      </div>";
}

?>
 