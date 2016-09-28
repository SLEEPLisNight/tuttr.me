<?php
header('Content-type: text/html; charset=utf-8');
include 'connect_to_mysql.php';
require 'PHPMailer/PHPMailerAutoload.php';

    function utf8_urldecode($str) 
	{
	$str = htmlspecialchars($str, ENT_QUOTES);
	$str = mysql_real_escape_string($str);
    return $str;
	}	
     
     $signup_username = utf8_urldecode($_GET['signup_username']);
	 $signup_email = utf8_urldecode($_GET['signup_email']);
     //get form data
     $username = addslashes(strip_tags($signup_username));
     $password = addslashes(strip_tags($_GET['signup_password']));
     $email = addslashes(strip_tags($signup_email));
     
    if ((strlen($username)==0||$username == "Username")||(strlen($password)==0||$password=="Password")||(strlen($email)==0||$email=="Email")){
		echo "Please fill in all the fields.";
    } else {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "Invalid email address.";
		} else {
			//check if username already taken
			$check = mysql_query("SELECT * FROM users WHERE email='$email'");
			if (mysql_num_rows($check)>=1){
			   echo "This email address has been used.";
			} else {
				//generate random code
				$code = rand(11111111,99999999);

				$subject = "Welcome to tuttr.me";
				$body = "Hi $signup_username,\n\nThank you for choosing tuttr.me! Please click the link below to activate your account:\n\nhttp://tuttr.me/index_activate.php?code=".$code."\n\nThank you.\n\n\ntuttr.me Inc.";
				$from = "admin@tuttr.me";
				$to = $email;
				$headers = "From: Admin (no reply)" . "\r\n" .
				"CC: jameswang218@gmail.com";

				if (mail($to,$subject,$body,$headers)){
					$ip=$_SERVER['REMOTE_ADDR'];
					$default_imagelocation = "photos/users/default_profile.jpg";
					mysql_query ("INSERT INTO users (username,password,email,code,active,imagelocation,ip,autologin) VALUES ('$username','$password','$email','$code','0','$default_imagelocation','$ip','0')") or die(mysql_error());
					echo "sent";
				} else {
					echo "Fail - ".mail($to,$subject,$body,$headers);
				}
				
				/*
				$mail = new PHPMailer();
				$mail->SMTPDebug = 1;
				
				//Send mail using gmail
				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->SMTPAuth = true; // enable SMTP authentication
				$mail->SMTPSecure = "ssl"; // sets the prefix to the servier
				$mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
				$mail->Port = 465; // set the SMTP port for the GMAIL server
				$mail->Username = "jameswang218@gmail.com"; // GMAIL username
				$mail->Password = "wjywjy2181"; // GMAIL password
				
				//Typical mail data
				$mail->AddAddress($to, $signup_username);
				$mail->SetFrom($from, "Admin");
				$mail->Subject = $subject;
				$mail->Body = $body;
				
				try{
					$mail->Send();
					
				} catch(Exception $e){
					//Something went bad
					echo "Fail - " . $mail->ErrorInfo;
				}
				*/
			}
		}  
	}
   
?>