<?php 
header('Content-type: text/html; charset=utf-8');
include 'connect_to_mysql.php';  
    
    $code = $_GET['code'];

    if (!$code){
        echo "This activation code does not exist. Back to <a href='index.php'>tuttr.me</a>";
    } 
    else
    {
        $check = mysql_query("SELECT * FROM users WHERE code='$code' AND active='1'");
        if (mysql_num_rows($check)==1){
            echo "Your account was activated already. Go to <a href='index.php'>tuttr.me</a>";
        }
        else
        {
            $activate = mysql_query("UPDATE users SET active='1' WHERE code='$code'");
            header('Location: index.php');
            echo "Your account is activated!";
        }
        
    }
    
?>