
function refresh_duitasuo(pn,schoolid)
{

if (pn != "0"){
document.getElementById("nextpage").innerHTML ="<img src='images/loading.gif'>";

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_refresh_duitasuo=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_refresh_duitasuo=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_refresh_duitasuo.onreadystatechange=function()
  {
  if (xmlhttp_refresh_duitasuo.readyState==4 && xmlhttp_refresh_duitasuo.status==200)
    {
    var div=document.getElementById("nextpage"); 
    div.parentNode.removeChild(div); 
    document.getElementById("posts").innerHTML=document.getElementById("posts").innerHTML + xmlhttp_refresh_duitasuo.responseText;
    }
  }
xmlhttp_refresh_duitasuo.open("GET","server_refresh_love.php?pn="+pn+"&schoolid="+schoolid,true);
xmlhttp_refresh_duitasuo.send();
} else {
document.getElementById("refresh_posts").innerHTML="<img src='images/loading.gif'>";

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_refresh_duitasuo=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_refresh_duitasuo=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_refresh_duitasuo.onreadystatechange=function()
  {
  if (xmlhttp_refresh_duitasuo.readyState==4 && xmlhttp_refresh_duitasuo.status==200)
    {
    document.getElementById("refresh_posts").innerHTML="Refresh";
    document.getElementById("posts").innerHTML=xmlhttp_refresh_duitasuo.responseText;
    }
  }
xmlhttp_refresh_duitasuo.open("GET","server_refresh_love.php?pn="+pn+"&schoolid="+schoolid,true);
xmlhttp_refresh_duitasuo.send();
}

}

function $_(id) {
    return document.getElementById(id);
}

function role_switch(role){
	if (role=='1'){
		$_("role").value = "student";
		$_("role1").className = "cb-enable selected";
		$_("role2").className = "cb-disable";
	} else {
		$_("role").value = "tutor";
		$_("role1").className = "cb-enable";
		$_("role2").className = "cb-disable selected";
	}
}

function time_switch(time)
{
$_("time_display").innerHTML = time;
}

function sayit()
{
document.getElementById("post_sayit").innerHTML = "Pending";
}

function leave_comment(id)
{
var leave_comment_input_id = "leave_comment_input_"+id;

document.getElementById(leave_comment_input_id).innerHTML = "<textarea rows='2' type='text' id='leave_comment_textarea_"+id+"' class='leave_comment_textarea'></textarea>"+
"<a id='leave_comment_input_submit_"+id+"' class='leave_comment_input_submit' onclick='leave_comment_input_submit("+id+")'>Post</a>"+
"<a class='cancel_comment_input_submit' onclick='cancel_comment_input_submit("+id+")'>Cancel</a>";
}

function cancel_comment_input_submit(id) {
var leave_comment_input_id = "leave_comment_input_"+id;
document.getElementById(leave_comment_input_id).innerHTML = "";
}

function leave_comment_input_submit(id)
{
var leave_comment_input_submit_id = "leave_comment_input_submit_"+id;
document.getElementById(leave_comment_input_submit_id).innerHTML = "Pending";

var leave_comment_input_id = "leave_comment_input_"+id;
var leave_comment_textarea_id = "leave_comment_textarea_"+id;
var comment = document.getElementById(leave_comment_textarea_id).value;
comment = encodeURIComponent(comment);
if (comment ==""){
alert("Please write something");
} else {
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_leave_comment_input_submit=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_leave_comment_input_submit=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_leave_comment_input_submit.onreadystatechange=function()
  {
  if (xmlhttp_leave_comment_input_submit.readyState==4 && xmlhttp_leave_comment_input_submit.status==200 && xmlhttp_leave_comment_input_submit.responseText != "")
    {
    document.getElementById(leave_comment_input_id).innerHTML = "";
    document.getElementById("comments_"+id).innerHTML = document.getElementById("comments_"+id).innerHTML + xmlhttp_leave_comment_input_submit.responseText;
    }
  }
xmlhttp_leave_comment_input_submit.open("GET","server_leave_comment.php?id="+id+"&comment="+comment,true);
xmlhttp_leave_comment_input_submit.send();
}

}

function edit_msg(id)
{
var content_msg_id = "content_msg_"+id;
var content_msg_edit_id = "content_msg_edit_"+id;

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_edit_msg=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_edit_msg=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_edit_msg.onreadystatechange=function()
  {
  if (xmlhttp_edit_msg.readyState==4 && xmlhttp_edit_msg.status==200)
    {
	document.getElementById(content_msg_id).style.display = "none";
	document.getElementById(content_msg_edit_id).style.display = "";
	document.getElementById(content_msg_edit_id).innerHTML = 
	"<textarea type='text' rows='3' id='words_msg_textarea_"+id+"' class='content_msg_edit_textarea'>"+xmlhttp_edit_msg.responseText+"</textarea>"+
	"<a id='content_msg_edit_submit_"+id+"' class='content_msg_edit_submit' onclick='edit_msg_submit("+id+")'>Confirm</a>"+
	"<a class='cancel_content_msg_edit_submit' onclick='cancel_edit_msg_submit("+id+")'>Cancel</a>";
    }
  }
xmlhttp_edit_msg.open("GET","server_edit_msg.php?id="+id,true);
xmlhttp_edit_msg.send();

}

function edit_msg_submit(id)
{
var content_msg_edit_submit_id = "content_msg_edit_submit_"+id;
document.getElementById(content_msg_edit_submit_id).innerHTML = "Pending";

var content_msg_id = "content_msg_"+id;
var content_msg_edit_id = "content_msg_edit_"+id;
var words_msg_id = "words_msg_"+id;

var words_msg_textarea_id = "words_msg_textarea_"+id;
var words_msg_textarea = document.getElementById(words_msg_textarea_id).value;
var words = encodeURIComponent(words_msg_textarea);

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_edit_msg_submit=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_edit_msg_submit=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_edit_msg_submit.onreadystatechange=function()
  {
  if (xmlhttp_edit_msg_submit.readyState==4 && xmlhttp_edit_msg_submit.status==200)
    {
	var edit_msg_submit = new Array();
	document.getElementById(content_msg_id).innerHTML = "<p id='"+words_msg_id+"' class='post_words_owner'>"+xmlhttp_edit_msg_submit.responseText+"</p>";
    document.getElementById(content_msg_id).style.display = "";
    document.getElementById(content_msg_edit_id).style.display = "none";
    }
  }
xmlhttp_edit_msg_submit.open("GET","server_edit_msg_submit.php?id="+id+"&words="+words,true);
xmlhttp_edit_msg_submit.send();

}

function cancel_edit_msg_submit(id)
{
var content_msg_id = "content_msg_"+id;
var content_msg_edit_id = "content_msg_edit_"+id;

document.getElementById(content_msg_id).style.display = "";
document.getElementById(content_msg_edit_id).style.display = "none";
}

function cancel_msg(id,schoolid) 
{
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Post</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"pmsg_close_question\">Are you sure? A deleted post can not be recovered.</div>"+
    "<div class=\"buttons\">"+
    "<p class=\"pmsg_close_question_yes\" onclick=\"cancel_msg_confirm("+id+","+schoolid+")\">Yes</p>"+
    "<p class=\"pmsg_close_question_no\" onclick=\"close_pmsg()\">No</p>"+
    "</div>";
    "</div>";
    window.scrollTo(0,0);
}

function cancel_msg_confirm(id,schoolid)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_cancel_msg=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_cancel_msg=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_cancel_msg.onreadystatechange=function()
  {
  if (xmlhttp_cancel_msg.readyState==4 && xmlhttp_cancel_msg.status==200)
    {
	document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Post</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"login_successful\">The post is deleted.</div>"+
    "</div>";
    window.scrollTo(0,0);
    setTimeout("close_pmsg()",1800);
	refresh_duitasuo('0',schoolid);
    }
  }
xmlhttp_cancel_msg.open("GET","server_delete_msg.php?id="+id,true);
xmlhttp_cancel_msg.send();
}

