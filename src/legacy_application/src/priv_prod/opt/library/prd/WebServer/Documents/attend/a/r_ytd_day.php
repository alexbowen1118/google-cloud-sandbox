<?php
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');
//include("../../../include/get_parkcodes.php");
extract($_REQUEST);
$database="park_use";

include("../../../include/iConnect.inc");

include("../../../include/get_parkcodes_reg.php");
//echo "parkCode<pre>";print_r($parkCode);echo "</pre>";    //exit;

$database="park_use";
mysqli_select_db($connection,$database);

//echo "<pre>";print_r($_REQUEST);echo "</pre>";    //exit;

if(@!$year){$year=date('Y');}
if(@!$month){$month=(date('m'))-1;}// DEFAULT to previous month
$month=str_pad($month,2,"0",STR_PAD_LEFT);
$menu['r_ytd']="r_ytd_day.php";
$menuM=$month;
$menuY=$year;
$varQuery="submit=Enter&year=$menuY&month=$menuM";

if(@!$xls){include("../menu.php");}// ignore menu for Excel export

// if(@$xls=="excel"){header('Content-Type: application/vnd.ms-excel');
// header('Content-Disposition: attachment; filename=NC_State_Park_Attendance.xls');
// }

// Get park totals for calendar year thru specified month
$nextMonth=$month+1;
$nextMonth=str_pad($nextMonth,2,"0",STR_PAD_LEFT);
if($month=="12"){$findYear=$year+1;$nextMonth="01";}else{$findYear=$year;}
$findYearB=$year."0100"; $findYearE=$findYear.$nextMonth."00";

if($findYear>2011)
	{
	$table="stats_day";
	$q_field="year_month_day";
	}
	else
	{
	$table="stats";
	$q_field="year_month_week";
	}
$sql = "SELECT LEFT(`park`,4) as park, sum(attend_tot) FROM $table
where $q_field > '$findYearB' and $q_field < '$findYearE'
group by LEFT(`park`,4) order by park"; //echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkNameYear[]=$row[0];
	$parkTotYear[$row[0]]=$row[1];
	}
foreach($parkCode as $index=>$pc)
	{
	if(!in_array($pc,$parkNameYear)){$parkNameYear[]=$pc;}
	}
//echo "<pre>"; print_r($parkNameYear); echo "</pre>";

// Get park totals for PREVIOUS calendar year
if($month=="12"){$findYear=$year+1;$nextMonth="01";}else{$findYear=$year;}
$findYearB=($year-1)."0100"; $findYearE=($findYear-1).$nextMonth."00";

//echo "$findYear $findYearB  <br />";
if($findYearB<20120100)
	{
	$table="stats";
	$q_field="year_month_week";
	}
	else
	{
	$table="stats_day";
	$q_field="year_month_day";
	}
$sql = "SELECT LEFT(`park`,4) as park, sum(attend_tot) FROM $table
where $q_field> '$findYearB' and $q_field < '$findYearE'
group by LEFT(`park`,4) order by park"; //echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkNamePreYear[]=$row[0];
	$parkTotPreYear[$row[0]]=$row[1];
	}
foreach($parkCode as $index=>$pc)
	{
	if(!in_array($pc,$parkNamePreYear)){$parkNamePreYear[]=$pc;}
	}
//echo "<pre>"; print_r($parkName
//echo "$sql $month $nextMonth";

// Uses $parkNameYear for park name

$y=$year; $m=$month; $mPad=str_pad($m,2,"0",STR_PAD_LEFT); $mPad_1=str_pad($m+1,2,"0",STR_PAD_LEFT);
$t=$y.$mPad."01";
$mF=strftime("%B",strtotime($t));

if($y>2011)
	{
	$table="stats_day";
	$q_field="year_month_day";
	}
	else
	{
	$table="stats";
	$q_field="year_month_week";
	}
// Get park totals for current month
$startMonth=$y.$mPad."00"; $endMonth=$y.$mPad_1."00";
$sql = "SELECT LEFT(`park`,4) as park, sum(attend_tot) FROM $table
where $q_field > '$startMonth' and $q_field < '$endMonth'
group by LEFT(`park`,4)";
//echo " $sql";

