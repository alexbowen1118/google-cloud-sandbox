<?php

ini_set('display_errors',1);
$database="fuel";
include("../../include/iConnect.inc");// database connection parameters
mysqli_select_db($connection,$database)
       or die ("Couldn't select database $database");
       
//echo "<pre>"; print_r($_SERVER); echo "</pre>"; // exit;
//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;
//echo "<pre>"; print_r($_SERVER); echo "</pre>"; // exit;
 
 if($_POST)
 	{
		$skip=array("rep","Submit");
 		foreach($_POST as $k=>$v)
 			{
 				if(!$v OR in_array($v,$skip)){continue;}
 				$oper1="='";
 					$oper2="' and ";
 				$pass_query.=$k."=$v&";
 				$clause.="t1.".$k.$oper1.$v.$oper2;
 			}
 			$clause="and ".rtrim($clause," and ");
 			$pass_query=rtrim($pass_query,"&");
 			$pass_ncas_number=$_POST['ncas_number'];
 	}
 


$order_by="order by t1.active desc,  t1.section";

	
	if($_SERVER['QUERY_STRING']!="form_type=dpr_doa_parking")
		{
		$skip=array("rep");
		$exp1=explode("&",$_SERVER['QUERY_STRING']);
		$pass_query=$_SERVER['QUERY_STRING'];
		
		if(@$sort=="comments")
			{
			$desc="DESC";
			$order_by="order by $sort $desc, t1.active desc";
			}
			else
			{
			if(!empty($sort)){$sort="t1.$sort, ";}else{$sort="";}
			@$order_by="order by $sort t1.active desc";
			}
		}

if(!isset($clause)){$clause="";}

$sql="select t1.* 
from dpr_doa_parking as t1
where 1  $clause
$order_by"; 
//echo "$sql<br />"; exit;

$result = mysqli_query($connection,$sql) or die ("Couldn't execute query 1. $sql");

if(mysqli_num_rows($result)<1)
		{
			$ncas_number=$_REQUEST['ncas_number'];
			echo "No items for $ncas_number were found.";exit;
		}

while ($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]=$row;
	if($row['active']=="YES"){@$active++;}
	}


$count_flds=count($ARRAY[0])-2;
$count_records=count($ARRAY);

if(@$_REQUEST['rep']=="")
	{
echo "<div align='center'><table border='1' cellpadding='5'>";
	if(!isset($pass_query)){$pass_query="";}
	$not=$count_records-$active;
	echo "<tr><th colspan='$count_flds'><font color='brown'>DPR DOA Vehicles - Active ($active) + Not active ($not) = $count_records</font></th>";
	if($level>1)
		{
		echo "<th><a href='edit_dpr_doa_parking.php?submit=Add'>Add</th>";
		}
	
	echo "<th colspan='1'>Excel <a href='dpr_doa_parking.php?$pass_query&rep=1&sort=plate'>export</a></th></tr>";
		echo "<tr>";
	}
	else
	{
	
// 	echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=dpr_doa_parking.csv");
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
foreach($ARRAY[0] as $k=>$v)
		{	
			@$i++;
			if($k=="cost"){$pass_col_num=$i;}
			$k1=str_replace("_"," ",$k);
			if($level>0)
				{$k1="<a href='menu.php?form_type=dpr_doa_parking&sort=$k'>$k1</a>";}
			
			echo "<th>$k1</th>";
		}
	echo "</tr>";

		
foreach($ARRAY as $num=>$value_array)
		{
			echo "<tr>";
			foreach($value_array as $k=>$v)
				{
				if($k=="id" and $level>3)
					{
					$v="<a href='edit_dpr_doa_parking.php?id=$v' target='_blank'>$v</a>";
					}
				if($k=="owner" and $v=="DPR")
					{
					$v="<font color='blue'>$v</font>";
					}
				echo "<td>$v</td>";
				}
			echo "</tr>";
		}
		
	echo "</table>";
?>