function cancel_msg_my_msgs(id)
{
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Post</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"pmsg_close_question\">Are you sure? A deleted post can not be recovered.</div>"+
    "<div class=\"buttons\">"+
    "<p class=\"pmsg_close_question_yes\" onclick=\"cancel_msg_my_msgs_confirm("+id+")\">Yes</p>"+
    "<p class=\"pmsg_close_question_no\" onclick=\"close_pmsg()\">No</p>"+
    "</div>";
    "</div>";
    window.scrollTo(0,0);
}

function cancel_msg_my_msgs_confirm(id)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_cancel_msg_my_msgs=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_cancel_msg_my_msgs=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_cancel_msg_my_msgs.onreadystatechange=function()
  {
  if (xmlhttp_cancel_msg_my_msgs.readyState==4 && xmlhttp_cancel_msg_my_msgs.status==200)
    {
	document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Post</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"login_successful\">The post is deleted.</div>"+
    "</div>";
    window.scrollTo(0,0);
    setTimeout("close_pmsg()",1800);
	see_my_msgs();
    }
  }
xmlhttp_cancel_msg_my_msgs.open("GET","server_delete_msg.php?id="+id,true);
xmlhttp_cancel_msg_my_msgs.send();
}

function cancel_msg_unread_comment(id)
{
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Post</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"pmsg_close_question\">Are you sure? A deleted post can not be recovered.</div>"+
    "<div class=\"buttons\">"+
    "<p class=\"pmsg_close_question_yes\" onclick=\"cancel_msg_unread_comment_confirm("+id+")\">Yes</p>"+
    "<p class=\"pmsg_close_question_no\" onclick=\"close_pmsg()\">No</p>"+
    "</div>";
    "</div>";
    window.scrollTo(0,0);
}

function cancel_msg_unread_comment_confirm(id)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_cancel_msg_unread_comment=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_cancel_msg_unread_comment=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_cancel_msg_unread_comment.onreadystatechange=function()
  {
  if (xmlhttp_cancel_msg_unread_comment.readyState==4 && xmlhttp_cancel_msg_unread_comment.status==200)
    {
	document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Post</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"login_successful\">The post is deleted.</div>"+
    "</div>";
    window.scrollTo(0,0);
    setTimeout("close_pmsg()",1800);
	see_unread_comment();
    }
  }
xmlhttp_cancel_msg_unread_comment.open("GET","server_delete_msg.php?id="+id,true);
xmlhttp_cancel_msg_unread_comment.send();
}

function like_msg(id){
var like_msg_id = "like_msg_"+id;
var geili_id = "geili_"+id;

if (document.getElementById(like_msg_id).innerHTML == "like"){
	document.getElementById(like_msg_id).innerHTML = "unlike";
	var like = 1;
} else {
	document.getElementById(like_msg_id).innerHTML = "like";
	var like = 0;
}

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_like_msg=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_like_msg=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_like_msg.onreadystatechange=function()
  {
  if (xmlhttp_like_msg.readyState==4 && xmlhttp_like_msg.status==200 && xmlhttp_like_msg.responseText != "")
    {
    document.getElementById(geili_id).innerHTML=xmlhttp_like_msg.responseText;
    }
  }
xmlhttp_like_msg.open("GET","server_like_msg.php?id="+id+"&like="+like,true);
xmlhttp_like_msg.send();
}

function like_comment(id)
{
var like_comment_id = "like_comment_"+id;
var geili_id = "geili_comment_"+id;

if (document.getElementById(like_comment_id).innerHTML == "like")
{
document.getElementById(like_comment_id).innerHTML = "unlike";
var like = 1;
}
else 
{
document.getElementById(like_comment_id).innerHTML = "like";
var like = 0;
}

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_like_comment=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_like_comment=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_like_comment.onreadystatechange=function()
  {
  if (xmlhttp_like_comment.readyState==4 && xmlhttp_like_comment.status==200 && xmlhttp_like_comment.responseText != "")
    {
    document.getElementById(geili_id).innerHTML=xmlhttp_like_comment.responseText;
    }
  }
xmlhttp_like_comment.open("GET","server_like_comment.php?id="+id+"&like="+like,true);
xmlhttp_like_comment.send();
}

function online_user_counter()
{

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  var xmlhttp_online_user_counter=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  var xmlhttp_online_user_counter=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_online_user_counter.onreadystatechange=function()
  {
  if (xmlhttp_online_user_counter.readyState==4 && xmlhttp_online_user_counter.status==200)
    {
    
    }
  }
xmlhttp_online_user_counter.open("GET","server_online_count.php", true);
xmlhttp_online_user_counter.send(null);

}

var unread_comment_change = 0;
var unread_comment_duitasuo_title = "";

function unread_comment()
{
if (unread_comment_duitasuo_title == ""){
unread_comment_duitasuo_title = document.title;
}

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  var xmlhttp_unread_comment=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  var xmlhttp_unread_comment=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_unread_comment.onreadystatechange=function()
  {
  if (xmlhttp_unread_comment.readyState==4 && xmlhttp_unread_comment.status==200)
    {
    var unread_comment = xmlhttp_unread_comment.responseText.replace(/\s+/g,'');
    if (unread_comment=="nonewupdate"){
    document.getElementById("new_notification").className = "";
	document.getElementById("new_notification").innerHTML = "";
	unread_comment_change = 0;
	if (unread_pmsg_change == 0){
	document.title = unread_comment_duitasuo_title;
	}
    } else {
    document.getElementById("new_notification").className = "new_notification";
	
	if (unread_comment_change != unread_comment){
	if (unread_comment_change == 0){
	unread_comment_sendemail();
	}
	document.getElementById("new_notification").innerHTML = unread_comment;
	document.title = "tuttr.me ("+unread_comment+")";
	}
	unread_comment_change = unread_comment;
	
	}
    
	}
  }
xmlhttp_unread_comment.open("GET","server_unread_comment.php", true);
xmlhttp_unread_comment.send(null);
}

function unread_comment_sendemail()
{
var update = 0;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_unread_comment_sendemail=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_unread_comment_sendemail=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_unread_comment_sendemail.onreadystatechange=function()
  {
  if (xmlhttp_unread_comment_sendemail.readyState==4 && xmlhttp_unread_comment_sendemail.status==200)
    {
    }
  }
xmlhttp_unread_comment_sendemail.open("GET","server_unread_sendemail.php?update="+update,true);
xmlhttp_unread_comment_sendemail.send();
}

function see_unread_comment()
{
document.getElementById("duitasuo_display").innerHTML = 
"<div id=\"post_status\" onselectstart=\"return false\">"+
"<div id=\"post_status_control\" class=\"post_status_control\">"+
"<a id=\"refresh_posts\" class=\"refresh_posts\" onclick=\"see_unread_comment()\"><img src=\"images/loading.gif\"></a>"+
"</div></div>"+
"<div id=\"posts\" class=\"posts\"></div>";

document.getElementById("new_notification").className = "";
document.getElementById("new_notification").innerHTML = "";

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_see_unread_comment=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_see_unread_comment=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_see_unread_comment.onreadystatechange=function()
  {
  if (xmlhttp_see_unread_comment.readyState==4 && xmlhttp_see_unread_comment.status==200)
    {
    document.getElementById("posts").innerHTML=xmlhttp_see_unread_comment.responseText;
    document.getElementById("refresh_posts").innerHTML="Refresh";
    }
  }
xmlhttp_see_unread_comment.open("GET","server_see_unread_comment.php",true);
xmlhttp_see_unread_comment.send();
}

function refresh_top_posts()
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_refresh_top_posts=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_refresh_top_posts=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_refresh_top_posts.onreadystatechange=function()
  {
  if (xmlhttp_refresh_top_posts.readyState==4 && xmlhttp_refresh_top_posts.status==200)
    {
    document.getElementById("top_posts").innerHTML=xmlhttp_refresh_top_posts.responseText;
    }
  }
xmlhttp_refresh_top_posts.open("GET","server_refresh_top_posts.php",true);
xmlhttp_refresh_top_posts.send();
}

function login_signup()
{
 document.getElementById("login_signup").style.visibility = "visible";
}