$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkNameMonth[]=$row[0];
	$parkTotMonth[$row[0]]=$row[1];
	$parkCodes[$row[0]]=$row[0];
	}
// Uses $parkNameYear for park name
//echo "<pre>";print_r($parkCodes);print_r($parkTotMonth);echo "</pre>";

// Get park totals for current month PREVIOUS YEAR
$startMonth=($y-1).$mPad."00"; $endMonth=($y-1).$mPad_1."00";

if($y>2012)
	{
	$table="stats_day";
	$q_field="year_month_day";
	}
	else
	{
	$table="stats";
	$q_field="year_month_week";
	}
$sql = "SELECT LEFT(`park`,4) as park, sum(attend_tot) FROM $table
where $q_field > '$startMonth' and $q_field < '$endMonth'
group by LEFT(`park`,4)";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkNameMonthPreY[]=$row[0];
	$parkTotMonthPreY[$row[0]]=$row[1];
	}

$ly=$y-1; $mon=substr($mF,0,3);


if(@$submit=="Enter")
	{
	if(empty($xls))
		{
	echo "<body><div align='center'><table border='1' cellpadding='5'>";
	echo "<tr><th>NC STATE<BR>PARK</th><th>$mF<br>$y</th><th>TOTAL YTD<BR>$mF $y</th><th>$mF<br>$ly</th><th>TOTAL YTD<BR>$mF $ly</th><th>% CHANGE<br>($y/$ly)<br>$mon &nbsp; YTD</th></tr>";
		}
	
$combineParks=array("ENRI","NERI");// code below handles these two separately
$skipParks=array("OCMO","MOJE","BAIS","BATR","BECR","BEPA","BULA","DERI","LOHA","MIMI","PIBO","BUMO","RUHI","SARU","SCRI","SUMO","THRO","WOED","YEMO");
	
	for($i=0;$i<count($parkNameYear);$i++)
		{
		if(@$source!="pub" and @$level>0)
			{
			if($year>2011){$page="form_day.php";}else{$page="form.php";}
			$parkLink="<a href='$page?parkcode=$parkNameYear[$i]&passM=$m&yearPass=$year'>$parkNameYear[$i]</a>";
			}
		else
			{
			@$p=$parkCodeName[$parkNameYear[$i]];
			$parkLink=$p;
			}
		
		if(in_array($parkNameYear[$i],$skipParks)){continue;}
		
		@$monthPark=number_format($parkTotMonth[$parkNameYear[$i]],0);
		@$monthPreYearPark=number_format($parkTotMonthPreY[$parkNameYear[$i]],0);
		
		@$yearPark=number_format($parkTotYear[$parkNameYear[$i]],0);
		@$yearParkPreY=number_format($parkTotPreYear[$parkNameYear[$i]],0);
		
		@$perCentChangeM=number_format((($parkTotMonth[$parkNameYear[$i]]/$parkTotMonthPreY[$parkNameYear[$i]])-1)*100);
		@$perCentChangeY=number_format((($parkTotYear[$parkNameYear[$i]]/$parkTotPreYear[$parkNameYear[$i]])-1)*100);
		
		if(!in_array($parkNameYear[$i],$combineParks))
			{
			@$parkMonthTot=$parkMonthTot+$parkTotMonth[$parkNameYear[$i]];
			@$yearMonthTot=$yearMonthTot+$parkTotYear[$parkNameYear[$i]];
			@$yearPrevTot=$yearPrevTot+$parkTotMonthPreY[$parkNameYear[$i]];
			@$yearPrevTotAll=$yearPrevTotAll+$parkTotPreYear[$parkNameYear[$i]];
			}
		
		
		if($parkNameYear[$i]=="ENRI")
			{
			$pc2="OCMO";
			$in="plus";
			if(@$parkTotMonth['OCMO']<1)
				{
				$parkTotMonth['OCMO']=0;
				}
			if(@$source!="pub" and empty($xls))
				{
				$pc="<a href='$page?parkcode=$parkNameYear[$i]&passM=$m&yearPass=$year'>$parkNameYear[$i]</a>";
				$var_OCMO=number_format($parkTotYear['OCMO'],0);
				$pc2=" $var_OCMO for <a href='$page?parkcode=$pc2&passM=$m&yearPass=$year'> $m $pc2</a>";
				}
				else
				{$pc2="";}
			@$parkLink=$parkCodeName[$parkNameYear[$i]]." ".number_format($parkTotYear['ENRI'],0)."  $in<br />".$parkCodeName['OCMO'].$pc2;
			
			@$monthPark=number_format($parkTotMonth['ENRI']+$parkTotMonth['OCMO'],0);
			@$monthPreYearPark=number_format($parkTotMonthPreY['ENRI']+$parkTotMonthPreY['OCMO'],0);
			
			@$yearPark=number_format($parkTotYear['ENRI']+$parkTotYear['OCMO'],0);
			@$yearParkPreY=number_format($parkTotPreYear['ENRI']+$parkTotPreYear['OCMO'],0);
			
			@$dualParkTot=$parkTotMonth['ENRI']+$parkTotMonth['OCMO'];
			@$dualParkTotPreY=$parkTotMonthPreY['ENRI']+$parkTotMonthPreY['OCMO'];
			
			@$dualParkTotYear=$parkTotYear['ENRI']+$parkTotYear['OCMO'];
			@$dualParkTotPreYear=$parkTotPreYear['ENRI']+$parkTotPreYear['OCMO'];
			
			$perCentChangeM=number_format((($dualParkTot/$dualParkTotPreY)-1)*100);
			$perCentChangeY=number_format((($dualParkTotYear/$dualParkTotPreYear)-1)*100);
			
			@$parkMonthTot=$parkMonthTot+$parkTotMonth['ENRI']+$parkTotMonth['OCMO'];
			$yearMonthTot=$yearMonthTot+$parkTotYear['ENRI']+$parkTotYear['OCMO'];
			@$yearPrevTot=$yearPrevTot+$parkTotMonthPreY['ENRI']+$parkTotMonthPreY['OCMO'];
			@$yearPrevTotAll=$yearPrevTotAll+$parkTotPreYear['ENRI']+$parkTotPreYear['OCMO'];
			}// END ENRI - OCMO
		
		if($parkNameYear[$i]=="NERI")
			{
			$pc2="MOJE";
			$in="plus";
			if(@$parkTotMonth['MOJE']<1)
				{
				$parkTotMonth['MOJE']=0;}
			if(@$source!="pub" and empty($xls))
				{
				$pc="<a href='$page?parkcode=$parkNameYear[$i]&passM=$m&yearPass=$year'>$parkNameYear[$i]</a>";
			//	$var_MOJE=number_format($parkTotMonth['MOJE'],0);
				$var_MOJE=number_format($parkTotYear['MOJE'],0);
				$pc2=" $var_MOJE for <a href='$page?parkcode=$pc2&passM=$m&yearPass=$year'> $m $pc2</a>";
				}
				else{$pc2="";}
			@$parkLink=$parkCodeName[$parkNameYear[$i]]." ".number_format($parkTotYear['NERI'],0)."  $in<br />".$parkCodeName['MOJE'].$pc2;
			
			@$monthPark=number_format($parkTotMonth['NERI']+$parkTotMonth['MOJE'],0);
			@$monthPreYearPark=number_format($parkTotMonthPreY['NERI']+$parkTotMonthPreY['MOJE'],0);
			
			@$yearPark=number_format($parkTotYear['NERI']+$parkTotYear['MOJE'],0);
			@$yearParkPreY=number_format($parkTotPreYear['NERI']+$parkTotPreYear['MOJE'],0);
			
			@$dualParkTot=$parkTotMonth['NERI']+$parkTotMonth['MOJE'];
			@$dualParkTotPreY=$parkTotMonthPreY['NERI']+$parkTotMonthPreY['MOJE'];
			
			@$dualParkTotYear=$parkTotYear['NERI']+$parkTotYear['MOJE'];
			@$dualParkTotPreYear=$parkTotPreYear['NERI']+$parkTotPreYear['MOJE'];
			
			$perCentChangeM=number_format((($dualParkTot/$dualParkTotPreY)-1)*100);
			$perCentChangeY=number_format((($dualParkTotYear/$dualParkTotPreYear)-1)*100);
			
			@$parkMonthTot=$parkMonthTot+$parkTotMonth['NERI']+$parkTotMonth['MOJE'];
			@$yearMonthTot=$yearMonthTot+$parkTotYear['NERI']+$parkTotYear['MOJE'];
			@$yearPrevTot=$yearPrevTot+$parkTotMonthPreY['NERI']+$parkTotMonthPreY['MOJE'];
			@$yearPrevTotAll=$yearPrevTotAll+$parkTotPreYear['NERI']+$parkTotPreYear['MOJE'];
			}// END NERI - MOJE
		
		//if(fmod($i,2)==0){$bg=" bgcolor='Silver'";}else{$bg="";}
		if(@$j==0){$bg=" bgcolor='Silver'";}else{$bg="";}
		
		if(@$xls=="excel" AND @$source!="pub")
			{
			$parkLink=$parkNameYear[$i];
			if($parkLink=="ENRI"){$parkLink.="/OCMO";}
			if($parkLink=="NERI"){$parkLink.="/MOJE";}
			}
		
		$csv_array[]=array("$parkLink", "$monthPark", "$yearPark", "$monthPreYearPark", "$yearParkPreY", "$perCentChangeM%  $perCentChangeY%");
		if(empty($xls))
			{
			echo "<tr$bg><td>$parkLink</td>
			<td align='right'>$monthPark</td>
			<td align='right'>$yearPark</td>
			<td align='right'>$monthPreYearPark</td>
			<td align='right'>$yearParkPreY</td>
			<td align='center'>$perCentChangeM% &nbsp; $perCentChangeY%</td>
			</tr>";
			}
		@$j++; if($j==2){$j=0;}
		}
	
// 	echo "test<pre>"; print_r($csv_array); echo "</pre>"; // exit;
	$perCentChangeMtot=@number_format((($parkMonthTot/$yearPrevTot)-1)*100);
	$perCentChangeYtot=@number_format((($yearMonthTot/$yearPrevTotAll)-1)*100);
	
	$parkMonthTot=number_format($parkMonthTot,0);
	$yearMonthTot=number_format($yearMonthTot,0);
	$yearPrevTot=number_format($yearPrevTot,0);
	$yearPrevTotAll=number_format($yearPrevTotAll,0);
	
		$csv_array[]=array("", "$parkMonthTot", "$yearMonthTot", "$yearPrevTot", "$yearPrevTotAll", "$perCentChangeMtot%  $perCentChangeYtot%");
	if(empty($xls))
			{
			echo "<tr><td><b>SYSTEMWIDE TOTAL</b></td>
			<td align='right'>$parkMonthTot</td>
			<td align='right'>$yearMonthTot</td>
			<td align='right'>$yearPrevTot</td>
			<td align='right'>$yearPrevTotAll</td>
			<td align='center'>$perCentChangeMtot% &nbsp; $perCentChangeYtot%</td>
			</tr>";
	
		echo "</table></div></body>";
		echo "</html>";
			}
	if(!empty($xls))
			{
			
	$header_array[]=array("NC STATE PARK", " $mF $y", "TOTAL YTD $mF $y", " $mF $ly", "TOTAL YTD $mF $ly", "% CHANGE ($y/$ly)");
			header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=file.csv");
	// Disable caching
	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
	header("Pragma: no-cache"); // HTTP 1.0
	header("Expires: 0"); // Proxies

	
	function outputCSV($header_array, $data) {
		$output = fopen("php://output", "w");
		foreach ($header_array as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		foreach ($data as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		fclose($output);
	}

	outputCSV($header_array, $csv_array);

	exit;
			}
	}
?>