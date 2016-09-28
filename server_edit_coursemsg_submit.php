<?php
include 'connect_to_mysql.php';
$id = $_GET['id'];
$words = $_GET['words'];

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

$words = find_pre_code_tag(utf8_urldecode($words));
		
mysql_query ("UPDATE coursemsg SET words='$words' WHERE id='$id'") or die(mysql_error());

$result = mysql_query("SELECT * FROM coursemsg WHERE id='$id'") or die();
$row =  mysql_fetch_array($result);
$words_edit = $row['words'];
		
echo $words_edit;

?>