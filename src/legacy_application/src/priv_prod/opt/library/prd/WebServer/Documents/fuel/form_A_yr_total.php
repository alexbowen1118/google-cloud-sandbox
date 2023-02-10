<?php
extract($_REQUEST);

//echo "<pre>"; print_r($_REQUEST); echo "</pre>";  exit;

include("../../include/iConnect.inc");// database connection parameters
$database="fuel";
$db = mysqli_select_db($connection,$database)
   or die ("Couldn't select database");



// if($level<4){exit;}
$skip=array("year","month","vehicle","center_code");

// FIELD NAMES are stored in $fieldArray
$sql = "SHOW COLUMNS FROM items";//echo "$sql";
$result = mysqli_query($connection,$sql) or die ("Couldn't execute query SHOW2.");
	$fieldArray[]="vin";
	$fieldArray[]="vehicle_id";
while ($row=mysqli_fetch_assoc($result))
	{
	$rf=$row['Field'];
	$fieldArray[]=$rf;
	if(in_array($rf,$skip)){continue;}
	$rf1=$rf;
	if($rf=="E-10"){$rf1="E_10";}
	if($rf=="E-85"){$rf1="E_85";}
	if($rf=="re-refined"){$rf1="re_refined";}
	if($rf=="lbs_refrig-1"){$rf1="lbs_refrig_1";}
	if($rf=="lbs_refrig-2"){$rf1="lbs_refrig_2";}
	@$field_list.="sum(`t1`.`$rf`) as `$rf1`,";
	}

//echo "<pre>"; print_r($fieldArray); echo "</pre>";  //exit;
$field_list=rtrim($field_list,",");

if(@$rep=="x")
	{
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename=form_A.xls');
	}


	$sql= "SELECT t2.center_code,t2.vin,t2.vehicle_id,$field_list
	from items as t1
	LEFT JOIN `vehicle` as t2 on t1.vehicle=t2.id 
	where t1.year='$pass_year' and center_code is not NULL
	group by vehicle
	order by center_code";
//	echo "$sql"; exit;
	$result = mysqli_query($connection,$sql) or die ("Couldn't execute query.");
			while($row=mysqli_fetch_assoc($result))
			{
			$ARRAY[]=$row;
			}
//	echo "<pre>"; print_r($ARRAY); echo "</pre>";  exit;

		$title=$pass_year."_Form_A";
		$header_array[]=array_keys($ARRAY[0]);

		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=$title.csv");
		// Disable caching
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
		header("Pragma: no-cache"); // HTTP 1.0
		header("Expires: 0"); // Proxies

	
		function outputCSV($header_array, $data)
			{
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
			
// echo "<table border='1' cellpadding='5'>";
// 	
// $row_header=array("unl_3","unl_5");
// 	echo "<tr><th><font color='blue'>$pass_year</font><br /><a href=form_A_yr_total.php?pass_year=$pass_year&rep=x>Excel</a></th>";
// 			foreach($fieldArray as $k1=>$v1){
// 				if(in_array($v1,$skip)){continue;}
// 			//	$var=explode("_",strtoupper($v1));
// 				@$header.="<th>&nbsp;&nbsp;$v1&nbsp;&nbsp;</th>";
// 				echo "<th>&nbsp;&nbsp;$v1&nbsp;&nbsp;</th>";
// 				}
// 	echo "</tr>";
// 
// 
// // Data entry
// //echo "<pre>"; print_r($fieldArray); echo "</pre>"; // exit;
// foreach($ARRAY as $k=>$v)
// 	{
// 	extract($v);
// 	if(fmod($k,2)==0){$tr=" bgcolor='aliceblue'";}else{$tr="";}
// 	echo "<tr$tr><th>$center_code</th>";
// 		foreach($fieldArray as $k1=>$v1)
// 			{
// 			if(in_array($v1,$skip)){continue;}
// 			if($v1=="E-10"){$v1="E_10";}
// 			if($v1=="E-85"){$v1="E_85";}
// 			if($v1=="re-refined"){$v1="re_refined";}
// 			if($v1=="lbs_refrig-1"){$v1="lbs_refrig_1";}
// 			if($v1=="lbs_refrig-2"){$v1="lbs_refrig_2";}
// 			$value=${$v1};
// 			echo "<td align='right'>$value</td>";
// 			@${"tot_".$v1}+=$value;
// 			}
// 	echo "</tr>";
// 	}
// //echo "<pre>a"; print_r($tot_unl_1); echo "</pre>"; // exit;
// echo "<tr><th></th><th></th><th>Total</th>";
// 
// $skip=array("year","month","center_code","vin","vehicle","vehicle_id");	
// 	foreach($fieldArray as $k1=>$v1){
// 				if(in_array($v1,$skip)){continue;}
// 				if($v1=="E-10"){$v1="E_10";}
// 				if($v1=="E-85"){$v1="E_85";}
// 				if($v1=="re-refined"){$v1="re_refined";}
// 				if($v1=="lbs_refrig-1"){$v1="lbs_refrig_1";}
// 				if($v1=="lbs_refrig-2"){$v1="lbs_refrig_2";}
// 				$var=${"tot_".$v1};
// 				if($var=="0"){$var="-";}else{$var=number_format($var,1);}
// 				echo "<th bgcolor='yellow'>$var</th>";
// 				}
// echo "</tr>";
// echo "<tr><th>park_code</th>$header</tr>";
// echo "</table></html>";
?>