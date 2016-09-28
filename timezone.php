<?php
    session_start();
	
	if (strcmp($_GET['time'], "-11") == 0){ //-11 + 24 = 13
		$_SESSION['timezonename'] = "Pacific/Tongatapu";
	} else if (strcmp($_GET['time'], "-10") == 0){ //-10 + 21 = 11
		$_SESSION['timezonename'] = "Asia/Magadan";
	} else if (strcmp($_GET['time'], "-9") == 0){ //-9 = 
		$_SESSION['timezonename'] = "Pacific/Auckland";
	} else if (strcmp($_GET['time'], "-8") == 0){ //-8 + 18 = 10
		$_SESSION['timezonename'] = "Australia/Brisbane";
	} else if (strcmp($_GET['time'], "-7") == 0){ //-7 + 16 = 9
		$_SESSION['timezonename'] = "Asia/Tokyo";
	} else if (strcmp($_GET['time'], "-6") == 0){ //-6 + 14 = 8
		$_SESSION['timezonename'] = "Asia/Hong_Kong";
	} else if (strcmp($_GET['time'], "-5") == 0){ //-5 + 12 = 7
		$_SESSION['timezonename'] = "Asia/Bangkok";
	} else if (strcmp($_GET['time'], "-4") == 0){ //-4 + 10 = 6
		$_SESSION['timezonename'] = "Asia/Dhaka";
	} else if (strcmp($_GET['time'], "-3") == 0){ //-3 + 8 = 5
		$_SESSION['timezonename'] = "Asia/Tashkent";
	} else if (strcmp($_GET['time'], "-2.5") == 0){ //-2.5 + 7 = 4.5
		$_SESSION['timezonename'] = "Asia/Kabul";
	} else if (strcmp($_GET['time'], "-2") == 0){ //-2 + 6 = 4
		$_SESSION['timezonename'] = "Asia/Dubai";
	} else if (strcmp($_GET['time'], "-1.5") == 0){ //-1.5 + 5 = 3.5
		$_SESSION['timezonename'] = "Asia/Tehran";
	} else if (strcmp($_GET['time'], "-1") == 0){ //-1 + 4 = 3
		$_SESSION['timezonename'] = "Africa/Addis_Ababa";
	} else if (strcmp($_GET['time'], "0") == 0){ //0 + 2 = 2
		$_SESSION['timezonename'] = "Asia/Damascus";
	} else if (strcmp($_GET['time'], "1") == 0){ //1 + 0 = 1
		$_SESSION['timezonename'] = "Europe/Amsterdam";
	} else if (strcmp($_GET['time'], "2") == 0){ //2 - 2 = 0
		$_SESSION['timezonename'] = "Europe/London";
	} else if (strcmp($_GET['time'], "3") == 0){ //3 - 4 = -1
		$_SESSION['timezonename'] = "Atlantic/Azores";
	} else if (strcmp($_GET['time'], "3.5") == 0){ //3.5 - 5 = -1.5
		$_SESSION['timezonename'] = "America/Noronha";
	} else if (strcmp($_GET['time'], "4") == 0){ //4 - 6 = -2
		$_SESSION['timezonename'] = "America/Noronha";
	} else if (strcmp($_GET['time'], "4.5") == 0){ //4.5 - 7 = -2.5
		$_SESSION['timezonename'] = "America/Argentina/Buenos_Aires";
	} else if (strcmp($_GET['time'], "5") == 0){ //5 - 8 = -3
		$_SESSION['timezonename'] = "America/Argentina/Buenos_Aires";
	} else if (strcmp($_GET['time'], "5.5") == 0){ //5.5 - 9 = -3.5
		$_SESSION['timezonename'] = "America/St_Johns";
	} else if (strcmp($_GET['time'], "6") == 0){ //6 - 11 = -5
		$_SESSION['timezonename'] = "America/New_York";
	} else if (strcmp($_GET['time'], "6.5") == 0){ //6.5 - 12 = -5.5
		$_SESSION['timezonename'] = "America/Chicago";
	} else if (strcmp($_GET['time'], "7") == 0){ //7 - 13 = -6
		$_SESSION['timezonename'] = "America/Chicago";
	} else if (strcmp($_GET['time'], "8") == 0){ //8 - 15 = -7
		$_SESSION['timezonename'] = "America/Denver";
	} else if (strcmp($_GET['time'], "9") == 0){ //9 - 17 = -8
		$_SESSION['timezonename'] = "America/Los_Angeles";
	} else if (strcmp($_GET['time'], "10") == 0){ //10 - 19 = -9
		$_SESSION['timezonename'] = "America/Anchorage";
	} else if (strcmp($_GET['time'], "10.5") == 0){ //10.5 - 19 = -8.5
		$_SESSION['timezonename'] = "America/Anchorage";
	} else if (strcmp($_GET['time'], "11") == 0){ //11 - 21 = -10
		$_SESSION['timezonename'] = "America/Adak";
	} else if (strcmp($_GET['time'], "12") == 0){ //12 - 22 = -10
		$_SESSION['timezonename'] = "Pacific/Honolulu";
	} else if (strcmp($_GET['time'], "13") == 0){ //13 - 24 = -11
		$_SESSION['timezonename'] = "Pacific/Midway";
	} else if (strcmp($_GET['time'], "14") == 0){ //14 - 26 = -12
		$_SESSION['timezonename'] = "Pacific/Wake";
	} 
?>