function login(schoolid)
{
var renrenuserid = 0;
if (document.autologinform.autologin.checked == true){
var autologin = 1;
} else {
var autologin = 0;
}
var email = document.getElementById("login_email").value;
var password= document.getElementById("login_password").value;

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_login=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_login=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_login.onreadystatechange=function()
  {
  if (xmlhttp_login.readyState==4 && xmlhttp_login.status==200)
    {
    if (xmlhttp_login.responseText=="login"){
		window.location="home.php?schoolid="+schoolid+"";
    } else {
		document.getElementById("login_message").className="login_error";
		document.getElementById("login_message").style.visibility="visible";
		document.getElementById("login_message").innerHTML=xmlhttp_login.responseText;
    }
    }
  }
xmlhttp_login.open("GET","index_login.php?login_email="+email+"&login_password="+password+"&autologin="+autologin+"&renrenuserid="+renrenuserid,true);
xmlhttp_login.send();
}

function finish_signup()
{
document.getElementById("finish_signup_button").innerHTML = "Pending";
var renrenuserid = 0;
var username = document.getElementById("signup_username").value;
username = encodeURIComponent(username);
var email = document.getElementById("signup_email").value;
var password= document.getElementById("signup_password").value;

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_finish_signup=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_finish_signup=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_finish_signup.onreadystatechange=function()
  {
  if (xmlhttp_finish_signup.readyState==4 && xmlhttp_finish_signup.status==200)
    {
    if (xmlhttp_finish_signup.responseText=="sent"){
	document.getElementById("finish_signup_button").innerHTML = "Done";
    document.getElementById("signup_message").className="signup_successful";
    document.getElementById("signup_message").style.visibility="visible";
    document.getElementById("signup_message").innerHTML="Please check your email to activate your account.";
    }
    else {
	document.getElementById("finish_signup_button").innerHTML = "Done";
    document.getElementById("signup_message").className="signup_error";
    document.getElementById("signup_message").style.visibility="visible";
    document.getElementById("signup_message").innerHTML=xmlhttp_finish_signup.responseText;
    }
    }
  }
xmlhttp_finish_signup.open("GET","index_signup.php?signup_email="+email+"&signup_password="+password+"&signup_username="+username+"&renrenuserid="+renrenuserid,true);
xmlhttp_finish_signup.send();
}

function close_signup()
{
 document.getElementById("login_signup").style.visibility = "hidden";
 document.getElementById("signup_message").style.visibility = "hidden";
 document.getElementById("login_message").style.visibility = "hidden";
}

function show_comment_bottom(id,classnum) 
{
var comment_bottom_id = "comment_bottom_"+id;
if (classnum == 1){
document.getElementById(comment_bottom_id).className = 'comment_bottom_owner';
} else if (classnum == 2){
document.getElementById(comment_bottom_id).className = 'comment_bottom_id';
} else {
document.getElementById(comment_bottom_id).className = 'comment_bottom_noid';
} 

}

function unshow_comment_bottom(id) 
{
var comment_bottom_id = "comment_bottom_"+id;
document.getElementById(comment_bottom_id).className = 'comment_bottom_none';
}

function open_pmsg(cmtid,cmtusername,cmtuserid,cmtuserip,msgid)
{
var cmtusername = encodeURIComponent(cmtusername);
cmtusername = decodeURIComponent(cmtusername);

document.getElementById("private_msg_field").innerHTML =
"<table class=\"private_msg_field_set\">"+
"<tr><td class=\"private_msg_field_set\">To :</td><td><div style=\"width: 455px;\" id=\"pmsg_to_username\">"+cmtusername+"</div></td></tr>"+
"<tr><td class=\"private_msg_field_set\">Subject : </td><td><input type=\"text\" style=\"width: 455px;\" id=\"pmsg_title\"></td></tr>"+
"<tr><td class=\"private_msg_field_set\">Content : </td><td><textarea rows=\"4\" type=\"text\" style=\"width: 455px;\" id=\"pmsg_content\"></textarea></td></tr>"+
"</table>"+
"<div id=\"pmsg_cmtid\">"+cmtid+"</div>"+
"<div id=\"pmsg_msgid\">"+msgid+"</div>"+
"<div id=\"pmsg_to_userid\">"+cmtuserid+"</div>"+
"<div id=\"pmsg_to_userip\">"+cmtuserip+"</div>"+
"<div class=\"buttons\">"+
"<p id=\"finish_send_pmsg_button\" class=\"finish_send_pmsg_button\" onclick=\"send_pmsg()\">Send</p>"+
"<p class=\"cancel_send_pmsg_button\" onclick=\"close_pmsg()\">Cancel</p>"+
"</div>";

document.getElementById("private_msg").style.visibility = "visible";

window.scrollTo(0,0);
}

function close_pmsg() 
{
document.getElementById("private_msg").style.visibility = "hidden";
}

function send_pmsg()
{
var cmtid = document.getElementById("pmsg_cmtid").innerHTML;
var msgid = document.getElementById("pmsg_msgid").innerHTML;
var touserid = document.getElementById("pmsg_to_userid").innerHTML;
var touserip = document.getElementById("pmsg_to_userip").innerHTML;
var title= document.getElementById("pmsg_title").value;
var content= document.getElementById("pmsg_content").value;
title = encodeURIComponent(title);
content = encodeURIComponent(content);

if (title ==""){
alert("Please write a subject");
} else if (content ==""){
alert("Please write a content");
} else {
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_send_pmsg=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_send_pmsg=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_send_pmsg.onreadystatechange=function()
  {
  if (xmlhttp_send_pmsg.readyState==4 && xmlhttp_send_pmsg.status==200)
    {
    document.getElementById("private_msg_field").innerHTML = "<div id=\"login_successful\">Your message has been sent.</div>";
    setTimeout("close_pmsg()",1800);
    }
  }
xmlhttp_send_pmsg.open("GET","server_send_pmsg.php?cmtid="+cmtid+"&msgid="+msgid+"&touserid="+touserid+"&touserip="+touserip+"&title="+title+"&content="+content,true);
xmlhttp_send_pmsg.send();
}

}

var unread_pmsg_change = 0;
var unread_pmsg_duitasuo_title = "";

function unread_pmsg()
{
if (unread_pmsg_duitasuo_title == ""){
unread_pmsg_duitasuo_title = document.title;
}

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  var xmlhttp_unread_pmsg=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  var xmlhttp_unread_pmsg=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_unread_pmsg.onreadystatechange=function()
  {
  if (xmlhttp_unread_pmsg.readyState==4 && xmlhttp_unread_pmsg.status==200)
    {
    var unread_pmsg = xmlhttp_unread_pmsg.responseText.replace(/\s+/g,'');
    if (unread_pmsg=="nonewupdate"){
    document.getElementById("new_message").className = "";
    document.getElementById("new_message").innerHTML = "";
	unread_pmsg_change = 0;
	if (unread_comment_change == 0){
	document.title = unread_pmsg_duitasuo_title;
	} 
    } else {
    document.getElementById("new_message").className = "new_message";
    
	if (unread_pmsg_change != unread_pmsg){
	if (unread_pmsg_change == 0){
	unread_pmsg_sendemail();
	}
	document.getElementById("new_message").innerHTML = unread_pmsg;
    document.title = "tuttr.me ("+unread_pmsg+")";
	}
	unread_pmsg_change = unread_pmsg;
	
	}
	
    }
  }
xmlhttp_unread_pmsg.open("GET","server_unread_pmsg.php", true);
xmlhttp_unread_pmsg.send(null);
}

function unread_pmsg_sendemail()
{
var update = 1;
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_unread_pmsg_sendemail=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_unread_pmsg_sendemail=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_unread_pmsg_sendemail.onreadystatechange=function()
  {
  if (xmlhttp_unread_pmsg_sendemail.readyState==4 && xmlhttp_unread_pmsg_sendemail.status==200)
    {
    }
  }
xmlhttp_unread_pmsg_sendemail.open("GET","server_unread_sendemail.php?update="+update,true);
xmlhttp_unread_pmsg_sendemail.send();
}

