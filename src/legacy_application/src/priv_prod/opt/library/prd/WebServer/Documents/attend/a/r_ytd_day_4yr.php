<?php
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');
//include("../../../include/get_parkcodes.php");

$database="attend";
$database="park_use";
//include("../../../include/auth.inc");
include("../../../include/iConnect.inc");
// include("../../no_inject_i.php");

extract($_REQUEST);
include("../../../include/get_parkcodes_reg.php");

mysqli_select_db($connection,$database);

//echo "<pre>";print_r($_REQUEST);echo "</pre>";    //exit;

if(@!$year){$year=date('Y');}
if(@!$month){$month=(date('m'))-1;}// DEFAULT to previous month
$month=str_pad($month,2,"0",STR_PAD_LEFT);
$menu['r_ytd']="r_ytd_day_4yr.php";
$menuM=$month;
$menuY=$year;
$varQuery="submit=Enter&year=$menuY&month=$menuM";

if(@!$xls){include("../menu.php");}// ignore menu for Excel export

if(@$xls=="excel"){header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=NC_State_Park_Attendance_4yr.xls');
}

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
$sql = "SELECT LEFT(`park`,4) as park, sum(attend_tot) 
FROM $table
where $q_field > '$findYearB' and $q_field < '$findYearE'
group by LEFT(`park`,4) order by park"; //echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkNameYear[]=$row[0];
	$parkTotYear[$row[0]]=$row[1];
	}
if(empty($parkNameYear)){exit;}
//echo "<pre>"; print_r($parkCode); echo "</pre>";  exit;
foreach($parkCode as $index=>$var_pc)
	{
	if(!in_array($var_pc,$parkNameYear)){$parkNameYear[]=$var_pc;}
	}
//echo "<pre>"; print_r($parkNameYear); echo "</pre>";  exit;
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
foreach($parkCode as $index=>$var_pc)
	{
	if(!in_array($var_pc,$parkNamePreYear)){$parkNamePreYear[]=$var_pc;}
	}
//echo "<pre>"; print_r($parkNamePreYear); echo "</pre>";  exit;
//echo "$sql $month $nextMonth";


$findYearB=($year-2)."0100"; $findYearE=($findYear-2).$nextMonth."00";
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
group by LEFT(`park`,4) order by park"; //echo "$sql<br /><br />";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkNamePreYear2[]=$row[0];
	$parkTotPreYear2[$row[0]]=$row[1];
	}
foreach($parkCode as $index=>$var_pc)
	{
	if(!in_array($var_pc,$parkNamePreYear2)){$parkNamePreYear[]=$var_pc;}
	}
//echo "<pre>"; print_r($parkNamePreYear); echo "</pre>";  exit;
//echo "$sql $month $nextMonth";
	
$findYearB=($year-3)."0100"; $findYearE=($findYear-3).$nextMonth."00";
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
group by LEFT(`park`,4) order by park"; //echo "$sql<br /><br />";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkNamePreYear3[]=$row[0];
	$parkTotPreYear3[$row[0]]=$row[1];
	}
foreach($parkCode as $index=>$var_pc)
	{
	if(!in_array($var_pc,$parkNamePreYear3)){$parkNamePreYear[]=$var_pc;}
	}
	
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
//echo "$sql";

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

if($y>2012)  // We had to change to counting daily attendance, rather than weekly, in 2012
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


if($y<2014)
	{
	$table="stats";   // remove in 2016
	$q_field="year_month_week";	   // remove in 2016
	}
$startMonth=($y-2).$mPad."00"; $endMonth=($y-2).$mPad_1."00";
$sql = "SELECT LEFT(`park`,4) as park, sum(attend_tot) 
FROM $table
where $q_field > '$startMonth' and $q_field < '$endMonth'
group by LEFT(`park`,4)";   //echo "$sql"; exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkNameMonthPreY2[]=$row[0];
	$parkTotMonthPreY2[$row[0]]=$row[1];
	}

if($y<2016)
	{
	$table="stats"; 
	$q_field="year_month_week";
	}

$startMonth=($y-3).$mPad."00"; $endMonth=($y-3).$mPad_1."00";
$sql = "SELECT LEFT(`park`,4) as park, sum(attend_tot) 
FROM $table
where $q_field > '$startMonth' and $q_field < '$endMonth'
group by LEFT(`park`,4)";  // echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_array($result))
	{
	$parkNameMonthPreY3[]=$row[0];
	$parkTotMonthPreY3[$row[0]]=$row[1];
	}
