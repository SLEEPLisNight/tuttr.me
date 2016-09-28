function addschool(){
	document.getElementById("profile_school_search").style.visibility = "visible";
	document.getElementById("profile_schoolname_id").style.visibility = "hidden";
}

function cancel_addschool(){
	document.getElementById("profile_school_search").style.visibility = "hidden";
	document.getElementById("profile_school_search_results").style.visibility = "hidden";
	document.getElementById("profile_schoolname_id").style.visibility = "visible";
}

function school_search(){
var schoolname = escape(document.getElementById("profile_school_search_input").value);

if (schoolname.length==0)
  { 
  document.getElementById("profile_school_search_results").style.visibility = "hidden";
  document.getElementById("profile_school_search_results").innerHTML = "";
  return;
  } 
else 
  {
  document.getElementById("profile_school_search_results").style.visibility = "visible";
  //document.getElementById("profile_school_search_results").innerHTML = "<div class=\"choose_school_more\">搜索学校中...</div>";
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
    document.getElementById("profile_school_search_results").style.visibility = "visible";
    document.getElementById("profile_school_search_results").innerHTML = xmlhttp_campus_search.responseText;
    }
  }
xmlhttp_campus_search.open("GET","server_profile_school_search.php?name="+schoolname,true);
xmlhttp_campus_search.send();

}

function update_users_schoolid(schoolid, userid){
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
		window.location="profile.php?userid="+userid+"";
		}
	  }
	xmlhttp_campus_search.open("GET","server_update_users_schoolid.php?schoolid="+schoolid,true);
	xmlhttp_campus_search.send();
}

function addcourse(){
	document.getElementById("profile_course_search").style.visibility = "visible";
	document.getElementById("profile_coursenumber_id_first").style.visibility = "hidden";
}

function cancel_addcourse(){
	document.getElementById("profile_course_search").style.visibility = "hidden";
	document.getElementById("profile_course_search_results").style.visibility = "hidden";
	document.getElementById("profile_coursenumber_id_first").style.visibility = "visible";
}

function profile_course_search(university){
var coursename = escape(document.getElementById("profile_course_search_input").value);

if (coursename.length==0)
  { 
  document.getElementById("profile_course_search_results").style.visibility = "hidden";
  document.getElementById("profile_course_search_results").innerHTML = "";
  return;
  } 
else 
  {
  document.getElementById("profile_course_search_results").style.visibility = "visible";
  //document.getElementById("profile_course_search_results").innerHTML = "<div class=\"choose_school_more\">搜索学校中...</div>";
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
    document.getElementById("profile_course_search_results").style.visibility = "visible";
    document.getElementById("profile_course_search_results").innerHTML = xmlhttp_campus_search.responseText;
    }
  }
xmlhttp_campus_search.open("GET","server_profile_course_search.php?name="+coursename+"&university="+university,true);
xmlhttp_campus_search.send();

}

function update_usercourses(courseid, userid){
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
		window.location="profile.php?userid="+userid+"";
		}
	  }
	xmlhttp_campus_search.open("GET","server_update_usercourses.php?courseid="+courseid,true);
	xmlhttp_campus_search.send();
}

function deletecourse(courseid, userid){
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
		window.location="profile.php?userid="+userid+"";
		}
	  }
	xmlhttp_campus_search.open("GET","server_delete_usercourses.php?courseid="+courseid,true);
	xmlhttp_campus_search.send();
}

function updateroles(usercourses_id, role, checkbox){
	var value = 0;
	if (checkbox.checked) {
		value = 1;
	} 
	
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
		if (xmlhttp_finish_signup.readyState==4 && xmlhttp_finish_signup.status==200){
			
		}
	}
			  
	xmlhttp_finish_signup.open("GET","server_update_usercourses_roles.php?id="+usercourses_id+"&role="+role+"&value="+value, true);
	xmlhttp_finish_signup.send();
}

function save_profile_description(){
	var description = document.getElementById("description").value;
	description = encodeURIComponent(description);
	
	if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp_save_profile_description=new XMLHttpRequest();
		}
	else
		{// code for IE6, IE5
		xmlhttp_save_profile_description=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp_save_profile_description.onreadystatechange=function()
		{
		if (xmlhttp_save_profile_description.readyState==4 && xmlhttp_save_profile_description.status==200){
			if (xmlhttp_save_profile_description.responseText == "saved"){
				alert("Your description is saved");
			}
		}
	}
			  
	xmlhttp_save_profile_description.open("GET","server_save_profile_description.php?description="+description, true);
	xmlhttp_save_profile_description.send();
}