function see_pmsg()
{
var pn = 1;

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_see_pmsg=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_see_pmsg=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_see_pmsg.onreadystatechange=function()
  {
  if (xmlhttp_see_pmsg.readyState==4 && xmlhttp_see_pmsg.status==200)
    {
    document.getElementById("duitasuo_display").innerHTML = xmlhttp_see_pmsg.responseText;
    }
  }
xmlhttp_see_pmsg.open("GET","server_see_pmsg.php?pn="+pn,true);
xmlhttp_see_pmsg.send();
}

function see_pmsg_content(pmsgid,updateread)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_see_pmsg_content=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_see_pmsg_content=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_see_pmsg_content.onreadystatechange=function()
  {
  if (xmlhttp_see_pmsg_content.readyState==4 && xmlhttp_see_pmsg_content.status==200)
    {
    document.getElementById("duitasuo_display").innerHTML = xmlhttp_see_pmsg_content.responseText;
    }
  }
xmlhttp_see_pmsg_content.open("GET","server_see_pmsg_content.php?pmsgid="+pmsgid+"&updateread="+updateread,true);
xmlhttp_see_pmsg_content.send();
}

function reply_pmsg(pmsgid,otheruserid,otheruserip)
{
var content= document.getElementById("reply_pmsg_text").value;
content = encodeURIComponent(content);
document.getElementById("reply_pmsg_button").innerHTML = "Pending";

if (content ==""){
alert("Please write something.");
} else {
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_reply_pmsg=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_reply_pmsg=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_reply_pmsg.onreadystatechange=function()
  {
  if (xmlhttp_reply_pmsg.readyState==4 && xmlhttp_reply_pmsg.status==200)
    {
	document.getElementById("reply_pmsg_button").innerHTML = "Send";
    document.getElementById("reply_pmsg_text").value = "";
    document.getElementById("pmsg_replys").innerHTML = document.getElementById("pmsg_replys").innerHTML + xmlhttp_reply_pmsg.responseText;
    }
  }
xmlhttp_reply_pmsg.open("GET","server_pmsg_reply.php?pmsgid="+pmsgid+"&otheruserid="+otheruserid+"&otheruserip="+otheruserip+"&content="+content,true);
xmlhttp_reply_pmsg.send();
}

}

function cancel_comment(cmtid)
{
var commentid = "comment_"+cmtid;
var removediv = document.getElementById(commentid);

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_cancel_comment=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_cancel_comment=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_cancel_comment.onreadystatechange=function()
  {
  if (xmlhttp_cancel_comment.readyState==4 && xmlhttp_cancel_comment.status==200)
    {
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Comment</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"login_successful\">Comment is deleted.</div>"+
    "</div>";
    setTimeout("close_pmsg()",1800);
    removediv.parentNode.removeChild(removediv);
    }
  }
xmlhttp_cancel_comment.open("GET","server_cancel_comment.php?cmtid="+cmtid,true);
xmlhttp_cancel_comment.send();
}

function set_pmsg_close_question(pmsgid)
{
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>End Conversation</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"pmsg_close_question\">Are you sure? Ended conversation can not be recovered.</div>"+
    "<div class=\"buttons\">"+
    "<p class=\"pmsg_close_question_yes\" onclick=\"set_pmsg_close("+pmsgid+")\">Yes</p>"+
    "<p class=\"pmsg_close_question_no\" onclick=\"close_pmsg()\">No</p>"+
    "</div>";
    "</div>";
    window.scrollTo(0,0);
}

function set_pmsg_close(pmsgid)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_set_pmsg_close=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_set_pmsg_close=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_set_pmsg_close.onreadystatechange=function()
  {
  if (xmlhttp_set_pmsg_close.readyState==4 && xmlhttp_set_pmsg_close.status==200)
    {
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>End Conversation</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"login_successful\">This conversation is ended.</div>"+
    "</div>";
    window.scrollTo(0,0);
    setTimeout("close_pmsg()",1800);
    see_pmsg();
    }
  }
xmlhttp_set_pmsg_close.open("GET","server_set_pmsg_close.php?pmsgid="+pmsgid,true);
xmlhttp_set_pmsg_close.send();
}

function set_pmsg_unread(pmsgid)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_set_pmsg_unread=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_set_pmsg_unread=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_set_pmsg_unread.onreadystatechange=function()
  {
  if (xmlhttp_set_pmsg_unread.readyState==4 && xmlhttp_set_pmsg_unread.status==200)
    {
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Mark Unread</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"login_successful\">This conversation is marked as unread.</div>"+
    "</div>";
    window.scrollTo(0,0);
    setTimeout("close_pmsg()",1800);
    see_pmsg();
    }
  }
xmlhttp_set_pmsg_unread.open("GET","server_set_pmsg_unread.php?pmsgid="+pmsgid,true);
xmlhttp_set_pmsg_unread.send();
}

function see_my_msgs()
{
document.getElementById("duitasuo_display").innerHTML = 
"<div id=\"post_status\" onselectstart=\"return false\">"+
"<div id=\"post_status_control\" class=\"post_status_control\">"+
"<a id=\"refresh_posts\" class=\"refresh_posts\" onclick=\"see_my_msgs()\"><img src=\"images/loading.gif\"></a>"+
"</div></div>"+
"<div id=\"posts\" class=\"posts\"></div>";

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_see_my_msgs=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_see_my_msgs=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_see_my_msgs.onreadystatechange=function()
  {
  if (xmlhttp_see_my_msgs.readyState==4 && xmlhttp_see_my_msgs.status==200)
    {
    document.getElementById("posts").innerHTML=xmlhttp_see_my_msgs.responseText;
    document.getElementById("refresh_posts").innerHTML="Refresh";
    }
  }
xmlhttp_see_my_msgs.open("GET","server_see_my_msgs.php",true);
xmlhttp_see_my_msgs.send();
}

function post_msg(schoolid){
	var role = document.getElementById("role").value;
	var words = document.getElementById("words").value;
	role = encodeURIComponent(role);
	words = encodeURIComponent(words);
			
	if (document.getElementById("words").value =="" || document.getElementById("words").value == "and I have a question..."){
		alert("Please write something to post");
	} else {
		if (document.getElementById("image_preview").style.display != "none"){
			document.getElementById("uploadimage_schoolid").value = schoolid;
			document.getElementById('upload_photo').click();
		} else {
			document.getElementById("post_button").innerHTML = "Posting";
			
			var post_picture_result = "";
			
			if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp_post_msg=new XMLHttpRequest();
			} else {// code for IE6, IE5
				xmlhttp_post_msg=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp_post_msg.onreadystatechange=function()
			{
				if (xmlhttp_post_msg.readyState==4 && xmlhttp_post_msg.status==200)
				{
					window.location="home.php?schoolid="+schoolid+"";
				}
			}
			xmlhttp_post_msg.open("GET","server_post_love.php?role="+role+"&words="+words+"&imagelocation="+post_picture_result+"&schoolid="+schoolid,true);
			xmlhttp_post_msg.send();
		}
	}
}

function see_pmsg_page(pn)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_see_pmsg_page=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_see_pmsg_page=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_see_pmsg_page.onreadystatechange=function()
  {
  if (xmlhttp_see_pmsg_page.readyState==4 && xmlhttp_see_pmsg_page.status==200)
    {
    document.getElementById("duitasuo_display").innerHTML = xmlhttp_see_pmsg_page.responseText;
    }
  }
xmlhttp_see_pmsg_page.open("GET","server_see_pmsg.php?pn="+pn,true);
xmlhttp_see_pmsg_page.send();
}

