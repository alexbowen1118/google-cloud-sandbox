<?php
ini_set('display_errors',1);

date_default_timezone_set('America/New_York');
$end_point=date("Y").date("m");

// echo "<pre>"; print_r($_POST); echo "</pre>"; // exit;

$database="attend";
include("../../../include/auth.inc");

$database="park_use";
include("../../../include/iConnect.inc");

include("../../../include/get_parkcodes_reg.php");

mysqli_select_db($connection,$database);

$menu="<html><head></head><body><div align='center'>
<table><tr><td>
Visitation Summaries</td></tr>";

$menu.="<tr><form method='POST' action='export_stats.php'><td style='vertical-align:top'>Average visitation per day from (2012-01-01) to previous month of this year</td><td>
<input type='hidden' name='report' value=\"visitation_day\">
<input type='submit' name='submit' value=\"Visitation by Day\"></form></td></tr>";

$menu.="<tr><form method='POST' action='export_stats.php'><td style='vertical-align:top'>Average visitation for Weekends and Weekdays (2012-01-01) to previous month of this year</td><td>
<input type='hidden' name='report' value=\"visitation_week\">
<input type='submit' name='submit' value=\"Visitation by Weekend/Weekday\"></form></td></tr>";

$menu.="</table>";
	

if(empty($_POST['report']))
	{
	echo "$menu";
	exit;
	}

if($_POST['report']=="visitation_day")
	{
	$testMinMonth=date("Ym");// seed min month
	$sql = "SELECT 
		year_month_day,
		if(DAYOFWEEK(year_month_day)=1 or DAYOFWEEK(year_month_day)=7,if(DAYOFWEEK(year_month_day)=1, 'Sunday', 'Saturday'), 'day') as day,
		round(AVG(attend_tot)) as 'AVG(visitation)'
	FROM 
		`stats_day` 
	where 
		LEFT(year_month_day,4)>2011 and LEFT(year_month_day,6)<$end_point
	group by 
	year_month_day";
	}

if($_POST['report']=="visitation_week")
	{
	$testMinMonth=date("Ym");// seed min month
	$sql = "SELECT 
		left(year_month_day,4) as year,
		if(DAYOFWEEK(year_month_day)=1 or DAYOFWEEK(year_month_day)=7,'Weekend', 'day') as day,
		round(AVG(attend_tot)) as 'AVG(visitation)'
	FROM 
		`stats_day` 
	where 
		LEFT(year_month_day,4)>2011 and LEFT(year_month_day,6)<$end_point
	group by year,
	day";
	}
// 	echo "$sql";
	
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");
	while ($row=mysqli_fetch_assoc($result))
		{
		$ARRAY[]=$row;
		}
// echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;
if($_POST['report']=="visitation_week")
	{
	foreach($ARRAY as $index=>$array)
		{
		$new_ARRAY[$array['year']][$array['day']]=$array['AVG(visitation)'];
		if(isset($new_ARRAY[$array['year']]['Weekend']))
			{
			$new_ARRAY[$array['year']]['ratio']=round($new_ARRAY[$array['year']]['Weekend']/$new_ARRAY[$array['year']]['day'],2);
			
			}
		
 	@$ARRAY[$index]['ratio']=$new_ARRAY[$array['year']]['ratio'];
		}
	}
// echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;
if($_POST['submit']=="CVS export")
	{
// 	echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;
	$filename=$report."_export.csv";
	header("Content-Type: text/csv");
	header("Content-Disposition: attachment; filename=$filename");
	// Disable caching
	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
	header("Pragma: no-cache"); // HTTP 1.0
	header("Expires: 0"); // Proxies
	
	function outputCSV($header_array, $data)
		{
		$output = fopen("php://output", "w");
		foreach ($header_array as $row)
			{
			fputcsv($output, $row); // here you can change delimiter/enclosure
			}
		foreach ($data as $row)
			{
			fputcsv($output, $row); // here you can change delimiter/enclosure
			}
		fclose($output);
		}

	$header_array[]=array_keys($ARRAY[0]);
	outputCSV($header_array, $ARRAY);
	exit;
	}

// echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;

echo "$menu";
$skip=array();
$c=count($ARRAY);
echo "<table><tr><td style='vertical-align:top' colspan='2'>$c items for $submit</td><td>
<form method='POST' action='export_stats.php'>
<input type='hidden' name='report' value=\"$report\">
<input type='submit' name='submit' value=\"CVS export\">
</form>
</td></tr>";

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
		echo "<td>$value</td>";
		}
	echo "</tr>";
	}
	echo "</table>";
	
echo "</div></body></html>";
?>