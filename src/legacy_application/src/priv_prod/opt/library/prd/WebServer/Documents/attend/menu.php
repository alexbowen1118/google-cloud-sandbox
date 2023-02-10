<?php
// echo "<pre>"; print_r($_SESSION); echo "</pre>";
// echo "<pre>"; print_r($_REQUEST); echo "</pre>"; // exit;
ini_set('display_errors',1);
if(@$source!="pub")
	{
	$database="attend";
	if($_SERVER['PHP_SELF']=="/attend/a/r_ytd.php" or $_SERVER['PHP_SELF']=="/attend/a/r_ytd_director.php")
		{
		if(empty($_SESSION))
			{
			session_start();
			}
		$level=$_SESSION['attend']['level'];
		if($level<1){exit;}
		$tempID=$_SESSION['attend']['tempID'];
		}
		else
		{
		include("../../../include/auth.inc");	
		}
	//echo "<pre>";print_r($_SERVER);echo "<pre>";//exit;
	
	$level=$_SESSION['attend']['level'];
	$tempID=$_SESSION['attend']['tempID'];
	if($level<2)
		{
		if($_SESSION['attend']['select']=="ENRI"||$_SESSION['attend']['select']=="OCMO")
			{
			// do nothing - let $parkcode pass through
			}
		else
			{
			if(!isset($parkcode))
				{$parkcode=$_SESSION['attend']['select'];}			
			}
		}
	
	include("css/TDnull.inc");
	echo "
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"../../jscalendar/calendar-brown.css\" title=\"calendar-brown.css\" />
	  <!-- main calendar program -->
	  <script type=\"text/javascript\" src=\"../../jscalendar/calendar.js\"></script>
	  <!-- language for the calendar -->
	  <script type=\"text/javascript\" src=\"../../jscalendar/lang/calendar-en.js\"></script>
	  <!-- the following script defines the Calendar.setup helper function, which makes adding a calendar a matter of 1 or 2 lines of code. -->
	  <script type=\"text/javascript\" src=\"../../jscalendar/calendar-setup.js\"></script>
	<script language=\"JavaScript\">";
	
	echo "
	<!--
	function MM_jumpMenu(targ,selObj,restore){ //v3.0
	  eval(targ+\".location='\"+selObj.options[selObj.selectedIndex].value+\"'\");
	  if (restore) selObj.selectedIndex=0;
	}
	
	function CheckAll()
	{
	count = document.frm.elements.length;
		for (i=0; i < count; i++) 
		{
		if(document.frm.elements[i].checked == 1)
			{document.frm.elements[i].checked = 0; }
		else {document.frm.elements[i].checked = 1;}
		}
	}
	function UncheckAll(){
	count = document.frm.elements.length;
		for (i=0; i < count; i++) 
		{
		if(document.frm.elements[i].checked == 1)
			{document.frm.elements[i].checked = 0; }
		else {document.frm.elements[i].checked = 1;}
		}
	}
	//-->
	
	</script><title>NC DPR Visitation Tracking System</title>";
	
	echo "</head><body>";
	
	echo "<div align='center'>
	<table border='1' cellpadding='5'>
	<tr>";
	
	
	// ******** Menu 0 *************   Non-Admin
	//"FY-Visitation DNCR"=>"/attend/a/r_ytd_by_month_day_fiscal_year.php",
	//"Visitation by Month-DNCR test"=>"/attend/a/r_ytd_by_month_day_dncr_original.php", 
	$menuArray0=array("Enter Visitation"=>"/attend/a/form_day.php","YTD-Visitation"=>"/attend/a/r_ytd.php","YTD-Visitation Director"=>"/attend/a/r_ytd_day_director.php","YTD-Visitation-sn"=>"/attend/a/r_ytd.php?source=pub","YTD-Visitation-4yr for Month"=>"/attend/a/r_ytd_day_4yr.php","Visitation by Multiyear_Park"=>"/attend/a/r_ytd_day_4yr_yr.php","Visitation by Multiyear_Park_Month"=>"/attend/a/r_ytd_day_multiyear_park_month.php","Visitation for Month for Park"=>"/attend/a/r_ytd_by_month_park.php","Visitation for Month for Park"=>"/attend/a/r_ytd_by_month_park.php", "Visitation by Month-DNCR"=>"/attend/a/r_ytd_month_dncr.php","Yearly Use Summary"=>"/attend/a/use_summary.php","Yearly Totals"=>"/attend/a/annual_attend.php", "Volunteer Hours"=>"/attend/a/vol_form.php","Litter"=>"/attend/a/litter_form.php","Recycle"=>"/attend/a/recycle_form.php");
	
	
	// ******** Menu 0 *************   Admin
	
	if($level>3)
		{
		$menuArray0[' - - - - - - ']="";
		$menuArray0['Edit Categories for Park']="/attend/a/cat.php";
		$menuArray0['Edit Categories for Park by Day']="/attend/a/cat_day.php";
		$menuArray0['Edit Categories for Volunteers']="/attend/portal.php?database=attend&dbTable=vol_cat";
		$menuArray0['Volunteer Stats by Park']="/attend/a/vol_stats_park.php";
		$menuArray0['Volunteer Stats by Month/Park']="/attend/a/vol_stats_year_park_month.php";
		$menuArray0['Litter Stats by Park']="/attend/a/litter_stats_park.php";
		$menuArray0['Recycle Stats by Park']="/attend/a/recycle_stats_park.php";
		$menuArray0['Daily Visitation']="/attend/a/form_day.php";
		$menuArray0['YTD-Visitation Daily']="/attend/a/r_ytd_day.php";
		$menuArray0['Graph Visitation Daily']="/attend/d/park_daily.php";
		$menuArray0['Visitation by Year, Month, Park']="/attend/a/r_ytd_day_multiyear_park_month.php";
		}
	
	if($level>3)
		{
		$menuArray0['Export Daily Stats']="/attend/a/export_stats.php";
		}
	if($level=5)
	{
		$menuArray0['Traffic Counters']="/attend/traffic_cntr/ubidots_api.php";
	}


	echo "<td><form><select name=\"menu0\" onChange=\"MM_jumpMenu('parent',this,0)\"><option selected>Select...</option>"; //$s="value";
	foreach($menuArray0 as $k => $v){
			echo "<option value='$v'>$k\n";
		   }
	   echo "</select></form></td>";
	}

if(@$menu)
	{
	//set in calling file.  ***********
// 	echo "<pre>"; print_r($menu); echo "</pre>"; // exit;
	$m=date('n');
	if($m==1)
		{
		$m=12;
		$menuM=$m;
		$y=date('Y')-1;
		$menuY=date('Y')-1;
		}
	else
		{
		@$m=$n-1;
		$y=date('Y');
		}
	
	if($menuM)
		{
		$m=$menuM;
		$m=str_pad($m,2,"0",STR_PAD_LEFT);
		}
	if($menuY)
		{$y=$menuY;}
	
	if($_SERVER['PHP_SELF']=="/attend/a/r_ytd_by_month.php")
		{$y=$year;}
	
	if(@$source=="pub")
		{
		$varQuery.="&source=pub";
		$pub="<input type='hidden' name='source' value='pub'>";
		}
		else
		{$pub="";}
// 	echo "<pre>"; print_r($menu); echo "</pre>"; // exit;
	if($_SERVER['PHP_SELF']=="/attend/a/r_ytd_by_month_day_fiscal_year.php")
		{
		echo "<form action='$menu[r_ytd]'>
		$pub
		<td>Enter Start FY: <input type='text' name='start_fy' value='$start_fy' size='5'> Enter End FY: <input type='text' name='end_fy' value='$end_fy' size='3'> <input type='submit' name='submit' value='Enter'></form></td>";
		if(!empty($submit))
			{
			echo "<td><a href='$menu[r_ytd]?$varQuery&xls=excel'>Excel Export</a></td>";
			}
		}
		else
		{
		
		echo "<form action='$menu[r_ytd]'>
		$pub";
		if($_SERVER['PHP_SELF']=="/attend/a/r_ytd_by_month_park.php")
			{
			echo "<td>Park: <select name='park'><option value=\"\"></option>\n";
			foreach($parkCode as $k=>$v)
				{
				if($parkcode==$v){$s="selected";}else{$s="";}
				echo "<option value='$v' $s>$v</option>\n";
				}
			echo "</select></td>";
			@$varQuery.="&park=$parkcode";
			}
		$var_echo="<td>Enter Year: <input type='text' name='year' value='$y' size='5'> Month: <input type='text' name='month' value='$m' size='3'> <input type='submit' name='submit' value='Enter'></form></td>";
		
		if($_SERVER['PHP_SELF']=="/attend/a/r_ytd_day_4yr_yr.php" or $_SERVER['PHP_SELF']=="/attend/a/r_ytd_day_multiyear_park_month.php")
			{
			$var_echo="<td>Enter Beginning Year:
			<input type='text' name='year_1' value='' size='5'>
			Enter Ending Year:
			<input type='text' name='year_2' value='' size='5'>
			<input type='submit' name='submit' value='Enter'>
			</form></td>";
			}
		echo "$var_echo";
		if(!empty($submit) OR !empty($submit_form))
			{
			echo "<td><a href='$menu[r_ytd]?$varQuery&xls=excel'>Excel Export</a></td>";
			}
		}

	} 

echo "</tr></table></div>";

?>