var user_points = "";
function open_send_gift()
{
user_points = document.getElementById("user_points").innerHTML;

document.getElementById("send_gift_field").innerHTML = 
"<table class=\"send_gift_field_set\">"+
"<tr><td class=\"send_gift_field_set\">发送时间：</td><td><input type=\"text\" style=\"width: 250px;\" id=\"send_gift_time\" />（例子：2011/5/26 下午3点10分）</td></tr>"+
"<tr><td class=\"send_gift_field_set\">发送地址：</td><td><textarea rows=\"2\" type=\"text\" style=\"width: 453px;\" id=\"send_gift_location\"></textarea></td></tr>"+
"<tr><td class=\"send_gift_field_set\">要说的话：</td><td><textarea rows=\"4\" type=\"text\" style=\"width: 453px;\" id=\"send_gift_words\"></textarea></td></tr>"+
"<tr><td class=\"send_gift_field_set\">选择礼物：</td><td><div id=\"send_gift_choice\" style=\"width: 453px;\">"+
"<form name=\"select_gift\">"+
"<table width=\"460\" class=\"select_gift_table\">"+
"<tr>"+
"<td><img src=\"images/rose.png\" class=\"gift_pic\" onclick=\"show_gift_pic(0)\" /></td>"+
"<td width=\"90\">1.暗恋表白：</td>"+
"<td><input type=\"checkbox\" name=\"gift\" onclick=\"select_gift_checkbox(this)\" />玫瑰一朵，巧克力一块。 (说币：4500)</td>"+
"</tr>"+
"<tr>"+
"<td><img src=\"images/pen.gif\" class=\"gift_pic\" onclick=\"show_gift_pic(1)\" /></td>"+
"<td>2.学生礼物：</td>"+
"<td><input type=\"checkbox\" name=\"gift\" onclick=\"select_gift_checkbox(this)\" />彩色荧光笔一支，牛奶饮料一瓶，圆珠笔一支。 (说币：5500)</td>"+
"</tr>"+
"<tr>"+
"<td><img src=\"images/noodles.png\" class=\"gift_pic\" onclick=\"show_gift_pic(2)\" /></td>"+
"<td>3.考试礼物：</td>"+
"<td><input type=\"checkbox\" name=\"gift\" onclick=\"select_gift_checkbox(this)\" />方便面一盒，彩色荧光笔一支，铅笔3只，橡皮擦一只，燕麦棒一支。 (说币：9000)</td>"+
"</tr>"+
"<tr>"+
"<td><img src=\"images/clock.png\" class=\"gift_pic\" onclick=\"show_gift_pic(3)\" /></td>"+
"<td>4.新学期礼物：</td>"+
"<td><input type=\"checkbox\" name=\"gift\" onclick=\"select_gift_checkbox(this)\" />方便面一盒，巧克力一块，闹钟一个，饼干(小包)。 (说币：10000)</td>"+
"</tr>"+
"<tr>"+
"<td><img src=\"images/chocolate.png\" class=\"gift_pic\" onclick=\"show_gift_pic(4)\" /></td>"+
"<td>5.单独礼物：</td>"+
"<td><input type=\"checkbox\" name=\"gift\" onclick=\"select_gift_checkbox(this)\" />玫瑰一朵 (说币：1000)  <br><input type=\"checkbox\" name=\"gift\" onclick=\"select_gift_checkbox(this)\" />巧克力一块 (说币：2500)</td>"+
"</tr>"+
"</table>"+
"<table width=\"470\" class=\"select_gift_table\">"+
"<tr>"+
"<td width=\"80\">已选的礼物：</td>"+
"<td class=\"selected_gifts_name\"><div id=\"selected_gifts\"></div></td>"+
"</tr>"+
"</table>"+
"</form>"+
"</div></td></tr>"+
"</table>"+
"<div class=\"buttons\">"+
"<p id=\"send_gift_button\" class=\"finish_send_pmsg_button\" onclick=\"send_gift()\">发送</p>"+
"<p class=\"cancel_send_pmsg_button\" onclick=\"close_send_gift()\">取消</p>"+
"</div>";

document.getElementById("send_gift").style.visibility = "visible";
window.scrollTo(0,0);
}

function close_send_gift()
{
document.getElementById("send_gift_pic_field").innerHTML = "";
document.getElementById("send_gift_pic_name").innerHTML = "礼物照片";
document.getElementById("send_gift").style.visibility = "hidden";
document.getElementById("user_points").innerHTML = user_points;
}

function select_gift_checkbox(checkbox)
{
var num = "";
for (var i=0; i < document.select_gift.gift.length; i++)
    {
    if (document.select_gift.gift[i].checked)
        {
		num = num + i +",";
		}
	}

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_select_gift_checkbox=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_select_gift_checkbox=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_select_gift_checkbox.onreadystatechange=function()
  {
  if (xmlhttp_select_gift_checkbox.readyState==4 && xmlhttp_select_gift_checkbox.status==200)
    {	
	var select_gift_result = new Array();
	select_gift_result = xmlhttp_select_gift_checkbox.responseText.split("|");
	if (select_gift_result[0] == "nopointsleft"){
	alert("对不起，您的说币余额不足！");
	checkbox.checked = false;
	} else {
	document.getElementById("user_points").innerHTML = select_gift_result[0];
	document.getElementById("selected_gifts").innerHTML = select_gift_result[1];
	}
	
	}
  }
xmlhttp_select_gift_checkbox.open("GET","server_select_gift.php?num="+num,true);
xmlhttp_select_gift_checkbox.send();	
	 
}

function send_gift()
{
var time = encodeURIComponent(document.getElementById("send_gift_time").value);
var location = encodeURIComponent(document.getElementById("send_gift_location").value);
var words = encodeURIComponent(document.getElementById("send_gift_words").value);

if (time.replace(/\s+/g,' ') == "" || location.replace(/\s+/g,' ') == "" || words.replace(/\s+/g,' ') == ""){
alert("请正确填入所有的信息。");
} else if (document.getElementById("selected_gifts").innerHTML == ""){
alert("请您选择至少一件礼物。");
} else {
document.getElementById("send_gift_button").innerHTML = "发送中..";

var num = "";
for (var i=0; i < document.select_gift.gift.length; i++)
    {
    if (document.select_gift.gift[i].checked)
        {
		num = num + i +",";
		}
	}

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_send_gift=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_send_gift=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_send_gift.onreadystatechange=function()
  {
  if (xmlhttp_send_gift.readyState==4 && xmlhttp_send_gift.status==200)
    {
	document.getElementById("send_gift_button").innerHTML = "发送";
	document.getElementById("send_gift").style.visibility = "hidden";
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>送礼物</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"login_successful\">已经成功申请发送礼物，请耐心等待我们的回复。</div>"+
    "</div>";
    window.scrollTo(0,0);
    setTimeout("close_pmsg()",1800);
	}
  }
xmlhttp_send_gift.open("GET","server_send_gift.php?time="+time+"&location="+location+"&words="+words+"&num="+num,true);
xmlhttp_send_gift.send();	
}

}

function show_gift_pic(num)
{
if (num == 0){
var gift_pic_name = "暗恋表白";
} else if (num == 1){
var gift_pic_name = "学生礼物";
} else if (num == 2){
var gift_pic_name = "考试礼物";
} else if (num == 3){
var gift_pic_name = "新学期礼物";
} else if (num == 4){
var gift_pic_name = "单独礼物";
}
document.getElementById("send_gift_pic_name").innerHTML = "礼物照片 - "+gift_pic_name;
var pic_name = "giftpic_"+num+".jpg";
document.getElementById("send_gift_pic_field").innerHTML = "<img src=\"images/"+pic_name+"\" height=\"235\" width=\"235\" />";
}

function cancel_show_gift_pic()
{
document.getElementById("send_gift_pic_name").innerHTML = "礼物照片";
document.getElementById("send_gift_pic_field").innerHTML = "";
}

function course_search(university){
var coursename = escape(document.getElementById("course_search_input").value);

if (coursename.length==0)
  { 
  document.getElementById("course_search_results").style.visibility = "hidden";
  document.getElementById("course_search_results").innerHTML = "";
  return;
  } 
else 
  {
  document.getElementById("course_search_results").style.visibility = "visible";
  //document.getElementById("course_search_results").innerHTML = "<div class=\"choose_school_more\">搜索学校中...</div>";
  }
  
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_campus_search=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_campus_search=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_campus_search.onreadystatechange=function()
  {
  if (xmlhttp_campus_search.readyState==4 && xmlhttp_campus_search.status==200)
    {
    document.getElementById("course_search_results").style.visibility = "visible";
    document.getElementById("course_search_results").innerHTML = xmlhttp_campus_search.responseText;
    }
  }
xmlhttp_campus_search.open("GET","server_course_search_home.php?name="+coursename+"&university="+university,true);
xmlhttp_campus_search.send();

}

