<?php
//These are placed outside of the webserver directory for security
$database="facilities";
// include("../../include/auth.inc"); // used to authenticate users

include("../../include/get_parkcodes_reg.php"); 
// echo "<pre>"; print_r($parkCode); echo "</pre>"; // exit;

// echo "<pre>";print_r($_POST);echo "</pre>";
// echo "<pre>";print_r($_SESSION);echo "</pre>";
$database="facilities";
$level=$_SESSION['facilities']['level'];



mysqli_select_db($connection,$database); // database

$sql="SELECT *
from counters
WHERE 1 
order by park_code, counter_function"; 
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]= $row;
	}
if(empty($ARRAY))
	{
	include("../_base_top.php");
	ECHO "No counter has been entered."; exit;
	}
	

	$ARRAY[]=array(" ");
	$ARRAY[]=array("Needed Counters");
$sql="SELECT * from counter_needs
WHERE 1 order by park_code, counter_function_need"; 
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query. $sql");
while($row=mysqli_fetch_assoc($result))
	{
	$ARRAY[]= $row;
	}
	header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=counter_details.csv");
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
	


?>