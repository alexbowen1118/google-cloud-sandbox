<?php
ini_set('display_errors',1);
date_default_timezone_set('America/New_York');
//include("../../../include/get_parkcodes.php");

$db="park_use";
include("../../../include/iConnect.inc");
// include("../../no_inject.php");

extract($_REQUEST);
include("../../../include/get_parkcodes_dist.php");

mysqli_select_db($connection,$database);

// echo "<pre>";print_r($_REQUEST);echo "</pre>";    exit;

if(@!$year){$year=date('Y');}
if(@!$month){$month=(date('m'))-1;}// DEFAULT to previous month
$month=str_pad($month,2,"0",STR_PAD_LEFT);
$menu['r_ytd']="r_ytd_day_4yr_yr.php";
$menuM=$month;
$menuY=$year;
if(!empty($year_1))
	{
	$varQuery="submit=Enter&year_1=$year_1&year_2=$year_2";
	}

if(@!$xls){include("../menu.php");}// ignore menu for Excel export


if(empty($year_1)){exit;}

// echo "<pre>"; print_r($_GET); echo "</pre>"; exit;
$sql = "SELECT left(park,4) as park, left(year_month_day,4) as year, format(sum(attend_tot),0) as visitation 

FROM `stats_day`

where left(year_month_day,4)>=$year_1 and left(year_month_day,4)<=$year_2 and park!='ARCH'

group by left(park,4), left(year_month_day,4)

order by park, left(year_month_day,4)"; //echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
while ($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}

if(@$xls=="excel")
	{
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=ncsp_year_park_visitation.csv");
	// Disable caching
	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
	header("Pragma: no-cache"); // HTTP 1.0
	header("Expires: 0"); // Proxies


	function outputCSV($header_array, $data) {

	$comment_line[]=array("To prevent Excel dropping any leading zero of an upper_left_code or upper_right_code an apostrophe is prepended to those values and only to those values.");
		$output = fopen("php://output", "w");
		foreach ($comment_line as $row) {
// 			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		foreach ($header_array as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
		foreach ($data as $row) {
			fputcsv($output, $row); // here you can change delimiter/enclosure
		}
	fclose($output);
	}

	$header_array[]=array_keys($ARRAY[0]);
	// 		echo "<pre>"; print_r($header_array); print_r($comment_line); echo "</pre>";  exit;
	outputCSV($header_array, $ARRAY);
	exit;
	}
	
$skip=array();
$c=count($ARRAY);
echo "<div><table cellpadding='5'><tr><td colspan='3'>For years: $year_1 to $year_2 </td></tr>";
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
// 		if($fld=="visitation"){$value=number_format($value, 0);}
		echo "<td align='center'>$value</td>";
		}
	echo "</tr>";
	}
// 	echo "<tr><td colspan='3'>This version has been hard-coded for working with 2013 data. It will need to be modified to work with 2014 data.</td></tr>";
	echo "</table></div></body>";
	
echo "</html>";
?>