function update_usercourses(courseid, schoolid){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp_campus_search=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp_campus_search=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp_campus_search.onreadystatechange=function()
	  {
	  if (xmlhttp_campus_search.readyState==4 && xmlhttp_campus_search.status==200)
		{
		window.location="home.php?schoolid="+schoolid+"";
		}
	  }
	xmlhttp_campus_search.open("GET","server_update_usercourses.php?courseid="+courseid,true);
	xmlhttp_campus_search.send();
}

function post_search(e){
	var evtobj=window.event? event : e; //distinguish between IE's explicit event object (window.event) and Firefox's implicit.
	var key=evtobj.charCode? evtobj.charCode : evtobj.keyCode;

	if(key == '13'){
		var post = escape(document.getElementById("post_search_input").value);

		if (post.length==0)
		  { 
		  return;
		  } 
		else 
		  {
			/*
			document.getElementById("duitasuo_display").innerHTML = 
			"<div id=\"post_status\" onselectstart=\"return false\">"+
			"<div id=\"post_status_control\" class=\"post_status_control\">"+
			"<a id=\"refresh_posts\" class=\"refresh_posts\" onclick=\"see_my_msgs()\"><img src=\"images/loading.gif\"></a>"+
			"</div></div>"+
			"<div id=\"posts\" class=\"posts\"></div>";
			*/
		  }
		  
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp_post_search=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp_post_search=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp_post_search.onreadystatechange=function()
		  {
		  if (xmlhttp_post_search.readyState==4 && xmlhttp_post_search.status==200)
			{
			document.getElementById("posts").innerHTML=xmlhttp_post_search.responseText;
			}
		  }
		xmlhttp_post_search.open("GET","server_post_search.php?searchwords="+post,true);
		xmlhttp_post_search.send();
	}
}

function goto_school(id)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_goto_school=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_goto_school=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_goto_school.onreadystatechange=function()
  {
  if (xmlhttp_goto_school.readyState==4 && xmlhttp_goto_school.status==200)
    {
    }
  }
xmlhttp_goto_school.open("GET","server_goto_school.php?id="+id,true);
xmlhttp_goto_school.send();
}

function file_selected(file_field){
	var file = file_field.files[0];
	var img = document.createElement("img");
	
	console.log("File name: " + file.fileName);
    console.log("File size: " + file.fileSize);
	
	/*
	img.src = file;
	img.width = 50;
	img.height = 50;
	document.getElementById("words").appendChild(img);
	*/
}

///////////////////////////// post search duplicated functions ///////////////////////////////////////////////////////////

function leave_comment_schoolmsg(id){
var leave_comment_input_id = "leave_comment_input_"+id;

document.getElementById(leave_comment_input_id).innerHTML = "<textarea rows='2' type='text' id='leave_comment_textarea_"+id+"' class='leave_comment_textarea'></textarea>"+
"<a id='leave_comment_input_submit_"+id+"' class='leave_comment_input_submit' onclick='leave_comment_input_submit_schoolmsg("+id+")'>Post</a>"+
"<a class='cancel_comment_input_submit' onclick='cancel_comment_input_submit_schoolmsg("+id+")'>Cancel</a>";
}

function cancel_comment_input_submit_schoolmsg(id) {
var leave_comment_input_id = "leave_comment_input_"+id;
document.getElementById(leave_comment_input_id).innerHTML = "";
}

function leave_comment_input_submit_schoolmsg(id)
{
var leave_comment_input_submit_id = "leave_comment_input_submit_"+id;
document.getElementById(leave_comment_input_submit_id).innerHTML = "Pending";

var leave_comment_input_id = "leave_comment_input_"+id;
var leave_comment_textarea_id = "leave_comment_textarea_"+id;
var comment = document.getElementById(leave_comment_textarea_id).value;
comment = encodeURIComponent(comment);
if (comment ==""){
alert("Please write something");
} else {
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_leave_comment_input_submit=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_leave_comment_input_submit=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_leave_comment_input_submit.onreadystatechange=function()
  {
  if (xmlhttp_leave_comment_input_submit.readyState==4 && xmlhttp_leave_comment_input_submit.status==200 && xmlhttp_leave_comment_input_submit.responseText != "")
    {
    document.getElementById(leave_comment_input_id).innerHTML = "";
    document.getElementById("comments_"+id).innerHTML = document.getElementById("comments_"+id).innerHTML + xmlhttp_leave_comment_input_submit.responseText;
    }
  }
xmlhttp_leave_comment_input_submit.open("GET","server_leave_comment.php?id="+id+"&comment="+comment,true);
xmlhttp_leave_comment_input_submit.send();
}

}

function leave_comment_coursemsg(id){
var leave_comment_input_id = "leave_comment_input_"+id;

document.getElementById(leave_comment_input_id).innerHTML = "<textarea rows='2' type='text' id='leave_comment_textarea_"+id+"' class='leave_comment_textarea'></textarea>"+
"<a id='leave_comment_input_submit_"+id+"' class='leave_comment_input_submit' onclick='leave_comment_input_submit_coursemsg("+id+")'>Post</a>"+
"<a class='cancel_comment_input_submit' onclick='cancel_comment_input_submit_coursemsg("+id+")'>Cancel</a>";
}

function cancel_comment_input_submit_coursemsg(id) {
var leave_comment_input_id = "leave_comment_input_"+id;
document.getElementById(leave_comment_input_id).innerHTML = "";
}

function leave_comment_input_submit_coursemsg(id)
{
var leave_comment_input_submit_id = "leave_comment_input_submit_"+id;
document.getElementById(leave_comment_input_submit_id).innerHTML = "Pending";

var leave_comment_input_id = "leave_comment_input_"+id;
var leave_comment_textarea_id = "leave_comment_textarea_"+id;
var comment = document.getElementById(leave_comment_textarea_id).value;
comment = encodeURIComponent(comment);
if (comment ==""){
alert("Please write something");
} else {
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_leave_comment_input_submit=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_leave_comment_input_submit=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_leave_comment_input_submit.onreadystatechange=function()
  {
  if (xmlhttp_leave_comment_input_submit.readyState==4 && xmlhttp_leave_comment_input_submit.status==200 && xmlhttp_leave_comment_input_submit.responseText != "")
    {
    document.getElementById(leave_comment_input_id).innerHTML = "";
    document.getElementById("comments_"+id).innerHTML = document.getElementById("comments_"+id).innerHTML + xmlhttp_leave_comment_input_submit.responseText;
    }
  }
xmlhttp_leave_comment_input_submit.open("GET","server_leave_coursecomment.php?id="+id+"&comment="+comment,true);
xmlhttp_leave_comment_input_submit.send();
}

}

function like_msg_schoolmsg(id){
var like_msg_id = "like_msg_"+id;
var geili_id = "geili_"+id;

if (document.getElementById(like_msg_id).innerHTML == "like"){
	document.getElementById(like_msg_id).innerHTML = "unlike";
	var like = 1;
} else {
	document.getElementById(like_msg_id).innerHTML = "like";
	var like = 0;
}

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_like_msg=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_like_msg=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_like_msg.onreadystatechange=function()
  {
  if (xmlhttp_like_msg.readyState==4 && xmlhttp_like_msg.status==200 && xmlhttp_like_msg.responseText != "")
    {
    document.getElementById(geili_id).innerHTML=xmlhttp_like_msg.responseText;
    }
  }
xmlhttp_like_msg.open("GET","server_like_msg.php?id="+id+"&like="+like,true);
xmlhttp_like_msg.send();
}

