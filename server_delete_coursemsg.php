<?php
include 'connect_to_mysql.php';
$id = $_GET['id'];

mysql_query("DELETE FROM coursemsg WHERE id ='$id'") or die(mysql_error());

?>