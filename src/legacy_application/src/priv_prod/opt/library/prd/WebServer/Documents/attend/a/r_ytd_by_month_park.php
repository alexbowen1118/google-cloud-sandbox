<?php
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');

$database="attend";
include("../../../include/auth.inc");
$database="park_use";
include("../../../include/iConnect.inc");
include("../../../include/get_parkcodes_reg.php");

// $database="park_use";
mysqli_select_db($connection,$database);

if(@!$year){$year=date('Y');}
if(@!$month){$month=(date('m'))-1;}// DEFAULT to previous month
//echo "<pre>";print_r($_REQUEST);echo "</pre>";exit;
$month=str_pad($month,2,"0",STR_PAD_LEFT);
$menu['r_ytd']="r_ytd_by_month_park.php";
$menuM=$month;
$menuY=$year;
$varQuery="submit=Enter&year=$menuY&month=$menuM";

if(@!$xls){include("../menu.php");}// ignore menu for Excel export

if(@$xls=="excel"){header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename=NC_State_Park_Visitation.xls');
}

// Get park totals for calendar year thru specified month
$nextMonth=$month+1;
$nextMonth=str_pad($nextMonth,2,"0",STR_PAD_LEFT);
if($month=="12"){$findYear=$year+1;$nextMonth="01";}else{$findYear=$year;}
$findYearB=$year."0100"; $findYearE=$findYear.$nextMonth."00";

if($year<date('Y')){$findYearE=$year."1300";}

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
if(empty($park)){exit;}
$var_month=$year.$month;
$sql = "SELECT park, left(`year_month_day`,6) as date, sum(`attend_tot`) as visitation

FROM `stats_day`

where park like '$park%' and substring(`year_month_day` from 1 for 6)='$var_month'

group by park";

//echo "$sql"; //exit;
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
if(!isset($ARRAY)){exit;}
$skip=array();
$total=0;
$c=count($ARRAY);
echo "<table border='1' cellpadding='3'><tr><td colspan='3'>Units: $c</td></tr>";
foreach($ARRAY AS $index=>$array)
	{
	if($index==0)
		{
		echo "<tr>";
		foreach($ARRAY[0] AS $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			echo "<th>$fld</th>";
			}
		echo "</tr>";
		}
	echo "<tr>";
	foreach($array as $fld=>$value)
		{
		if(in_array($fld,$skip)){continue;}
		if($fld=="visitation")
			{
			$total+=$value;
			$value=number_format($value,0);
			}
		echo "<td>$value</td>";
		}
	echo "</tr>";
	}
	echo "<tr><td colspan='3' align='right'>".number_format($total,0)."</td></tr>";
	echo "</table></div></body>";
	
echo "</html>";
?>