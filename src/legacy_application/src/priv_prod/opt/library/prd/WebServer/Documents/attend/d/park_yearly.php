<?php

$db="park_use";
$database=$db;
ini_set('display_errors',1);
$domain="auth.dpr.ncparks.gov";
include("../../../include/iConnect.inc");  //sets $database
mysqli_select_db($connection,$database) or die ("Couldn't select database");
extract ($_REQUEST);
date_default_timezone_set('America/New_York');
//print_r($_REQUEST);exit;

//	include("../../_base_top.php");

if(!empty($yearPass) and !empty($yearPass))
	{
	$sql = "SELECT park, sum(attend_tot) as attendance
	FROM `stats_day` 
	WHERE `park` LIKE '$parkcode%' AND `year_month_day` LIKE '$yearPass%'
	group by park";  //echo "$sql"; exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	if(mysqli_num_rows($result)<1)
		{
		echo "No Visitation was recorded for $parkcode.";
		if($level>3){echo "$sql";}
		exit;
		}
	while($row=mysqli_fetch_assoc($result))
		{
		$ARRAY[]=$row;
		}
	}

include("../../../include/get_parkcodes_reg.php"); // get park name
include("/opt/library/prd/WebServer/Documents/attend/a/park_code_areas.php"); // get subunits

$c=count($ARRAY);
echo "<table><tr><td colspan='2'>$c reporting areas for $parkcode $yearPass</td></tr>";
foreach($ARRAY AS $index=>$array)
	{
	if($index==0)
		{
		echo "<tr>";
		foreach($ARRAY[0] AS $fld=>$value)
			{
			echo "<th>$fld</th>";
			}
		echo "</tr>";
		}
	echo "<tr>";
	foreach($array as $fld=>$value)
		{
		$display_value=$value;
		if($fld=="attendance")
			{
			$display_value=number_format($value,0);
			@$tot_attend+=$value;
			}
		if($fld=="park")
			{
			$display_value=$parkCodeName[$value];
			}
		echo "<td>$display_value</td>";
		}
	echo "</tr>";
	}
$tot_attend=number_format($tot_attend,0);	
echo "<tr><td colspan='2' align='right'>$tot_attend</td></tr>";
echo "</table>";
?>