function like_msg_coursemsg(id){
var like_msg_id = "like_msg_"+id;
var geili_id = "geili_"+id;

if (document.getElementById(like_msg_id).innerHTML == "like"){
	document.getElementById(like_msg_id).innerHTML = "unlike";
	var like = 1;
} else {
	document.getElementById(like_msg_id).innerHTML = "like";
	var like = 0;
}

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_like_msg=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_like_msg=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_like_msg.onreadystatechange=function()
  {
  if (xmlhttp_like_msg.readyState==4 && xmlhttp_like_msg.status==200 && xmlhttp_like_msg.responseText != "")
    {
    document.getElementById(geili_id).innerHTML=xmlhttp_like_msg.responseText;
    }
  }
xmlhttp_like_msg.open("GET","server_like_coursemsg.php?id="+id+"&like="+like,true);
xmlhttp_like_msg.send();
}

function edit_msg_schoolmsg(id)
{
var content_msg_id = "content_msg_"+id;
var content_msg_edit_id = "content_msg_edit_"+id;
var words_msg_id = "words_msg_"+id;
var words_msg = document.getElementById(words_msg_id).innerHTML;

	document.getElementById(content_msg_id).style.display = "none";
	document.getElementById(content_msg_edit_id).style.display = "";
	document.getElementById(content_msg_edit_id).innerHTML = 
	"<textarea type='text' rows='3' id='words_msg_textarea_"+id+"' class='content_msg_edit_textarea'>"+words_msg+"</textarea>"+
	"<a id='content_msg_edit_submit_"+id+"' class='content_msg_edit_submit' onclick='edit_msg_submit_schoolmsg("+id+")'>Confirm</a>"+
	"<a class='cancel_content_msg_edit_submit' onclick='cancel_edit_msg_submit_schoolmsg("+id+")'>Cancel</a>";

}

function edit_msg_submit_schoolmsg(id)
{
var content_msg_edit_submit_id = "content_msg_edit_submit_"+id;
document.getElementById(content_msg_edit_submit_id).innerHTML = "Pending";

var content_msg_id = "content_msg_"+id;
var content_msg_edit_id = "content_msg_edit_"+id;
var words_msg_id = "words_msg_"+id;

var words_msg_textarea_id = "words_msg_textarea_"+id;
var words_msg_textarea = document.getElementById(words_msg_textarea_id).value;
var words = encodeURIComponent(words_msg_textarea);

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_edit_msg_submit=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_edit_msg_submit=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_edit_msg_submit.onreadystatechange=function()
  {
  if (xmlhttp_edit_msg_submit.readyState==4 && xmlhttp_edit_msg_submit.status==200)
    {
	var edit_msg_submit = new Array();
    document.getElementById(words_msg_id).innerHTML = xmlhttp_edit_msg_submit.responseText;
    document.getElementById(content_msg_id).style.display = "";
    document.getElementById(content_msg_edit_id).style.display = "none";
    }
  }
xmlhttp_edit_msg_submit.open("GET","server_edit_msg_submit.php?id="+id+"&words="+words,true);
xmlhttp_edit_msg_submit.send();

}

function cancel_edit_msg_submit_schoolmsg(id)
{
var content_msg_id = "content_msg_"+id;
var content_msg_edit_id = "content_msg_edit_"+id;

document.getElementById(content_msg_id).style.display = "";
document.getElementById(content_msg_edit_id).style.display = "none";
}

function edit_msg_coursemsg(id)
{
var content_msg_id = "content_msg_"+id;
var content_msg_edit_id = "content_msg_edit_"+id;
var words_msg_id = "words_msg_"+id;
var words_msg = document.getElementById(words_msg_id).innerHTML;

	document.getElementById(content_msg_id).style.display = "none";
	document.getElementById(content_msg_edit_id).style.display = "";
	document.getElementById(content_msg_edit_id).innerHTML = 
	"<textarea type='text' rows='3' id='words_msg_textarea_"+id+"' class='content_msg_edit_textarea'>"+words_msg+"</textarea>"+
	"<a id='content_msg_edit_submit_"+id+"' class='content_msg_edit_submit' onclick='edit_msg_submit_coursemsg("+id+")'>Confirm</a>"+
	"<a class='cancel_content_msg_edit_submit' onclick='cancel_edit_msg_submit_coursemsg("+id+")'>Cancel</a>";

}

function edit_msg_submit_coursemsg(id)
{
var content_msg_edit_submit_id = "content_msg_edit_submit_"+id;
document.getElementById(content_msg_edit_submit_id).innerHTML = "Pending";

var content_msg_id = "content_msg_"+id;
var content_msg_edit_id = "content_msg_edit_"+id;
var words_msg_id = "words_msg_"+id;

var words_msg_textarea_id = "words_msg_textarea_"+id;
var words_msg_textarea = document.getElementById(words_msg_textarea_id).value;
var words = encodeURIComponent(words_msg_textarea);

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_edit_msg_submit=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_edit_msg_submit=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_edit_msg_submit.onreadystatechange=function()
  {
  if (xmlhttp_edit_msg_submit.readyState==4 && xmlhttp_edit_msg_submit.status==200)
    {
	var edit_msg_submit = new Array();
    document.getElementById(words_msg_id).innerHTML = xmlhttp_edit_msg_submit.responseText;
    document.getElementById(content_msg_id).style.display = "";
    document.getElementById(content_msg_edit_id).style.display = "none";
    }
  }
xmlhttp_edit_msg_submit.open("GET","server_edit_coursemsg_submit.php?id="+id+"&words="+words,true);
xmlhttp_edit_msg_submit.send();

}

function cancel_edit_msg_submit_coursemsg(id)
{
var content_msg_id = "content_msg_"+id;
var content_msg_edit_id = "content_msg_edit_"+id;

document.getElementById(content_msg_id).style.display = "";
document.getElementById(content_msg_edit_id).style.display = "none";
}

function cancel_msg_my_msgs_schoolmsg(id)
{
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Post</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"pmsg_close_question\">Are you sure? A deleted post can not be recovered.</div>"+
    "<div class=\"buttons\">"+
    "<p class=\"pmsg_close_question_yes\" onclick=\"cancel_msg_my_msgs_confirm_schoolmsg("+id+")\">Yes</p>"+
    "<p class=\"pmsg_close_question_no\" onclick=\"close_pmsg()\">No</p>"+
    "</div>";
    "</div>";
    window.scrollTo(0,0);
}

function cancel_msg_my_msgs_confirm_schoolmsg(id)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_cancel_msg_my_msgs=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_cancel_msg_my_msgs=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_cancel_msg_my_msgs.onreadystatechange=function()
  {
  if (xmlhttp_cancel_msg_my_msgs.readyState==4 && xmlhttp_cancel_msg_my_msgs.status==200)
    {
	document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Post</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"login_successful\">The post is deleted.</div>"+
    "</div>";
    window.scrollTo(0,0);
    setTimeout("close_pmsg()",1800);
	see_my_msgs();
    }
  }
xmlhttp_cancel_msg_my_msgs.open("GET","server_delete_msg.php?id="+id,true);
xmlhttp_cancel_msg_my_msgs.send();
}

function cancel_msg_my_msgs_coursemsg(id)
{
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Post</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"pmsg_close_question\">Are you sure? A deleted post can not be recovered.</div>"+
    "<div class=\"buttons\">"+
    "<p class=\"pmsg_close_question_yes\" onclick=\"cancel_msg_my_msgs_confirm_coursemsg("+id+")\">Yes</p>"+
    "<p class=\"pmsg_close_question_no\" onclick=\"close_pmsg()\">No</p>"+
    "</div>";
    "</div>";
    window.scrollTo(0,0);
}

function cancel_msg_my_msgs_confirm_coursemsg(id)
{
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_cancel_msg_my_msgs=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_cancel_msg_my_msgs=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_cancel_msg_my_msgs.onreadystatechange=function()
  {
  if (xmlhttp_cancel_msg_my_msgs.readyState==4 && xmlhttp_cancel_msg_my_msgs.status==200)
    {
	document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Post</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"login_successful\">The post is deleted.</div>"+
    "</div>";
    window.scrollTo(0,0);
    setTimeout("close_pmsg()",1800);
	see_my_coursemsgs();
    }
  }
xmlhttp_cancel_msg_my_msgs.open("GET","server_delete_coursemsg.php?id="+id,true);
xmlhttp_cancel_msg_my_msgs.send();
}

