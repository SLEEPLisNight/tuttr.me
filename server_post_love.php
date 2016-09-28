<?php
header('Content-type: text/html; charset=utf-8');
include 'connect_to_mysql.php';
if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}
$ip = $_SESSION['ip'];
/*
function utf8_urldecode($str) {
			$str = nl2br($str);
	        $str = str_replace("'","\'",$str);
	        $str = str_replace("<","&lt;",$str);
    		$str = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode($str));
    		return html_entity_decode($str,null,'gb2312');;
    		}	
			*/
function utf8_urldecode($str){
	$str = htmlspecialchars($str, ENT_QUOTES);
	$str = mysql_real_escape_string($str);
	return $str;
}	

function find_pre_code_tag($str){
	$str = str_replace("(code-start)","<pre class=pre_class><code>",$str);
	$str = str_replace("(code-end)","</code></pre>",$str);
	return $str;
}
     
	   $timesubmit = time();
	   $schoolid = $_GET['schoolid'];
	   $imagelocation = $_GET['imagelocation'];
	   $role = utf8_urldecode($_GET['role']);
	   $words = find_pre_code_tag(utf8_urldecode($_GET['words']));
	     
		if ($words!="" ){	 	   	
			$sql_insert =  mysql_query("INSERT INTO duitasuomsg (role,words,timesubmit,likes,userid,schoolid,ip,imagelocation) VALUES('$role','$words','$timesubmit','0','$userid','$schoolid','$ip','$imagelocation')") or die(mysql_error());       
        } 
                   
	   $schoollikes = mysql_query("SELECT * FROM universities WHERE id='$schoolid'") OR die(mysql_query());
	   $row = mysql_fetch_assoc($schoollikes);
	   $likes = $row['likes'];
	   $likes++;
	   mysql_query ("UPDATE universities SET likes = '$likes' WHERE id='$schoolid'") or die(mysql_error());

?>