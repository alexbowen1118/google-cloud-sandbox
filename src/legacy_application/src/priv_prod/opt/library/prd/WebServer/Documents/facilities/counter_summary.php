<?php
//These are placed outside of the webserver directory for security
$database="facilities";
// include("../../include/auth.inc"); // used to authenticate users

include("../../include/get_parkcodes_dist.php"); 
// echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;
// echo "<pre>"; print_r($staffed_parks); echo "</pre>"; // exit;

// echo "<pre>";print_r($_POST);echo "</pre>";
// echo "<pre>";print_r($_SESSION);echo "</pre>";
$database="facilities";
$level=$_SESSION['facilities']['level'];

mysqli_select_db($connection,$database); // database

$sql="SELECT park_code, counter_name
from counters
WHERE 1 and counter_brand like '%Insights%' and see_insight_id=''"; 
// , counter_function
// echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql".mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY_no_SI[]= $row;
	}

$sql="SELECT park_code, count(counter_function) as 'Number of Counters', group_concat(counter_function order by counter_function) as counter_function_all,
sum(time_to_check) as 'Time Commitment', sum(distance_from_VC) as 'Travel Distance'
from counters
WHERE 1 group by park_code"; 
// , counter_function
// echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql".mysqli_error($connection));
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY_pc[]=$row['park_code'];
	$ARRAY[]= $row;
	}

$skip_unit=array("ARCH","EADI","NODI","SODI","WEDI","YORK","OCMO");
	foreach($staffed_parks as $k=>$v)
		{
		IF(in_array($k,$skip_unit)){continue;}
		if(!in_array($k,$ARRAY_pc))
			{
			$no_entry[$k]=$k;
			}
		}
	
if(empty($ARRAY))
	{
	include("../_base_top.php");
	ECHO "No counter has been entered."; exit;
	}
	
if(!empty($rep))
	{
	$ARRAY[]=array(" ");
	$ARRAY[]=array("Needed Counters");
$sql="SELECT park_code, counter_function_need as 'Needed Counter', count(counter_function_need) as 'Number' from counter_needs
WHERE 1 group by park_code, counter_function_need"; 
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]= $row;
	}
	
	header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=counter_export.csv");
		// Disable caching
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
		header("Pragma: no-cache"); // HTTP 1.0
		header("Expires: 0"); // Proxies
		
		
		function outputCSV($header_array, $data) {
		
// 		$comment_line[]=array("To prevent Excel dropping any leading zero of an upper_left_code or upper_right_code an apostrophe is prepended to those values and only to those values.");
			$output = fopen("php://output", "w");
// 			foreach ($comment_line as $row) {
// 				fputcsv($output, $row); // here you can change delimiter/enclosure
// 			}
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

include("../_base_top.php");
$skip=array("id","counter_function");
$tot_time=0;
$tot_num=0;
$tot_distance=0;
$tot_traffic=0;
$tot_trail=0;
$tot_vc=0;
$c=count($ARRAY);
// if($level>4)
// 	{
// 	echo "<pre>"; print_r($staffed_parks); echo "</pre>"; // exit;
// 	echo "<pre>"; print_r($ARRAY); echo "</pre>"; // exit;
// 	}
echo "<table border='1' cellpadding='5'><tr><td colspan='5'> <strong>Existing Counters</strong> at $c state park units</td>";

if($level>1){echo "<td>.csv <a href='counter_details.php'>export</a></td>";}

echo "</tr>";
foreach($ARRAY AS $index=>$array)
	{
	if($index==0)
		{
		echo "<tr>";
		foreach($ARRAY[0] AS $fld=>$value)
			{
			if(in_array($fld,$skip)){continue;}
			$var_fld=$fld;
		if($fld=="Travel Distance")
			{
			$var_fld=$fld."*";
			}
			echo "<th>$var_fld</th>";
			}
		echo "</tr>";
		}
	echo "<tr>";
	foreach($array as $fld=>$value)
		{
		if($fld=="Time Commitment"){$tot_time+=$value;}
		if($fld=="Number of Counters"){$tot_num+=$value;}
		if($fld=="Travel Distance")
			{
			$value=number_format($value, 3);
			$tot_distance+=$value;
			}
		if($fld=="park_code"){$value="<a href='park_counters.php?park_code=$value'>$value</a>";}
		if($fld=="park_code" or $fld=="counter_function_all")
			{
			if($fld=="counter_function_all")
				{
				$exp=explode(",",$value);
				$tot_traffic+=count(array_keys($exp, "traffic"));
				$tot_trail+=count(array_keys($exp, "trail"));
				$tot_vc+=count(array_keys($exp, "VC"));
				$value=str_replace("trail", "<font color='orange'>trail</font>", $value);
				$value=str_replace("VC", "<font color='magenta'>VC</font>", $value);
				}
			echo "<td>$value</td>";
			}
			else
			{
			echo "<td align='right'>$value</td>";
			}
		}
	echo "</tr>";
	}
// echo "<pre>"; print_r($counts); echo "</pre>"; // exit;
echo "<tr bgcolor='#e6e6ff'><td colspan='1'></td>
<td>$tot_traffic Traffic<br />$tot_trail Trail<br />$tot_vc VC</td>
<td>$tot_num total</td>";
$week_hours=$tot_time*7;
$year_hours=number_format($week_hours*52,2);
echo "<td>$tot_time hours per day<br />
$week_hours hours per week<br />
$year_hours hours per year</td>";

$week_miles=$tot_distance*7;
$year_miles=number_format($week_miles*52,2);
$td=number_format($tot_distance, 3);
echo "<td>$td miles per day<br />
$week_miles miles per week<br />
$year_miles miles per year<br />
* Miles are not devoted solely to counter reading.</td></tr>";
echo "</table>";

$ARRAY=array();
$sql="SELECT park_code, counter_function_need as 'Needed Counter', count(counter_function_need) as 'Number' from counter_needs
WHERE 1 group by park_code, counter_function_need"; 
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]= $row;
	}
$skip=array("id");
$tot_num=0;
$c=count($ARRAY);
echo "<table><tr><td colspan='2'> <strong>Needed Counters</strong></td></tr>";
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
		if($fld=="Number"){$tot_num+=$value;}
		echo "<td>$value</td>";
		}
	echo "</tr>";
	}
echo "<tr><td align='right' colspan='3'>Total: $tot_num</td></tr>";
echo "</table>";
if(!empty($no_entry) and $level >2)
	{
	echo "Units not reporting:<pre>"; print_r($no_entry); echo "</pre>"; // exit;
	}
	else
	{
	echo "<p><font color='green'>All units have completed the survey.</font></p>"; // exit;
	}
if(!empty($ARRAY_no_SI) and $level >4)
	{
	echo "Parks with See Insights but missing ID:<pre>"; print_r($ARRAY_no_SI); echo "</pre>"; // exit;
	}
echo "</body></html>";

?>