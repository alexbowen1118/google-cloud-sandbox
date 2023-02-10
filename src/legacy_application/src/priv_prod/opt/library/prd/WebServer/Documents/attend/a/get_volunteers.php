<?php
ini_set('display_errors',1);
session_start();
$level=$_SESSION['annual_report']['level'];
if($level<2){@$park_code=$_SESSION['annual_report']['select'];}

$database="park_use";
include("/opt/library/prd/WebServer/include/iConnect.inc"); // connection parameters
mysqli_select_db($connection,$database); // database 

extract($_REQUEST);

$year1="20".substr($f_year,0,2);
$year2="20".substr($f_year,-2);

$y1="20".substr($f_year,0,2)."07";
$y2="20".substr($f_year,-2)."06";

$sql="SELECT distinct concat(Fname, ' ', Lname) as num
from vol_stats
where `year_month`>='$y1' and `year_month`<='$y2'
and park='$park_code'";
//echo "$f_year $y1 $y2 $sql<br /><br />";
$result = mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));

while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY_1[]=$row['num'];
	}
$num_vols=count($ARRAY_1);

unset($ARRAY_1);
$sql="SELECT sum(admin_hours) as admin_hours, sum(camp_host_hours) as camp_host_hours, sum(trail_hours) as trail_hours, sum(ie_hours) as ie_hours, sum(main_hours) as maintenance_hours, sum(research_hours) as research_hours, sum(res_man_hours) as resource_manage_hours,sum(other_hours) as other_hours, count(concat(Fname,Lname)) as number_of_volunteers
from vol_stats
where `year_month`>='$y1' and `year_month`<='$y2'
and park='$park_code'";
//echo "$f_year $y1 $y2 $sql<br /><br />";
	
$result = mysqli_query($connection,$sql) or die("$sql Error 1#". mysqli_errno($connection) . ": " . mysqli_error($connection));

while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY_1[]=$row;
	}
//echo "<pre>"; print_r($ARRAY_1); echo "</pre>"; // exit;

$lead_for="VC";
include("lead_ranger.php");

echo "<table cellpadding='10'><tr><td>Copy the <b>Volunteer</b> info, close this window, and paste into appropriate section.</td></tr></table>";

if(empty($lead_ranger))
	{$lr="No ranger designated as Lead for Volunteers";}
	else
	{$lr="$lead_ranger is the park's Lead for $lead_for.";}

echo "<table><tr><td>$lr</td></tr>";

if(mysqli_num_rows($result)<1)
	{
	echo "</table>No volunteer hours were entered into the database for $park_code for $y1 - $y2.";
	exit;
	}
echo "<tr><td>Volunteer total for July 1, $year1 through June 30, $year2:</td></tr>";

foreach($ARRAY_1 as $index=>$array)
	{
	foreach($array as $fld=>$value)
		{
		if(!isset($value)){$value="blank";}
		if($fld!="number_of_volunteers")
			{
			@$total+=$value;
			}
			else
			{$value=$num_vols;}
		
		$value=number_format($value,0);
		echo "<tr><td>$fld $value</td></tr>";
		}
	}
$total=number_format($total,1);
echo "<tr><td>Total Hours: $total</td></tr></table>";
