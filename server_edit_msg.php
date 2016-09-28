<?php
include 'connect_to_mysql.php';
$id = $_GET['id'];

function replace_br($str) 
{	
	$str = str_replace(" ","",$str);
    return $str;
}

function replace_pre_code_tag($str){
	$str = str_replace("<pre class=pre_class><code>","(code-start)",$str);
	$str = str_replace("</code></pre>","(code-end)",$str);
	return $str;
}

$duitasuo_msg = mysql_query("SELECT * FROM duitasuomsg WHERE id='$id'") OR die(mysql_query());
$row = mysql_fetch_assoc($duitasuo_msg);
$words = replace_pre_code_tag($row['words']);

echo $words;

?>