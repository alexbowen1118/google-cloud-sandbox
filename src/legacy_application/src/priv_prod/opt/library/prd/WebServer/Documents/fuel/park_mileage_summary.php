<?php
extract($_REQUEST);

//echo "<pre>"; print_r($_SESSION); echo "</pre>";  exit;
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;
@$pass_district=$district;
include("../../include/connectROOT.inc");// database connection parameters
include("../../include/get_parkcodes.php");

$database="fuel";
  $db = mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

$sort_by="order by total_miles desc";

if(@$report==2){$sort_by="order by t1.center_code, t1.user";}
$sql="SELECT t6.district, t1.center_code, t1.license, concat(t5.Fname,' ',t5.Lname) as name, posTitle, t1.user as `staff`, t1.body, t1.model, t1.year, t1.fuel, t1.mileage as start_mileage_2010, sum(t3.mileage) as mileage_from_Jan_2010, (t1.mileage+sum(t3.mileage)) as total_miles

FROM `vehicle` as t1

left join divper.position as t2 on t1.assigned_to=t2.beacon_num

left join fuel.items as t3 on t1.id=t3.vehicle

left join divper.emplist as t4 on t4.beacon_num=t2.beacon_num

left join divper.empinfo as t5 on t5.emid=t4.emid

left join dpr_system.parkcode_names as t6 on t6.park_code=t1.center_code

where t1.center_code!='surp'

group by t1.id

$sort_by";
//echo "<br>$sql<br>";
	
	$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SELECT. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		$ARRAY[]=$row;
		}

$c=count($ARRAY);


if(empty($rep))
	{
echo "<html><body bgcolor='beige'><table><tr><td colspan='2'>$c vehicles</td><td colspan='2'>Excel <a href='park_mileage_summary.php?rep=1&report=$report'>export</a></td></tr>";
	}
	else
	{
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=park_mileage_summary.xls');
	echo "<table>";
	}
	
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
		echo "<td>$value</td>";
		}
	echo "</tr>";
	}
echo "</table>";

if(empty($rep))
	{echo "</body></html>";}

	
?>