//echo "<pre>"; print_r($parkTotMonthPreY2); echo "</pre>";  exit;
	
	
$ly=$y-1; 
$ly2=$y-2; 
$ly3=$y-3; 
$mon=substr($mF,0,3);


if(@$submit=="Enter")
	{
	echo "<body><div align='center'><table border='1' cellpadding='5'>";
	echo "<tr><th>NC STATE<BR>PARK</th>
	<th bgcolor='yellow'>$mF<br>$y</th>
	<th>$mF<br>$ly</th>
	<th bgcolor='yellow'>$mF<br>$ly2</th>
	
	<th>$mF<br>$ly3</th>
	<th bgcolor='yellow'>% CHANGE<br>
	($y/$ly)<br>$mon &nbsp; YTD</th></tr>";
	
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
	@$monthPre2YearPark=number_format($parkTotMonthPreY2[$parkNameYear[$i]],0);
	@$monthPre3YearPark=number_format($parkTotMonthPreY3[$parkNameYear[$i]],0);
	
	@$yearPark=number_format($parkTotYear[$parkNameYear[$i]],0);
	@$yearParkPreY=number_format($parkTotPreYear[$parkNameYear[$i]],0);
	@$yearParkPreY2=number_format($parkTotPreYear2[$parkNameYear[$i]],0);
	@$yearParkPreY3=number_format($parkTotPreYear3[$parkNameYear[$i]],0);
	
	@$perCentChangeM=number_format((($parkTotMonth[$parkNameYear[$i]]/$parkTotMonthPreY[$parkNameYear[$i]])-1)*100);
	@$perCentChangeY=number_format((($parkTotYear[$parkNameYear[$i]]/$parkTotPreYear[$parkNameYear[$i]])-1)*100);
	
	if(!in_array($parkNameYear[$i],$combineParks))
		{
		@$parkMonthTot=$parkMonthTot+$parkTotMonth[$parkNameYear[$i]];
		@$yearMonthTot=$yearMonthTot+$parkTotYear[$parkNameYear[$i]];
		@$yearPrevTot=$yearPrevTot+$parkTotMonthPreY[$parkNameYear[$i]];
		@$yearPrevTotAll=$yearPrevTotAll+$parkTotPreYear[$parkNameYear[$i]];
		
		@$yearPrevTot2=$yearPrevTot2+$parkTotMonthPreY2[$parkNameYear[$i]];
		@$yearPrevTotAll2=$yearPrevTotAll2+$parkTotPreYear2[$parkNameYear[$i]];
		@$yearPrevTot3=$yearPrevTot3+$parkTotMonthPreY3[$parkNameYear[$i]];
		@$yearPrevTotAll3=$yearPrevTotAll3+$parkTotPreYear3[$parkNameYear[$i]];
		}
	
	
	if($parkNameYear[$i]=="ENRI")
		{
		$pc2="OCMO";
		$in="including";
		if(@$parkTotMonth['OCMO']<1)
			{
			$parkTotMonth['OCMO']=0;
			}
		if(@$source!="pub")
			{
			$pc="<a href='$page?parkcode=$parkNameYear[$i]&passM=$m&yearPass=$year'>$parkNameYear[$i]</a>";
			$var_OCMO=number_format($parkTotMonth['OCMO'],0);
			$pc2=" $var_OCMO for <a href='$page?parkcode=$pc2&passM=$m&yearPass=$year'> $m $pc2</a>";
			}
			else
			{$pc2="";}
		@$parkLink=$parkCodeName[$parkNameYear[$i]]." $pc $in<br />".$parkCodeName['OCMO'].$pc2;
		
		@$monthPark=number_format($parkTotMonth['ENRI']+$parkTotMonth['OCMO'],0);
		@$monthPreYearPark=number_format($parkTotMonthPreY['ENRI']+$parkTotMonthPreY['OCMO'],0);
		@$monthPre2YearPark=number_format($parkTotMonthPreY2['ENRI']+$parkTotMonthPreY['OCMO'],0);
		@$monthPre3YearPark=number_format($parkTotMonthPreY3['ENRI']+$parkTotMonthPreY['OCMO'],0);
		
		@$yearPark=number_format($parkTotYear['ENRI']+$parkTotYear['OCMO'],0);
		@$yearParkPreY=number_format($parkTotPreYear['ENRI']+$parkTotPreYear['OCMO'],0);
		@$yearParkPreY2=number_format($parkTotPreYear2['ENRI']+$parkTotPreYear2['OCMO'],0);
		@$yearParkPreY3=number_format($parkTotPreYear3['ENRI']+$parkTotPreYear3['OCMO'],0);
		
		@$dualParkTot=$parkTotMonth['ENRI']+$parkTotMonth['OCMO'];
		@$dualParkTotPreY=$parkTotMonthPreY['ENRI']+$parkTotMonthPreY['OCMO'];
		@$dualParkTotPreY2=$parkTotMonthPreY2['ENRI']+$parkTotMonthPreY2['OCMO'];
		@$dualParkTotPreY3=$parkTotMonthPreY3['ENRI']+$parkTotMonthPreY3['OCMO'];
		
		@$dualParkTotYear=$parkTotYear['ENRI']+$parkTotYear['OCMO'];
		@$dualParkTotPreYear=$parkTotPreYear['ENRI']+$parkTotPreYear['OCMO'];
		@$dualParkTotPreYear2=$parkTotPreYear2['ENRI']+$parkTotPreYear2['OCMO'];
		@$dualParkTotPreYear3=$parkTotPreYear3['ENRI']+$parkTotPreYear3['OCMO'];
		
		$perCentChangeM=number_format((($dualParkTot/$dualParkTotPreY)-1)*100);
		$perCentChangeY=number_format((($dualParkTotYear/$dualParkTotPreYear)-1)*100);
		
		@$parkMonthTot=$parkMonthTot+$parkTotMonth['ENRI']+$parkTotMonth['OCMO'];
		$yearMonthTot=$yearMonthTot+$parkTotYear['ENRI']+$parkTotYear['OCMO'];
		@$yearPrevTot=$yearPrevTot+$parkTotMonthPreY['ENRI']+$parkTotMonthPreY['OCMO'];
		@$yearPrevTotAll=$yearPrevTotAll+$parkTotPreYear['ENRI']+$parkTotPreYear['OCMO'];
		
		@$yearPrevTot2=$yearPrevTot2+$parkTotMonthPreY2['ENRI']+$parkTotMonthPreY2['OCMO'];
		@$yearPrevTotAll2=$yearPrevTotAll2+$parkTotPreYear2['ENRI']+$parkTotPreYear2['OCMO'];
		}// END ENRI - OCMO
	
	if($parkNameYear[$i]=="NERI")
		{
		$pc2="MOJE";
		$in="including";
		if(@$parkTotMonth['MOJE']<1)
			{
			$parkTotMonth['MOJE']=0;}
		if(@$source!="pub")
			{
			$pc="<a href='$page?parkcode=$parkNameYear[$i]&passM=$m&yearPass=$year'>$parkNameYear[$i]</a>";
			$var_MOJE=number_format($parkTotMonth['MOJE'],0);
			$pc2=" $var_MOJE for <a href='$page?parkcode=$pc2&passM=$m&yearPass=$year'> $m $pc2</a>";
			}
			else{$pc2="";}
		@$parkLink=$parkCodeName[$parkNameYear[$i]]." $pc $in<br />".$parkCodeName['MOJE'].$pc2;
		
		@$monthPark=number_format($parkTotMonth['NERI']+$parkTotMonth['MOJE'],0);
		@$monthPreYearPark=number_format($parkTotMonthPreY['NERI']+$parkTotMonthPreY['MOJE'],0);
		@$monthPre2YearPark=number_format($parkTotMonthPreY2['NERI']+$parkTotMonthPreY['MOJE'],0);
		@$monthPre3YearPark=number_format($parkTotMonthPreY3['NERI']+$parkTotMonthPreY['MOJE'],0);
		
		@$yearPark=number_format($parkTotYear['NERI']+$parkTotYear['MOJE'],0);
		@$yearParkPreY=number_format($parkTotPreYear['NERI']+$parkTotPreYear['MOJE'],0);
		@$yearParkPreY2=number_format($parkTotPreYear2['NERI']+$parkTotPreYear2['MOJE'],0);
		@$yearParkPreY3=number_format($parkTotPreYear3['NERI']+$parkTotPreYear3['MOJE'],0);
		
		@$dualParkTot=$parkTotMonth['NERI']+$parkTotMonth['MOJE'];
		@$dualParkTotPreY=$parkTotMonthPreY['NERI']+$parkTotMonthPreY['MOJE'];
		@$dualParkTotPreY2=$parkTotMonthPreY2['NERI']+$parkTotMonthPreY2['MOJE'];
		@$dualParkTotPreY3=$parkTotMonthPreY3['NERI']+$parkTotMonthPreY3['MOJE'];
		
		@$dualParkTotYear=$parkTotYear['NERI']+$parkTotYear['MOJE'];
		@$dualParkTotPreYear=$parkTotPreYear['NERI']+$parkTotPreYear['MOJE'];
		@$dualParkTotPreYear2=$parkTotPreYear2['NERI']+$parkTotPreYear2['MOJE'];
		@$dualParkTotPreYear3=$parkTotPreYear3['NERI']+$parkTotPreYear3['MOJE'];
		
		$perCentChangeM=number_format((($dualParkTot/$dualParkTotPreY)-1)*100);
		$perCentChangeY=number_format((($dualParkTotYear/$dualParkTotPreYear)-1)*100);
		
		@$parkMonthTot=$parkMonthTot+$parkTotMonth['NERI']+$parkTotMonth['MOJE'];
		@$yearMonthTot=$yearMonthTot+$parkTotYear['NERI']+$parkTotYear['MOJE'];
		@$yearPrevTot=$yearPrevTot+$parkTotMonthPreY['NERI']+$parkTotMonthPreY['MOJE'];
		@$yearPrevTotAll=$yearPrevTotAll+$parkTotPreYear['NERI']+$parkTotPreYear['MOJE'];
		@$yearPrevTot2=$yearPrevTot2+$parkTotMonthPreY2['NERI']+$parkTotMonthPreY2['MOJE'];
		@$yearPrevTotAll2=$yearPrevTotAll2+$parkTotPreYear2['NERI']+$parkTotPreYear2['MOJE'];
		}// END NERI - MOJE
	
	//if(fmod($i,2)==0){$bg=" bgcolor='Silver'";}else{$bg="";}
	if(@$j==0){$bg=" bgcolor='Silver'";}else{$bg="";}
	
	if(@$xls=="excel" AND @$source!="pub")
		{
		$parkLink=$parkNameYear[$i];
		if($parkLink=="ENRI"){$parkLink.="/OCMO";}
		if($parkLink=="NERI"){$parkLink.="/MOJE";}
		}
	
	echo "<tr$bg><td>$parkLink</td>
	<td align='right'>$monthPark</td>
	<td align='right'>$monthPreYearPark</td>
	<td align='right'>$monthPre2YearPark</td>
	<td align='right'>$monthPre3YearPark</td>
	<td align='center'>$perCentChangeM% &nbsp; $perCentChangeY%</td>
	</tr>";
	@$j++; if($j==2){$j=0;}
	}
	
	$perCentChangeMtot=@number_format((($parkMonthTot/$yearPrevTot)-1)*100);
	$perCentChangeYtot=@number_format((($yearMonthTot/$yearPrevTotAll)-1)*100);
	
	$parkMonthTot=number_format($parkMonthTot,0);
	$yearMonthTot=number_format($yearMonthTot,0);
	$yearPrevTot=number_format($yearPrevTot,0);
	$yearPrevTotAll=number_format($yearPrevTotAll,0);
	
	$yearPrevTot2=number_format($yearPrevTot2,0);
	$yearPrevTotAll2=number_format($yearPrevTotAll2,0);
	$yearPrevTot3=number_format($yearPrevTot3,0);
	$yearPrevTotAll3=number_format($yearPrevTotAll3,0);
	echo "<tr><td><b>SYSTEMWIDE TOTAL</b></td>
	<td align='right'>$parkMonthTot</td>
	<td align='right'>$yearPrevTot</td>
	<td align='right'>$yearPrevTot2</td>
	<td align='right'>$yearPrevTot3</td>
	<td align='center'>$perCentChangeMtot% &nbsp; $perCentChangeYtot%</td>
	
	</tr>";
	echo "<tr><th></th>
	<th bgcolor='yellow'>$mF<br>$y</th>
	<th>$mF<br>$ly</th>
	<th bgcolor='yellow'>$mF<br>$ly2</th>
	
	<th>$mF<br>$ly3</th>
	<th bgcolor='yellow'>% CHANGE<br>
	($y/$ly)<br>$mon &nbsp; YTD</th></tr>";
	
	echo "<tr><td colspan='10'>This version has been hard-coded for working with 2013 data. It will need to be modified to work with 2014 data.</td></tr>";
	echo "</table></div></body>";
	}
echo "</html>";
?>