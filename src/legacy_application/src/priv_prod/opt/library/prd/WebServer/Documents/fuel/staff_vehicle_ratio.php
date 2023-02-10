<?php
echo "<a href='menu.php?form_type=report_menu'>Go back (this page doesn't work in production either)</a>";
exit;
extract($_REQUEST);

//echo "<pre>"; print_r($_SESSION); echo "</pre>";  exit;
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  //exit;
@$pass_district=$district;
include("../../include/connectROOT.inc");// database connection parameters
include("../../include/get_parkcodes.php");

$database="hr";
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

$sql="SELECT center_code, count(id) as seasonal FROM `seasonal_payroll`
where div_app='y' and park_approve='y'
group by center_code

";
//echo "<br>$sql<br>";
	
	$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SELECT. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		$cc=$row['center_code'];
		if($cc=="ADMI"){$cc="ARCH";}
		if($cc=="USBG"){$cc="REMA";}
		$sea_ARRAY[$cc]=$row['seasonal'];
		}
$sea_ARRAY['PAR3']=3;
$sea_ARRAY['NRTF']=1;
$sea_ARRAY['DEDE']=2;
$sea_ARRAY['WARE']=0;

$database="divper";
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

$sql="SELECT park, count(seid) as permanent FROM `position`
where 1
group by park

";
//echo "<br>$sql<br>";
	
	$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SELECT. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
		$cc=$row['park'];
	//	if($cc=="ARCH"){$cc=="ADMI";}
		$perm_ARRAY[$cc]=$row['permanent'];
		}

$database="fuel";
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

$sql="SELECT center_code, user, count(user) as staff FROM `vehicle`
where 1 and center_code!='surp'
group by center_code,user
";
//echo "<br>$sql<br>";
	
	$result = mysqli_query($connection, $sql) or die ("Couldn't execute query SELECT. $sql");
	while($row=mysqli_fetch_assoc($result))
		{
	//	$staff_ARRAY[$row['center_code']]=$row['permanent'];
		$staff_ARRAY[$row['center_code']][$row['user']]=$row['staff'];
		}
//echo "<pre>"; print_r($sea_ARRAY); echo "</pre>"; // exit;
//echo "<pre>"; print_r($perm_ARRAY); echo "</pre>"; // exit;
//echo "<pre>"; print_r($staff_ARRAY); echo "</pre>"; // exit;
//exit;

if(empty($rep))
	{
echo "<html><body bgcolor='beige'><table><tr><td colspan='2'>$c vehicles</td></tr>";
	}
	else
	{
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=park_mileage_summary.xls');
	echo "<table>";
	}
	
foreach($sea_ARRAY AS $park1=>$num_sea_emp)
	{
	echo "<tr>";
	echo "<td>$park1</td><td> seasonal employees = $num_sea_emp</td>";
	
	$num_perm=$perm_ARRAY[$park1];
	echo "<td> permanent employees = $num_perm</td>";
	
	$sea_vehicle=$staff_ARRAY[$park1]['seasonal'];
	$perm_vehicle=$staff_ARRAY[$park1]['permanent'];
	echo "<td> seasonal vehicles = $sea_vehicle</td>";
	echo "<td> permanent vehicles = $perm_vehicle</td>";
	
	$tot_emp=$num_sea_emp+$num_perm;
	$tot_veh=$sea_vehicle+$perm_vehicle;
	echo "<td> total employees = $tot_emp</td>";
	echo "<td> total vehicles = $tot_veh</td>";
	
	$tot_ratio=round(($tot_veh/$tot_emp),2);
	
	echo "<td> Ratio vehicle/staff = $tot_ratio</td>";
	echo "</tr>";
	}
echo "</table>";

if(empty($rep))
	{echo "</body></html>";}

	
?>
