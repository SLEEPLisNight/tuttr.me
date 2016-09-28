function init(TimezoneAndPage){
	/*
	setInterval(function(){refresh_duitasuo('0');},600000);
	*/
	var t1 = setInterval(function(){online_user_counter();},6000);
	var t2 = setInterval(function(){unread_comment();},2000);
	var t3 = setInterval(function(){unread_pmsg();},2000);
	var t4 = setInterval(function(){chat_online_friends_counter();},2000);
	var t5 = setInterval(function(){chat_unreceive_ids();},1000);
	InitDragDrop();
	/*
	setInterval(function(){refresh_top_posts();},300000);
	*/
	get_user_timezone(TimezoneAndPage);
}

/* user local time zone */

function get_user_timezone(TimezoneAndPage){
	var TimezoneAndPageArray = new Array();
	TimezoneAndPageArray = TimezoneAndPage.split("|");

	var timezone = TimezoneAndPageArray[0] + "";
	var page = TimezoneAndPageArray[1] + "";
	
	if (timezone.length == 0){
		var visitortime = new Date();
		var visitortimezone = -visitortime.getTimezoneOffset()/60;
		
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp_timezone=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp_timezone=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp_timezone.onreadystatechange=function()
		  {
			if (xmlhttp_timezone.readyState==4 && xmlhttp_timezone.status==200){
				window.location = page;
			}
		  }
		xmlhttp_timezone.open("GET","timezone.php?time="+visitortimezone,true);
		xmlhttp_timezone.send();
	}
}