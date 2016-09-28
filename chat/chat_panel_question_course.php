<?php
include '../connect_to_mysql.php'; 
if (isset($_SESSION['id'])){
$userid = $_SESSION['id']; // assign SESSION 'id' value to $userid.
} else {
$userid = 0;
}
$userchatid = $_SESSION['chatid'];
$courseid = $_SESSION['courseid'];

$result_chatname = mysql_query("SELECT * FROM coursechatname WHERE userchatid='$userchatid' AND userid='$userid' AND courseid='$courseid' ORDER BY id ASC;") or die();
$chatname_row = mysql_fetch_array($result_chatname);
$loginas = $chatname_row['loginas'];	

echo $loginas."|".$userid;

?>