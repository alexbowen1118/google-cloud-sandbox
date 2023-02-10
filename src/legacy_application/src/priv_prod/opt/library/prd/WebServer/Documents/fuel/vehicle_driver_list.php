<?php
 $userAddress = $_SERVER['REMOTE_ADDR']; //echo"u=$source"; 
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;
//echo "<pre>"; print_r($_SERVER); echo "</pre>"; // exit;
// 
// session_start();
// echo "<pre>"; print_r($_SESSION); echo "</pre>"; // exit;

date_default_timezone_set('America/New_York');
$database="fuel"; 
$dbName="fuel";
include("../../include/auth.inc");
include("../../include/iConnect.inc");
mysqli_select_db($connection,$database)
       or die ("Couldn't select database");

	$sql = "SELECT t1.center_code, t1.make, t1.model, t1.fuel, t1.drive, t1.vin, t1.license, concat(t4.Fname,' ',t4.Lname) as assigned_to, t2.posTitle

FROM `vehicle` as t1

left join divper.position as t2 on t1.assigned_to=t2.beacon_num
left join divper.emplist as t3 on t2.beacon_num=t3.beacon_num
left join divper.empinfo as t4 on t3.emid=t4.emid

where assigned_to !='' and t1.center_code!='surp'
order by t1.center_code, assigned_to
";
	$result = @mysqli_query($connection,$sql) or die("$sql Error 1#");
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	}
	
if(!empty($rep))
	{
	$header_array[]=array_keys($ARRAY[0]);

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

	outputCSV($header_array, $ARRAY);

	exit;
	}
$skip=array();
$c=count($ARRAY);
echo "<table><tr><td>$c</td><td><a href='vehicle_driver_list.php?rep=1'>export</a></td></tr>";
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
?>