function like_comment_schoolmsg(id)
{
var like_comment_id = "like_comment_"+id;
var geili_id = "geili_comment_"+id;

if (document.getElementById(like_comment_id).innerHTML == "like")
{
document.getElementById(like_comment_id).innerHTML = "unlike";
var like = 1;
}
else 
{
document.getElementById(like_comment_id).innerHTML = "like";
var like = 0;
}

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_like_comment=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_like_comment=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_like_comment.onreadystatechange=function()
  {
  if (xmlhttp_like_comment.readyState==4 && xmlhttp_like_comment.status==200 && xmlhttp_like_comment.responseText != "")
    {
    document.getElementById(geili_id).innerHTML=xmlhttp_like_comment.responseText;
    }
  }
xmlhttp_like_comment.open("GET","server_like_comment.php?id="+id+"&like="+like,true);
xmlhttp_like_comment.send();
}

function like_comment_coursemsg(id)
{
var like_comment_id = "like_comment_"+id;
var geili_id = "geili_comment_"+id;

if (document.getElementById(like_comment_id).innerHTML == "like")
{
document.getElementById(like_comment_id).innerHTML = "unlike";
var like = 1;
}
else 
{
document.getElementById(like_comment_id).innerHTML = "like";
var like = 0;
}

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_like_comment=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_like_comment=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_like_comment.onreadystatechange=function()
  {
  if (xmlhttp_like_comment.readyState==4 && xmlhttp_like_comment.status==200 && xmlhttp_like_comment.responseText != "")
    {
    document.getElementById(geili_id).innerHTML=xmlhttp_like_comment.responseText;
    }
  }
xmlhttp_like_comment.open("GET","server_like_coursecomment.php?id="+id+"&like="+like,true);
xmlhttp_like_comment.send();
}

function cancel_comment_schoolmsg(cmtid)
{
var commentid = "comment_"+cmtid;
var removediv = document.getElementById(commentid);

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_cancel_comment=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_cancel_comment=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_cancel_comment.onreadystatechange=function()
  {
  if (xmlhttp_cancel_comment.readyState==4 && xmlhttp_cancel_comment.status==200)
    {
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Comment</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"login_successful\">Comment is deleted.</div>"+
    "</div>";
    setTimeout("close_pmsg()",1800);
    removediv.parentNode.removeChild(removediv);
    }
  }
xmlhttp_cancel_comment.open("GET","server_cancel_comment.php?cmtid="+cmtid,true);
xmlhttp_cancel_comment.send();
}

function cancel_comment_coursemsg(cmtid)
{
var commentid = "comment_"+cmtid;
var removediv = document.getElementById(commentid);

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp_cancel_comment=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp_cancel_comment=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp_cancel_comment.onreadystatechange=function()
  {
  if (xmlhttp_cancel_comment.readyState==4 && xmlhttp_cancel_comment.status==200)
    {
    document.getElementById("private_msg").style.visibility = "visible";
    document.getElementById("private_msg_form").innerHTML =
    "<h2>Delete Comment</h2>"+ 
    "<div id=\"private_msg_field\">"+
    "<div id=\"login_successful\">Comment is deleted.</div>"+
    "</div>";
    setTimeout("close_pmsg()",1800);
    removediv.parentNode.removeChild(removediv);
    }
  }
xmlhttp_cancel_comment.open("GET","server_cancel_coursecomment.php?cmtid="+cmtid,true);
xmlhttp_cancel_comment.send();
}

function add_code(){
	var words = document.getElementById("words").value;
	document.getElementById("words").value = words + "(code-start)/*Add your code here*/(code-end)";
}

/*********************** post image ***************************/
$(document).ready(function (e) {
$("#uploadimage").on('submit',(function(e) {
e.preventDefault();
//$("#message").empty();

	document.getElementById("post_button").innerHTML = "Posting";
			
	$.ajax({
		url: "server_upload_picture_schoolmsgs.php", // Url to which the request is send
		type: "POST",             // Type of request to be send, called as method
		data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
		contentType: false,       // The content type used when sending data to the server.
		cache: false,             // To unable request pages to be cached
		processData:false,        // To send DOMDocument or non processed data file it is set to false
		success: function(data)   // A function to be called if request succeeds
		{
			var schoolid = document.getElementById("uploadimage_schoolid").value;
			var role = document.getElementById("role").value;
			var words = document.getElementById("words").value;
			role = encodeURIComponent(role);
			words = encodeURIComponent(words);
			
			if (data.indexOf("Error") > -1){ //check if data contain "Error"
				alert(data);
			} else {
				if (window.XMLHttpRequest)
				  {// code for IE7+, Firefox, Chrome, Opera, Safari
				  xmlhttp_post_msg=new XMLHttpRequest();
				  }
				else
				  {// code for IE6, IE5
				  xmlhttp_post_msg=new ActiveXObject("Microsoft.XMLHTTP");
				  }
				xmlhttp_post_msg.onreadystatechange=function()
				  {
				  if (xmlhttp_post_msg.readyState==4 && xmlhttp_post_msg.status==200)
					{
					window.location="home.php?schoolid="+schoolid+"";
					}
				  }
				xmlhttp_post_msg.open("GET","server_post_love.php?role="+role+"&words="+words+"&imagelocation="+data+"&schoolid="+schoolid,true);
				xmlhttp_post_msg.send();
			}
		}
	});
}));

// Function to preview image after validation
$(function() {
$("#file").change(function() {
//$("#message").empty(); // To remove the previous error message
var file = this.files[0];
var imagefile = file.type;
var match= ["image/jpeg","image/png","image/jpg","image/bmp"];
if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]) || (imagefile==match[3])))
{
$('#previewing').attr('src','noimage.png');
alert("Please Select A valid Image File: Only jpeg, jpg, bmp and png Images type allowed");
return false;
}
else
{
var reader = new FileReader();
reader.onload = imageIsLoaded;
reader.readAsDataURL(this.files[0]);
}
});
});

function imageIsLoaded(e) {
//$("#file").css("color","green");
$('#image_preview').css("display", "inline");
$('#previewing').attr('src', e.target.result);
$('#previewing').attr('width', '100px');
$('#previewing').attr('height', '100px');
};
});

/******************************************************************************/

function deletecourse(courseid, schoolid){
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp_campus_search=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp_campus_search=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp_campus_search.onreadystatechange=function()
	  {
	  if (xmlhttp_campus_search.readyState==4 && xmlhttp_campus_search.status==200)
		{
		window.location="home.php?schoolid="+schoolid+"";
		}
	  }
	xmlhttp_campus_search.open("GET","server_delete_usercourses.php?courseid="+courseid,true);
	xmlhttp_campus_search.send();
}

function updateroles(id, usercourses_id, role, checkbox){
	var roles_id = "roles_"+id;
	var roles_id_innerHTML = document.getElementById(roles_id);
	
	var value = 0;
	if (checkbox.checked) {
		value = 1;
	} 
	
	if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp_updateroles=new XMLHttpRequest();
		}
	else
		{// code for IE6, IE5
		xmlhttp_updateroles=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp_updateroles.onreadystatechange=function()
		{
		if (xmlhttp_updateroles.readyState==4 && xmlhttp_updateroles.status==200){
			roles_id_innerHTML.innerHTML = xmlhttp_updateroles.responseText;
		}
	}
			  
	xmlhttp_updateroles.open("GET","server_update_usercourses_roles.php?id="+usercourses_id+"&role="+role+"&value="+value, true);
	xmlhttp_updateroles.send();
}

function show_update_roles(id){
	var update_roles_id = "update_roles_"+id;
	var update_roles_id_display = document.getElementById(update_roles_id);
	
	if (update_roles_id_display.style.display == "none"){
		update_roles_id_display.style.display = "";
	} else {
		update_roles_id_display.style.display = "none";
	}
}