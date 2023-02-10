<?php
$database="fuel";
include("../../include/auth_i.inc");
//echo "<pre>"; print_r($_SESSION); echo "</pre>";  //exit;
//echo "<pre>"; print_r($_SERVER); echo "</pre>";  //exit;
extract($_REQUEST);
$level=$_SESSION['fuel']['level'];
$tempID=$_SESSION['fuel']['tempID'];
date_default_timezone_set('America/New_York');

if($level<1)
	{
	echo "You do not have access to this database.";
	exit;
	}

if(strtolower($_SESSION['fuel']['tempID'])=="brodie2030")
	{$_SESSION['fuel']['select']="REMA";}
	
/*
if(strtolower($_SESSION['fuel']['tempID'])=="brodie2030")
	{
	$_SESSION['fuel']['select']="REMA";
	$_SESSION['fuel']['accessPark']="REMA,????";
	}
*/
//echo "<pre>"; print_r($_SESSION); echo "</pre>";  //exit;

if(empty($_REQUEST['rep']))
	{
	echo "<html><head>
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"../../jscalendar/calendar-brown.css\" title=\"calendar-brown.css\" />
	  <!-- main calendar program -->
	  <script type=\"text/javascript\" src=\"../../jscalendar/calendar.js\"></script>
	  <!-- language for the calendar -->
	  <script type=\"text/javascript\" src=\"../../jscalendar/lang/calendar-en.js\"></script>
	  <!-- the following script defines the Calendar.setup helper function, which makes adding a calendar a matter of 1 or 2 lines of code. -->
	  <script type=\"text/javascript\" src=\"../../jscalendar/calendar-setup.js\"></script>
	  
	<link type=\"text/css\" href=\"../css/ui-lightness/jquery-ui-1.8.23.custom.css\" rel=\"Stylesheet\" />    
	<script type=\"text/javascript\" src=\"../js/jquery-1.8.0.min.js\"></script>
	<script type=\"text/javascript\" src=\"../js/jquery-ui-1.8.23.custom.min.js\"></script>
	
	<script language='JavaScript'>

	<!--
	function MM_jumpMenu(targ,selObj,restore){ //v3.0
	  eval(targ+\".location='\"+selObj.options[selObj.selectedIndex].value+\"'\");
	  if (restore) selObj.selectedIndex=0;
	}

	function confirmLink()
	{
	 bConfirm=confirm('Are you sure you want to delete this record?')
	 return (bConfirm);
	}

	function toggleDisplay(objectID) {
	var object = document.getElementById(objectID);
	state = object.style.display;
	if (state == 'none')
		object.style.display = 'block';
	else if (state != 'none')
		object.style.display = 'none'; 
}

	function popitup(url) {
			newwindow=window.open(url,'name','resizable=1,scrollbars=1,height=1000,width=1130');
			if (window.focus) {newwindow.focus()}
			return false;
	}


	function radio_button_checker()
	{
	for (n=0; n<frmTest.length; n++){
		if(frmTest[n].type == 'radio'){

		var checkRadio=frmTest[n].name;
			if(checkRadio=='trans'){
				var radio_choice = false;
				for (counter = 0; counter < frmTest.trans.length; counter++)
					{
					if (frmTest.trans[counter].checked)
					radio_choice = true;
					}
				if (!radio_choice)
					{
					alert(\"Please select the vehicle\'s \"+ checkRadio + \" type.\")
					return (false);
					}
				//	return (true);
				}
			
			if(checkRadio=='duty'){
				var radio_choice = false;
				for (counter = 0; counter < frmTest.duty.length; counter++)
					{
					if (frmTest.duty[counter].checked)
					radio_choice = true;
					}
				if (!radio_choice)
					{
					alert(\"Please select the vehicle\'s \"+ checkRadio + \" type.\")
					return (false);
					}
				//	return (true);
				}
			
			if(checkRadio=='drive'){
				var radio_choice = false;
				for (counter = 0; counter < frmTest.drive.length; counter++)
					{
					if (frmTest.drive[counter].checked)
					radio_choice = true;
					}
				if (!radio_choice)
					{
					alert(\"Please select the vehicle\'s \" + checkRadio + \" type.\")
					return (false);
					}
				//	return (true);
				}
			
			if(checkRadio=='fuel'){
				var radio_choice = false;
				for (counter = 0; counter < frmTest.fuel.length; counter++)
					{
					if (frmTest.fuel[counter].checked)
					radio_choice = true;
					}
				if (!radio_choice)
					{
					alert(\"Please select the vehicle\'s \" + checkRadio + \" type.\")
					return (false);
					}
				//	return (true);
				}
			
			if(checkRadio=='used_for'){
				var radio_choice = false;
				for (counter = 0; counter < frmTest.used_for.length; counter++)
					{
					if (frmTest.used_for[counter].checked)
					radio_choice = true;
					}
				if (!radio_choice)
					{
					alert(\"Please select the vehicle\'s \" + checkRadio + \" type.\")
					return (false);
					}
				//	return (true);
				}
			}
		}
	}
	//-->

	</script><title>NC FUEL REPORT</title>
	</head>
	";
//	include("css/TDnull.inc");
// ,"All Terrain Vehicle (ATV) Inventory"=>"menu.php?form_type=atv&search=Find"
// "Utility Terrain Vehicle (UTV) Inventory"=>"menu.php?form_type=utv",
		$menu_array1=array("On-Road Vehicle Inventory"=>"menu.php?form_type=inventory","Motor Fleet Inventory"=>"menu.php?form_type=motor_fleet");
		if($level>3)
			{
// 			$menu_array1['DPR/DOA Parking']="menu.php?form_type=dpr_doa_parking";
			}
		$menu_array2=array(""=>"",
		"On-Road Form-A"=>"menu.php?form_type=form_A","On-Road Form-A-by_month"=>"menu.php?form_type=form_A_month","Off-Road Form-B"=>"menu.php?form_type=form_B",
		"On-Road Form-A"=>"menu.php?form_type=form_A",
		"Radio Assignments"=>"menu.php?form_type=dpr_radio");
		
	$menu_array=array_merge($menu_array1, $menu_array2);
	
		if($level>1)
			{
			$menu_array['On-Road Form-A Summary']="menu.php?form_type=form_A_summary";
			$menu_array['Off-Road Form-B Summary']="menu.php?form_type=form_B_summary";
			}
		if($level>2)
			{
// 			$menu_array['Water Craft Inventory']="menu.php?form_type=water";
// 			$menu_array['Travel Log']="menu.php?form_type=travel_log";
// 			$menu_array['DOT Keys']="menu.php?form_type=dot_keys";
			}
		if($level>1)
			{
			$menu_array['Keep, Surplus, Request Vehicle']="menu.php?form_type=pasu_decide";
// 			$menu_array['Change of Location']="menu.php?form_type=change_location";
			}
	
		if($level>0)
			{
			$menu_array['Report']="menu.php?form_type=report_menu";
			$menu_array['Vehicle-Driver']="menu.php?form_type=vehicle_driver_list";
		$menu_array['Equipment Inventory']="menu.php?form_type=equipment";
			}	
		if($level>2)
			{
			$menu_array['Find Division-Owned Vehicle']="menu.php?form_type=find_any";
			}
// 	$equip_array=array("Howerton3639","Howard6319","Reavis6725");
// 	
// 	if(in_array($tempID,$equip_array))
// 		{
// 		}
	
	echo "<body bgcolor='beige'><div align='center'>";

	if(!isset($form_type)){$form_type="";}
	if($form_type=="inventory"||$form_type=="")
		{
		echo "<img src='/inc/css/images/dpr_1.jpg'> ";
		}

	$d=date("D, M d, Y");
	$n=date('n'); //$n=1;
	if(empty($year))
		{
		if($n>1){$year=date('Y');}else{$year=date('Y')-1;}
		}

	echo "<table><tr><td><font color='brown'>NC DPR Fuel/Vehicle/Equipment Reporting</font> <select name='form_type'  onChange=\"MM_jumpMenu('parent',this,0)\">
	<option selected=''></option>";
	foreach($menu_array as $k=>$v)
		{
		if($v==$form_type){$s="selected";}else{$s="value";}
				echo "<option $s='$v'>$k</option>";
		}
	echo "</select> ";

	if($form_type=="form_B")
		{
		echo "for <font color='blue'>$year</font></td>";
		}
		else
		{
		echo "<font color='brown'>$d</font></td>";
		}
	echo "</tr></table></div>";
	
	}
$file=$form_type.".php"; // echo "$file";
	if($form_type){include("$file");}
?>